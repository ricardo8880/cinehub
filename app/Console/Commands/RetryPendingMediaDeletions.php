<?php

namespace App\Console\Commands;

use App\Support\MediaFileChanges;
use Illuminate\Console\Command;
use Throwable;

class RetryPendingMediaDeletions extends Command
{
    protected $signature = 'cinehub:retry-media-deletions';

    protected $description = 'Retry failed media deletions without an automatic scheduler';

    public function handle(MediaFileChanges $changes): int
    {
        try {
            $processed = $changes->processPending();
            $this->info("Pending media entries processed: {$processed}");
            return self::SUCCESS;
        } catch (Throwable $exception) {
            $this->error($exception->getMessage());
            return self::FAILURE;
        }
    }
}
