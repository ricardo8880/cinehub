<?php

namespace App\Console\Commands;

use App\Models\Media;
use App\Models\SiteSetting;
use App\Support\ArticleImageManager;
use App\Support\MediaFileChanges;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;
use Throwable;

class PruneUnusedMedia extends Command
{
    protected $signature = 'mrrcine:prune-media {--dry-run : Show what would be removed without deleting files} {--hours=168 : Minimum file age before deleting abandoned uploads}';

    protected $description = 'Remove abandoned post images and article uploads that are no longer referenced in the database';

    public function handle(ArticleImageManager $articles, MediaFileChanges $changes): int
    {
        $hours = filter_var($this->option('hours'), FILTER_VALIDATE_INT);
        if ($hours === false || $hours < 0) {
            $this->error('The minimum age must be zero hours or greater.');
            return self::FAILURE;
        }

        // This command is manual only. An explicit --hours=0 allows a complete
        // existing-orphan audit while normal use keeps the seven-day safety margin.
        if (! $this->option('dry-run')) {
            try {
                $changes->processPending();
            } catch (Throwable $exception) {
                $this->error($exception->getMessage());
                return self::FAILURE;
            }
        }

        $referenced = array_fill_keys(
            Media::query()->whereNotNull('thumbnail_path')->pluck('thumbnail_path')->all(),
            true
        );
        $referenced += $articles->allReferencedPaths();

        $otherUrls = SiteSetting::query()->whereNotNull('value')->pluck('value')->all();
        $disk = Storage::disk('public');
        $cutoff = now()->subHours($hours)->getTimestamp();
        $removed = 0;
        $failed = 0;

        foreach (['posts', 'blog-content'] as $folder) {
            foreach ($disk->allFiles($folder) as $path) {
                if (isset($referenced[$path])) {
                    continue;
                }

                // A book cover or another configured URL may reuse a local
                // file without having a media record.
                foreach ($otherUrls as $url) {
                    if (is_string($url) && str_contains($url, '/storage/'.$path)) {
                        continue 2;
                    }
                }

                try {
                    if ($disk->lastModified($path) > $cutoff) {
                        continue;
                    }

                    if ($this->option('dry-run')) {
                        $this->line('Unused: '.$path);
                        $removed++;
                    } else {
                        if (str_starts_with($path, 'posts/')) {
                            $changes->deleteFile($path);
                        } elseif (str_starts_with($path, 'blog-content/')) {
                            $articles->deleteFile($path);
                        }
                        if ($disk->exists($path)) {
                            throw new \RuntimeException('Could not remove: '.$path);
                        }
                        $removed++;
                    }
                } catch (Throwable $exception) {
                    $failed++;
                    report($exception);
                    $this->warn('Could not inspect: '.$path);
                }
            }
        }

        $this->info(($this->option('dry-run') ? 'Unused files found: ' : 'Unused files removed: ').$removed);

        return $failed === 0 ? self::SUCCESS : self::FAILURE;
    }
}
