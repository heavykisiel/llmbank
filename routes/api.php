<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\BankController;


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

// Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//     return $request->user();
// });


Route::post('/login', [AuthController::class, 'login']);
Route::post('/register', [AuthController::class, 'register']);
Route::post('/userList', [AuthController::class, 'userList']);
Route::post('/change', [AuthController::class, 'change']);



Route::post('/bankNumbers', [BankController::class, 'bankNumbers']);
Route::post('/transfer', [BankController::class, 'transfer']);
Route::post('/history', [BankController::class, 'showTransactionHistory']);
Route::post('/selectedHistory', [BankController::class, 'selectedHistory']);
Route::post('/infoBankAcc', [BankController::class, 'infoBankAcc']);

// Route::get('/getCurs', [BankController::class, 'getCurs']);
