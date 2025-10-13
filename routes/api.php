<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\Hk\TransactionController;
use App\Http\Controllers\Api\V1\TransactionsController;

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

// Rest Api's
Route::get('transactions',[TransactionsController::class, 'handshake']);
Route::post('history/{val}',[TransactionsController::class, 'history']);
Route::post('wfs-sync',[TransactionsController::class, 'transmit']);

Route::post('store_transaction', [TransactionController::class, 'store']);
Route::post('get_history', [TransactionController::class, 'history']);
Route::get('/managers', [TransactionController::class, 'getActiveManagers']);
Route::get('/managers/{id}', [TransactionController::class, 'getManagerById']);
