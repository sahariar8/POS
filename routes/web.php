<?php

use App\Http\Controllers\CategoryController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\ProductController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});


Route::get('/category',[CategoryController::class,'index']);
Route::get('/product',[ProductController::class,'index']);
Route::get('/customer',[CustomerController::class,'index']);
