<?php

namespace App\Http\Controllers;

use App\Models\Event;
use App\Http\Requests\StoreEventRequest;
use App\Http\Requests\UpdateEventRequest;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\Storage;

class EventController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $events = Event::all();
        return response()->json([
            'events' => $events
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
    public function store(StoreEventRequest $request)
    {
        try{

            $validatedData = $request->validate([
                'gallery_id' => 'required|integer',
                'event_name' => 'required|string|max:255',
                'event_name_ar' => 'required|string|max:255',
                'event_start_date' => 'required|date',
                'event_end_date' => 'required|date|after_or_equal:event_start_date',
                'event_duration' => 'nullable|integer',
                'event_location' => 'required|string|max:255',
                'event_link' => 'nullable|url',
                'event_description' => 'nullable|string',
                'event_description_ar' => 'nullable|string',
                'event_image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
                // 'event_status' => 'required|in:active,inactive',
                // 'is_approved' => 'required|boolean'
            ]);
            
            if ($request->hasFile('event_image')) {
                $coverImagePath = $request->file('event_image')->store('event_image', 'public');
                $validatedData['event_image'] = URL::to(Storage::url($coverImagePath));
            }
            
            $event = Event::create($validatedData);
            return response()->json([
                'message' => 'Event created successfully',
                'event' => $event
            ], 201);
        }catch(\Exception $e){
            return response()->json([
                'message' => 'Event creation failed',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function galleryEvents($gallery_id)
    {
        $events = Event::where('gallery_id', $gallery_id)->get();
        return response()->json([
            'events' => $events
        ]);
    }
    /**
     * Display the specified resource.
     */
    public function show(Event $event)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Event $event)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateEventRequest $request, Event $event)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Event $event)
    {
        //
    }
}
