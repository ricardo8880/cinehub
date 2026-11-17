<?php

namespace App\Support;

use App\Models\Media;
use App\Models\Post;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use RuntimeException;
use Throwable;

/**
 * Coordinates database changes with public post images. Retired files are deleted
 * before committing and restored if the transaction fails. Created files are
 * removed when the database operation cannot be completed.
 */
class MediaFileChanges
{
    private array $created = [];

    private array $retired = [];

    public function __construct(private readonly OptimizedImageStorage $images)
    {
    }

    /**
     * Delete retired files before the database commit, not afterwards. Temporary
     * file snapshots make deleted files restorable if the DB operation fails.
     * A failed file deletion aborts and rolls back the database change.
     */
    public function transaction(callable $callback): mixed
    {
        $this->created = [];
        $this->retired = [];
        $backups = [];

        try {
            return DB::transaction(function () use ($callback, &$backups) {
                $result = $callback($this);
                $backups = $this->backupRetiredFiles();

                foreach ($backups as $path => $stream) {
                    $this->deleteFileByPath($path);
                }

                return $result;
            });
        } catch (Throwable $exception) {
            $restoreFailures = $this->restoreBackedUpFiles($backups);
            $uploadFailures = $this->removeFailedUploads();

            if ($restoreFailures !== [] || $uploadFailures !== []) {
                throw new RuntimeException(
                    'Media transaction failed; files needing attention: '.implode(', ', array_merge($restoreFailures, $uploadFailures)),
                    previous: $exception,
                );
            }

            throw $exception;
        } finally {
            foreach ($backups as $stream) {
                if (is_resource($stream)) {
                    fclose($stream);
                }
            }
            $this->created = [];
            $this->retired = [];
        }
    }

    /**
     * Permanently delete a post and its files in the same coordinated operation.
     * The same path is used by the UI and by the legacy soft-delete migration.
     */
    public function deletePost(Post $post): void
    {
        $postId = (int) $post->id;
        $articleImages = app(ArticleImageManager::class);

        $this->transaction(function () use ($post, $postId, $articleImages): void {
            foreach ($post->media()->pluck('thumbnail_path')->filter() as $path) {
                $this->retire($path);
            }
            foreach ($post->localizations()->get() as $localization) {
                foreach (['article_body', 'excerpt', 'synopsis', 'review', 'spoilers'] as $field) {
                    foreach ($articleImages->pathsFromHtml($localization->{$field}) as $path) {
                        $this->retire($path);
                    }
                }
            }
            // Also remove files belonging to this post that have no DB record.
            foreach (Storage::disk('public')->allFiles('posts/'.$postId) as $path) {
                $this->retire($path);
            }
            $post->delete();
        });
    }

    /**
     * Snapshot every file before deleting any. No file is deleted if a backup
     * fails. Shared/referenced files are intentionally preserved.
     *
     * @return array<string, resource>
     */
    private function backupRetiredFiles(): array
    {
        $paths = array_unique($this->retired);
        $backups = [];
        $disk = Storage::disk('public');
        $articleReferences = null;

        try {
            foreach ($paths as $path) {
                if (str_starts_with($path, 'blog-content/')) {
                    $articleReferences ??= app(ArticleImageManager::class)->allReferencedPaths();
                }
                if ($this->isStillReferenced($path, $articleReferences ?? [])) {
                    continue;
                }
                if (! $disk->exists($path)) {
                    continue;
                }

                $input = $disk->readStream($path);
                $backup = tmpfile();
                if (! is_resource($input) || ! is_resource($backup)) {
                    if (is_resource($input)) {
                        fclose($input);
                    }
                    if (is_resource($backup)) {
                        fclose($backup);
                    }
                    throw new RuntimeException('Could not back up media before deletion: '.$path);
                }
                try {
                    if (stream_copy_to_stream($input, $backup) === false || ! rewind($backup)) {
                        throw new RuntimeException('Could not back up media before deletion: '.$path);
                    }
                    $backups[$path] = $backup;
                } catch (Throwable $exception) {
                    fclose($backup);
                    throw $exception;
                } finally {
                    fclose($input);
                }
            }
        } catch (Throwable $exception) {
            foreach ($backups as $backup) {
                fclose($backup);
            }
            throw $exception;
        }

        return $backups;
    }

    /** @param array<string, resource> $backups */
    private function restoreBackedUpFiles(array $backups): array
    {
        $disk = Storage::disk('public');
        $failed = [];
        foreach ($backups as $path => $stream) {
            try {
                if ($disk->exists($path)) {
                    continue;
                }
                rewind($stream);
                if (! $disk->writeStream($path, $stream) || ! $disk->exists($path)) {
                    throw new RuntimeException('Could not restore media after rollback: '.$path);
                }
            } catch (Throwable $exception) {
                report($exception);
                $failed[] = $path;
            }
        }
        return $failed;
    }

    /** Remove new uploads when the transaction fails, as in the original flow. */
    private function removeFailedUploads(): array
    {
        $failed = [];
        foreach (array_unique($this->created) as $path) {
            try {
                $this->deleteFileByPath($path);
            } catch (Throwable $exception) {
                report($exception);
                $failed[] = $path;
            }
        }
        if ($failed !== []) {
            // A failed upload is never referenced by a committed DB row.
            // Keep the existing manual recovery journal for catastrophic disk errors.
            try {
                foreach ($failed as $path) {
                    DB::table('pending_media_deletions')->insert([
                        'path' => $path,
                        'created_at' => now(),
                        'updated_at' => now(),
                    ]);
                }
            } catch (Throwable $exception) {
                report($exception);
            }
        }
        return $failed;
    }

    private function isStillReferenced(string $path, array $articleReferences): bool
    {
        $referenced = str_starts_with($path, 'posts/')
            ? Media::query()->where('thumbnail_path', $path)->exists()
            : isset($articleReferences[$path]);

        return $referenced || \App\Models\SiteSetting::query()
            ->where('value', 'like', '%/storage/'.str_replace(['%', '_'], ['\\%', '\\_'], $path).'%')
            ->exists();
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

                if (! $this->isStillReferenced($path, $articleReferences)) {
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
