<?php

namespace App\Http\Controllers\Api;

use App\Order;
use Illuminate\Http\Request;
use App\Services\ZaloService;
use App\Http\Controllers\Controller;

class OrderController extends Controller
{
    protected $zaloService;

    public function __construct(ZaloService $zaloService) {
        $this->zaloService = $zaloService;
    }

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
            $total = 0;
            $carts = $request->cart;
            
            $total = collect($carts)->reduce(function ($total, $item) {
                return $total + $item['subtotal'];
            });

            $dataOrder['total'] = $total;

            $order = Order::create($dataOrder);
            
            if($order && $carts){
                $dataCart = [];
                $messageDetail = '';
                foreach($carts as $cart){
                    $dataCart[] = [
                        'product_id' => $cart['product']['id'],
                        'price' => $cart['product']['price'],
                        'quantity' => $cart['quantity'],
                        'subtotal' => $cart['subtotal'],
                        'options' => $cart['size']['name'],
                        'note' => $cart['note'],
                    ];
                    $messageDetail .= $cart['product']['name'] .', giá:'.number_format($cart['product']['price']).' đ, số lượng:'. $cart['quantity'] .'/';
                }
    
                $order->cart()->attach($dataCart);
                //send message Zalo
                // $message = 'Cảm ơn bạn đã sử dụng dịch vụ của FPT Telecom. Chi tiết đơn hàng: '.$messageDetail.'. Tổng đơn hàng: ' .number_format($total). ' đ';
                // $this->zaloService->sendMessage($request->user['id'], $message);

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
