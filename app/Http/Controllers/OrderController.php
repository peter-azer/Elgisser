<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Http\Requests\StoreOrderRequest;
use App\Http\Requests\UpdateOrderRequest;
use App\Models\Artist;
use App\Models\ArtWork;
use App\Models\OrderItem;
use App\Models\User;
use App\Models\Cart;
use App\Services\OrderNumberService;
use Illuminate\Support\Facades\Validator;
use App\Notifications\SubmitOrder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Log;

class OrderController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index($id)
    {
        try{
            $orders = Order::where('user_id', $id)->get();
            return response()->json(['orders' => $orders]);
        }catch(\Exception $e){
            return response()->json(['error' => 'An error occurred while fetching orders.'], 500);
        }
    }

    /**
     * Display artist orders.
     */
    public function showArtistOrders()
    {
        try{
            $artist = Artist::where('user_id', auth()->user()->id)->first();
            $orders = OrderItem::where('artist_id', $artist->id)
                ->with(['product', 'order' => function ($query) {
                    $query->select('id', 'address');
                }])
                ->get();
            return response()->json(['orders' => $orders]);
        }catch(\Exception $e){
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
    /**
     * Display a specified order.
     */
    public function showArtistOrder($id)
    {
        try{
            $artist = Artist::where('user_id', auth()->user()->id)->first();
            $order = OrderItem::where('artist_id', $artist->id)
                ->with(['product', 'order' => function ($query) {
                    $query->select('id', 'address');
                }])
                ->findOrFail($id);
            return response()->json(['order' => $order]);
        }catch(\Exception $e){
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function artistSetStatus($id, $status)
    {
        try{
            $orderItem = OrderItem::where('artist_id', auth()->user()->id)->findOrFail($id);
            $orderItem->status = $status;
            $orderItem->save();
            return response()->json(['message' => 'Status updated successfully.']);
        }catch(\Exception $e){
            return response()->json(['error' => 'An error occurred while updating the status.'], 500);
        }
    }
    /**
     * Store a newly created resource in storage.
     */
    public function checkout(StoreOrderRequest $request)
    {
    DB::beginTransaction();

    try {
        $user = auth()->user();
        $userId = $user->id;
        $orderNumber = OrderNumberService::generate();
        $items = $request->input('items', []);
        $totalOrderPrice = 0;

        // Validate each item
        foreach ($items as $item) {
            Validator::make($item, [
                'cart_id' => 'required|integer|exists:carts,id',
                'product_id' => 'required|integer|exists:products,id',
                'quantity' => 'required|integer|min:1',
                'price' => 'required|numeric|min:0',
            ])->validate();

            $totalOrderPrice += $item['quantity'] * $item['price'];
        }

        // Merge and validate the order data
        $request->merge([
            'user_id' => $userId,
            'order_number' => $orderNumber,
            'total_amount' => $totalOrderPrice,
            'address' => $user->address,
            'address_ar' => $user->address_ar,
            'status' => 'pending',
        ]);

        $validatedData = $request->validate([
            'user_id' => 'required|integer|exists:users,id',
            'order_number' => 'required|string|unique:orders,order_number',
            'address' => 'required|string',
            'address_ar' => 'required|string',
            'total_amount' => 'required|numeric|min:0',
            'currency' => 'sometimes|string|max:3',
            'status' => 'required|string|in:pending,completed,canceled',
        ]);

        // Create the order
        $order = Order::create($validatedData);

        // Process each order item
        foreach ($items as $item) {
            $artwork = ArtWork::findOrFail($item['product_id']);

            $orderItemData = [
                'order_id'    => $order->id,
                'product_id'  => $item['product_id'],
                'artist_id'   => $artwork->artist_id,
                'quantity'    => $item['quantity'],
                'price'       => $item['price'],
                'total_price' => $item['quantity'] * $item['price'],
            ];

            Validator::make($orderItemData, [
                'order_id'    => 'required|integer|exists:orders,id',
                'product_id'  => 'required|integer|exists:products,id',
                'artist_id'   => 'required|integer|exists:artists,id',
                'quantity'    => 'required|integer|min:1',
                'price'       => 'required|numeric|min:0',
                'total_price' => 'required|numeric|min:0',
            ])->validate();

            OrderItem::create($orderItemData);
            $artwork->decrement('quantity', $item['quantity']);

            // Notify artist
            $artist = Artist::find($orderItemData['artist_id']);
            $artistUser = User::findOrFail($artist->user_id);

            if ($artistUser) {
                $artistUser->notify(new SubmitOrder($order));
            }

            // Remove the item from the cart
            Cart::findOrFail($item['cart_id'])->delete();
        }

        // Notify the user who placed the order
        $user->notify(new SubmitOrder($order));

        DB::commit();

        return response()->json(['message' => 'Order placed successfully', 'order' => $order], 201);

    } catch (\Exception $e) {
        DB::rollBack();
        Log::error('Checkout failed', ['error' => $e->getMessage()]);
        return response()->json(['error' => 'Checkout failed', 'details' => $e->getMessage()], 500);
    }
}


    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        try{
            $order = Order::findOrFail($id)->load(['user', 'orderItems.product', 'payment']);
            return response()->json(['order' => $order]);
        }catch(\Exception $e){
            return response()->json(['error' => 'An error occurred while fetching the order.'], 500);
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Order $order)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateOrderRequest $request, Order $order)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Order $order)
    {
        //
    }
}
