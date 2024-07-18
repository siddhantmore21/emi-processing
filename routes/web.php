<?php

use App\Http\Controllers\admin\EmiDetailsController;
use App\Http\Controllers\admin\LoanDetailsController;
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

Route::get('/', function () {
    return view('welcome');
});

Auth::routes();

Route::middleware('auth')->group(function () {

    Route::get('/loans-details', [LoanDetailsController::class, 'index'])->name('loan-details.index');
    Route::get('/emi-details', [EmiDetailsController::class, 'index'])->name('emi-details.index');
    Route::post('/emi-calculations', [EmiDetailsController::class, 'processEmiCalculations'])->name('process-emi-calculations');

});
