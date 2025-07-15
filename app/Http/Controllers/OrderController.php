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
use Illuminate\Http\Request;

class OrderController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $id = auth()->user()->id;
        try {
            $orders = Order::where('user_id', $id)->with('orderItems', 'orderItems.product')->get();
            return response()->json(['orders' => $orders]);
        } catch (\Exception $e) {
            return response()->json(['error' => 'An error occurred while fetching orders.'], 500);
        }
    }

    /**
     * Display artist orders.
     */
    public function showArtistOrders()
    {
        try {
            $user = auth()->user();
            $artist = Artist::where('user_id', $user->id)->first();
            if (!$artist) {
                return response()->json(['error' => 'Artist not found.'], 404);
            }
            // Retrieve all orders for the artist
            $orders = OrderItem::where('artist_id', $artist->id)
                ->with('product', 'order', 'order.user')
                ->get();
            return response()->json(['orders' => $orders]);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
    /**
     * Display a specified order.
     */
    public function showArtistOrder($id)
    {
        try {
            $artist = Artist::where('user_id', auth()->user()->id)->first();
            $order = OrderItem::where('artist_id', $artist->id)
                ->with(['product', 'order' => function ($query) {
                    $query->select('id', 'address');
                }])
                ->findOrFail($id);
            return response()->json(['order' => $order]);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function artistSetStatus($id, $status)
    {
        try {
            $orderItem = OrderItem::where('artist_id', auth()->user()->id)->findOrFail($id);
            $orderItem->status = $status;
            $orderItem->save();
            return response()->json(['message' => 'Status updated successfully.']);
        } catch (\Exception $e) {
            return response()->json(['error' => 'An error occurred while updating the status.'], 500);
        }
    }
    /**
     * Store a newly created resource in storage.
     */
    public function checkout(Request $request)
    {
        try {
            $user = auth()->user();
            $userId = $user->id;
            $orderNumber = OrderNumberService::generate();
            $items = $request->input('items');
            $totalOrderPrice = 0;

            // Merge order-related fields
            $orderData = ([
                'user_id' => $userId,
                'order_number' => $orderNumber,
                'total_amount' => $totalOrderPrice,
                'address' => $user->address,
                'address_ar' => $user->address_ar,
                'status' => 'pending',
            ]);

            // Create order
            $order = Order::create($orderData);

            // Create order items
            foreach ($items as $item) {
                // Validate item data
                validator($item, [
                    'cart_id' => 'required|integer|exists:carts,id',
                    'product_id' => 'required|integer|exists:art_works,id',
                    'quantity' => 'required|integer|min:1',
                    'price' => 'required|numeric|min:1',
                ])->validate();
                // Find the artwork
                $artwork = ArtWork::findOrFail($item['product_id']);
                $orderItemData = new OrderItem();
                $orderItemData->order_id = $order->id;
                $orderItemData->product_id = $item['product_id'];
                $orderItemData->artist_id = $artwork->artist_id;
                $orderItemData->quantity = $item['quantity'];
                $orderItemData->price = $item['price'];
                $orderItemData->total_price = $item['quantity'] * $artwork->price;
                $orderItemData->save();

                $totalOrderPrice += $orderItemData->total_price;
                // Decrement available quantity
                $artwork->decrement('quantity', $item['quantity']);

                // Remove item from cart
                Cart::findOrFail($item['cart_id'])->delete();

                // Notify artist
                // $artistUser = User::find($artwork->artist->user->id);
                // if ($artistUser) {
                //     $artistUser->notify(new SubmitOrder($orderItemData));
                // }
            }
            $order->update(['total_amount' => $totalOrderPrice]);
            // Notify user about the order
            // $user->notify(new SubmitOrder($order));

            return response()->json($order->load('orderItems'));
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }


    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        try {
            $order = Order::findOrFail($id)->load(['user', 'orderItems.product', 'payment']);
            return response()->json(['order' => $order]);
        } catch (\Exception $e) {
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
