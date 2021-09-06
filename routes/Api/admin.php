<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\Api\{
    MaskapaiController,
    HotelController,
    OperationalController,
    TipeController
};
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
Route::get('logs', '\Rap2hpoutre\LaravelLogViewer\LogViewerController@index');

Route::post('admin/login',[LoginController::class, 'adminLogin'])->name('adminLogin');
Route::group( ['prefix' => 'admin','middleware' => ['auth:admin-api','scopes:admin'] ],function(){
    // authenticated staff routes here
    Route::get('dashboard',[LoginController::class, 'adminDashboard']);
    Route::group(['prefix' => 'maskapai'], function (){
        Route::get('/list', [MaskapaiController::class, 'list']);
        Route::get('/', [MaskapaiController::class, 'index']);
        Route::get('/{id}', [MaskapaiController::class, 'show']);
        Route::post('/', [MaskapaiController::class, 'store']);
        Route::put('/{id}', [MaskapaiController::class, 'update']);
        Route::delete('/{id}', [MaskapaiController::class, 'destroy']);
    });

    Route::group(['prefix' => 'hotel'], function() {
        Route::get('/', [HotelController::class, 'index']);
        Route::get('/list', [HotelController::class, 'listHotel']);
        Route::get('/{id}', [HotelController::class, 'show']);
        Route::put('/{id}', [HotelController::class, 'update']);
        Route::post('/', [HotelController::class, 'store']);
        Route::delete('/{id}', [HotelController::class, 'destroy']);
    });

    Route::group(['prefix' => 'paket'], function() {
        Route::get('/', [OperationalController::class, 'index']);
        Route::post('/', [OperationalController::class, 'store']);
        Route::get('/{id}', [OperationalController::class, 'show']);
        Route::put('/{id}', [OperationalController::class, 'update']);
        Route::delete('{id}', [OperationalController::class, 'destroy']);
        Route::group(['prefix' => '{paket_id}/types'], function() {
            Route::get('/', [TipeController::class, 'index']);
            Route::get('/{id}', [TipeController::class, 'show']);
            Route::post('/', [TipeController::class, 'store']);
            Route::put('/{id}', [TipeController::class, 'update']);
            Route::delete('/{id}', [TipeController::class, 'destroy']);
        });
        Route::prefix('{paket_id}/photo')->group(function () {
            Route::get('/', [OperationalController::class, 'listPhoto']);
            Route::post('/', [OperationalController::class, 'postPhoto']);
            Route::delete('/{foto_id}', [OperationalController::class, 'postDeletePhoto']);
        });
        Route::prefix('{paket_id}/file')->group(function(){
            Route::get('/', [OperationalController::class, 'getFile']);
            Route::post('/', [OperationalController::class, 'postFile']);
        });
    });
});
