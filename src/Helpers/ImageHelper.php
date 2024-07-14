<?php

namespace Vendor\DevREwais\MediaManagerPro\Helpers;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\Facades\Image;

class ImageHelper
{
    public static function addMediaSingle(UploadedFile $image, $modelName, $modelId): string
    {
        $directory = "$modelName/$modelId";
        if (!Storage::exists($directory)) {
            Storage::makeDirectory($directory);
        }

        $filename = md5(uniqid()) . '.' . $image->getClientOriginalExtension();
        $filePath = $image->storeAs($directory, $filename, ['disk' => 'public']);

        Image::make(storage_path("app/public/{$filePath}"))->save();

        return "storage/$filePath";
    }

    public static function updateMediaSingle(UploadedFile $image, $modelName, $modelId, $existingImagePath)
    {
        $directory = "$modelName/$modelId";
        if (!Storage::exists($directory)) {
            Storage::makeDirectory($directory);
        }

        if ($existingImagePath && Storage::disk('public')->exists($existingImagePath)) {
            Storage::disk('public')->delete($existingImagePath);
        }

        $filename = md5(uniqid()) . '.' . $image->getClientOriginalExtension();
        $filePath = $image->storeAs($directory, $filename, ['disk' => 'public']);

        Image::make(storage_path("app/public/{$filePath}"))->save();

        return "storage/$filePath";
    }
}
