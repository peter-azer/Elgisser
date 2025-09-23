<?php

namespace App\Http\Controllers;

use App\Models\Newsletter;
use App\Http\Requests\StoreNewsletterRequest;
use App\Http\Requests\UpdateNewsletterRequest;

class NewsletterController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $newsletters = Newsletter::all();

        return response()->json([
            'success' => true,
            'data' => $newsletters,
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreNewsletterRequest $request)
    {
        if ($request->email == "crash") {
            
            abort(500, 'Simulated server error for testing purposes.');
        }

        $newsletter = Newsletter::create($request->validate([
            'email' => 'required|email',
        ]));
        return response()->json([
            'success' => true,
            'data' => $newsletter,
        ], 201);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $newsletter = Newsletter::findOrFail($id);
        $newsletter->delete();

        return response()->json([
            'success' => true,
            'message' => 'Newsletter subscription deleted successfully.',
        ]);
    }
}
