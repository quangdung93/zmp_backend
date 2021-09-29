<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Order;
use App\ZaloUser;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    public function checkout(Request $request){
        // try {
            $dataOrder = [
                'name' => $request->address['name'],
                'address' => $request->address['address'],
                'phone' => $request->address['phone'],
                'note' => $request->note,
                'discount' => $request->discount,
                'shipping' => $request->shipping,
                'shipping_time' => $request->shipping_time,
                'shop' => @$request->shop['name'] ?? null,
                'user_id' => $request->user['id']
            ];
    
            $order = Order::create($dataOrder);
            $carts = $request->cart;
            if($order && $carts){
                $dataCart = [];
                $total = 0;
                foreach($carts as $cart){
                    $subtotal = str_replace('.', '', $$cart['subtotal']);
                    $dataCart[] = [
                        'product_id' => $cart['product']['id'],
                        'price' => $cart['product']['price'],
                        'quantity' => $cart['quantity'],
                        'subtotal' => $subtotal,
                        'options' => $cart['size']['name'],
                        'note' => $cart['note'],
                    ];
                    $total += $subtotal;
                }
    
                $order->cart()->attach($dataCart);
                $order->total = $total;
                $order->save();

                return response()->json([
                    'error' => 0,
                    'message' => 'Đặt hàng thành công!',
                    'data' => $order->detail
                ]);
            }
        // } catch (\Throwable $th) {
        //     return response()->json([
        //         'error' => -1,
        //         'message' => 'Unknown exception'
        //     ]);
        // }
    }

    public function getHistory(){
        $user = request()->get('user');

        $orders = Order::where('user_id', $user['id'])
                        ->with('cart')
                        ->OrderByDesc('created_at')
                        ->get();

        return response()->json([
            'error' => 0,
            'message' => 'Success!',
            'data' => $orders
        ]);
    }
}
