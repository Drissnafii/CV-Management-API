<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use App\Http\Requests\StoreCVRequest;
use App\Http\Requests\UpdateCVRequest;
use App\Models\CV;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;
class CVController extends Controller
{
    use AuthorizesRequests;

    public function index()
    {
        $user = Auth::user();

        if (! $user) {
            return response()->json([
                'status' => 'error',
                'message'=> 'Unauthorized'
            ], 401);
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
        if ($cv->user_id !== Auth::id()) {
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

public function update(UpdateCVRequest $request, CV $cv)
{
    // La vérification de l'existence du CV n'est plus nécessaire
    // car le Route Model Binding renverra une erreur 404 si le CV n'est pas trouvé.

    // Utilisation de la politique d'autorisation
    $this->authorize('update', $cv);

    // Mise à jour du titre si présent
    if ($request->has('title')) {
        $cv->title = $request->title;
    }

    // Gestion de la mise à jour du fichier
    if ($request->hasFile('cv_file')) {
        $path_of_cv = $cv->file_path;
        if (Storage::disk('public')->exists($path_of_cv)) {
            Storage::disk('public')->delete($path_of_cv);
        }

        $file = $request->file('cv_file');
        $fileName = time() . '_' . Str::slug($request->title ?? $cv->title) . '.' . $file->getClientOriginalExtension();
        $filePath = $file->storeAs('cvs/' . Auth::id(), $fileName, 'public');

        $cv->file_path = $filePath;
        $cv->file_name = $fileName;
        $cv->file_type = $file->getClientMimeType();
        $cv->file_size = $file->getSize();
    }

    $cv->save();

    return response()->json([
        'status' => 'success',
        'message' => 'CV updated successfully',
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
        if (Storage::disk('public')->exists($cv->file_path)) {
            Storage::disk('public')->delete($cv->file_path);
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
