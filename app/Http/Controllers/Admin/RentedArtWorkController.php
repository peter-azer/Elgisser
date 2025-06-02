<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\RentedArtWork;
use App\Services\RentedArtworkNumberService;
class RentedArtWorkController extends Controller
{

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $rentedArtWorks = RentedArtWork::with(['artWork', 'user'])->get();
        return response()->json([
            'rentedArtWorks' => $rentedArtWorks
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        try{
            $rentNumber = RentedArtworkNumberService::generate();
            $request->merge(['rental_code' => $rentNumber]);
            $validatedData = $request->validate([
                'art_work_id' => 'required|exists:art_works,id',
                'gallery_id' => 'required|exists:galleries,id',
                'rental_code' => 'required|string|unique:rented_art_works,rental_code',
                'rental_start_date' => 'required|date',
                'rental_end_date' => 'required|date|after:rental_start_date',
                'rental_duration' => 'required|integer|min:1',
                'rental_price' => 'required|numeric|min:0',
                'rental_status' => 'required|in:active,returned'
            ]);
            $rentedArtWork = RentedArtWork::create($validatedData);
            return response()->json([
                'message' => 'Rented Art Work created successfully',
                'rentedArtWork' => $rentedArtWork
            ], 201);
        }
        catch(\Exception $e){
            return response()->json([
                'message' => 'Error creating rented art work',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(RentedArtWork $rentedArtWork)
    {
        return response()->json([
            'rentedArtWork' => $rentedArtWork->load(['artWork', 'user'])
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, RentedArtWork $rentedArtWork)
    {
        try {
            $validatedData = $request->validate([
                'art_work_id' => 'sometimes|exists:art_works,id',
                'gallery_id' => 'sometimes|exists:galleries,id',
                'rental_code' => 'sometimes|string|unique:rented_art_works,rental_code',
                'rental_start_date' => 'sometimes|date',
                'rental_end_date' => 'sometimes|date|after:rental_start_date',
                'rental_duration' => 'sometimes|integer|min:1',
                'rental_price' => 'sometimes|numeric|min:0',
                'rental_status' => 'sometimes|in:active,returned'
            ]);
            $rentedArtWork->update($validatedData);
            return response()->json([
            'message' => 'Rented Art Work updated successfully',
            'rentedArtWork' => $rentedArtWork
            ]);
        } catch (\Exception $e) {
            return response()->json([
            'message' => 'Error updating rented art work',
            'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function status(Request $request, RentedArtWork $rentedArtWork)
    {
        try {
            $validatedData = $request->validate([
                'rental_status' => 'required|string|in:active,returned'
            ]);
            $rentedArtWork->update($validatedData);
            return response()->json([
                'message' => 'Rented Art Work status updated successfully',
                'rentedArtWork' => $rentedArtWork
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Error updating rented art work status',
                'error' => $e->getMessage()
            ], 500);
        }
    }
    /**
     * Remove the specified resource from storage.
     */
    public function destroy(RentedArtWork $rentedArtWork)
    {
        try {
            // Check if the rented art work is already deleted (soft delete check)
            if ($rentedArtWork->trashed()) {
                return response()->json([
                    'message' => 'Rented Art Work already deleted'
                ], 404);
            }

            // Delete the rented art work
            $rentedArtWork->delete();
            return response()->json([
                'message' => 'Rented Art Work deleted successfully'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Error deleting rented art work',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
