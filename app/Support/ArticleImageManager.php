<?php

namespace App\Support;

use App\Models\PostLocalization;
use DOMDocument;
use DOMElement;
use DOMXPath;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use RuntimeException;

class ArticleImageManager
{
    public function __construct(private readonly OptimizedImageStorage $imageStorage)
    {
    }

    public function store(UploadedFile $file, int $userId, string $locale): array
    {
        $path = $this->imageStorage->store($file, "blog-content/{$userId}/{$locale}", 1600, 1600, 82);

        return [
            'path' => $path,
            'url' => Storage::disk('public')->url($path),
        ];
    }

    public function deleteRemoved(?string $oldHtml, ?string $newHtml): void
    {
        $oldPaths = $this->pathsFromHtml($oldHtml);
        $newPaths = $this->pathsFromHtml($newHtml);
        $removed = array_values(array_diff($oldPaths, $newPaths));

        $this->deletePathsIfUnused($removed);
    }

    public function deleteFromHtml(?string $html): void
    {
        $this->deletePathsIfUnused($this->pathsFromHtml($html));
    }

    public function cleanupOrphansForUser(int $userId, int $olderThanHours = 168): void
    {
        $disk = Storage::disk('public');
        $prefix = "blog-content/{$userId}";
        $cutoff = now()->subHours($olderThanHours)->getTimestamp();
        $referenced = $this->allReferencedPaths();

        foreach ($disk->allFiles($prefix) as $path) {
            try {
                if ($disk->lastModified($path) > $cutoff) {
                    continue;
                }
            } catch (\Throwable) {
                continue;
            }

            if (! isset($referenced[$path])) {
                $this->deleteFile($path);
            }
        }
    }

    public function deleteFile(string $path): void
    {
        if (! str_starts_with($path, 'blog-content/')) {
            return;
        }
        $disk = Storage::disk('public');
        if ($disk->exists($path) && (! $disk->delete($path) || $disk->exists($path))) {
            throw new RuntimeException('Could not remove article image: '.$path);
        }
        $folder = dirname($path);
        while (str_starts_with($folder, 'blog-content/') && $folder !== 'blog-content') {
            if (! $disk->directoryExists($folder)) {
                $folder = dirname($folder);
                continue;
            }
            if ($disk->allFiles($folder) !== [] || $disk->directories($folder) !== []) {
                break;
            }
            if (! $disk->deleteDirectory($folder) || $disk->directoryExists($folder)) {
                throw new RuntimeException('Could not remove empty article folder: '.$folder);
            }
            $folder = dirname($folder);
        }
    }

    public function pathsFromHtml(?string $html): array
    {
        $html = trim((string) $html);

        if ($html === '' || ! str_contains(strtolower($html), '<img')) {
            return [];
        }

        if (! class_exists(DOMDocument::class)) {
            preg_match_all('/<img\b[^>]*\bsrc\s*=\s*(["\'])(.*?)\1/is', $html, $matches);
            $paths = array_map(fn (string $src): ?string => $this->pathFromSrc($src), $matches[2] ?? []);

            return array_values(array_unique(array_filter($paths)));
        }

        $document = new DOMDocument('1.0', 'UTF-8');
        $previous = libxml_use_internal_errors(true);
        $document->loadHTML('<?xml encoding="UTF-8"><div id="article-images-root">'.$html.'</div>', LIBXML_HTML_NOIMPLIED | LIBXML_HTML_NODEFDTD);
        libxml_clear_errors();
        libxml_use_internal_errors($previous);

        $root = (new DOMXPath($document))->query('//*[@id="article-images-root"]')->item(0);
        if (! $root instanceof DOMElement) {
            return [];
        }

        $paths = [];
        foreach ($root->getElementsByTagName('img') as $image) {
            $path = $this->pathFromSrc($image->getAttribute('src'));
            if ($path !== null) {
                $paths[] = $path;
            }
        }

        return array_values(array_unique($paths));
    }

    private function deletePathsIfUnused(array $paths): void
    {
        if ($paths === []) {
            return;
        }

        $disk = Storage::disk('public');
        // Compare full normalized paths, not just filenames. A similarly named
        // image in another article must not prevent cleanup.
        $references = $this->allReferencedPaths();

        foreach (array_values(array_unique($paths)) as $path) {
            if (str_starts_with($path, 'blog-content/') && ! isset($references[$path])) {
                $this->deleteFile($path);
            }
        }
    }

    public function allReferencedPaths(): array
    {
        $paths = [];

        PostLocalization::query()
            ->select(['id', 'article_body', 'excerpt', 'synopsis', 'review', 'spoilers'])
            ->orderBy('id')
            ->chunkById(200, function ($localizations) use (&$paths): void {
                foreach ($localizations as $localization) {
                    foreach (['article_body', 'excerpt', 'synopsis', 'review', 'spoilers'] as $field) {
                        foreach ($this->pathsFromHtml($localization->{$field}) as $path) {
                            $paths[$path] = true;
                        }
                    }
                }
            });

        return $paths;
    }

    private function pathFromSrc(string $src): ?string
    {
        $src = trim(html_entity_decode($src, ENT_QUOTES | ENT_HTML5, 'UTF-8'));
        if ($src === '') {
            return null;
        }

        $urlPath = parse_url($src, PHP_URL_PATH);
        $urlPath = is_string($urlPath) ? rawurldecode($urlPath) : $src;
        $marker = '/storage/';
        $position = strpos($urlPath, $marker);

        if ($position === false) {
            return null;
        }

        $path = ltrim(substr($urlPath, $position + strlen($marker)), '/');

        return preg_match('~^blog-content/[0-9]+/[a-z0-9-]+/[A-Za-z0-9_.-]+$~', $path) ? $path : null;
    }
}
