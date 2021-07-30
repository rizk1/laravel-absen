<?php

use App\Http\Controllers\AbsenController;
use App\Http\Controllers\UserController;
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

Route::get('/', function () {
    return view('layout.app-layout');
});

Route::get('/absen', [AbsenController::class, 'index']);
Route::post('/absen', [AbsenController::class, 'absen']);
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


