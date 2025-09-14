<?php

use Illuminate\Support\Str;
use Intervention\Image\Facades\Image;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpFoundation\File\File; // Parent of UploadedFile

if (!function_exists('storeWebPWithSpatie')) {
    function storeWebPWithSpatie($model, File $file, string $collection = 'default', int $quality = 85): mixed
    {
        if (!file_exists($file->getRealPath())) {
            return null;
        }

        try {
            $webp = Image::make($file->getRealPath())->encode('webp', $quality);
        } catch (\Exception $e) {
            return null;
        }

        $tempFilename = Str::uuid() . '.webp';
        $tempPath = storage_path('app/' . $tempFilename);

        if (file_put_contents($tempPath, $webp) === false || !file_exists($tempPath)) {
            return null;
        }

        $media = $model->addMedia($tempPath)
            ->usingFileName(Str::random(20) . '.webp')
            ->toMediaCollection($collection);

        @unlink($tempPath);

        return $media;
    }
}

if (!function_exists('storeWebP')) {
    function storeWebP(File $file, string $directory = 'uploads', int $quality = 85): ?string
    {
        if (!file_exists($file->getRealPath())) {
            return null;
        }

        try {
            $webp = Image::make($file->getRealPath())->encode('webp', $quality);
        } catch (\Exception $e) {
            return null;
        }

        $filename = Str::random(40) . '.webp';
        $path = $directory . '/' . $filename;

        if (!Storage::disk('public')->put($path, (string) $webp)) {
            return null;
        }

        return $path;
    }
}

