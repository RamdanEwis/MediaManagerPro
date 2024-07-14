<?php

namespace Vendor\DevREwais\MediaManagerPro\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\Facades\Image;

class ImageController extends Controller
{
    public function getImage($width, $storage, $model, $id, $filename)
    {
        $originalImagePath = "$storage/$model/$id/$filename";
        if (!Storage::disk('public')->exists($originalImagePath)) {
            return response()->json(['error' => 'Image not found.'], 404);
        }

        $resizedImagePath = $this->saveResizedImage($originalImagePath, $width);
        return response()->file(storage_path("app/public/{$resizedImagePath}"));
    }

    protected function saveResizedImage(string $originalImagePath, int $width): string
    {
        $folderPath = pathinfo($originalImagePath, PATHINFO_DIRNAME);
        $originalFilename = pathinfo($originalImagePath, PATHINFO_FILENAME);
        $extension = 'webp';
        $resizedFilename = "{$originalFilename}_{$width}.{$extension}";
        $resizedImagePath = "{$folderPath}/{$resizedFilename}";

        if (Storage::disk('public')->exists($resizedImagePath)) {
            return $resizedImagePath;
        }

        $image = Image::make(storage_path("app/public/{$originalImagePath}"))->encode('webp', 90);
        $aspectRatio = $image->width() / $image->height();
        $newHeight = $width / $aspectRatio;
        $image->resize($width, $newHeight)->save(storage_path("app/public/{$resizedImagePath}"));

        return $resizedImagePath;
    }
}
