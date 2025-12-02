<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use App\Models\Cart;
use App\Models\Payment;

class PaymentController extends Controller
{
    private $apiKey = '0JRGGXJ-9HS4MSW-NXSC4AB-4PMN020';
    private $baseUrl = 'https://api.nowpayments.io/v1';

    public function createPayment(Request $request)
    {
        \Log::info('Payment creation started', ['user_id' => auth()->id(), 'session_id' => session()->getId()]);
        
        $userId = auth()->id();
        $sessionId = session()->getId();
        
        // Get cart items
        $cartItems = Cart::with('digitalAsset')
            ->where(function($query) use ($userId, $sessionId) {
                if ($userId) {
                    $query->where('user_id', $userId);
                } else {
                    $query->where('session_id', $sessionId);
                }
            })
            ->get();
            
        \Log::info('Cart items found', ['count' => $cartItems->count(), 'items' => $cartItems->toArray()]);
            
        if ($cartItems->isEmpty()) {
            \Log::warning('Cart is empty for payment creation');
            return response()->json(['error' => 'Cart is empty'], 400);
        }
        
        $total = $cartItems->sum(function($item) {
            return $item->price * $item->quantity;
        });
        
        try {
            // Create payment with NOWPayments
            $response = Http::withHeaders([
                'x-api-key' => $this->apiKey,
                'Content-Type' => 'application/json'
            ])->post($this->baseUrl . '/payment', [
                'price_amount' => $total,
                'price_currency' => 'USD',
                'pay_currency' => $request->input('currency', 'btc'),
                'order_id' => 'ORDER_' . time() . '_' . ($userId ?? $sessionId),
                'order_description' => 'Digital Assets Purchase',
                'success_url' => url('/payment/success'),
                'cancel_url' => url('/payment/cancel')
            ]);
            
            if ($response->successful()) {
                $paymentData = $response->json();
                
                // Store payment record
                Payment::create([
                    'user_id' => $userId,
                    'session_id' => $userId ? null : $sessionId,
                    'payment_id' => $paymentData['payment_id'],
                    'amount' => $total,
                    'currency' => 'USD',
                    'pay_currency' => $request->input('currency', 'btc'),
                    'status' => 'waiting',
                    'payment_url' => $paymentData['payment_url'] ?? $paymentData['invoice_url'] ?? null
                ]);
                
                $redirectUrl = $paymentData['payment_url'] ?? $paymentData['invoice_url'] ?? null;
                
                return response()->json([
                    'success' => true,
                    'payment_url' => $redirectUrl,
                    'payment_id' => $paymentData['payment_id']
                ]);
            }
            
            // Log the error response for debugging
            \Log::error('NOWPayments API Error', [
                'status' => $response->status(),
                'body' => $response->body()
            ]);
            
            return response()->json([
                'error' => 'Payment creation failed',
                'details' => $response->json()['message'] ?? 'Unknown error'
            ], 500);
            
        } catch (\Exception $e) {
            \Log::error('Payment creation exception', ['error' => $e->getMessage()]);
            return response()->json(['error' => 'Payment system error: ' . $e->getMessage()], 500);
        }
    }
    
    public function getAvailableCurrencies()
    {
        try {
            $response = Http::withHeaders([
                'x-api-key' => $this->apiKey
            ])->get($this->baseUrl . '/currencies');
            
            if ($response->successful()) {
                return response()->json($response->json());
            }
            
            \Log::error('NOWPayments currencies error', [
                'status' => $response->status(),
                'body' => $response->body()
            ]);
            
            return response()->json(['error' => 'Failed to fetch currencies'], 500);
            
        } catch (\Exception $e) {
            \Log::error('Currencies fetch exception', ['error' => $e->getMessage()]);
            return response()->json(['error' => 'API connection failed'], 500);
        }
    }
    
    public function paymentStatus($paymentId)
    {
        $response = Http::withHeaders([
            'x-api-key' => $this->apiKey
        ])->get($this->baseUrl . "/payment/{$paymentId}");
        
        if ($response->successful()) {
            return response()->json($response->json());
        }
        
        return response()->json(['error' => 'Payment not found'], 404);
    }
    
    public function webhook(Request $request)
    {
        $payment = Payment::where('payment_id', $request->input('payment_id'))->first();
        
        if ($payment) {
            $payment->update([
                'status' => $request->input('payment_status'),
                'actually_paid' => $request->input('actually_paid'),
                'pay_amount' => $request->input('pay_amount')
            ]);
            
            // If payment is confirmed, clear cart
            if ($request->input('payment_status') === 'confirmed') {
                Cart::where('user_id', $payment->user_id)
                    ->orWhere('session_id', $payment->session_id)
                    ->delete();
            }
        }
        
        return response()->json(['status' => 'ok']);
    }
}