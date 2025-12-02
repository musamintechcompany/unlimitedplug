<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Models\Subcategory;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::get('/categories/{categoryId}/subcategories', function ($categoryId) {
    try {
        $subcategories = Subcategory::where('category_id', $categoryId)->get(['id', 'name']);
        return response()->json($subcategories);
    } catch (\Exception $e) {
        return response()->json(['error' => 'Failed to load subcategories'], 500);
    }
});