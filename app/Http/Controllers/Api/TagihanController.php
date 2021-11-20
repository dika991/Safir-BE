<?php

namespace App\Http\Controllers\Api;

use App\Models\Pemesanan;
use App\Models\Tagihan;
use App\Models\Transaksi;
use App\Traits\JsonResponse;
use App\Transformers\PembayaranTransformer;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use PDO;

class TagihanController extends Controller
{
    use JsonResponse;

    public function index(Request $request)
    {
        $data = Tagihan::query();
        $data = $data->with('pemesanan');
        if (isset($request['q']) && $request['q'] != "") {
            $data = $data->where('nominal', "LIKE", '%' . $request['q'] . "%");
        }
        $data = $data->where('status', ">", 0)->orderBy('created_at', 'DESC')->paginate(10);
        return $this->successWithData(
            trans('message.data'),
            (new PembayaranTransformer)->paginator($data)
        );
    }

    public function show($id)
    {
        $data = Tagihan::where('id', $id)->with('pembayaran', 'pictures')->first();

        return $this->successWithData(
            trans('message.data'),
            (new PembayaranTransformer)->transform($data)
        );
    }

    public function updateVerifikasi($id)
    {
        $tagihan = Tagihan::where('id', $id)->first();

        $tagihan->status = 2;
        $pemesanan = Pemesanan::findOrFail($tagihan->id_pemesanan);
        $pemesanan->status = 1;
        $transaksi = Transaksi::findOrFail($pemesanan->id_transaksi);
        $dibayar = $transaksi->sudah_dibayar + $tagihan->nominal;
        $belum = $transaksi->belum_dibayar - $tagihan->nominal;
        $transaksi->sudah_dibayar = $dibayar;
        $transaksi->belum_dibayar = $belum;

        DB::beginTransaction();
        try {
            $tagihan->save();
            $pemesanan->save();
            $transaksi->save();
            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            Log::info($e->getMessage() . "|" . $e->getFile() . "(" . $e->getLine() . ")");
        }

        return $this->success("Verifikasi Berhasil", 200);
    }

    public function postTagihanAdmin($id, Request $request)
    {
        $tagihan = Tagihan::where('id_pemesanan', $id)->get();
        $pemesanan = Pemesanan::findOrFail($id);
        $transaksi = Transaksi::findOrFail($pemesanan->id_transaksi);
        $total = 0;
        foreach ($tagihan as $tg) {
            $total = $total + $tg->nominal;
        }

        if ($total > $transaksi->total_harga) {
            return $this->fail("Tagihan Melebihi Jumlah Transaksi", 400);
        }

        $tagihan = Tagihan::create([
            "id_pemesanan" => $id,
            "deskripsi" => $request->deskripsi,
            "tipe" => "cicilan",
            "limit_at" => Carbon::parse($request->limited_at)->format("Y-m-d"),
            "nominal" => $request->nominal,
            "status" => 0
        ]);

        return $this->successWithData("Tagihan Berhasil Ditambahkan", $tagihan, 201);
    }

    public function deleteTagihan($id)
    {
        $tagihan = Tagihan::findOrFail($id);

        if ($tagihan->status > 0) {
            return $this->fail("Tagihan tidak dapat dibayar!", 400);
        }

        $tagihan->delete();

        return $this->success("Tagihan Berhasil dihapus!", 200);
    }

    public function detailTagihan($id)
    {
        $tagihan = Tagihan::with("pembayaran", "pictures")->findOrFail($id);

        return $this->successWithData("Berhasil", $tagihan, 200);
    }

    public function updateTagihan($id, Request $request)
    {
        $tagihan = Tagihan::findOrFail($id);

        $tagihan->deskripsi = $request->deskripsi;
        $tagihan->nominal = $request->nominal;
        $tagihan->limit_at = $request->limit_at;

        $tagihan->save();

        return $this->success("Data berhasil disimpan!", 200);
    }
}
