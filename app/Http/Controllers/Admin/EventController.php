<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Event;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Str;
class EventController extends Controller
{

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        try{
            $events = Event::all();
            return response()->json($events);
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
                'gallery_id' => 'required|integer',       
                'event_name' => 'required|string|max:255',       
                'event_start_date' => 'required|date',       
                'event_end_date' => 'required|date|after_or_equal:event_start_date',       
                'event_duration' => 'required|integer|min:1',       
                'event_location' => 'required|string|max:255',       
                'event_link' => 'nullable|url',       
                'event_description' => 'nullable|string',       
                'event_image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',       
                'event_status' => 'required|in:active,inactive',       
                'is_approved' => 'required|boolean'
            ]);

            if($request->hasFile('event_image')){
                $imagePath = $request->file('event_image')->store('event_image', 'public');
                $validatedData['event_image'] = URL::to(Storage::url($imagePath));
            }

            $event = Event::create($validatedData);

            return response()->json(['message'=>'Event Created Successfully'], 201);
        }catch(\Exception $error){
            return response()->json(['message', $error->getMessage()], $error->getCode());
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Event $event)
    {
        try{
            return response()->json($event);
        }catch(\Exception $error){
            return response()->json(['error' => $error->getMessage()], 500);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Event $event)
    {
        try {
            $validatedData = $request->validate([
            'gallery_id' => 'required|integer',       
            'event_name' => 'required|string|max:255',       
            'event_start_date' => 'required|date',       
            'event_end_date' => 'required|date|after_or_equal:event_start_date',       
            'event_duration' => 'required|integer|min:1',       
            'event_location' => 'required|string|max:255',       
            'event_link' => 'nullable|url',       
            'event_description' => 'nullable|string',       
            'event_image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',       
            'event_status' => 'required|in:active,inactive',       
            'is_approved' => 'required|boolean'
            ]);

            if ($request->hasFile('event_image')) {
            // Delete the old image if it exists
            if ($event->event_image) {
                $oldImagePath = str_replace(URL::to('/storage'), '', $event->event_image);
                Storage::disk('public')->delete($oldImagePath);
            }

            $imagePath = $request->file('event_image')->store('event_image', 'public');
            $validatedData['event_image'] = URL::to(Storage::url($imagePath));
            }

            $event->update($validatedData);

            return response()->json(['message' => 'Event Updated Successfully'], 200);
        } catch (\Exception $error) {
            return response()->json(['error' => $error->getMessage()], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Event $rentRequest)
    {
        try {
            $rentRequest->delete();
            return response()->json(['message' => 'Event Deleted Successfully'], 200);
            } catch (\Exception $error) {
                return response()->json(['error' => $error->getMessage()], 500);
                }
    }
}
