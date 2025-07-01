<?php

namespace App\Http\Controllers;

use App\Models\RentRequest;
use App\Http\Requests\StoreRentRequestRequest;
use App\Http\Requests\UpdateRentRequestRequest;
use App\Services\RentedArtworkNumberService;
use App\Models\Artist;
use App\Models\Gallery;
use App\Models\RentedArtWork;
use Illuminate\Http\Request;

class RentRequestController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $user = auth()->user()->id;
        $artist = Artist::where('user_id', $user)->first();
        $rentRequests = RentRequest::where('artist_id', $artist->id)
                        ->with('artwork', 'gallery')
                        ->get();
        return response()->json([
            'rentRequests' => $rentRequests,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreRentRequestRequest $request)
    {
        $userId = auth()->user()->id;
        $gallery = Gallery::where('user_id', $userId)->first();

        $request->merge(['gallery_id' => $gallery->id]);
        try{
            $validatedData = $request->validate([
                'gallery_id' => 'required|exists:galleries,id',
                'art_work_id' => 'required|exists:art_works,id',
                'artist_id' => 'required|exists:artists,id',
                'rental_start_date' => 'required|date|after_or_equal:today',
                'rental_end_date' => 'required|date|after:rental_start_date',
                'rental_duration' => 'required|integer|min:1',
                // 'status' => 'required|in:pending,approved,disapproved',
            ]);

            $rentRequest = RentRequest::create($validatedData); 
            return response()->json([
                'message' => 'Rent request created successfully',
                'rentRequest' => $rentRequest,
            ], 201);
        }catch(\Exception $error){
            return response()->json([
                'message' => 'Error creating rent request',
                'error' => $error->getMessage(),
            ], 500);
        }
    }
    public function galleryRentRequests()
    {
        $user = auth()->user()->id;
        $rentRequests = RentRequest::where('gallery_id', $user)
                        ->with('artwork', 'artist')
                        ->get();
        return response()->json([
            'rentRequests' => $rentRequests,
        ]);
    }
    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $rentRequest = RentRequest::with('artwork', 'gallery')->find($id);
        if (!$rentRequest) {
            return response()->json([
                'message' => 'Rent request not found',
            ], 404);
        }
        return response()->json([
            'rentRequest' => $rentRequest,
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function approve(Request $id)
    {
        try{
            $rentRequest = RentRequest::findOrFail($id);
            $rentRequest->update(['status' => 'approved']);
            $rental_code = RentedArtworkNumberService::generate();
            RentedArtWork::create([
                'art_work_id' => $rentRequest->art_work_id,
                'gallery_id' => $rentRequest->gallery_id,
                'rental_code' => $rental_code,
                'rental_start_date' => $rentRequest->rental_start_date,
                'rental_end_date' => $rentRequest->rental_end_date,
                'rental_duration' => $rentRequest->rental_duration,
                'rental_status' => 'active',
                'payment_status' => 'pending',
                'payment_method' => 'N/A',
            ]);
            return response()->json([
                'message' => 'Rent request approved successfully',
                'rentRequest' => $rentRequest,
            ]);
        }catch(\Exception $error){
            return response()->json(['message'=> $error->getMessage()], 500);
        }
    }
    public function disapprove(RentRequest $rentRequest)
    {
        try{
            $rentRequest->status = 'disapprove';
            $rentRequest->save();
            return response()->json([
                'message' => 'Rent request disapprove successfully',
                'rentRequest' => $rentRequest,
            ]);
        }catch(\Exception $error){
            return response()->json(['message'=> $error->getMessage()], 500);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateRentRequestRequest $request, RentRequest $rentRequest)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(RentRequest $rentRequest)
    {
        //
    }
}
