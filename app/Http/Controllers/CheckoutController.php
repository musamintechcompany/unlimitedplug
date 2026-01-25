<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Cart;
use App\Models\OrderItem;
use App\Mail\OrderConfirmed;
use App\Mail\Admin\NewOrderReceived;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;

class CheckoutController extends Controller
{
    public function success()
    {
        // Check if user came from payment callback
        if (!session()->has('payment_success')) {
            return redirect()->route('marketplace')->with('info', 'No recent payment found');
        }

        $order = Order::with(['items.product'])
            ->where('orderable_type', \App\Models\User::class)
            ->where('orderable_id', auth()->id())
            ->latest()
            ->first();

        // Clear the session flag
        session()->forget('payment_success');

        return view('user.checkout-success', compact('order'));
    }
    
    public function createFreeOrder(Request $request)
    {
        try {
            $user = auth()->user();
            $currency = session('currency', 'USD');
            
            $cartItems = Cart::with('cartable')
                ->where('user_id', $user->id)
                ->get();
            
            if ($cartItems->isEmpty()) {
                return response()->json(['success' => false, 'message' => 'Cart is empty'], 400);
            }
            
            $total = $cartItems->sum(function ($item) use ($currency) {
                if (!$item->cartable) return 0;
                $pricing = $item->cartable->prices()->where('currency_code', $currency)->first();
                $price = $pricing ? $pricing->price : $item->cartable->price;
                return $price * $item->quantity;
            });
            
            if ($total > 0) {
                return response()->json(['success' => false, 'message' => 'This order is not free'], 400);
            }
            
            DB::beginTransaction();
            
            $order = Order::create([
                'orderable_type' => get_class($user),
                'orderable_id' => $user->id,
                'order_number' => 'ORD-' . strtoupper(uniqid()),
                'currency' => $currency,
                'subtotal' => 0,
                'total_amount' => 0,
                'payment_method' => 'free',
                'payment_status' => 'completed',
                'status' => 'completed'
            ]);
            
            foreach ($cartItems as $cartItem) {
                if (!$cartItem->cartable) continue;
                
                $pricing = $cartItem->cartable->prices()->where('currency_code', $currency)->first();
                $price = $pricing ? $pricing->price : $cartItem->cartable->price;
                
                OrderItem::create([
                    'order_id' => $order->id,
                    'product_id' => $cartItem->cartable_id,
                    'product_name' => $cartItem->cartable->name,
                    'quantity' => $cartItem->quantity,
                    'price' => $price,
                    'product_files' => [
                        'files' => $cartItem->cartable->file,
                        'license_type' => $cartItem->cartable->license_type
                    ]
                ]);
            }
            
            Cart::where('user_id', $user->id)->delete();
            
            DB::commit();
            
            $order->load(['items.product', 'orderable']);
            Mail::to($user->email)->queue(new OrderConfirmed($order));
            Mail::to(env('ADMIN_EMAIL'))->queue(new NewOrderReceived($order));
            
            $this->notifyAdmins($order);
            
            session(['payment_success' => true]);
            
            return response()->json(['success' => true]);
            
        } catch (\Exception $e) {
            DB::rollBack();
            \Log::error('Free order creation failed: ' . $e->getMessage());
            return response()->json(['success' => false, 'message' => 'Order creation failed'], 500);
        }
    }
    
    private function notifyAdmins($order)
    {
        $admins = \App\Models\Admin::all();
        
        foreach ($admins as $admin) {
            $notification = \App\Models\Notification::create([
                'notifiable_type' => \App\Models\Admin::class,
                'notifiable_id' => $admin->id,
                'type' => 'order_placed',
                'title' => 'New Order Received',
                'message' => 'Order #' . $order->order_number . ' placed by ' . $order->orderable->name . ' for ' . $order->currency . ' ' . number_format($order->total_amount, 2),
                'data' => json_encode(['order_id' => $order->id, 'order_number' => $order->order_number])
            ]);
            
            broadcast(new \App\Events\AdminNotificationCreated($notification));
        }
        
        broadcast(new \App\Events\AnalyticsUpdated());
    }
}
