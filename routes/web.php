<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\customUserController;
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
#Route::redirect('/', 'http://localhost:4200/main');
Route::get('/welcome', function () {
    return view('welcome');
});

Route::get('/customUser', 'customUserController@index');

 
Route::get('/user', [customUserController::class, 'index']);