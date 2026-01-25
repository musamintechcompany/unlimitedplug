<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use App\Models\Cart;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Payment;
use App\Models\User;
use App\Models\Admin;
use App\Models\Notification;
use App\Events\AdminNotificationCreated;
use App\Events\AnalyticsUpdated;
use App\Mail\OrderConfirmed;
use App\Mail\Admin\NewOrderReceived;

class FlutterwaveController extends Controller
{
    private $secretKey;
    private $publicKey;
    private $encryptionKey;

    public function __construct()
    {
        $this->secretKey = config('payment.flutterwave.secret_key');
        $this->publicKey = config('payment.flutterwave.public_key');
        $this->encryptionKey = config('payment.flutterwave.encryption_key');
    }

    public function initializePayment(Request $request)
    {
        // Check if Flutterwave keys are configured
        if (!$this->secretKey || !$this->publicKey) {
            return response()->json([
                'success' => false, 
                'message' => 'Payment system not configured. Please contact support.'
            ], 500);
        }

        $currencies = array_keys(config('payment.currencies'));
        $request->validate([
            'email' => 'required|email',
            'amount' => 'required|numeric|min:1',
            'currency' => 'required|in:' . implode(',', $currencies)
        ]);

        try {
            $txRef = 'FLW_' . time() . '_' . uniqid();
            
            // Store transaction reference in session for callback
            session(['flutterwave_tx_ref' => $txRef]);
            
            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . $this->secretKey,
                'Content-Type' => 'application/json',
            ])->post('https://api.flutterwave.com/v3/payments', [
                'tx_ref' => $txRef,
                'amount' => $request->amount,
                'currency' => $request->currency,
                'redirect_url' => route('flutterwave.callback'),
                'customer' => [
                    'email' => $request->email,
                    'name' => Auth::check() ? Auth::user()->name : 'Guest User',
                ],
                'customizations' => [
                    'title' => 'Unlimited Plug',
                    'description' => 'Payment for digital products',
                    'logo' => url('/logo.png'),
                ],
            ]);

            if ($response->successful()) {
                $data = $response->json();
                
                if ($data['status'] === 'success') {
                    return response()->json([
                        'success' => true,
                        'payment_url' => $data['data']['link'],
                        'tx_ref' => $txRef
                    ]);
                }
            }

            Log::error('Flutterwave API Error', [
                'status' => $response->status(),
                'body' => $response->body()
            ]);

