<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\ArtWork;
use App\Models\ArtWorkImages;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\URL;
class ArtWorkController extends Controller
{

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        try{
            $artworks = ArtWork::with('artist', 'category')->get();
            return response()->json([
                'artworks' => $artworks
            ]);
        }catch(\Exception $e){
            return response()->json([
                'message' => 'Error fetching artworks',
                'error' => $e->getMessage()
            ], 500);
        }   
    }

    /**
     * Store a newly created resource in storage.
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
                'images' => 'nullable|array',
                'images.*' => 'image|mimes:jpeg,png,jpg,gif|max:2048',
                'description' => 'required|string',
                'description_ar' => 'required|string',
                'for_rent' => 'required|boolean',
                'rent_price' => 'nullable|numeric',
                'status'=> 'required|in:active,inactive',
                // 'status_ar'=> 'required|in:active,نشط',
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
     * Display the specified resource.
     */
    public function show(ArtWork $artwork)
    {
        try{
            $artwork->load('artist', 'category', 'artWorkImages');
            return response()->json($artwork);  
        }catch(\Exception $e){
            return response()->json([
                'message' => 'Error fetching artwork',
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
                // 'status_ar' => 'required|in:active,نشط',
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
                foreach ($artwork->artWorkImages as $image) {
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
                'artwork' => $artwork->load('artist', 'category', 'artWorkImages')
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Error updating artwork',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(ArtWork $artwork)
    {
        try {
            // Delete the cover image
            if ($artwork->cover_image) {
                $oldCoverImagePath = str_replace(URL::to('/storage'), '', $artwork->cover_image);
                Storage::disk('public')->delete($oldCoverImagePath);
            }

            // Delete the artwork images
            foreach ($artwork->artWorkImages as $image) {
                $oldImagePath = str_replace(URL::to('/storage'), '', $image->image_path);
                Storage::disk('public')->delete($oldImagePath);
                $image->delete();
            }

            // Delete the artwork
            $artwork->delete();

            return response()->json([
                'message' => 'Artwork deleted successfully'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Error deleting artwork',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
