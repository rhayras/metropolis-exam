<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PageController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\LogsController;
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

Route::get('/', [PageController::class, 'index'])->name('index');
Route::post('login', [LoginController::class, 'login']);
Route::get('logout', [LoginController::class, 'logout']);

Route::get('dashboard', [PageController::class, 'dashboard'])->middleware('auth');

Route::post('loadVisitLogs', [LogsController::class, 'index'])->name('loadVisitLogs');
Route::post('saveLog', [LogsController::class, 'saveLog'])->name('saveLog');
Route::post('saveCheckout', [LogsController::class, 'saveCheckout'])->name('saveCheckout');
