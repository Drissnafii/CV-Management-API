<?php

use App\Models\CV;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Response;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/test-cloud-storage', function () {
    try {
        // Create a test file with current timestamp
        $content = 'This is a test file created at ' . now()->toDateTimeString();
        $fileName = 'test-' . time() . '.txt';

        // Attempt to store the file
        Storage::disk('s3')->put($fileName, $content);

        // Check if the file exists
        $exists = Storage::disk('s3')->exists($fileName);

        // For private buckets, always use temporaryUrl
        $url = null;
        try {
            // Generate a temporary URL that expires in 5 minutes
            $url = Storage::disk('s3')->temporaryUrl(
                $fileName,
                now()->addMinutes(5)
            );
            $urlType = "Temporary URL (for private bucket)";
        } catch (\Exception $e) {
            $url = "Could not generate temporary URL: " . $e->getMessage();
            $urlType = "Error";
        }

        return response()->json([
            'success' => true,
            'message' => 'File uploaded successfully',
            'file_exists' => $exists,
            'file_name' => $fileName,
            'url' => $url,
            'url_type' => $urlType,
            'configuration' => [
                'driver' => config('filesystems.disks.s3.driver'),
                'bucket' => config('filesystems.disks.s3.bucket'),
                'region' => config('filesystems.disks.s3.region'),
                'endpoint' => config('filesystems.disks.s3.endpoint'),
                // Not showing sensitive keys
            ]
        ]);
    } catch (\Exception $e) {
        return response()->json([
            'success' => false,
            'message' => 'Error testing cloud storage',
            'error' => $e->getMessage(),
            'configuration' => [
                'driver' => config('filesystems.disks.s3.driver'),
                'bucket' => config('filesystems.disks.s3.bucket'),
                'region' => config('filesystems.disks.s3.region'),
                'endpoint' => config('filesystems.disks.s3.endpoint'),
                // Not showing sensitive keys
            ]
        ], 500);
    }
});


Route::get('download-cv/{id}', function ($id) {
    // Find the CV
    $cv = CV::find($id);

    if (!$cv) {
        abort(404, 'CV not found');
    }

    $filePath = $cv->file_path;

    // Check if the file exists
    if (!Storage::disk('public')->exists($filePath)) {
        abort(404, 'File not found');
    }

    // Return the file as a download
    $fullPath = Storage::disk('public')->path($filePath);
    return Response::download($fullPath, $cv->file_name);
})->name('cv.download');
