<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Dokumen;
use App\Traits\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class DokumenController extends Controller
{
    use JsonResponse;

    public function listDokumen($id_pemesanan, $id_jemaah)
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

        return $this->successWithData("Success get Data", $response, 200);
    }

    public function postNewDokumen($code, $id_jemaah, Request $request)
    {
        $data = [
            "jenis" => $request->jenis,
            "id_jemaah" => $request->id_jemaah
        ];
        $dokumen = Dokumen::firstOrCreate($data);

        return $this->successWithData("Success Create Dokumen", $dokumen, 201);
    }

    public function uploadDokumen($code, $id_jemaah, $id_dokumen, Request $request)
    {
        $name = $request->file('file')->getClientOriginalName();
        $path = $request->file("file")->store('public/dokumen/' . $code . "/" . $id_jemaah . "/");

        $dokumen = Dokumen::findOrFail($id_dokumen);
        $dokumen->name = $name;
        $dokumen->path = $path;
        $dokumen->url = config('app.url') . "storage/" . str_replace('public/', '', $path);
        $dokumen->save();

        return $this->successWithData("Success Upload Dokumen", $dokumen, 200);
    }

    public function deleteDokumen($code, $id_jemaah, $id_dokumen)
    {
        $dokumen = Dokumen::findOrFail($id_dokumen);

        $image_path = $dokumen->path;
        $path = "/storage/" . str_replace('public/', '', $image_path);
        if (file_exists(public_path() . $path)) {
            Storage::delete(public_path() . $path);
            // unlink(public_path() . $path);
            $dokumen->delete();
        }

        return $this->success("Dokumen Berhasil dihapus", 200);
    }
}
