<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Inventaris;
use App\Traits\JsonResponse;
use App\Transformers\InventarisTransformer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use PDO;

class InventarisController extends Controller
{
    use JsonResponse;

    public function listInventaris(Request $request)
    {
        $inventaris = Inventaris::query();
        if (isset($request->q) && $request->q != "") {
            $inventaris = $inventaris->where("nama", "LIKE", "%" . $request->q . "%");
        }

        $inventaris = $inventaris->paginate(10);

        return $this->successWithData(
            "Data",
            (new InventarisTransformer)->paginator($inventaris),
            200
        );
    }

    public function storeInventaris(Request $request)
    {
        $inventaris = Inventaris::create([
            "nama" => $request->nama,
            "jenis" => $request->jenis,
            "catatan" => $request->catatan,
            "jumlah" => $request->jumlah
        ]);

        return $this->successWithData(
            "Success",
            (new InventarisTransformer)->transform($inventaris),
            201
        );
    }

    public function showInventaris($id)
    {
        $inventaris = Inventaris::findOrFail($id);

        return $this->successWithData(
            "Success",
            (new InventarisTransformer)->transform($inventaris),
            200
        );
    }

    public function updateInventaris($id, Request $request)
    {
        $inventaris = Inventaris::findOrFail($id);
        $inventaris->nama = $request->nama;
        $inventaris->jenis =  $request->jenis;
        $inventaris->catatan = $request->catatan;
        $inventaris->jumlah = $request->jumlah;
        $inventaris->save();

        return $this->successWithData(
            "Success",
            (new InventarisTransformer)->transform($inventaris),
            201
        );
    }

    public function deleteInventaris($id)
    {
        $inventaris = Inventaris::findOrFail($id);

        $inventaris->delete();

        return $this->success("Data telah dihapus!");
    }
}
