<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;

class PublishRobots extends Command
{
    protected $signature = 'cinehub:publish-robots';

    protected $description = 'Write production robots.txt with the canonical sitemap URL from APP_URL.';

    public function handle(): int
    {
        $baseUrl = rtrim((string) config('app.url'), '/');
        $parts = parse_url($baseUrl);
        $host = strtolower((string) ($parts['host'] ?? ''));
        $scheme = strtolower((string) ($parts['scheme'] ?? ''));

        if ($scheme !== 'https' || $host === '' || in_array($host, ['localhost', '127.0.0.1', 'example.com', 'seudominio.com'], true)) {
            $this->error('Set APP_URL to the public HTTPS domain before publishing robots.txt.');

            return self::FAILURE;
        }

        $content = "User-agent: *\nAllow: /\nDisallow: /admin\n\nSitemap: {$baseUrl}/sitemap.xml\n";

        if (File::put(public_path('robots.txt'), $content) !== strlen($content)) {
            $this->error('Could not write public/robots.txt; check file permissions.');

            return self::FAILURE;
        }

        $this->info('robots.txt updated for '.$baseUrl);

        return self::SUCCESS;
    }
}
