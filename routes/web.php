<?php

use Illuminate\Support\Facades\Route;


Route::get('/product', [App\Http\Controllers\ProductController::class, 'index']);
Route::post('/product-store', [App\Http\Controllers\ProductController::class, 'store']);
Route::get('/get-product', [App\Http\Controllers\ProductController::class, 'getProduct']);
Route::get('/delete-product/{id}', [App\Http\Controllers\ProductController::class, 'destroy']);

Route::get('/search', [App\Http\Controllers\ProductController::class, 'search'])->name('search');
Route::post('/search-pro', [App\Http\Controllers\ProductController::class, 'searchProduct']);

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
