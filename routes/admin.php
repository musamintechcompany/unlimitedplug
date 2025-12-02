<?php

use App\Http\Controllers\Admin\LoginController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\DigitalAssetController;
use App\Http\Controllers\Admin\OnboardingController;
use Illuminate\Support\Facades\Route;

// Admin Onboarding Routes (accessible only when no admins exist)
Route::prefix('management/portal/admin')->group(function () {
    Route::get('/onboarding', [OnboardingController::class, 'show'])->name('admin.onboarding');
    Route::post('/onboarding', [OnboardingController::class, 'store'])->name('admin.onboarding.store');
});

// Admin Authentication Routes (No middleware - public access)
Route::prefix('management/portal/admin')->group(function () {
    Route::get('/login', [LoginController::class, 'showLogin'])->name('admin.login');
    Route::post('/login', [LoginController::class, 'authenticate'])->name('admin.authenticate');
    Route::post('/logout', [LoginController::class, 'logout'])->name('admin.logout');
});

// Protected Admin Routes (Requires admin authentication)
Route::prefix('management/portal/admin')->middleware('auth:admin')->name('admin.')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/profile', [DashboardController::class, 'profile'])->name('profile');
    
    // Digital Assets Management
    Route::resource('digital-assets', DigitalAssetController::class);
    Route::delete('digital-assets/{digital_asset}/delete-banner', [DigitalAssetController::class, 'deleteBanner'])->name('digital-assets.delete-banner');
    Route::delete('digital-assets/{digital_asset}/delete-media/{index}', [DigitalAssetController::class, 'deleteMedia'])->name('digital-assets.delete-media');
    Route::delete('digital-assets/{digital_asset}/delete-file/{index}', [DigitalAssetController::class, 'deleteFile'])->name('digital-assets.delete-file');
    
    // User Management
    Route::resource('users', \App\Http\Controllers\Admin\UserController::class)->only(['index', 'store', 'update', 'destroy']);
    
    // Category Management
    Route::get('/categories', [\App\Http\Controllers\Admin\CategoryController::class, 'index'])->name('categories.index');
    Route::post('/categories/category', [\App\Http\Controllers\Admin\CategoryController::class, 'storeCategory'])->name('categories.store-category');
    Route::post('/categories/subcategory', [\App\Http\Controllers\Admin\CategoryController::class, 'storeSubcategory'])->name('categories.store-subcategory');
    Route::put('/categories/category/{category}', [\App\Http\Controllers\Admin\CategoryController::class, 'updateCategory'])->name('categories.update-category');
    Route::put('/categories/subcategory/{subcategory}', [\App\Http\Controllers\Admin\CategoryController::class, 'updateSubcategory'])->name('categories.update-subcategory');
    Route::delete('/categories/category/{category}', [\App\Http\Controllers\Admin\CategoryController::class, 'destroyCategory'])->name('categories.destroy-category');
    Route::delete('/categories/subcategory/{subcategory}', [\App\Http\Controllers\Admin\CategoryController::class, 'destroySubcategory'])->name('categories.destroy-subcategory');
});