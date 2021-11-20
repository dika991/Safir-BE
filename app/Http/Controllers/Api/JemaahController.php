<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Model\Jemaah;
use App\Models\Dokumen;
use App\Models\Jemaah as ModelsJemaah;
use App\Models\Pemesanan;
use App\Traits\JsonResponse;
use Illuminate\Http\Request;

class JemaahController extends Controller
{
    use JsonResponse;

    public function listJemaah($code)
    {
        $pemesanan = Pemesanan::where('code', $code)->select('id')->first();
        $jemaah = ModelsJemaah::where('id_pemesanan', $pemesanan->id)->get();

        return $this->successWithData("Success Get Data", $jemaah, 200);
    }

    public function detailJemaah($id_pemesanan, $id_jemaah)
    {
        $jemaah = ModelsJemaah::findOrFail($id_jemaah);

        return $this->successWithData("Success", $jemaah, 200);
    }

    public function updateJemaah($id_pemesanan, $id_jemaah, Request $request)
    {
        $validate = $request->validate([
            "nama" => "required",
            "jenis_kelamin" => "required",
            "usia" => "required",
        ]);

        $jemaah = ModelsJemaah::findOrFail($id_jemaah);
        $jemaah->nama = $request['nama'];
        $jemaah->usia = $request['usia'];
        $jemaah->jenis_kelamin = $request['jenis_kelamin'];
        $jemaah->save();

        return $this->successWithData("Update Jemaah Berhasil", $jemaah);
    }

    public function detailJemaahAdmin($id)
    {
        $jemaah = ModelsJemaah::findOrFail($id);
        $dokumen = $this->listDokumen($jemaah->id);

        $jemaah['dokumen'] = $dokumen['dokumen'];
        $jemaah['check'] = $dokumen['check'];
        
        return $this->successWithData("Detail Jemaah", $jemaah);
    }

    public function listDokumen($id_jemaah)
    {
        $dokumen = ["passport", "ktp", "surat_vaksin", "pcr", "buku_nikah"];
        $check = [];
        foreach ($dokumen as $dd) {
            $list = Dokumen::where('id_jemaah', $id_jemaah)->where('jenis', "LIKE", "%" . $dd . "%")->first();
            if (!$list) {
                array_push($check, $dd);
            }
        }
        $list = Dokumen::where('id_jemaah', $id_jemaah)->get();

        $response = [
            "check" => $check,
            "dokumen" => $list
        ];
        return $response;
    }
}
