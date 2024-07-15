<?php

namespace DevREwais\MediaManagerPro\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\Facades\Image;

class ImageController extends Controller
{
    public function getImage($width, $storage, $model, $id, $filename)
    {
        /*        $width = $request->width;
                if (!isset($width)){
                    return response()->json(['error' => 'width is required'], 404);
                }*/
        $originalImagePathNew = "app/public/$model/$id/$filename";
        if (!file_exists(storage_path($originalImagePathNew))) {
            return response()->json(['error' => 'Image not found.'], 404);
        }
        $resizedImagePath = $this->saveResizedImage($originalImagePathNew, $width);
        return response()->file(storage_path("{$resizedImagePath}"));
    }

    public function saveResizedImage(string $originalImagePath, int $width): string
    {
        // Define the path for the company folder
        $folderPath = pathinfo($originalImagePath, PATHINFO_DIRNAME);
        $originalFilename = pathinfo($originalImagePath, PATHINFO_FILENAME);
        $originalExtension = pathinfo($originalImagePath, PATHINFO_EXTENSION);
        $extension = ($originalExtension == '.webp') ? $originalExtension : '.webp';
        // Define the resized image filename
        $resizedFilename = "{$originalFilename}_{$width}{$extension}";
        $resizedImagePath = "{$folderPath}/{$resizedFilename}";
        if (file_exists(storage_path($resizedImagePath))) {
            return $resizedImagePath;
        }
        // Load the original image
        $image = Image::make(storage_path($originalImagePath));
        // Calculate new dimensions
        $aspectRatio = $image->width() / $image->height();
        $newHeight = $width / $aspectRatio;
        if ($originalExtension != 'webp') {
            $image->encode('webp', 90)->resize($width, $newHeight)->save(storage_path($resizedImagePath));
            return $resizedImagePath;
        }
        $image->resize($width, $newHeight)->save(storage_path($resizedImagePath));
        return $resizedImagePath;
    }
}