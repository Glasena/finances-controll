<?php

use App\Http\Controllers\BankAccountsController;
use App\Http\Controllers\BanksController;
use App\Http\Controllers\BankTransactionsController;
use App\Http\Controllers\CreditCardBillController;
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
Route::get('/banks/{bank}', [BanksController::class, 'showSingle']);
Route::get('/banks', [BanksController::class, 'showAll']);
Route::post('/banks/delete/{bank}', [BanksController::class, 'delete']);

// User Routes
Route::post('/users', [UsersController::class, 'store']);
Route::post('/users/delete/{user}', [UsersController::class, 'delete']);

//Transactions Categories Routes 
Route::post('/transaction-categories/{user}', [TransactionCategoriesController::class, 'store']);
//Route::post('/transaction-categories/{user}', [TransactionCategoriesController::class, 'delete']);

//Bank Accounts Routes
Route::post('/bank_account', [BankAccountsController::class, 'store']);

//Bank Transactions 
Route::post('/bank_transaction', [BankTransactionsController::class, 'store']);
Route::post('/bank_transaction/import/{bank_account}', [BankTransactionsController::class, 'import']);
Route::put('/bank_transaction/category/{bank_transaction}', [BankTransactionsController::class, 'updatetransactioncategory']);

//Credit Card Bill
Route::post('/credit_card/import/{bank_account}', [CreditCardBillController::class, 'import']);
