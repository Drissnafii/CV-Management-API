<?php

namespace App\Http\Controllers\Api;

use Illuminate\Support\Facades\Auth;
use App\Jobs\ProcessJobApplication;
use App\Jobs\SendApplicationConfirmationEmail;
use App\Models\JobApplication;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

use App\Http\Controllers\Controller;
class JobApplicationController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'job_offer_id' => 'required|integer|exists:job_offers,id',
            'cv_id' => 'required|integer|exists:cvs,id',
            'cover_letter' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'errors' => $validator->errors()
            ], 422);
        }
        $jobApplication = JobApplication::create([
            'job_offer_id' => $request->job_offer_id,
            'cv_id' => $request->cv_id,
            'cover_letter' => $request->cover_letter,
            'user_id' => Auth::user()->id, // Assurez-vous que l'utilisateur est authentifié
        ]);

        // Dispatch le job pour le traitement de la candidature
        ProcessJobApplication::dispatch($jobApplication);

        // Dispatch le job pour l'envoi de l'email de confirmation
        SendApplicationConfirmationEmail::dispatch($jobApplication);

        return response()->json([
            'status' => 'success',
            'message' => 'Votre candidature a été soumise avec succès.'
        ], 201);
    }


    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
