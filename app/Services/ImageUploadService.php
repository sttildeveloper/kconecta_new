<?php

namespace App\Services;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class ImageUploadService
{
    public function saveImages(
        array|UploadedFile $files,
        string $targetDir = 'uploads/images',
        array $allowedExtensions = ['jpg', 'jpeg', 'png', 'gif'],
        bool $convertToWebp = false,
        bool $keepOriginal = false
    ): array {
        $result = ['success' => [], 'errors' => []];
        $targetPath = public_path($targetDir);

        if (! is_dir($targetPath) && ! mkdir($targetPath, 0755, true)) {
            $result['errors'][] = "Failed to create target directory: {$targetPath}";
            return $result;
        }

        $normalizedFiles = is_array($files) ? $files : [$files];

        foreach ($normalizedFiles as $file) {
            if (! $file instanceof UploadedFile || ! $file->isValid()) {
                $result['errors'][] = 'Invalid file or upload error.';
                continue;
            }

            $ext = strtolower($file->getClientOriginalExtension());
            if (! in_array($ext, $allowedExtensions, true)) {
                $result['errors'][] = "Disallowed file type: {$ext}";
                continue;
            }

            $newName = Str::random(40) . '.' . $ext;
            $originalFullPath = $targetPath . DIRECTORY_SEPARATOR . $newName;

            try {
                $file->move($targetPath, $newName);
                $relativePath = $targetDir . '/' . $newName;

                if ($ext === 'webp') {
                    $result['success'][] = $relativePath;
                    continue;
                }

                if ($convertToWebp) {
                    $webpPath = $this->convertToWebp($originalFullPath, $targetDir, $keepOriginal);
                    if ($webpPath) {
                        $result['success'][] = $webpPath;
                    } else {
                        $result['success'][] = $relativePath;
                        $result['errors'][] = "Failed to convert {$newName} to WebP.";
                    }
                } else {
                    $result['success'][] = $relativePath;
                }
            } catch (\Throwable $e) {
                $result['errors'][] = "Error saving {$newName}: " . $e->getMessage();
            }
        }

        return $result;
    }

    private function convertToWebp(string $sourcePath, string $targetDir, bool $keepOriginal): ?string
    {
        if (! extension_loaded('gd')) {
            Log::error('GD library is not installed.');
            return null;
        }

        $imageInfo = @getimagesize($sourcePath);
        if (! $imageInfo) {
            Log::error('Failed to read image info: ' . $sourcePath);
            return null;
        }

        $mimeType = $imageInfo['mime'];
        $image = null;

        switch ($mimeType) {
            case 'image/jpeg':
                $image = imagecreatefromjpeg($sourcePath);
                break;
            case 'image/png':
                $image = imagecreatefrompng($sourcePath);
                imagepalettetotruecolor($image);
                imagealphablending($image, true);
                imagesavealpha($image, true);
                break;
            default:
                Log::error('Unsupported image format for WebP conversion: ' . $mimeType);
                return null;
        }

        if (! $image) {
            Log::error('Failed to create GD image from: ' . $sourcePath);
            return null;
        }

        $targetPath = public_path($targetDir);
        $webpFilename = pathinfo($sourcePath, PATHINFO_FILENAME) . '.webp';
        $webpFullPath = $targetPath . DIRECTORY_SEPARATOR . $webpFilename;

        $success = imagewebp($image, $webpFullPath, 60);
        imagedestroy($image);

        if (! $success) {
            Log::error('Failed to save WebP: ' . $webpFullPath);
            return null;
        }

        if (! $keepOriginal) {
            @unlink($sourcePath);
        }

        return $targetDir . '/' . $webpFilename;
    }
}
