<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Model\Pembayaran;
use App\Models\Paket;
use App\Models\Pembayaran as ModelsPembayaran;
use App\Models\PembayaranPictures;
use App\Models\Pemesanan;
use App\Models\Tagihan;
use App\Traits\JsonResponse;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class PembayaranController extends Controller
{
    use JsonResponse;

    public function detailPayment($id_pembayaran)
    {
        $pembayaran = ModelsPembayaran::where('id', $id_pembayaran)->first();
        if (!$pembayaran) {
            return $this->fail(trans('message.failed'));
        }

        return $this->successWithData(trans('message.success'), $pembayaran);
    }

    public function postPayment(Request $request)
    {
        $validate = Validator::make($request->all(), [
            "nominal" => "required",
            "code" => "required",
            "keterangan" => "required",
            "bank_id" => "required"
        ]);
        if ($validate->fails()) {
            return $validate->errors();
        }
        $pemesanan = Pemesanan::where('code', $request['code'])->select('id')->first();
        if (!$pemesanan) {
            return $this->fail("Code not found", 404);
        }

        $pembayaran = new ModelsPembayaran();
        $pembayaran->tanggal = Carbon::parse()->today()->format('Y-m-d');
        $pembayaran->nominal = $request['nominal'];
        $pembayaran->catatan = $request['keterangan'];
        $pembayaran->id_pemesanan = $pemesanan->id;
        $pembayaran->id_tagihan = $request['id_tagihan'];
        $pembayaran->save();

        return $this->successWithData("Data saved.", $pembayaran);
    }

    public function postImage($idTagihan, Request $request)
    {
        $validatedData = $request->validate([
            'image' => 'required|max:2048',
        ]);

        $tagihan = Tagihan::findOrFail($idTagihan);

        $name = $request->file('image')->getClientOriginalName();
        $path = $request->file("image")->store('public/images/payment/' . $tagihan->id_pemesanan);

        $save = new PembayaranPictures;
        $save->id_tagihan = $idTagihan;
        $save->name = $name;
        $save->path = $path;
        $save->url = config('app.url') . "/storage/" . str_replace('public/', '', $path);

        $tagihan->status = 1;
        $pembayaran = new ModelsPembayaran;
        $pembayaran->tanggal = Carbon::create()->today();
        $pembayaran->nominal = $tagihan->nominal;
        $pembayaran->catatan = $tagihan->deskripsi;
        $pembayaran->id_pemesanan = $tagihan->id_pemesanan;
        $pembayaran->id_tagihan = $tagihan->id;

        DB::beginTransaction();
        try {
            $pembayaran->save();
            $save->save();
            $tagihan->save();
            DB::commit();
            return $this->successWithData('Upload Success', $save);
        } catch (\Exception $e) {
            DB::rollBack();
            return $this->fail($e->getMessage(), 500);
        }
    }
}
