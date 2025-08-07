<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\CartController;

//product get, add, update and delete
Route::get('/product-form', [ProductController::class, 'showForm']);
Route::post('/product-store', [ProductController::class, 'storeForm']);
Route::post('/product-update/{id}', [ProductController::class, 'update']);
Route::post('/product-delete', [ProductController::class, 'destroy']);
Route::get('/product/{id}/images', [ProductController::class, 'getImages']);
Route::post('/product-image-delete/{id}', [ProductController::class, 'deleteImage']);

//cart add and get
Route::post('/cart-add', [CartController::class, 'addToCart']);
Route::get('/cart-list', [CartController::class, 'getCartItems']);
Route::get('/products', [ProductController::class, 'list']);

?>