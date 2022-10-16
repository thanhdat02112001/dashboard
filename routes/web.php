<?php

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

Route::get('/', function () {
    return view('merchant');
});
Route::get("/user-profile", function(){

})->name('user.profile.index');
Route::post("/logout", function(){})->name('logout');
Route::get('dashboard-merchant', function(){})->name('dashboard.merchant');
Route::get('/dashboard', [DashboardController::class, 'index']);
Route::get('/gmvgrowth', [DashboardController::class, 'gmvGrowth']);
Route::get('/gmv-proportion', [DashboardController::class, 'gmvProportion']);
Route::get('trans-status-of-brands', [DashboardController::class, 'transStatusOfBrand']);
Route::get('/issueBank', [DashboardController::class, 'issueBank']);
Route::get('/errorDetail', [DashboardController::class, 'errorDetail']);
Route::get('/rateTransaction', [DashboardController::class, 'rateTransaction']);

