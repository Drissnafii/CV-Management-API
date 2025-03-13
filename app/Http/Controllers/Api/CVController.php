<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\CV;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;

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

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'title' => 'required|string|max:255',
            'cv_file' => 'required|file|mimes:pdf,docx|max:5120',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'errors' => $validator->errors()
            ], 422);
        }

        $file = $request->file('cv_file');
        $fileName = time() . '_' . Str::slug($request->title) . '.' . $file->getClientOriginalExtension();

        // Get authenticated user ID
        $userId = Auth::id();

        // Store file locally
        $filePath = Storage::disk('local')->put("cvs/{$userId}", $file);

        $cv = CV::create([
            'user_id' => $userId,
            'title' => $request->title,
            'file_path' => $filePath,
            'file_name' => $fileName,
            'file_size' => $file->getSize()
        ]);

        // ProcessCVFile::dispatch($cv);

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


        public function download(CV $cv)
        {
            // Check if the authenticated user owns the CV
            if ($cv->user_id !== Auth::id()) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Unauthorized access to CV'
                ], 403);
            }

            // Generate a download URL for the local file
            $filePath = Storage::disk('local')->path($cv->file_path);

            if (!file_exists($filePath)) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'File not found'
                ], 404);
            }

            $downloadUrl = url("download-cv/{$cv->id}");

            return response()->json([
                'status' => 'success',
                'download_url' => $downloadUrl
            ]);
        }

}
