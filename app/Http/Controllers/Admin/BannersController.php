<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Banners;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Str;
class BannersController extends Controller
{

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        try{
            $banners = Banners::all();
            return response()->json($banners);
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
                'title' => 'required|string|max:255',
                'title_ar' => 'required|string|max:255',
                'description' => 'nullable|string',
                'description_ar' => 'nullable|string',
                'image' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
                'url' => 'nullable|url',
                'url_text' => 'nullable|string|max:255',
            ]);

            if($request->hasFile('image')){
                $imagePath = $request->file('image')->store('banners', 'public');
                $validatedData['image'] = URL::to(Storage::url($imagePath));
            }

            $banner = Banners::create($validatedData);
            return response()->json(['message' => 'Banner created successfully', 'banner' => $banner]);
        }catch(\Exception $error){
            return response()->json(['error' => $error->getMessage()], 500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Banners $banner)
    {
        try{
            return response()->json($banner);
        }catch(\Exception $error){
            return response()->json(['error' => $error->getMessage()], 500);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Banners $banner)
    {
        try{
            $validatedData = $request->validate([
            'title' => 'required|string|max:255',
            'title_ar' => 'required|string|max:255',
            'description' => 'nullable|string',
            'description_ar' => 'nullable|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'url' => 'nullable|url',
            'url_text' => 'nullable|string|max:255',
            ]);

            if($request->hasFile('image')){
            $imagePath = $request->file('image')->store('banners', 'public');
            $validatedData['image'] = URL::to(Storage::url($imagePath));
            }

            $banner->update($validatedData);
            return response()->json(['message' => 'Banner updated successfully', 'banner' => $banner]);
        }catch(\Exception $error){
            return response()->json(['error' => $error->getMessage()], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Banners $banner)
    {
        try{
            $banner->delete();
            return response()->json(['message' => 'Banner deleted successfully']);
            }catch(\Exception $error){
                return response()->json(['error' => $error->getMessage()], 500);
                }
    }
}
