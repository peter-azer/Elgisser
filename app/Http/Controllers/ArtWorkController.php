<?php

namespace App\Http\Controllers;

use App\Models\ArtWork;
use App\Models\ArtworkViewHistory;
use App\Http\Requests\StoreArtWorkRequest;
use App\Http\Requests\UpdateArtWorkRequest;
use Illuminate\Http\Request;
use App\Models\Artist;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\Storage;
use App\Models\ArtWorkImages;

class ArtWorkController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $artworks = ArtWork::with('artist','category', 'style', 'subject', 'medium','material')->get();
        return response()->json($artworks);
    }
    /**
     * Display the specified resource.
     */
    public function show(ArtWork $artWork)
    {
        try{
            $artwork = ArtWork::with('artist', 'category', 'style', 'subject', 'medium','material', 'artWorkImages')->findOrFail($artWork->id);
            return response()->json($artwork);
        }catch(\Exception $e){
            return response()->json([
                'message' => 'Artwork not found',
                'error' => $e->getMessage()
            ], 404);
        }
    }

    /**
     * Store artist artworks.
     */
    public function store(Request $request)
    {
        try{
            $validatedData = $request->validate([
                'artist_id' => 'required|exists:artists,id',
                'category_id' => 'required|exists:categories,id',
                'style_id' => 'required|exists:styles,id',
                'subject_id' => 'required|exists:subjects,id',
                'media_id' => 'required|exists:media,id',
                'material_id' => 'required|exists:materials,id',
                'title' => 'required|string|max:255',
                'title_ar' => 'required|string|max:255',
                'price' => 'required|numeric',
                'dimensions' => 'required|string|max:255',
                'quantity' => 'required|integer',
                'one_of_a_kind' => 'required|boolean',
                'cover_image' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
                'images' => 'array',
                'images.*' => 'image|mimes:jpeg,png,jpg,gif|max:2048',
                'description' => 'required|string',
                'description_ar' => 'required|string',
                'for_rent' => 'required|boolean',
                'rent_price' => 'nullable|numeric',
                'status'=> 'required|in:active,inactive',
            ]);
            if ($request->hasFile('cover_image')) {
                $coverImagePath = $request->file('cover_image')->store('artworks', 'public');
                $validatedData['cover_image'] = URL::to(Storage::url($coverImagePath));
            }
            $artwork = ArtWork::create($validatedData);
            if ($request->hasFile('images')) {
                foreach ($request->file('images') as $image) {
                    $imagePath = $image->store('artworks', 'public');
                    $imgPath= URL::to(Storage::url($imagePath));
                    $artworkImage = ArtWorkImages::create([
                        'art_work_id' => $artwork->id,
                        'image_path' => $imgPath
                    ]);
                }
            }
        }catch(\Exception $e){
            return response()->json([
                'message' => 'Error creating artwork',
                'error' => $e->getMessage()
            ], 500);
        }
    }
        /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, ArtWork $artwork)
    {
        try {
            $validatedData = $request->validate([
                'artist_id' => 'required|exists:artists,id',
                'category_id' => 'required|exists:categories,id',
                'style_id' => 'required|exists:styles,id',
                'subject_id' => 'required|exists:subjects,id',
                'media_id' => 'required|exists:media,id',
                'material_id' => 'required|exists:materials,id',
                'title' => 'required|string|max:255',
                'title_ar' => 'required|string|max:255',
                'price' => 'required|numeric',
                'dimensions' => 'required|string|max:255',
                'quantity' => 'required|integer',
                'one_of_a_kind' => 'required|boolean',
                'cover_image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
                'images' => 'array',
                'images.*' => 'image|mimes:jpeg,png,jpg,gif|max:2048',
                'description' => 'required|string',
                'description_ar' => 'required|string',
                'for_rent' => 'required|boolean',
                'rent_price' => 'nullable|numeric',
                'status' => 'required|in:active,inactive',
            ]);

            if ($request->hasFile('cover_image')) {
                // Delete the old cover image if it exists
                if ($artwork->cover_image) {
                    $oldCoverImagePath = str_replace(URL::to('/storage'), '', $artwork->cover_image);
                    Storage::disk('public')->delete($oldCoverImagePath);
                }
                $coverImagePath = $request->file('cover_image')->store('artworks', 'public');
                $validatedData['cover_image'] = URL::to(Storage::url($coverImagePath));
            }

            $artwork->update($validatedData);

            if ($request->hasFile('images')) {
                // Delete old images
                foreach ($artwork->images as $image) {
                    $oldImagePath = str_replace(URL::to('/storage'), '', $image->image_path);
                    Storage::disk('public')->delete($oldImagePath);
                    $image->delete();
                }

                // Add new images
                foreach ($request->file('images') as $image) {
                    $imagePath = $image->store('artworks', 'public');
                    $imgPath = URL::to(Storage::url($imagePath));
                    ArtWorkImages::create([
                        'art_work_id' => $artwork->id,
                        'image_path' => $imgPath
                    ]);
                }
            }

            return response()->json([
                'message' => 'Artwork updated successfully',
                'artwork' => $artwork->load('artist', 'category', 'images')
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Error updating artwork',
                'error' => $e->getMessage()
            ], 500);
        }
    }
    /**
     * Display artist artworks.
     */
    public function showArtistArtwork(){
        $user = auth()->user();
        $artist = Artist::where('user_id', $user->id)->first();
        $artworks = ArtWork::where('artist_id', $artist->id)->get();
        return response()->json($artworks);
    }
    /**
     * Display the most viewed artworks.
     */
    public function mostViewed()
    {
        $artworks = ArtworkViewHistory::select('artwork_id', DB::raw('COUNT(*) as views'))
            ->groupBy('artwork_id')
            ->orderBy('views', 'desc')
            ->take(10)
            ->get();
        return response()->json($artworks);
    }
    /**
     * Display recent viewed artworks.
     */
    public function recentViewed()
    {
        $artworks = ArtworkViewHistory::select('user_id', DB::raw('MAX(created_at) as last_viewed'))
            ->groupBy('user_id')
            ->orderBy('last_viewed', 'desc')
            ->take(10)
            ->get();
        return response()->json($artworks);
    }

}
