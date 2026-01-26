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
use App\Http\Controllers\CheckoutController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\SitemapController;
use App\Http\Controllers\NewsletterController;
use Illuminate\Support\Facades\Route;

// ============================================================================
// PUBLIC ROUTES
// ============================================================================
Route::get('/', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

// SEO Routes
Route::get('/sitemap.xml', [SitemapController::class, 'index'])->name('sitemap');

Route::get('/marketplace', [MarketplaceController::class, 'index'])->name('marketplace');
Route::get('/marketplace/search', [MarketplaceController::class, 'search'])->name('marketplace.search');
Route::post('/cart/add', [App\Http\Controllers\CartController::class, 'add'])->name('cart.add');
Route::get('/cart/count', [App\Http\Controllers\CartController::class, 'getCount'])->name('cart.count');
Route::get('/cart/items', [App\Http\Controllers\CartController::class, 'getItems'])->name('cart.items');
Route::post('/cart/update', [App\Http\Controllers\CartController::class, 'update'])->name('cart.update');
Route::post('/cart/remove', [App\Http\Controllers\CartController::class, 'remove'])->name('cart.remove');
Route::post('/cart/clear', [App\Http\Controllers\CartController::class, 'clear'])->name('cart.clear');

// Favorites
Route::post('/favorites/toggle', [App\Http\Controllers\FavoriteController::class, 'toggle'])->name('favorites.toggle');
Route::get('/favorites/check', [App\Http\Controllers\FavoriteController::class, 'check'])->name('favorites.check');

// Currency switching
Route::post('/currency/set', [App\Http\Controllers\CurrencyController::class, 'setCurrency'])->name('currency.set');

// Flutterwave Routes
Route::post('/flutterwave/initialize', [App\Http\Controllers\FlutterwaveController::class, 'initializePayment'])->name('flutterwave.initialize');
Route::get('/flutterwave/callback', [App\Http\Controllers\FlutterwaveController::class, 'handleCallback'])->name('flutterwave.callback');
Route::post('/flutterwave/webhook', [App\Http\Controllers\FlutterwaveController::class, 'webhook'])->name('flutterwave.webhook');

// Payment Cancel Page
Route::get('/payment/failed', function () {
    if (!session()->has('payment_failed')) {
        return redirect()->route('marketplace');
    }
    session()->forget('payment_failed');
    return view('user.payment-failed');
})->name('payment.failed');



Route::get('/marketplace/product/{id}', [MarketplaceController::class, 'show'])->name('product.detail');

Route::get('/c/{slug}', [CategoryController::class, 'show'])->name('category.show');
Route::get('/c/{slug}/{productId}', [CategoryController::class, 'showProduct'])->name('category.product.detail');

// Newsletter Routes
Route::post('/newsletter/subscribe', [NewsletterController::class, 'subscribe'])->name('newsletter.subscribe');
Route::get('/newsletter/confirm/{token}', [NewsletterController::class, 'confirm'])->name('newsletter.confirm');
Route::get('/newsletter/unsubscribe/{email}', [NewsletterController::class, 'unsubscribe'])->name('newsletter.unsubscribe');

Route::get('/how-it-works', function () {
    return view('how-it-works');
})->name('how-it-works');

Route::get('/licenses/regular', function () {
    return view('licenses.regular');
})->middleware('auth')->name('license.regular');

Route::get('/licenses/extended', function () {
    return view('licenses.extended');
})->middleware('auth')->name('license.extended');

Route::get('/licenses/commercial', function () {
    return view('licenses.commercial');
})->middleware('auth')->name('license.commercial');

Route::get('/terms', function () {
    return view('terms');
})->name('terms');

Route::get('/policy', function () {
    return view('policy');
})->name('policy');

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

    // Notifications
    Route::get('/notifications', [App\Http\Controllers\NotificationController::class, 'index'])->name('notifications.index');
    Route::get('/notifications/unread', [App\Http\Controllers\NotificationController::class, 'unread'])->name('notifications.unread');
    Route::post('/notifications/{id}/read', [App\Http\Controllers\NotificationController::class, 'markAsRead'])->name('notifications.read');
    Route::post('/notifications/read-all', [App\Http\Controllers\NotificationController::class, 'markAllAsRead'])->name('notifications.read-all');

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
    
    // Checkout (requires authentication)
    Route::get('/checkout', function () {
        return view('user.checkout');
    })->name('checkout');
    Route::post('/order/create-free', [App\Http\Controllers\CheckoutController::class, 'createFreeOrder'])->name('order.create-free');

    // My Purchases
    Route::get('/my-purchases', [App\Http\Controllers\PurchaseController::class, 'index'])->name('purchases.index');
    Route::get('/my-purchases/{product}', [App\Http\Controllers\PurchaseController::class, 'show'])->name('purchases.show');
    
    // Reviews
    Route::post('/products/{product}/review', [App\Http\Controllers\ReviewController::class, 'store'])->name('reviews.store');
    
    // Coupons
    Route::post('/coupon/apply', [App\Http\Controllers\CouponController::class, 'apply'])->name('coupon.apply');
    Route::post('/coupon/remove', [App\Http\Controllers\CouponController::class, 'remove'])->name('coupon.remove');
    
    // Download
    Route::get('/download/{orderItem}', [App\Http\Controllers\DownloadController::class, 'download'])->name('download');
    
    // Payment Success Page (requires auth to see purchases)
    Route::get('/checkout/success', [App\Http\Controllers\CheckoutController::class, 'success'])->name('checkout.success');

    // Logout
    Route::post('logout', [AuthenticatedSessionController::class, 'destroy'])->name('logout');
    
    // Favorites
    Route::get('/favorites', [App\Http\Controllers\FavoriteController::class, 'index'])->name('favorites.index');
});
