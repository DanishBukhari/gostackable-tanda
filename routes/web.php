<?php

use App\Http\Controllers\Mono\MonoController;
use App\Http\Controllers\Okra\OkraController;
use Illuminate\Support\Facades\Route;
use phpseclib3\Crypt\RSA;

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

Route::get('/', function () {
    return redirect('login');
});

// Okra endpoints
Route::prefix("okra")->middleware('guest')->name("okra.")->group(function () {
    Route::post('/webhook', [OkraController::class, 'webhook'])->name('webhook');
});

Route::prefix('mono')->middleware('guest')->name('mono')->group(function () {
    Route::post('/webhook', [MonoController::class, 'webhook'])->name('webhook');
});

Route::group(['namespace' => 'Admin', 'middleware' => ['auth']], function () {
    Route::get('/dashboard', DashboardController::class);
});
