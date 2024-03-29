<?php

use App\Http\Controllers\AbsenController;
use App\Http\Controllers\DataAbsenController;
use App\Http\Controllers\DetailUserController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\PaymentController;
use Illuminate\Support\Facades\Route;

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

// Route::get('/', function () {
//     return view('welcome');
// });





Route::get('/auth', [UserController::class, 'index']);
Route::get('/login', function () {
    return redirect('auth?action=login');
});
Route::post('/login', [UserController::class, 'loginUser']);
Route::get('/register', function () {
    return redirect('auth?action=register');
});
Route::post('/register', [UserController::class, 'registerUser']);
Route::get('/logout', [UserController::class, 'logout']);

Route::group(['middleware' => ['auth']], function() {
    Route::get('/', function () {
        return view('layout.app-layout');
    });
    Route::get('/absen', [AbsenController::class, 'index']);
    Route::post('/absen', [AbsenController::class, 'absen']);
    Route::post('/absen-telat', [AbsenController::class, 'absenTelat']);
    Route::get('/data-absen', [DataAbsenController::class, 'index']);
    Route::get('/map-data/{id}', [DataAbsenController::class, 'showMap']);

    Route::get('/detail-user/{id}', [DetailUserController::class, 'index']);

    Route::get('/payment', [PaymentController::class, 'index']);
    Route::post('/payment', [PaymentController::class, 'payment']);
});

