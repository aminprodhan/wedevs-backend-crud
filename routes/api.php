<?php

use App\Http\Controllers\AuthController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProductController;
/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});
Route::group(['prefix' => 'auth'], function () {
    Route::post('/register', [AuthController::class, 'store']);
    Route::post('/login', [AuthController::class, 'login']);
});
Route::group(['prefix' => 'products','middleware'=> ["auth:api"]], function () {
    Route::get('/index', [ProductController::class, 'index'])->name('admin.products.index');
    Route::post('/store', [ProductController::class, 'store'])->name('admin.products.store');
    Route::post('/update', [ProductController::class, 'update'])->name('admin.products.update');
    Route::post('/delete', [ProductController::class, 'delete'])->name('admin.products.delete');
 });
