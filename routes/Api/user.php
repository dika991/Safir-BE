<?php

use App\Http\Controllers\Api\DokumenController;
use App\Http\Controllers\Api\JemaahController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\Api\User\DashboardController;
use App\Http\Controllers\Api\PemesananController;
use App\Http\Controllers\Api\PembayaranController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!```
|
*/

Route::post('/user/login',[LoginController::class, 'userLogin'])->name('userLogin');
Route::get('/user/packages', [DashboardController::class, 'listPackage']);
Route::get('/user/recent_package', [DashboardController::class, 'recentPackage']);
Route::get('/user/package/{code_package}', [DashboardController::class,  'detailPackageCode']);

Route::group( ['prefix' => 'user','middleware' => ['auth:user-api','scopes:user'] ],function(){
    // authenticated staff routes here
    Route::get('dashboard',[LoginController::class, 'userDashboard']);

    // Route::prefix('package')->group(function () {
    //     Route::get("/{id_package}", [DashboardController::class, 'detailPackage']);
    // });

    Route::prefix('book')->group(function () {
        Route::post('/', [PemesananController::class, 'storeV2']);
        Route::get('/', [PemesananController::class, 'listPemesanan']);
        // Route::post('/detail', [PemesananController::class, 'detailPemesanan']);
        Route::get('/{code}/detail', [PemesananController::class, 'detPemesanan']);
        Route::get('/{code}/jemaah', [JemaahController::class, 'listJemaah']);
        Route::get('/{id_pemesanan}/jemaah/{id_jemaah}', [JemaahController::class, 'detailJemaah']);
        Route::put('/{id_pemesanan}/jemaah/{id_jemaah}', [JemaahController::class, 'updateJemaah']);
        Route::get('/{id_pemesanan}/jemaah/{id_jemaah}/dokumen', [DokumenController::class, 'listDokumen']);
        Route::post('/{code}/jemaah/{id_jemaah}/dokumen', [DokumenController::class, 'postNewDokumen']);
        Route::post('/{code}/jemaah/{id_jemaah}/dokumen/{id_dokumen}', [DokumenController::class, 'uploadDokumen']);
        Route::delete('/{code}/jemaah/{id_jemaah}/dokumen/{id_dokumen}', [DokumenController::class, 'deleteDokumen']);



        Route::post('/payment/{idTagihan}/upload', [PembayaranController::class, 'postImage']);

        Route::prefix('bill')->group(function () {
            Route::get('/', [PemesananController::class, 'listTagihan']);
            Route::get('/{id}', [PemesananController::class, 'detailTagihan']);
        });
        Route::prefix('payment')->group(function () {
            Route::get('/detail/{id_pembayaran}', [PembayaranController::class, 'detailPayment']);
            Route::post('/', [PembayaranController::class, 'postPayment']);
        });
    });
});
