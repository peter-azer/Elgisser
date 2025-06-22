<?php

namespace App\Http\Controllers;

use App\Models\Favorite;
use App\Http\Requests\StoreFavoriteRequest;
use App\Http\Requests\UpdateFavoriteRequest;

class FavoriteController extends Controller
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
    public function store(StoreFavoriteRequest $request, $artwork)
    {
        try{
            $favorite = Favorite::create([
                'user_id' => auth()->user()->id,
                'art_work_id' => $artwork,
                'artist_id' => $request->artist_id,
                'type' => $request->type ?? 'artwork', // Default to 'artwork' if not provided
            ]);
            return response()->json([
                'status' => 'success',
                'data' => $favorite,
            ]);
        }catch(\Exception $e){
            return response()->json([
                'status' => 'error',
                'message' => 'An error occurred while creating the favorite.',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show()
    {
        $user_id = auth()->user()->id;
        try{
            $usersFavorites = Favorite::where('user_id', $user_id)->with(['artWork', 'artist'])->get();
            return response()->json([
                'status' => 'success',
                'data' => $usersFavorites,
            ]);
        }catch(\Exception $e){
            return response()->json([
                'status' => 'error',
                'message' => 'An error occurred while fetching favorites.',
                'error' => $e->getMessage(),
            ], 500);
        }
    }
    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateFavoriteRequest $request, Favorite $favorite)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Favorite $favorite)
    {
        try{
            $favorite->delete();
            return response()->json([
                'status' => 'success',
                'message' => 'Favorite deleted successfully.',
            ]);
        }catch(\Exception $e){
            return response()->json([
                'status' => 'error',
                'message' => 'An error occurred while deleting the favorite.',
                'error' => $e->getMessage(),
            ], 500);
        }
    }
}
