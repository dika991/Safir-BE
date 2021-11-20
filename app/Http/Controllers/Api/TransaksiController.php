<?php

namespace App\Http\Controllers\Api;

use App\Models\Transaksi as ModelsTransaksi;
use App\Traits\JsonResponse;
use App\Transaksi;
use App\Transformers\TransaksiTransformer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class TransaksiController extends Controller
{
    use JsonResponse;
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $result = ModelsTransaksi::with('pemesan')->paginate(10);
        return $this->successWithData(
            trans('message.data'),
            (new TransaksiTransformer)->paginator($result)
        );
    }
}
