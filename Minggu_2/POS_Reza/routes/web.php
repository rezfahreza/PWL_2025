<?php

use App\Http\Controllers\UserController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\SalesController;
use Illuminate\Support\Facades\Route;

Route::get('/', [HomeController::class, 'index']);

Route::prefix('category')->group(function (){
    Route::get('/food-beverage', [CategoryController::class, 'foodBeverage']);
    Route::get('/beauty-health', [CategoryController::class, 'beautyHealth']);
    Route::get('/home-care', [CategoryController::class, 'homeCare']);
    Route::get('/baby-kid', [CategoryController::class, 'babyKid']);
});

Route::get('/user/{id}/name/{name}', [UserController::class, 'user']);

Route::get('/sales', [SalesController::class, 'sales']);