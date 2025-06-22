<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Http\Requests\StoreCartRequest;
use App\Http\Requests\UpdateCartRequest;

class CartController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        try{
            $userID = auth()->user()->id;
            $carts = Cart::where('user_id', $userID)->get();
            return response()->json(['cart' => $carts]);
        }catch(\Exception $e){
            return response()->json(['error' => 'An error occurred while fetching the cart.'], 500);
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreCartRequest $request)
    {
        try {
            $userID = auth()->user()->id;
            $validatedData = $request->validate([
                'artwork_id' => 'required|exists:art_works,id',
                'quantity' => 'required|integer|min:1',
                'price' => 'required|numeric|min:0',
            ]);
            $cart = Cart::create(array_merge($validatedData, ['user_id' => $userID]));
            return response()->json(['cart' => $cart], 201);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Cart $cart)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateCartRequest $request, Cart $cart)
    {
                try {
                    $userID = auth()->user()->id;
                    if ($cart->user_id !== $userID) {
                    return response()->json(['error' => 'Unauthorized.'], 403);
                    }

                    $validated = $request->validate([
                    'artwork_id' => 'sometimes|required|exists:art_works,id',
                    'quantity' => 'sometimes|required|integer|min:1',
                    'price' => 'sometimes|required|numeric|min:0',
                    'status' => 'sometimes|required|string|in:pending,completed,cancelled',
                    ]);

                    $cart->update($validated);

                    return response()->json(['cart' => $cart]);
                } catch (\Exception $e) {
                    return response()->json(['error' => 'An error occurred while updating the cart.'], 500);
                }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Cart $cart)
    {
        try {
            $userID = auth()->user()->id;
            if ($cart->user_id !== $userID) {
                return response()->json(['error' => 'Unauthorized.'], 403);
            }

            $cart->delete();
            return response()->json(['message' => 'Cart item deleted successfully.']);
        } catch (\Exception $e) {
            return response()->json(['error' => 'An error occurred while deleting the cart item.'], 500);
        }
    }
}
