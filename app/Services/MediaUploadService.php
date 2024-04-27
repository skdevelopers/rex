<?php

namespace App\Services;

use Illuminate\Support\Facades\Storage;
use Spatie\MediaLibrary\MediaCollections\Models\Media;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Validator;

class MediaUploadService
{
    public function uploadAndAttachMedia($file, $model): array
    {
        // Validate file type
        $validator = Validator::make(['file' => $file], [
            'file' => ['required', 'file', 'mimes:jpg,jpeg,png,gif,webp,svg'], // Add more mime types as needed
        ]);

        if ($validator->fails()) {
        // Handle validation failure
            return ['success' => false, 'message' => $validator->errors()->first()];
        }

        // Optimize image before uploading (optional)
        if (in_array($file->extension(), ['jpg', 'jpeg', 'png', 'gif', 'webp', 'svg'])) {
            $file = $this->optimizeImage($file);
        }

        // Upload and attach media
        $media = $model->addMedia($file)->toMediaCollection();
        dd($media);
        // Additional operations on $media if needed

        return ['success' => true, 'media' => $media];
    }

    protected function optimizeImage($file)
    {
        // Define maximum dimensions for the optimized image
        $maxWidth = 1200; // Adjust maximum width as needed
        $maxHeight = 800; // Adjust maximum height as needed

        // Get the original image dimensions
        $originalWidth = $file->getWidth();
        $originalHeight = $file->getHeight();

        // Calculate dimensions for resizing while maintaining aspect ratio
        if ($originalWidth > $originalHeight) {
            $manipulations = [
                'optimize' => true,
                'width' => $maxWidth,
                'height' => null, // Automatically calculate height to maintain aspect ratio
            ];
        } else {
            $manipulations = [
                'optimize' => true,
                'width' => null, // Automatically calculate width to maintain aspect ratio
                'height' => $maxHeight,
            ];
        }

        // Perform image optimization
        $optimizedImage = $file->manipulate($manipulations);

        // Get the model type (e.g., "App\Models\Product")
        $modelType = $file->model_type;

        // Extract the model name from the model type (e.g., "Product")
        $modelName = class_basename($modelType);

        // Construct the directory path based on the model name
        $directory = '/' . strtolower($modelName);

        // Store the optimized image in the directory specific to the model
        $path = Storage::disk($file->disk)->putFile($directory, $optimizedImage);

        // Delete the original image
        $file->delete();

        // Get the optimized media object
        return $file->copy($file->disk, $path);
    }
}
