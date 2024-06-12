<?php

namespace App\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class UploadController extends Controller
{
    /**
     * Handle file upload.
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function upload(Request $request)
    {
        if ($request->hasFile('file')) {
            $file = $request->file('file');
            $path = $file->store('uploads', 'public');

            return response()->json(['path' => $path], 200);
        }

        return response()->json(['error' => 'No file uploaded'], 400);
    }

    /**
     * Delete image.
     *
     * @param string $imageName
     * @return JsonResponse
     */
    public function delete($imageName)
    {
        if (Storage::disk('public')->exists($imageName)) {
            Storage::disk('public')->delete($imageName);
            return response()->json(['message' => 'Image deleted successfully'], 200);
        }

        return response()->json(['error' => 'Image not found'], 404);
    }
}
