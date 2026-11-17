<?php

namespace App\Support;

use App\Models\Media;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use RuntimeException;
use Throwable;

/**
 * Coordinates database changes with public post images. Files created by a failed
 * transaction are removed; replaced files are removed only after the DB commits.
 */
class MediaFileChanges
{
    private array $created = [];

    private array $retired = [];

    public function __construct(private readonly OptimizedImageStorage $images)
    {
    }

    /**
     * Commit the intent to delete in the same transaction as the database edit.
     * A disk error leaves a durable pending row and is reported to the caller.
     */
    public function transaction(callable $callback): mixed
    {
        $this->created = [];
        $this->retired = [];
        $queuedIds = [];

        try {
            $result = DB::transaction(function () use ($callback, &$queuedIds) {
                $result = $callback($this);
                foreach (array_unique($this->retired) as $path) {
                    $queuedIds[] = DB::table('pending_media_deletions')->insertGetId([
                        'path' => $path,
                        'created_at' => now(),
                        'updated_at' => now(),
                    ]);
                }
                return $result;
            });
        } catch (Throwable $exception) {
            $failed = [];
            foreach (array_unique($this->created) as $path) {
                try {
                    $this->deleteFileByPath($path);
                } catch (Throwable $cleanupError) {
                    report($cleanupError);
                    $failed[] = $path;
                }
            }
            $this->created = [];
            $this->retired = [];
            if ($failed !== []) {
                // The original transaction was rolled back. Persist failed
                // cleanup attempts independently so they can be retried.
                try {
                    foreach ($failed as $path) {
                        DB::table('pending_media_deletions')->insert([
                            'path' => $path,
                            'created_at' => now(),
                            'updated_at' => now(),
                        ]);
                    }
                } catch (Throwable $journalError) {
                    report($journalError);
                    throw new RuntimeException(
                        'Database change failed and new files could not be removed or recorded: '.implode(', ', $failed),
                        previous: $exception,
                    );
                }
                throw new RuntimeException(
                    'Database change failed and new files still require cleanup: '.implode(', ', $failed),
                    previous: $exception,
                );
            }
            throw $exception;
        }

        $this->created = [];
        $this->retired = [];
        $this->processPending($queuedIds);
        return $result;
    }

    public function store(UploadedFile $file, string $directory): string
    {
        $path = $this->images->store($file, $directory, 1920, 1920, 82);
        $this->created[] = $path;

        return $path;
    }

    public function storeArticle(UploadedFile $file, int $userId, string $locale): string
    {
        $path = $this->images->store($file, "blog-content/{$userId}/{$locale}", 1600, 1600, 82);
        $this->created[] = $path;
        return $path;
    }

    public function retire(?string $path): void
    {
        if ($this->isManagedPath($path)) {
            $this->retired[] = $path;
        }
    }

    /** Retries previously failed deletions. No scheduler is required. */
    public function processPending(?array $ids = null): int
    {
        $query = DB::table('pending_media_deletions')->orderBy('id');
        if ($ids !== null) {
            if ($ids === []) {
                return 0;
            }
            $query->whereIn('id', $ids);
        }

        $pending = $query->get(['id', 'path']);
        if ($pending->isEmpty()) {
            return 0;
        }

        $articleReferences = app(ArticleImageManager::class)->allReferencedPaths();
        $errors = [];
        $processed = 0;
        foreach ($pending as $item) {
            $path = $item->path;
            try {
                if (! $this->isManagedPath($path)) {
                    throw new RuntimeException('Invalid queued media path: '.$path);
                }

                $isReferenced = str_starts_with($path, 'posts/')
                    ? Media::query()->where('thumbnail_path', $path)->exists()
                    : isset($articleReferences[$path]);
                if (! $isReferenced) {
                    $isReferenced = \App\Models\SiteSetting::query()
                        ->where('value', 'like', '%/storage/'.str_replace(['%', '_'], ['\\%', '\\_'], $path).'%')
                        ->exists();
                }
                if (! $isReferenced) {
                    $this->deleteFileByPath($path);
                }
                DB::table('pending_media_deletions')->where('id', $item->id)->delete();
                $processed++;
            } catch (Throwable $exception) {
                report($exception);
                $errors[] = $path;
            }
        }
        if ($errors !== []) {
            throw new RuntimeException('Media deletion is incomplete; pending paths: '.implode(', ', $errors));
        }
        return $processed;
    }

    private function isManagedPath(?string $path): bool
    {
        return is_string($path) && (bool) preg_match(
            '~^(?:posts/[0-9]+(?:/[a-z0-9-]+)?|blog-content/[0-9]+/[a-z0-9-]+)/[A-Za-z0-9_.-]+$~D',
            $path,
        );
    }

    private function deleteFileByPath(string $path): void
    {
        if (str_starts_with($path, 'blog-content/')) {
            app(ArticleImageManager::class)->deleteFile($path);
        } else {
            $this->deleteFile($path);
        }
    }

    /** Delete a file and its now-empty folders, never an ancestor with other media. */
    public function deleteFile(string $path): void
    {
        $disk = Storage::disk('public');
        if ($disk->exists($path) && (! $disk->delete($path) || $disk->exists($path))) {
            throw new RuntimeException('Could not remove media file: '.$path);
        }

        // Clean up empty locale and post folders. Ignore missing directories.
        $folder = dirname($path);
        while (str_starts_with($folder, 'posts/') && $folder !== 'posts') {
            if (! $disk->directoryExists($folder)) {
                $folder = dirname($folder);
                continue;
            }
            if ($disk->allFiles($folder) !== [] || $disk->directories($folder) !== []) {
                break;
            }
            if (! $disk->deleteDirectory($folder) || $disk->directoryExists($folder)) {
                throw new RuntimeException('Could not remove empty media folder: '.$folder);
            }
            $folder = dirname($folder);
        }
    }
}
