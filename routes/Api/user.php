<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\Api\User\DashboardController;
use App\Http\Controllers\Api\PemesananController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::post('user/login',[LoginController::class, 'userLogin'])->name('userLogin');
Route::get('/user/packages', [DashboardController::class, 'listPackage']);
Route::group( ['prefix' => 'user','middleware' => ['auth:user-api','scopes:user'] ],function(){
    // authenticated staff routes here
    Route::get('dashboard',[LoginController::class, 'userDashboard']);

    Route::prefix('package')->group(function () {
        Route::get("/{id_package}", [DashboardController::class, 'detailPackage']);
    });

    Route::prefix('book')->group(function () {
        Route::post('/', [PemesananController::class, 'store']);
    });
});
