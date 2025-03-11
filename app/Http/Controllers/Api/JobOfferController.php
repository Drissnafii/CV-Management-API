<?php

namespace App\Http\Controllers\Api;


use App\Http\Controllers\Controller;
use App\Models\JobOffer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class JobOfferController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $jobOffers = JobOffer::paginate(10);
        return response()->json([
            'status' => 'success',
            'data' => $jobOffers
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validator = Validator()->make($request->all(), [
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'location' => 'required|string|max:255',
            'category' => 'required|string|max:255',
            'contact_type' => 'required|string|max:255',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'errors' => $validator->errors()
            ], 422);
        }

        $jobOffer = JobOffer::create([
            'title' => $request->title,
            'description' => $request->description,
            'location' => $request->location,
            'category' => $request->category,
            'contact_type' => $request->contact_type,
            'recruiter_id' => Auth::id()
        ]);

        return response()->json([
            'status' => 'success',
            'message' => 'Job offer created successfully',
            'data' => $jobOffer
        ], 201);

    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $jobOffer = JobOffer::findOrFail($id);

            return response()->json([
                'status' => 'error',
                'message' => 'Job offer not found',
                'data' => $jobOffer
            ],404);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $jobOffer = JobOffer::findOrFail($id);

        if (!$jobOffer) {
            return response()->json([
                'status' => 'error',
                'message' => 'Job offer not found',
                'data' => $jobOffer
            ],404);
        }

        $validator = Validator()->make($request->all(), [
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'location' => 'required|string|max:255',
            'category' => 'required|string|max:255',
            'contact_type' => 'required|string|max:255',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'errors' => $validator->errors()
            ], 422);
        }

        $jobOffer->update($request->all());
        return response()->json([
            'status' => 'success',
            'message' => 'Job offer updated successfully',
            'data' => $jobOffer->fresh()
        ]);

    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $jobOffer = JobOffer::find($id);
        if (!$jobOffer) {

            return response()->json([
                'status' => 'error',
                'message' => 'Job offer not found'
            ], 404);
        }

        $jobOffer->delete();

        return response()->json([
            'status' => 'success',
            'message' => 'Job offer delete successfully'
        ]);
    }
}
