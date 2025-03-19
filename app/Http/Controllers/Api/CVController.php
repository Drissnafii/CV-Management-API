<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreCVRequest;
use App\Models\CV;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class CVController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        if (! $user) {
            return response()->json([
                'status' => 'error',
                'message'=> 'Unauthorized'
            ],401);
        }

        $cvs = $user->cvs;

        return response()->json([
            'status' => 'success',
            'data' => $cvs
        ]);
    }

    public function store(StoreCVRequest $storeCVRequest)
    {
        // all the store request
        
        $file = $storeCVRequest->file('cv_file');
        $fileName = time() . '_' . Str::slug($storeCVRequest->title) . '.' . $file->getClientOriginalExtension();

        // Store file locally
        $filePath = $file->storeAs('cvs/' . Auth::id(), $fileName, 'public');

        $cv = CV::create([
            'user_id' => Auth::id(),
            'title' => $storeCVRequest->title,
            'file_path' => $filePath,
            'file_name' => $fileName,
            'file_type' => $file->getClientMimeType(),
            'file_size' => $file->getSize()
        ]);

        return response()->json([
            'status' => 'success',
            'message' => 'CV uploaded successfully',
            'data' => $cv
        ], 201);
    }


        public function show(CV $cv)
        {
            // Check if the authenticated user owns the CV
            if ($cv->user_id !== Auth::id()) {  // ! + == => !==
                return response()->json([
                    'status' => 'error',
                    'message' => 'Unauthorized access to CV'
                ], 403);
            }

            return response()->json([
                'status' => 'success',
                'data' => $cv
            ]);
        }


        public function destroy(CV $cv)
        {
            // Check if the authenticated user owns the CV
            if ($cv->user_id !== Auth::id()) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Unauthorized access to CV'
                ], 403);
            }

            // Delete file from local storage
            if (Storage::disk('local')->exists($cv->file_path)) {
                Storage::disk('local')->delete($cv->file_path);
            }

            $cv->delete();

            return response()->json([
                'status' => 'success',
                'message' => 'CV deleted successfully (from local)'
            ]);
        }


        public function download($id)
        {
            if (!Auth::check()) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Not authenticated'
                ], 401);
            }

            // Find CV
            $cv = CV::find($id);

            // Check if the cv exists
            if (!$cv) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'CV not found'
                ], 404);
            }

            // Check if the authenticated user owns the CV
            if ($cv->user_id !== Auth::id()) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Unauthorized access to CV',
                    'debug' => [
                        'cv_user_id' => $cv->user_id,
                        'auth_user_id' => Auth::id()
                    ]
                ], 403);
            }

            // Generate a download URL for the file - use public disk to match where files are stored
            $filePath = Storage::disk('public')->path($cv->file_path);

            if (!file_exists($filePath)) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'File not found',
                    'path' => $cv->file_path
                ], 404);
            }

            $downloadUrl = url("download-cv/{$cv->id}");

            return response()->json([
                'status' => 'success',
                'download_url' => $downloadUrl
            ]);
        }

}
