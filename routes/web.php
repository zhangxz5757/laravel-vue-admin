<?php

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

Route::get('/', [\App\Http\Controllers\IndexController::class, 'index']);

//二维码
Route::get('/qrcode', [\App\Http\Controllers\QrcodeController::class, 'indexAction']);

Route::get('/agreement/info', [\App\Http\Controllers\IndexController::class, 'agreement']);
Route::get('/banner/info', [\App\Http\Controllers\IndexController::class, 'banner']);
