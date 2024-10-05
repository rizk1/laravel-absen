<?php

use App\Http\Controllers\AbsenController;
use App\Http\Controllers\DataAbsenController;
use App\Http\Controllers\DetailUserController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\ShiftController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\JabatanController;
use App\Http\Controllers\DashboardController;
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





Route::get('/auth', [AuthController::class, 'index']);
Route::get('/login', function () {
    return redirect('auth?action=login');
});
    Route::post('/login', [AuthController::class, 'loginUser']);
Route::get('/register', function () {
    return redirect('auth?action=register');
});
Route::post('/register', [AuthController::class, 'registerUser']);
Route::get('/logout', [AuthController::class, 'logout']);

Route::group(['middleware' => ['auth']], function() {
    Route::get('/', function () {
        return view('layout.app-layout');
    });
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/absen', [AbsenController::class, 'index'])->name('absen');
    Route::post('/absen', [AbsenController::class, 'absen']);
    Route::post('/absen-telat', [AbsenController::class, 'absenTelat']);
    
    Route::get('/data-absen', [DataAbsenController::class, 'index'])->name('data-absen');
    Route::get('/map-data/{id}', [DataAbsenController::class, 'showMap']);
    Route::get('/download-absen-pdf', [DataAbsenController::class, 'downloadPdf'])->name('download-absen-pdf');

    Route::get('/detail-user', [DetailUserController::class, 'index'])->name('detail-user');
    Route::post('/user/{id}', [DetailUserController::class, 'saveOrUpdate'])->name('user.saveOrUpdate');


    Route::group(['middleware' => ['admin']], function() {
        Route::resource('shift', ShiftController::class);
        Route::resource('users', UserController::class);
        Route::resource('jabatan', JabatanController::class);
        Route::delete('/delete-absen/{id}', [DataAbsenController::class, 'destroy'])->name('delete-absen');
    });
});

