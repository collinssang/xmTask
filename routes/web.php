<?php

use App\Http\Controllers\ProcessDataController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::resource('/', ProcessDataController::class);
Route::post('/xm_task', [ProcessDataController::class, 'store'])->name('post_data');
Route::get('/xm_task/{company_symbol}', [ProcessDataController::class, 'showChart'])->name('display_chart');
Route::get('/send_email/{company_symbol}', [ProcessDataController::class, 'sendEmail'])->name('sendEmail');
Route::post('/generateImage/', [ProcessDataController::class, 'generateImage'])->name('generateImage');
