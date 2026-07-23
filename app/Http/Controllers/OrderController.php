<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class OrderController extends Controller
{
    public function index()
    {
        $order = Order::with(['orderItems.product.images'])
            ->where('user_id', auth()->id())
            ->where('status', 'pending')
            ->latest()
            ->first();

        return view('order.index', ['order' => $order]);
    }
    public function store(Request $request)
    {
        DB::beginTransaction();

        try {
            $validator = Validator::make($request->all(), [
                'items' => 'required|array|min:1',
                'items.*.product_id' => 'required|exists:products,id',
                'items.*.quantity' => 'required|integer|min:1',
            ]);

            if ($validator->fails()) {
                DB::rollBack();
                return redirect()->back()->withErrors($validator->errors())->withInput();
            }

            $validated = $validator->validated();

            $order = Order::firstOrCreate(
                [
                    'user_id' => Auth::id(),
                    'status' => 'pending',
                ],
                [
                    'total_amount' => 0,
                ]
            );

            foreach ($validated['items'] as $item) {
                $product = Product::findOrFail($item['product_id']);

                if ($product->stock < $item['quantity']) {
                    DB::rollBack();
                    return redirect()->back()->with('error', "Insufficient stock for {$product->name}")->withInput();
                }

                $orderItem = OrderItem::where('order_id', $order->id)
                    ->where('product_id', $product->id)
                    ->first();

                if ($orderItem) {
                    $newQuantity = $orderItem->quantity + $item['quantity'];
                    $orderItem->update([
                        'quantity' => $newQuantity,
                        'sub_total' => $product->price * $newQuantity,
                    ]);
                } else {
                    OrderItem::create([
                        'order_id' => $order->id,
                        'product_id' => $product->id,
                        'quantity' => $item['quantity'],
                        'price' => $product->price,
                        'sub_total' => $product->price * $item['quantity'],
                    ]);
                }

                $product->decrement('stock', $item['quantity']);
            }
            $order->update([
                'total_amount' => $order->orderItems()->sum('sub_total'),
            ]);

            DB::commit();

            return redirect()->back()->with('success', 'Product(s) added to cart');

        } catch (\Exception $exception) {
            DB::rollBack();
            return redirect()->back()->with('error', $exception->getMessage())->withInput();
        }
    }
}
