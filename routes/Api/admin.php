<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\Api\{
    MaskapaiController,
    HotelController,
    InventarisController,
    JemaahController,
    OperationalController,
    PemesananController,
    TagihanController,
    TipeController,
    TransaksiController
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

Route::post('admin/login', [LoginController::class, 'adminLogin'])->name('adminLogin');
Route::group(['prefix' => 'admin', 'middleware' => ['auth:admin-api', 'scopes:admin']], function () {
    // authenticated staff routes here
    Route::get('dashboard', [LoginController::class, 'adminDashboard']);
    Route::get('/whoami', [LoginController::class, 'siapaSaya']);
    Route::post('/putPassword', [LoginController::class, 'updatePassword']);
    Route::put('/putUser', [LoginController::class, 'updateUser']);
    Route::post('/logout', [LoginController::class, 'logout']);


    Route::group(["prefix" => '/admin', 'middleware' => ['scopes:superAdmin']], function () {
        Route::get("/", [LoginController::class, 'listAdmin']);
    });

    Route::group(['prefix' => '/admins', 'middleware' => ['scopes:superAdmin']], function () {
        Route::get('/', [LoginController::class, 'listAdmins']);
        Route::get('/{id}', [LoginController::class, 'detailAdmins']);
        Route::put('/{id}', [LoginController::class, 'updateAdmins']);
        Route::delete('/{id}', [LoginController::class, 'removeAdmins']);
    });

    Route::group(['prefix' => 'maskapai'], function () {
        Route::get('/list', [MaskapaiController::class, 'list']);
        Route::get('/', [MaskapaiController::class, 'index']);
        Route::get('/{id}', [MaskapaiController::class, 'show']);
        Route::post('/', [MaskapaiController::class, 'store']);
        Route::put('/{id}', [MaskapaiController::class, 'update']);
        Route::delete('/{id}', [MaskapaiController::class, 'destroy']);
    });

    Route::group(['prefix' => 'hotel'], function () {
        Route::get('/', [HotelController::class, 'index']);
        Route::get('/list', [HotelController::class, 'listHotel']);
        Route::get('/{id}', [HotelController::class, 'show']);
        Route::put('/{id}', [HotelController::class, 'update']);
        Route::post('/', [HotelController::class, 'store']);
        Route::delete('/{id}', [HotelController::class, 'destroy']);
    });

    Route::group(['prefix' => 'paket'], function () {
        Route::get('/', [OperationalController::class, 'index']);
        Route::post('/', [OperationalController::class, 'store']);
        Route::get('/{id}', [OperationalController::class, 'show']);
        Route::put('/{id}', [OperationalController::class, 'update']);
        Route::delete('{id}', [OperationalController::class, 'destroy']);
        Route::group(['prefix' => '{paket_id}/types'], function () {
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
        Route::prefix('{paket_id}/file')->group(function () {
            Route::get('/', [OperationalController::class, 'getFile']);
            Route::post('/', [OperationalController::class, 'postFile']);
        });
    });

    Route::group(['prefix' => 'transaksi'], function () {
        Route::get('/', [TagihanController::class, 'index']);
        Route::get('/{id}', [TagihanController::class, 'show']);
        Route::put('/{id}', [TagihanController::class, 'updateVerifikasi']);
    });

    Route::group(['prefix' => 'pemesanan'], function () {
        Route::get('/', [PemesananController::class, 'listPemesananAdmin']);
        Route::get('/{id}', [PemesananController::class, 'detailPemesananAdmin']);
        Route::put('/{id}', [TagihanController::class, 'updateVerifikasi']);
    });

    Route::prefix('jemaah')->group(function () {
        Route::get('detail/{id}', [JemaahController::class, "detailJemaahAdmin"]);
    });

    Route::prefix('tagihan')->group(function () {
        Route::get('/{id}', [TagihanController::class, "detailTagihan"]);
        Route::put('/{id}', [TagihanController::class, "updateTagihan"]);
        Route::post('/{id}', [TagihanController::class, "postTagihanAdmin"]);
        Route::delete('/{id}', [TagihanController::class, "deleteTagihan"]);
    });

    Route::prefix('inventaris')->group(function () {
        Route::get('/', [InventarisController::class, "listInventaris"]);
        Route::post('/', [InventarisController::class, "storeInventaris"]);
        Route::get('/{id}', [InventarisController::class, "showInventaris"]);
        Route::put('/{id}', [InventarisController::class, "updateInventaris"]);
        Route::delete('/{id}', [InventarisController::class, "deleteInventaris"]);
    });
});
