<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Order;
use App\Models\OrderItem;
use App\Services\OrderNumberService;

class OrderController extends Controller
{

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        try {
            $orders = Order::with('orderItems', 'user', 'orderItems.product', 'orderItems.artist', 'payment')->get();
            return response()->json($orders);
        } catch (\Exception $error) {
            return response()->json(['error' => $error->getMessage()], 500);
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        try {

            $totalOrderPrice = 0;
            $items = $request->input('items');
            $user = auth()->user()->id;
            $orderNumber = OrderNumberService::generate();
            $request->input['order_number'] = $orderNumber;
            $request->input['user_id'] = $user;
            $request->input['total_amount'] = $totalOrderPrice;
            $request->input['status'] = 'pending';

            $validatedData = $request->validate([
                'user_id' => 'required|integer|exists:users,id',
                'order_number' => 'required|string|unique:orders,order_number',
                'total_amount' => 'required|numeric|min:0',
                'currency' => 'sometimes|string|max:3',
                'status' => 'required|string|in:pending,completed,canceled',
            ]);
            $order = Order::create($validatedData);

            foreach ($items as $item) {
                $item['order_id'] = $order->id;
                $item['total_price'] = $item['quantity'] * $item['price'];
                $validatedItem = validator($item, [
                    'order_id' => 'required|integer|exists:orders,id',
                    'product_id' => 'required|integer|exists:products,id',
                    'quantity' => 'required|integer|min:1',
                    'price' => 'required|numeric|min:0',
                    'total_price' => 'required|numeric|min:0',
                ])->validate();

                $orderItem = OrderItem::create($validatedItem);
                $totalOrderPrice += $orderItem->total_price;
            }

            $order->update(['total_amount' => $totalOrderPrice]);
            return response()->json($order);
        } catch (\Exception $error) {
            return response()->json(['error' => $error->getMessage()], 500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Order $order)
    {
        try {
            $order->with('orderItems', 'user', 'orderItems.product', 'orderItems.artist', 'payment')->get();
            return response()->json($order);
        } catch (\Exception $error) {
            return response()->json(['error' => $error->getMessage()], 500);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, OrderItem $order)
    {
        try {
            $request['total_price'] = $request['price'] * $request['quantity'];
            $validatedData = $request->validate([
                'order_id' => 'required|integer|exists:orders,id',
                'product_id' => 'required|integer|exists:products,id',
                'quantity' => 'required|integer|min:1',
                'price' => 'required|numeric|min:0',
                'total_price' => 'required|numeric|min:0',
            ]);
            $order->update($validatedData);
            return response()->json($order);
        } catch (\Exception $error) {
            return response()->json(['error' => $error->getMessage()], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Order $order)
    {
        try {
            $order->delete();
            return response()->json(['message' => 'Order deleted successfully']);
        } catch (\Exception $error) {
            return response()->json(['error' => $error->getMessage()], 500);
        }
    }
}
