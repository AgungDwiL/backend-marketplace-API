<?php

use App\Http\Controllers\ProductController;
use App\Http\Controllers\CheckoutController;
use App\Http\Controllers\TransactionController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\VendorController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::patterns([
    'id' => '[0-9]+',
    'user_id' => '[0-9]+',
]);

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::post('/login', [UserController::class, 'login']);
Route::post('/register', [UserController::class, 'register']);

Route::group(['middleware' => 'auth:sanctum'], function () {
    Route::get('/logout', [UserController::class, 'logout']);

    Route::group(['prefix' => 'user'], function () {
        Route::patch('/', [UserController::class, 'update']);
    });

    Route::group(['prefix' => 'vendor'], function () {
        Route::get('/', [VendorController::class, 'index']);
        Route::get('/{id}', [VendorController::class, 'detail']);
        Route::post('/', [VendorController::class, 'create']);
        Route::patch('/{id}', [VendorController::class, 'update']);
        Route::delete('/{id}', [VendorController::class, 'delete']);
    });

    Route::group(['prefix' => 'product'], function () {
        Route::post('/', [ProductController::class, 'create']);
        Route::patch('/{id}', [ProductController::class, 'update']);
        Route::delete('/{id}', [ProductController::class, 'delete']);
    });

    Route::group(['prefix' => 'checkout'], function () {
        Route::get('/', [CheckoutController::class, 'index']);
        Route::get('/{id}', [CheckoutController::class, 'detail']);
        Route::post('/', [CheckoutController::class, 'create']);
        Route::patch('/{id}', [CheckoutController::class, 'update']);
        Route::delete('/{id}', [CheckoutController::class, 'delete']);
    });

    Route::group(['prefix' => 'transaction'], function () {
        Route::get('/', [TransactionController::class, 'index']);
        Route::get('/{id}', [TransactionController::class, 'detail']);
        Route::post('/', [TransactionController::class, 'create']);
        Route::delete('/{id}', [TransactionController::class, 'delete']);
    });
});

Route::group(['prefix' => 'product'], function () {
    Route::get('/', [ProductController::class, 'index']);
    Route::get('/{user_id}', [ProductController::class, 'indexByUser']);
    Route::get('/{id}', [ProductController::class, 'detail']);
});
