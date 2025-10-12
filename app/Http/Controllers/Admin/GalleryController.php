<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Gallery;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Str;

class GalleryController extends Controller
{

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        try{
            $galleries = Gallery::all();
            return response()->json($galleries);
        }catch(\Exception $error){
            return response()->json(['error' => $error->getMessage()], 500);
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        try{

            $validatedData = $request->validate([
                'user_id' => 'required|integer|exists:users,id',
                'auth_papers' => 'sometimes|file|mimes:pdf,png,jpg,jpeg',
                'gallery_name' => 'required|string',
                'gallery_name_ar' => 'required|string',
                'gallery_description' => 'required|string',
                'gallery_description_ar' => 'required|string',
                'logo' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
                'images' => 'nullable|array',
                'images.*' => 'image|mimes:jpeg,png,jpg,gif|max:2048'
            ]);
            
            if($request->hasFile('images')){
                $images = [];
                foreach($request->file('images') as $image){
                    $imagePath = $image->store('galleries', 'public');// store the image in the public disk
                    $images[] = URL::to(Storage::url($imagePath));
                }
                $validatedData['images'] = json_encode($images);
            }
            
            if($request->hasFile('logo')){
                $logo = $request->file('logo')->store('logos','public');
                $validatedData['logo'] = URL::to(Storage::url($logo));
            }
            if($request->hasFile('auth_papers')){
                $authPaperPath = $request->file('auth_papers')->store('auth_papers','public');
                $validatedData['auth_papers'] = URL::to(Storage::url($authPaperPath));
            }
            
            $gallery = Gallery::create($validatedData);
            return response()->json(['message'=>'Gallery created successfully'], 201);
        }catch(\Exception $error){
            return response()->json(['error' => $error->getMessage()], 500);
            }
    }

    /**
     * Display the specified resource.
     */
    public function show(Gallery $gallery)
    {
        try{
            return response()->json($gallery);
        }catch(\Exception $error){
            return response()->json(['error' => $error->getMessage()], 500);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Gallery $gallery)
    {
        
        try{

            $validatedData = $request->validate([
            'user_id' => 'required|integer|exists:users,id',
            'auth_papers' => 'sometimes|file|mimes:pdf,png,jpg,jpeg',
            'gallery_name' => 'sometimes|string',
            'gallery_name_ar' => 'sometimes|string',
            'gallery_description' => 'sometimes|string',
            'gallery_description_ar' => 'sometimes|string',
            'logo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'images' => 'nullable|array',
            'images.*' => 'image|mimes:jpeg,png,jpg,gif|max:2048'
            ]);

            if($request->hasFile('images')){
            $images = [];
            foreach($request->file('images') as $image){
                $imagePath = $image->store('galleries', 'public'); // store the image in the public disk
                $images[] = $imagePath;
            }
            $validatedData['images'] = $images;
            }

            if($request->hasFile('auth_papers')){
            $authPaperPath = $request->file('auth_papers')->store('auth_papers', 'public');
            $validatedData['auth_papers'] = $authPaperPath;
            }

            if($request->hasFile('logo')){
            $logoPath = $request->file('logo')->store('logos', 'public');
            $validatedData['logo'] = $logoPath;
            }

            $gallery->update($validatedData);

            return response()->json(['message' => 'Gallery updated successfully'], 200);
        }catch(\Exception $error){
            return response()->json(['error' => $error->getMessage()], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Gallery $gallery)
    {
        try{
            $gallery->delete();
            return response()->json(['message' => 'Gallery deleted successfully'], 200);
            }catch(\Exception $error){
                return response()->json(['error' => $error->getMessage()], 500);
                }
    }
}
