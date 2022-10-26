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
Route::post('/gmvgrowth', [DashboardController::class, 'gmvGrowth']);
Route::post('/gmv-proportion', [DashboardController::class, 'gmvProportion']);
Route::post('trans-status-of-brands', [DashboardController::class, 'transStatusOfBrand']);
Route::post('/issueBank', [DashboardController::class, 'issueBank']);
Route::post('/errorDetail', [DashboardController::class, 'errorDetail']);
Route::post('/rateTransaction', [DashboardController::class, 'rateTransaction']);

