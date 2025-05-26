<?php

namespace App\Http\Controllers;

use App\Models\Artist;
use App\Http\Requests\StoreArtistRequest;
use App\Http\Requests\UpdateArtistRequest;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\Storage;

class ArtistController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $artists = Artist::with('user')->get();
        return response()->json([
            'status' => true,
            'message' => 'Artists fetched successfully',
            'data' => $artists
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
    public function store(StoreArtistRequest $request)
    {
        $user = auth()->user()->id;
        $request->merge(['user_id' => $user]);
        try {
            $validatedData = $request->validate([
                'user_id' => 'required|integer|exists:users,id',
                'auth_papers' => 'required|file|mimes:pdf',
                'artist_name' => 'required|string',
                'artist_name_ar' => 'required|string',
                'experience' => 'required|string',
                'experience_ar' => 'required|string',
                'artist_bio' => 'required|string',
                'artist_bio_ar' => 'required|string',
                'artist_image' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048'
            ]);

            if ($request->hasFile('artist_image')) {
                $imagePath = $request->file('artist_image')->store('artists_images', 'public');
                $validatedData['artist_image'] = URL::to(Storage::url($imagePath));
            }

            if ($request->hasFile('auth_papers')) {
                $paperPath = $request->file('auth_papers')->store('auth_papers', 'public');
                $validatedData['auth_papers'] = URL::to(Storage::url($paperPath));
            }

            $artist = Artist::create($validatedData);
            return response()->json(['message' => 'Artist created successfully'], 201);
        } catch (\Exception $error) {
            return response()->json(['error' => $error->getMessage()], 500);
        }
    }

    /**
     * Upload portfolio data.
     */
    public function upload(Request $request){
        try{

            $validatedData = $request->validate([
                'artist_id' => 'required|integer|exists:artists,id',
                'portfolio_images.*' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
                'image_name' => 'nullable|string',
                'image_name_ar' => 'nullable|string',
                'image_type' => 'nullable|string',
                'image_type_ar' => 'nullable|string',
                'image_size' => 'nullable|integer',
                'image_description' => 'nullable|string',
                'image_description_ar' => 'nullable|string',
            ]);
            $artist = Artist::find($validatedData['artist_id']);
            
            if ($request->hasFile('portfolio_images')) {
                foreach ($request->file('portfolio_images') as $image) {
                    $imagePath = $image->store('portfolio_images', 'public');
                    $artist->portfolioImages()->create([
                        'image_path' => URL::to(Storage::url($imagePath)),
                        'artist_id' => $validatedData['artist_id'],
                        'image_name' => $validatedData['image_name'],
                        'image_type' => $validatedData['image_type'],
                        'image_size' => $validatedData['image_size'],
                        'image_description' => $validatedData['image_description'],
                    ]);
                }
            }
            
            return response()->json(['message' => 'Portfolio images uploaded successfully'], 201);
        }catch(\Exception $error){
            return response()->json(['error' => $error->getMessage()], 500);
        }
    }
    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        try{
            $artist = Artist::where('id', $id)
                    ->with('user', 'portfolioImages')
                    ->first();
            return response()->json([
                'status' => true,
                'message' => 'Artist fetched successfully',
                'data' => $artist
            ]);
        }catch(\Exception $e){
            return response()->json([
                'status' => false,
                'message' => 'Artist not found',
                'error' => $e->getMessage()
            ], 404);
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Artist $artist)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateArtistRequest $request, Artist $artist)
    {
        try {

            $validatedData = $request->validate([
            'user_id' => 'sometimes|integer|exists:users,id',
            'auth_papers' => 'nullable|file|mimes:pdf',
            'artist_name' => 'sometimes|string',
            'artist_name_ar' => 'sometimes|string',
            'experience' => 'sometimes|string',
            'experience_ar' => 'sometimes|string',
            'artist_bio' => 'sometimes|string',
            'artist_bio_ar' => 'sometimes|string',
            'artist_image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048'
            ]);

            if ($request->hasFile('artist_image')) {
            $imagePath = $request->file('artist_image')->store('artists_images', 'public');
            $validatedData['artist_image'] = URL::to(Storage::url($imagePath));
            }

            if ($request->hasFile('auth_papers')) {
            $paperPath = $request->file('auth_papers')->store('auth_papers', 'public');
            $validatedData['auth_papers'] = URL::to(Storage::url($paperPath));
            }

            $artist->update($validatedData);
            return response()->json(['message' => 'Artist updated successfully'], 200);
        } catch (\Exception $error) {
            return response()->json(['error' => $error->getMessage()], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Artist $artist)
    {
        //
    }
}
