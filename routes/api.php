<?php

use App\Http\Controllers\BanksController;
use App\Http\Controllers\TransactionCategoriesController;
use App\Http\Controllers\UsersController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

// Bank routes
Route::get('/banks', [BanksController::class], 'index');
Route::post('/banks', [BanksController::class, 'store']);
Route::post('/banks/{bank}', [BanksController::class, 'update']);
Route::post('/banks/delete/{bank}', [BanksController::class, 'delete']);

// User Routes
Route::post('/users', [UsersController::class, 'store']);
Route::post('/users/delete/{user}', [UsersController::class, 'delete']);

//Transactions Categories Routes 
Route::post('/transaction-categories/{user}', [TransactionCategoriesController::class, 'store']);
//Route::post('/transaction-categories/{user}', [TransactionCategoriesController::class, 'delete']);
