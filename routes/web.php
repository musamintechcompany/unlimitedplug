<?php

use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\Auth\ConfirmablePasswordController;
use App\Http\Controllers\Auth\EmailVerificationNotificationController;
use App\Http\Controllers\Auth\EmailVerificationPromptController;
use App\Http\Controllers\Auth\PasswordController;
use App\Http\Controllers\Auth\RegisteredUserController;
use App\Http\Controllers\Auth\VerifyEmailController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\MarketplaceController;
use App\Http\Controllers\DigitalAssetController;
use Illuminate\Support\Facades\Route;

// ============================================================================
// PUBLIC ROUTES
// ============================================================================
Route::get('/', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

Route::get('/marketplace', [MarketplaceController::class, 'index'])->name('marketplace');
Route::get('/marketplace/search', [MarketplaceController::class, 'search'])->name('marketplace.search');
Route::post('/cart/add', [App\Http\Controllers\CartController::class, 'add'])->name('cart.add');
Route::get('/cart/count', [App\Http\Controllers\CartController::class, 'getCount'])->name('cart.count');
Route::get('/cart/items', [App\Http\Controllers\CartController::class, 'getItems'])->name('cart.items');
Route::post('/cart/update', [App\Http\Controllers\CartController::class, 'update'])->name('cart.update');
Route::post('/cart/remove', [App\Http\Controllers\CartController::class, 'remove'])->name('cart.remove');
Route::post('/cart/clear', [App\Http\Controllers\CartController::class, 'clear'])->name('cart.clear');

// Currency switching
Route::post('/currency/set', [App\Http\Controllers\CurrencyController::class, 'setCurrency'])->name('currency.set');

// Paystack Routes
Route::post('/paystack/initialize', [App\Http\Controllers\PaystackController::class, 'initializePayment'])->name('paystack.initialize');
Route::get('/paystack/callback', [App\Http\Controllers\PaystackController::class, 'handleCallback'])->name('paystack.callback');
Route::post('/paystack/webhook', [App\Http\Controllers\PaystackController::class, 'webhook'])->name('paystack.webhook');

// Flutterwave Routes
Route::post('/flutterwave/initialize', [App\Http\Controllers\FlutterwaveController::class, 'initializePayment'])->name('flutterwave.initialize');
Route::get('/flutterwave/callback', [App\Http\Controllers\FlutterwaveController::class, 'handleCallback'])->name('flutterwave.callback');
Route::post('/flutterwave/webhook', [App\Http\Controllers\FlutterwaveController::class, 'webhook'])->name('flutterwave.webhook');

// Payment Cancel Page
Route::get('/payment/cancel', function () { return view('user.payment-cancel'); })->name('payment.cancel');



Route::get('/marketplace/product/{id}', [MarketplaceController::class, 'show'])->name('product.detail');

Route::get('/software', function () {
    return view('software');
})->name('software');

Route::get('/how-it-works', function () {
    return view('how-it-works');
})->name('how-it-works');

Route::get('/license-terms', function () {
    return view('license-terms');
})->name('license.terms');

// ============================================================================
// GUEST ROUTES (Not logged in)
// ============================================================================
Route::middleware('guest')->group(function () {
    // Registration
    Route::get('register', [RegisteredUserController::class, 'create'])->name('register');
    Route::post('register', [RegisteredUserController::class, 'store']);

    // Login
    Route::get('login', [AuthenticatedSessionController::class, 'create'])->name('login');
    Route::post('login', [AuthenticatedSessionController::class, 'store']);

    // Password Reset
    Route::get('forgot-password', [PasswordController::class, 'create'])->name('password.request');
    Route::post('password/send-code', [PasswordController::class, 'sendCode'])->name('password.send-code');
    Route::post('password/reset-with-code', [PasswordController::class, 'resetWithCode'])->name('password.reset-with-code');
});

// ============================================================================
// AUTHENTICATED ROUTES (Logged in)
// ============================================================================
Route::middleware('auth')->group(function () {
    // Dashboard
    Route::get('/dashboard', [App\Http\Controllers\DashboardController::class, 'index'])->middleware('verified')->name('dashboard');

    // Profile Management
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Password Management
    Route::put('password', [PasswordController::class, 'update'])->name('password.update');

    // Email Verification
    Route::get('verify-email', EmailVerificationPromptController::class)->name('verification.notice');
    Route::post('verify-code', [VerifyEmailController::class, 'verifyCode'])
        ->middleware('throttle:6,1')
        ->name('verification.code');
    Route::get('verify-email/{id}/{hash}', VerifyEmailController::class)
        ->middleware(['signed', 'throttle:6,1'])
        ->name('verification.verify');
    Route::post('email/verification-notification', [EmailVerificationNotificationController::class, 'store'])
        ->middleware('throttle:6,1')
        ->name('verification.send');

    // Password Confirmation
    Route::get('confirm-password', [ConfirmablePasswordController::class, 'show'])->name('password.confirm');
    Route::post('confirm-password', [ConfirmablePasswordController::class, 'store']);

    // Digital Assets Management
    Route::resource('digital-assets', DigitalAssetController::class);
    
    // Checkout (requires authentication)
    Route::get('/checkout', function () {
        return view('user.checkout');
    })->name('checkout');

    // My Purchases
    Route::get('/my-purchases', [App\Http\Controllers\PurchaseController::class, 'index'])->name('purchases.index');
    Route::get('/my-purchases/{digitalAsset}', [App\Http\Controllers\PurchaseController::class, 'show'])->name('purchases.show');
    
    // Download
    Route::get('/download/{orderItem}', [App\Http\Controllers\DownloadController::class, 'download'])->name('download');
    
    // Payment Success Page (requires auth to see purchases)
    Route::get('/checkout/success', function () { return view('user.checkout-success'); })->name('checkout.success');

    // Logout
    Route::post('logout', [AuthenticatedSessionController::class, 'destroy'])->name('logout');
});
