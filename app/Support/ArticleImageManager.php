<?php

namespace App\Support;

use App\Models\PostLocalization;
use DOMDocument;
use DOMElement;
use DOMXPath;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class ArticleImageManager
{
    public function store(UploadedFile $file, int $userId, string $locale): array
    {
        $extension = strtolower((string) $file->extension());
        $extension = in_array($extension, ['jpg', 'jpeg', 'png', 'webp', 'gif'], true) ? $extension : 'jpg';
        $filename = Str::uuid()->toString().'.'.$extension;
        $path = $file->storeAs("blog-content/{$userId}/{$locale}", $filename, 'public');

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

    public function cleanupOrphansForUser(int $userId, int $olderThanHours = 24): void
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
                $disk->delete($path);
            }
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

        foreach (array_values(array_unique($paths)) as $path) {
            if (! str_starts_with($path, 'blog-content/')) {
                continue;
            }

            $needle = basename($path);
            $stillReferenced = PostLocalization::query()
                ->whereNotNull('article_body')
                ->where('article_body', 'like', '%'.$needle.'%')
                ->exists();

            if (! $stillReferenced) {
                $disk->delete($path);
            }
        }
    }

    private function allReferencedPaths(): array
    {
        $paths = [];

        PostLocalization::query()
            ->whereNotNull('article_body')
            ->select(['id', 'article_body'])
            ->orderBy('id')
            ->chunkById(200, function ($localizations) use (&$paths): void {
                foreach ($localizations as $localization) {
                    foreach ($this->pathsFromHtml($localization->article_body) as $path) {
                        $paths[$path] = true;
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

        return str_starts_with($path, 'blog-content/') ? $path : null;
    }
}
