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
use App\Mail\PurchaseConfirmation;

class PaystackController extends Controller
{
    private $secretKey;
    private $publicKey;

    public function __construct()
    {
        $this->secretKey = config('payment.paystack.secret_key');
        $this->publicKey = config('payment.paystack.public_key');
    }

    public function initializePayment(Request $request)
    {
        // Check if Paystack keys are configured
        if (!$this->secretKey || !$this->publicKey) {
            return response()->json([
                'success' => false, 
                'message' => 'Payment system not configured. Please contact support.'
            ], 500);
        }

        $request->validate([
            'email' => 'required|email',
            'amount' => 'required|numeric|min:1',
            'currency' => 'required|in:NGN,USD'
        ]);

        try {
            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . $this->secretKey,
                'Content-Type' => 'application/json',
            ])->post('https://api.paystack.co/transaction/initialize', [
                'email' => $request->email,
                'amount' => $request->amount * 100, // Convert to kobo/cents
                'currency' => $request->currency,
                'callback_url' => route('paystack.callback'),
            ]);

            if ($response->successful()) {
                $data = $response->json();
                return response()->json([
                    'success' => true,
                    'authorization_url' => $data['data']['authorization_url'],
                    'reference' => $data['data']['reference']
                ]);
            }

            Log::error('Paystack API Error', [
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
        $reference = $request->query('reference');
        
        if (!$reference) {
            return redirect()->route('checkout')->with('error', 'Payment reference not found');
        }

        $response = Http::withHeaders([
            'Authorization' => 'Bearer ' . $this->secretKey,
        ])->get("https://api.paystack.co/transaction/verify/{$reference}");

        if ($response->successful()) {
            $data = $response->json();
            
            if ($data['data']['status'] === 'success') {
                // Payment successful - create order and clear cart
                $this->createOrder($data['data']);
                $this->clearUserCart();
                return redirect()->route('checkout.success')->with('success', 'Payment successful!');
            }
        }

        return redirect()->route('checkout')->with('error', 'Payment verification failed');
    }

    public function webhook(Request $request)
    {
        $payload = $request->getContent();
        $signature = $request->header('x-paystack-signature');
        
        if (!$this->verifyWebhook($payload, $signature)) {
            return response('Unauthorized', 401);
        }

        $event = json_decode($payload, true);
        
        Log::info('Paystack webhook received', $event);
        
        if ($event['event'] === 'charge.success') {
            // Handle successful payment
            $this->handleSuccessfulPayment($event['data']);
        }

        return response('OK', 200);
    }

    private function verifyWebhook($payload, $signature)
    {
        $computedSignature = hash_hmac('sha512', $payload, $this->secretKey);
        return hash_equals($signature, $computedSignature);
    }

    private function handleSuccessfulPayment($data)
    {
        // Process the successful payment
        Log::info('Processing successful payment', $data);
        $this->createOrder($data);
    }
    
    private function createOrder($paymentData)
    {
        $userId = Auth::id();
        if (!$userId) {
            Log::error('Cannot create order: User not authenticated');
            return;
        }

        // Get cart items
        $cartItems = Cart::with('digitalAsset')
            ->where('user_id', $userId)
            ->get();

        if ($cartItems->isEmpty()) {
            Log::warning('Cart is empty, cannot create order');
            return;
        }

        // Calculate total
        $total = $cartItems->sum(function($item) {
            return $item->price * $item->quantity;
        });

        // Create payment record
        $payment = Payment::create([
            'user_id' => $userId,
            'payment_id' => $paymentData['reference'] ?? uniqid('PAY-'),
            'amount' => $total,
            'currency' => $paymentData['currency'] ?? 'NGN',
            'pay_currency' => $paymentData['currency'] ?? 'NGN',
            'status' => 'confirmed',
            'actually_paid' => $paymentData['amount'] / 100 ?? $total,
            'pay_amount' => $paymentData['amount'] / 100 ?? $total,
        ]);

        // Create order
        $order = Order::create([
            'user_id' => $userId,
            'order_number' => Order::generateOrderNumber(),
            'payment_id' => $payment->id,
            'total_amount' => $total,
            'currency' => $paymentData['currency'] ?? 'NGN',
            'payment_method' => 'paystack',
            'payment_status' => 'completed',
            'status' => 'completed',
        ]);

        // Create order items
        foreach ($cartItems as $cartItem) {
            OrderItem::create([
                'order_id' => $order->id,
                'digital_asset_id' => $cartItem->digital_asset_id,
                'asset_name' => $cartItem->digitalAsset->name,
                'quantity' => $cartItem->quantity,
                'price' => $cartItem->price,
            ]);
        }

        Log::info('Order created successfully', ['order_id' => $order->id]);
        
        // Load relationships for email
        $order->load(['user', 'items']);
        
        // Send purchase confirmation email
        Mail::to($order->user->email)->send(new PurchaseConfirmation($order));
    }
    
    private function clearUserCart()
    {
        if (Auth::check()) {
            // Clear cart for authenticated user
            Cart::where('user_id', Auth::id())->delete();
        } else {
            // Clear cart for guest user (session-based)
            Cart::where('session_id', session()->getId())->delete();
        }
    }
}