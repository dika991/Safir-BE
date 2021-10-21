<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Model\Pembayaran;
use App\Models\Pembayaran as ModelsPembayaran;
use App\Models\Pemesanan;
use App\Traits\JsonResponse;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class PembayaranController extends Controller
{
    use JsonResponse;

    public function detailPayment($id_pembayaran){
        $pembayaran = ModelsPembayaran::where('id', $id_pembayaran)->first();
        if(!$pembayaran){
            return $this->fail(trans('message.failed'));
        }

        return $this->successWithData(trans('message.success'), $pembayaran);

    }

    public function postPayment(Request $request){
        $validate = Validator::make($request->all(), [
            "nominal" => "required",
            "code" => "required",
            "keterangan" => "required",
            "bank_id" => "required"
        ]);
        if($validate->fails()){
            return $validate->errors();
        }
        $pemesanan = Pemesanan::where('code', $request['code'])->select('id')->first(); 
        if(!$pemesanan){
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

    public function postImage(Request $request){
        $validatedData = $request->validate([
            'image' => 'required|image|mimes:png,jpg,jpeg|max:2048',
        ]);
        $paket = Paket::findOrFail($paket_id);

        $name = $request->file('image')->getClientOriginalName();
        $path = $request->file('image')->store('public/images/' . $paket->kode);

        $save = new FotoPaket;
        $save->id_paket = $paket_id;
        $save->name = $name;
        $save->path = $path;
        $save->url = config('app.url') . "/storage/" . str_replace('public/', '', $path);
        $save->save();

        return $this->successWithData('Upload Success', $save);
    }

    public function updatePayment(){

    }
}
