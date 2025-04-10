<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Artist;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Str;

class ArtistController extends Controller
{

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        try {
            $artists = Artist::all();
            return response()->json($artists);
        } catch (\Exception $error) {
            return response()->json(['error' => $error->getMessage()], 500);
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
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
     * Display the specified resource.
     */
    public function show(Artist $artist)
    {
        try {
            return response()->json($artist);
        } catch (\Exception $error) {
            return response()->json(['error' => $error->getMessage()], 500);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Artist $artist)
    {
        try {

            $validatedData = $request->validate([
                'user_id' => 'sometimes|integer|exists:users,id',
                'auth_papers' => 'sometimes|file|mimes:pdf',
                'artist_name' => 'sometimes|string',
                'artist_name_ar' => 'sometimes|string',
                'experience' => 'sometimes|string',
                'experience_ar' => 'sometimes|string',
                'artist_bio' => 'sometimes|string',
                'artist_bio_ar' => 'sometimes|string',
                'artist_image' => 'sometimes|image|mimes:jpeg,png,jpg,gif|max:2048'
            ]);

            if ($request->hasFile('artist_image')) {
                // delete old image
                if ($artist->artistImage) {
                    Storage::disk('public')->delete($artist->artist_image);
                }
                // upload the new image
                $imagePath = $request->file('artist_image')->store('artists_images', 'public');
                $validatedData['artist_image'] = URL::to(Storage::url($imagePath));
            }

            if ($request->hasFile('auth_papers')) {
                // delete old papers
                if ($artist->authPapers) {
                    Storage::disk('public')->delete($artist->auth_papers);
                    }
                // upload the new papers
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
     * Remove the specified resource from storage.
     */
    public function destroy(Artist $artist)
    {
        try{
            if ($artist->artistImage) {
                Storage::disk('public')->delete($artist->artist_image);
                }
                if ($artist->authPapers) {
                    Storage::disk('public')->delete($artist->auth_papers);
                    }
                    $artist->delete();
                    return response()->json(['message' => 'Artist deleted successfully'], 200);
        }catch(\Exception $error){
            return response()->json(['error' => $error->getMessage()], 500);
        }
    }
}
