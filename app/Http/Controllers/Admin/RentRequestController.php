<?php

namespace App\Http\Controllers\Admin;

use App\Models\RentRequest;
use App\Http\Requests\StoreRentRequestRequest;
use App\Http\Requests\UpdateRentRequestRequest;
use App\Http\Controllers\Controller;

class RentRequestController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $rentRequests = RentRequest::with(['gallery', 'artwork'])->get();
        return response()->json($rentRequests);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreRentRequestRequest $request)
    {
        try{
            $validatedData = $request->validate([
                'gallery_id' => 'required|exists:galleries,id',
                'art_work_id' => 'required|exists:art_works,id',
                'rental_start_date' => 'required|date',
                'rental_end_date' => 'required|date|after:rental_start_date',
                'rental_duration' => 'required|integer|min:1',
                'status' => 'in:pending,approved,disapproved'
            ]);
            $rentRequest = RentRequest::create($validatedData);
            return response()->json($rentRequest, 201);
        }catch(\Exception $e){
            return response()->json([
                'message' => 'Error creating rent request',
                'error' => $e->getMessage()
            ], 500);
        }   
    }

    /**
     * Display the specified resource.
     */
    public function show(RentRequest $rentRequest)
    {
        return response()->json($rentRequest->load(['gallery', 'artwork']));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateRentRequestRequest $request, RentRequest $rentRequest)
    {
        try {
            $validatedData = $request->validate([
            'gallery_id' => 'sometimes|exists:galleries,id',
            'art_work_id' => 'sometimes|exists:art_works,id',
            'rental_start_date' => 'sometimes|date',
            'rental_end_date' => 'sometimes|date|after:rental_start_date',
            'rental_duration' => 'sometimes|integer|min:1',
            'status' => 'in:pending,approved,disapproved'
            ]);
            $rentRequest->update($validatedData);
            return response()->json($rentRequest, 200);
        } catch (\Exception $e) {
            return response()->json([
            'message' => 'Error updating rent request',
            'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(RentRequest $rentRequest)
    {
        try{
            $rentRequest->delete();
            return response()->json([
                'message' => 'Rent request deleted successfully'
            ], 200);
        }catch(\Exception $e){
            return response()->json([
                'message' => 'Error deleting rent request',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
