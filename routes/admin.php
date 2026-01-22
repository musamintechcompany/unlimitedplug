<?php

use App\Http\Controllers\Admin\LoginController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\ProductController;
use App\Http\Controllers\Admin\OnboardingController;
use App\Http\Controllers\Admin\AnalyticsController;
use Illuminate\Support\Facades\Route;

// Admin Onboarding Routes (accessible only when no admins exist)
Route::prefix('management/portal/admin')->group(function () {
    Route::get('/onboarding', [OnboardingController::class, 'show'])->name('admin.onboarding');
    Route::post('/onboarding', [OnboardingController::class, 'store'])->name('admin.onboarding.store');
});

// Admin Authentication Routes (No middleware to avoid redirect issues)
Route::prefix('management/portal/admin')->group(function () {
    Route::get('/login', [LoginController::class, 'showLogin'])->name('admin.login');
    Route::post('/login', [LoginController::class, 'authenticate'])->name('admin.authenticate');
    Route::post('/logout', [LoginController::class, 'logout'])->name('admin.logout');
});

// Protected Admin Routes (Requires admin authentication)
Route::prefix('management/portal/admin')->middleware('auth:admin')->name('admin.')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/profile', [DashboardController::class, 'profile'])->name('profile');
    
    // Products Management
    Route::get('products/select-type', function () {
        return view('management.portal.admin.products.select-type');
    })->name('products.select-type');
    Route::post('products/bulk-action', [ProductController::class, 'bulkAction'])->name('products.bulk-action');
    Route::get('products/export', [ProductController::class, 'export'])->name('products.export');
    Route::resource('products', ProductController::class);
    Route::delete('products/{product}/delete-banner', [ProductController::class, 'deleteBanner'])->name('products.delete-banner');
    Route::delete('products/{product}/delete-media/{index}', [ProductController::class, 'deleteMedia'])->name('products.delete-media');
    Route::delete('products/{product}/delete-file/{index}', [ProductController::class, 'deleteFile'])->name('products.delete-file');
    
    // User Management
    Route::resource('users', \App\Http\Controllers\Admin\UserController::class)->only(['index', 'show', 'store', 'update', 'destroy']);
    
    // Category Management
    Route::get('/categories', [\App\Http\Controllers\Admin\CategoryController::class, 'index'])->name('categories.index');
    Route::post('/categories/category', [\App\Http\Controllers\Admin\CategoryController::class, 'storeCategory'])->name('categories.store-category');
    Route::post('/categories/subcategory', [\App\Http\Controllers\Admin\CategoryController::class, 'storeSubcategory'])->name('categories.store-subcategory');
    Route::put('/categories/category/{category}', [\App\Http\Controllers\Admin\CategoryController::class, 'updateCategory'])->name('categories.update-category');
    Route::put('/categories/subcategory/{subcategory}', [\App\Http\Controllers\Admin\CategoryController::class, 'updateSubcategory'])->name('categories.update-subcategory');
    Route::delete('/categories/category/{category}', [\App\Http\Controllers\Admin\CategoryController::class, 'destroyCategory'])->name('categories.destroy-category');
    Route::delete('/categories/subcategory/{subcategory}', [\App\Http\Controllers\Admin\CategoryController::class, 'destroySubcategory'])->name('categories.destroy-subcategory');
    
    // Payment History (Admin Only)
    Route::get('/payments', function () {
        $payments = \App\Models\Payment::with('user')
            ->orderBy('created_at', 'desc')
            ->paginate(20);
        return view('management.portal.admin.payments.index', compact('payments'));
    })->name('payments.index');
    
    // Reviews Management
    Route::get('/reviews', [\App\Http\Controllers\Admin\ReviewController::class, 'index'])->name('reviews.index');
    Route::patch('/reviews/{review}/approve', [\App\Http\Controllers\Admin\ReviewController::class, 'approve'])->name('reviews.approve');
    Route::delete('/reviews/{review}', [\App\Http\Controllers\Admin\ReviewController::class, 'destroy'])->name('reviews.destroy');
    
    // Coupons Management
    Route::resource('coupons', \App\Http\Controllers\Admin\CouponController::class);
    
    // Notifications Management
    Route::get('/notifications', [\App\Http\Controllers\Admin\NotificationController::class, 'index'])->name('notifications.index');
    Route::get('/notifications/count', [\App\Http\Controllers\Admin\NotificationController::class, 'count'])->name('notifications.count');
    Route::post('/notifications/{id}/read', [\App\Http\Controllers\Admin\NotificationController::class, 'markAsRead'])->name('notifications.read');
    Route::post('/notifications/read-all', [\App\Http\Controllers\Admin\NotificationController::class, 'markAllAsRead'])->name('notifications.read-all');
    
    // Analytics
    Route::get('/analytics/chart-data', [AnalyticsController::class, 'getChartData'])->name('analytics.chart-data');
});