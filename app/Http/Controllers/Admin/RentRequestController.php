<?php

namespace App\Http\Controllers\Admin;

use App\Models\RentRequest;
use App\Http\Requests\StoreRentRequestRequest;
use App\Http\Requests\UpdateRentRequestRequest;
use App\Http\Controllers\Controller;

class RentRequestController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreRentRequestRequest $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(RentRequest $rentRequest)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateRentRequestRequest $request, RentRequest $rentRequest)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(RentRequest $rentRequest)
    {
        //
    }
}
