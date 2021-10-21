<?php

namespace App\Http\Controllers\Api;

use App\Http\Requests\PostBookPemesanan;
use App\Models\Pemesanan;
use App\Models\Tagihan;
use App\Traits\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Services\PemesananService;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class PemesananController extends Controller
{
    use JsonResponse;
    private $pemesananService;

    public function __construct(
        PemesananService $pemesananService
    ) {
        $this->pemesananService = $pemesananService;
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(PostBookPemesanan $request)
    {
        $result = $this->pemesananService->store($request->all());
        if ($result->isFail()) {
            return $this->fail($result->getMessage());
        }

        return $this->successWithData(
            trans('message.store-data') . ' ' . trans('message.success'),
            $result->getResult()
        );
    }

    public function storeV2(PostBookPemesanan $request)
    {
        $result = $this->pemesananService->storeV2($request->all());
        if ($result->isFail()) {
            return $this->fail($result->getMessage());
        }

        return $this->successWithData(
            trans('message.store-data') . ' ' . trans('message.success'),
            $result->getResult()
        );
    }

    public function detailPemesanan(Request $request)
    {
        $pemesanan = Pemesanan::where('code', $request->code)->first();
        if (!$pemesanan) {
            return $this->fail(trans('message.not-found'));
        }

        return $this->successWithData(trans('message.success'), $pemesanan);
    }

    public function detPemesanan($code){
        $pemesanan = Pemesanan::where('code', $code)->with('tagihan', 'detail', 'detail.tipe', 'detail.paket', 'transaksi')->first();
        if(!$pemesanan){
            return $this->fail(trans('message.not-found'));
        }

        return $this->successWithData(trans('message.success'), $pemesanan);
    }

    public function listPemesanan()
    {
        $user = Auth::user();
        $pemesanan = Pemesanan::where('user_id', $user->id)->with('transaksi')->get();
        if ($pemesanan->isEmpty()) {
            return $this->fail(trans('message.empty'));
        }

        return $this->successWithData(trans('message.success'), $pemesanan);
    }

    public function listTagihan(Request $request){
        $user = Auth::user();
        $tagihan = Pemesanan::where('code', $request['q'])->where('user_id', $user->id)->with('tagihan')->first();
        if(!$tagihan){
            return $this->fail(trans('message.empty'));
        }

        return $this->successWithData(trans('message.success'), $tagihan);
    }

    public function detailTagihan($id){
        $pemesanan = Tagihan::where('id', $id)->with('pemesanan')->first();
        if(!$pemesanan){
            return $this->fail(trans('message.empty'));
        }

        return $this->successWithData(trans('message.success'), $pemesanan);

    }
}