            return response()->json([
                'success' => false, 
                'message' => 'Payment service unavailable. Please try again later.'
            ], 400);
            
        } catch (\Exception $e) {
            Log::error('Payment initialization error: ' . $e->getMessage());
            return response()->json([
                'success' => false, 
                'message' => 'Payment initialization failed. Please try again.'
            ], 500);
        }
    }

    public function handleCallback(Request $request)
    {
        $status = $request->query('status');
        $txRef = $request->query('tx_ref');
        $transactionId = $request->query('transaction_id');
        
        Log::info('Flutterwave callback received', [
            'status' => $status,
            'tx_ref' => $txRef,
            'transaction_id' => $transactionId,
            'all_params' => $request->all()
        ]);
        
        // Handle cancelled payment
        if ($status === 'cancelled') {
            Log::info('Payment was cancelled by user');
            session(['payment_failed' => true]);
            return redirect()->route('payment.failed')->with('info', 'You cancelled the payment');
        }
        
        if ($status !== 'successful' && $status !== 'completed') {
            Log::warning('Payment not successful', ['status' => $status]);
            session(['payment_failed' => true]);
            return redirect()->route('payment.failed')->with('error', 'Payment was not successful');
        }
        
        if (!$transactionId) {
            Log::error('No transaction ID in callback');
            session(['payment_failed' => true]);
            return redirect()->route('payment.failed')->with('error', 'Invalid payment response');
        }

        // Verify transaction
        try {
            Log::info('Verifying transaction', ['transaction_id' => $transactionId]);
            
            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . $this->secretKey,
            ])->get("https://api.flutterwave.com/v3/transactions/{$transactionId}/verify");

            Log::info('Verification API response', [
                'status' => $response->status(),
                'body' => $response->json()
            ]);

            if ($response->successful()) {
                $data = $response->json();
                
                Log::info('Full verification response', ['data' => $data]);
                
                // Check multiple possible success conditions
                $isSuccess = (
                    ($data['status'] === 'success' && isset($data['data']['status']) && $data['data']['status'] === 'successful') ||
                    ($data['status'] === 'success' && isset($data['data']['status']) && $data['data']['status'] === 'success')
                );
                
                if ($isSuccess) {
                    // Check if order already exists
                    $existingOrder = Order::where('transaction_reference', $txRef)->first();
                    
                    if (!$existingOrder) {
                        Log::info('Creating new order for transaction', ['tx_ref' => $txRef]);
                        
                        // Payment successful - create order
                        $order = $this->createOrder($data['data']);
                        
                        if ($order) {
                            // Clear cart  
                            $this->clearUserCart(Auth::id());
                            
                            session(['payment_success' => true]);
                            Log::info('Order created successfully, redirecting to success');
                            return redirect()->route('checkout.success')->with('success', 'Payment successful!');
                        } else {
                            Log::error('Failed to create order');
                            return redirect()->route('payment.failed')->with('error', 'Failed to process order');
                        }
                    } else {
                        Log::info('Order already exists', ['order_id' => $existingOrder->id]);
                        session(['payment_success' => true]);
                        return redirect()->route('checkout.success')->with('success', 'Payment already processed!');
                    }
                } else {
                    Log::error('Payment verification failed - status mismatch', [
                        'api_status' => $data['status'] ?? 'unknown',
                        'payment_status' => $data['data']['status'] ?? 'unknown'
                    ]);
                }
            } else {
                Log::error('Verification API request failed', [
                    'status' => $response->status(),
                    'body' => $response->body()
                ]);
            }
        } catch (\Exception $e) {
            Log::error('Flutterwave callback error: ' . $e->getMessage(), [
                'trace' => $e->getTraceAsString()
            ]);
        }

        session(['payment_failed' => true]);
        return redirect()->route('payment.failed')->with('error', 'Payment verification failed');
    }

    public function webhook(Request $request)
    {
        $signature = $request->header('verif-hash');
        
        if (!$signature || $signature !== $this->encryptionKey) {
            return response('Unauthorized', 401);
        }

        $payload = $request->all();
        
        Log::info('Flutterwave webhook received', $payload);
        
        if ($payload['event'] === 'charge.completed' && $payload['data']['status'] === 'successful') {
            $this->handleSuccessfulPayment($payload['data']);
        }

        return response('OK', 200);
    }

    private function handleSuccessfulPayment($data)
    {
        Log::info('Processing successful payment', $data);
        $this->createOrder($data);
    }
    
    private function createOrder($paymentData)
    {
        $userId = Auth::id();
        if (!$userId) {
            Log::error('Cannot create order: User not authenticated');
            return null;
        }

        // Get cart items with currency
        $currency = session('currency', 'USD');
        $cartItems = Cart::with('cartable.prices')
            ->where('user_id', $userId)
            ->get();

        if ($cartItems->isEmpty()) {
            Log::warning('Cart is empty, cannot create order');
            return null;
        }

        // Calculate total using current currency
        $total = $cartItems->sum(function($item) use ($currency) {
            if (!$item->cartable) {
                Log::warning('Cart item has no cartable', ['cart_id' => $item->id]);
                return 0;
            }
            $price = $item->cartable->getPriceForCurrency($currency);
            return $price * $item->quantity;
        });

        // Create payment record
        $payment = Payment::create([
            'user_id' => $userId,
            'payment_id' => $paymentData['tx_ref'] ?? $paymentData['flw_ref'] ?? uniqid('PAY-'),
            'amount' => $total,
            'currency' => $paymentData['currency'] ?? $currency,
            'pay_currency' => $paymentData['currency'] ?? $currency,
            'status' => 'confirmed',
            'actually_paid' => $paymentData['amount'] ?? $total,
            'pay_amount' => $paymentData['amount'] ?? $total,
        ]);

        // Create order
        $order = Order::create([
            'orderable_type' => User::class,
            'orderable_id' => $userId,
            'order_number' => Order::generateOrderNumber(),
            'payment_id' => $payment->id,
            'total_amount' => $total,
            'currency' => $paymentData['currency'] ?? $currency,
            'payment_method' => 'flutterwave',
            'payment_status' => 'completed',
            'status' => 'completed',
            'transaction_reference' => $paymentData['tx_ref'] ?? $paymentData['flw_ref'],
        ]);

        // Create order items
        foreach ($cartItems as $cartItem) {
            $price = $cartItem->cartable->getPriceForCurrency($currency);
            
            OrderItem::create([
                'order_id' => $order->id,
                'product_id' => $cartItem->cartable_id,
                'product_name' => $cartItem->cartable->name,
                'product_files' => [
                    'files' => $cartItem->cartable->file,
                    'license_type' => $cartItem->cartable->license_type
                ],
                'quantity' => $cartItem->quantity,
                'price' => $price,
            ]);
        }

        Log::info('Order created successfully', ['order_id' => $order->id]);
        
        // Load relationships for email
        $order->load(['orderable', 'items.product']);
        
        // Send purchase confirmation email
        try {
            if ($order->orderable) {
                Mail::to($order->orderable->email)->send(new OrderConfirmed($order));
                Log::info('Purchase confirmation email sent', ['order_id' => $order->id]);
            }
        } catch (\Exception $e) {
            Log::error('Failed to send purchase confirmation email: ' . $e->getMessage());
        }
        
        // Notify all admins about new purchase
        $this->notifyAdmins($order);
        
        return $order;
    }
    
    private function clearUserCart($userId = null)
    {
        $userId = $userId ?? Auth::id();
        if ($userId) {
            Cart::where('user_id', $userId)->delete();
            Log::info('Cart cleared for user', ['user_id' => $userId]);
        }
    }
    
    /**
     * Notify all admins about new purchase
     */
    private function notifyAdmins($order)
    {
        $admins = Admin::all();
        $user = $order->orderable;
        
        if (!$user) {
            Log::error('Cannot notify admins: Order has no user');
            return;
        }
        
        foreach ($admins as $admin) {
            $notification = Notification::create([
                'notifiable_type' => Admin::class,
                'notifiable_id' => $admin->id,
                'type' => 'order_placed',
                'title' => 'New Order Received',
                'message' => 'Order #' . $order->order_number . ' placed by ' . $user->name . ' for ' . $order->currency . ' ' . number_format($order->total_amount, 2),
                'data' => json_encode(['order_id' => $order->id, 'order_number' => $order->order_number])
            ]);
            
            broadcast(new AdminNotificationCreated($notification));
        }
        
        broadcast(new AnalyticsUpdated());
        
        // Send email notification to admin
        $admin = Admin::first();
        if ($admin && $admin->email) {
            try {
                Mail::to($admin->email)->send(new NewOrderReceived($order));
            } catch (\Exception $e) {
                Log::error('Failed to send admin notification email: ' . $e->getMessage());
            }
        }
    }
}
