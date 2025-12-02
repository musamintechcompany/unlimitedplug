<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use App\Models\Cart;

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
                // Payment successful - clear cart and process order
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