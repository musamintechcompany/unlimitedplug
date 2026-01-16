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
            return redirect()->route('payment.cancel')->with('info', 'You cancelled the payment');
        }
        
        if ($status !== 'successful' && $status !== 'completed') {
            Log::warning('Payment not successful', ['status' => $status]);
            return redirect()->route('payment.cancel')->with('error', 'Payment was not successful');
        }
        
        if (!$transactionId) {
            Log::error('No transaction ID in callback');
            return redirect()->route('payment.cancel')->with('error', 'Invalid payment response');
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
                
                if ($data['status'] === 'success' && isset($data['data']['status']) && $data['data']['status'] === 'successful') {
                    // Check if order already exists
                    $existingOrder = Order::where('transaction_reference', $txRef)->first();
                    
                    if (!$existingOrder) {
                        Log::info('Creating new order for transaction', ['tx_ref' => $txRef]);
                        
                        // Payment successful - create order
                        $order = $this->createOrder($data['data']);
                        
                        if ($order) {
                            // Clear cart
                            $this->clearUserCart($order->user_id);
                            
                            Log::info('Order created successfully, redirecting to success');
                            return redirect()->route('checkout.success')->with('success', 'Payment successful!');
                        } else {
                            Log::error('Failed to create order');
                            return redirect()->route('payment.cancel')->with('error', 'Failed to process order');
                        }
                    } else {
                        Log::info('Order already exists', ['order_id' => $existingOrder->id]);
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

        return redirect()->route('payment.cancel')->with('error', 'Payment verification failed');
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
        $cartItems = Cart::with('digitalAsset.prices')
            ->where('user_id', $userId)
            ->get();

        if ($cartItems->isEmpty()) {
            Log::warning('Cart is empty, cannot create order');
            return null;
        }

        // Calculate total using current currency
        $total = $cartItems->sum(function($item) use ($currency) {
            $price = $item->digitalAsset->getPriceForCurrency($currency);
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
            'user_id' => $userId,
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
            $price = $cartItem->digitalAsset->getPriceForCurrency($currency);
            
            OrderItem::create([
                'order_id' => $order->id,
                'digital_asset_id' => $cartItem->digital_asset_id,
                'asset_name' => $cartItem->digitalAsset->name,
                'quantity' => $cartItem->quantity,
                'price' => $price,
            ]);
        }

        Log::info('Order created successfully', ['order_id' => $order->id]);
        
        // Load relationships for email
        $order->load(['user', 'items.digitalAsset']);
        
        // Send purchase confirmation email
        try {
            Mail::to($order->user->email)->send(new PurchaseConfirmation($order));
            Log::info('Purchase confirmation email sent', ['order_id' => $order->id]);
        } catch (\Exception $e) {
            Log::error('Failed to send purchase confirmation email: ' . $e->getMessage());
        }
        
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
}
