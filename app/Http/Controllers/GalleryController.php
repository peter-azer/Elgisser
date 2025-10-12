<?php

namespace App\Http\Controllers;

use App\Models\Gallery;
use App\Http\Requests\StoreGalleryRequest;
use App\Http\Requests\UpdateGalleryRequest;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\URL;

class GalleryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $galleries = Gallery::all();
        return response()->json($galleries);
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
    public function store(StoreGalleryRequest $request)
    {
        
        $user = auth()->user()->id;
        $request->merge(['user_id' => $user]);
        try {
            $validatedData = $request->validate([
            'user_id' => 'required|integer|exists:users,id',
            'auth_papers' => 'sometimes|file|mimes:pdf,png,jpg,jpeg',
            'gallery_name' => 'required|string',
            'gallery_name_ar' => 'required|string',
            'gallery_description' => 'nullable|string',
            'gallery_description_ar' => 'nullable|string',
            'logo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'images.*' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048'
            ]);

            // Handle file uploads
            if ($request->hasFile('auth_papers')) {
            $authPapersPath = $request->file('auth_papers')->store('auth_papers', 'public');
            $validatedData['auth_papers'] = URL::to(Storage::url($authPapersPath));
            }

            if ($request->hasFile('logo')) {
            $logoPath = $request->file('logo')->store('gallery_logos', 'public');
            $validatedData['logo'] = URL::to(Storage::url($logoPath));
            }

            if ($request->hasFile('images')) {
            $imagePaths = [];
            foreach ($request->file('images') as $image) {
                $path = $image->store('gallery_images', 'public');
                $imagePaths[] = URL::to(Storage::url($path));
            }
            $validatedData['images'] = json_encode($imagePaths);
            }

            $gallery = Gallery::create($validatedData);
            return response()->json(['status' => true, 'message' => 'Gallery created successfully', 'data' => $gallery], 201);
        } catch (\Exception $error) {
            return response()->json(['error' => $error->getMessage()], 500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        try{
            $gallery = Gallery::where('id', $id)->with('events')->get();
            return response()->json($gallery);
        }catch(\Exception $error){
            return response()->json(['error' => $error->getMessage()], 404);
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Gallery $gallery)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateGalleryRequest $request, Gallery $gallery)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Gallery $gallery)
    {
        //
    }
}
