<?php

namespace App\Support;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class OptimizedImageStorage
{
    public function store(
        UploadedFile $file,
        string $directory,
        int $maxWidth = 1920,
        int $maxHeight = 1920,
        int $quality = 82,
    ): string {
        if (! $this->canOptimize($file)) {
            return $file->store($directory, 'public');
        }

        $contents = @file_get_contents($file->getRealPath());
        $source = is_string($contents) ? @imagecreatefromstring($contents) : false;

        if ($source === false) {
            return $file->store($directory, 'public');
        }

        $source = $this->applyExifOrientation($source, $file);
        $width = imagesx($source);
        $height = imagesy($source);
        $scale = min(1, $maxWidth / max(1, $width), $maxHeight / max(1, $height));
        $targetWidth = max(1, (int) round($width * $scale));
        $targetHeight = max(1, (int) round($height * $scale));
        $target = imagecreatetruecolor($targetWidth, $targetHeight);

        if ($target === false) {
            imagedestroy($source);

            return $file->store($directory, 'public');
        }

        imagealphablending($target, false);
        imagesavealpha($target, true);
        $transparent = imagecolorallocatealpha($target, 0, 0, 0, 127);
        imagefill($target, 0, 0, $transparent);
        imagecopyresampled($target, $source, 0, 0, 0, 0, $targetWidth, $targetHeight, $width, $height);

        ob_start();
        $encoded = imagewebp($target, null, max(1, min(100, $quality)));
        $webp = ob_get_clean();

        imagedestroy($source);
        imagedestroy($target);

        if (! $encoded || ! is_string($webp) || $webp === '') {
            return $file->store($directory, 'public');
        }

        $path = trim($directory, '/').'/'.Str::uuid()->toString().'.webp';
        Storage::disk('public')->put($path, $webp);

        return $path;
    }

    private function canOptimize(UploadedFile $file): bool
    {
        if (! function_exists('imagecreatefromstring') || ! function_exists('imagewebp')) {
            return false;
        }

        $mime = strtolower((string) $file->getMimeType());

        return in_array($mime, ['image/jpeg', 'image/png', 'image/webp'], true);
    }

    private function applyExifOrientation(\GdImage $image, UploadedFile $file): \GdImage
    {
        if (strtolower((string) $file->getMimeType()) !== 'image/jpeg' || ! function_exists('exif_read_data')) {
            return $image;
        }

        try {
            $exif = @exif_read_data($file->getRealPath());
            $orientation = (int) ($exif['Orientation'] ?? 1);
            $angle = match ($orientation) {
                3 => 180,
                6 => -90,
                8 => 90,
                default => 0,
            };

            if ($angle === 0) {
                return $image;
            }

            $rotated = imagerotate($image, $angle, 0);
            if ($rotated === false) {
                return $image;
            }

            imagedestroy($image);

            return $rotated;
        } catch (\Throwable) {
            return $image;
        }
    }
}
