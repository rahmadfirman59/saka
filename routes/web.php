<?php

use App\Http\Controllers\AkunController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\AuthController;

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

Route::post('login', [AuthController::class, 'login'])->name('post.login');
Route::get('login', [AuthController::class, 'index'])->name('login');

Route::group(['middleware' => ['ceklogin']], function () {
    Route::get('/', [DashboardController::class, 'index'])->name('dashboard');

    Route::get('/akun', [AkunController::class, 'index'])->name('akun');
    Route::get('/akun/edit/{id}', [AkunController::class, 'edit'])->name('akun.edit');
    // Route::get('/akun', [DashboardController::class, 'index'])->name('akun');
});
