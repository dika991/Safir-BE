<?php

namespace App\Http\Controllers\Api\User;

use App\Http\Controllers\Controller;
use App\Interfaces\OperationalInt;
use App\Services\OperationalService;
use App\Traits\JsonResponse;
use App\Transformers\PaketTransformer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class DashboardController extends Controller
{
    use JsonResponse;
    private $operationalService;
    private $operationalInterface;

    public function __construct(OperationalInt $operationalInt, OperationalService $operationalService)
    {
        $this->operationalInterface = $operationalInt;
        $this->operationalService = $operationalService;
    }
    public function listPackage(Request $request)
    {
        $result = $this->operationalInterface->listAvailable($request->all());
        if ($result->isEmpty()) {
            return $this->fail(trans('message.empty'), 404);
        }

        return $this->successWithData(trans('message.data'), $result);
    }

    public function detailPackage($code_package)
    {
        $result = $this->operationalInterface->show($code_package);
        if (!$result) {
            return $this->fail(trans('message.empty'));
        }

        return $this->successWithData(trans('message.success'), (new PaketTransformer)->transform($result));
    }

    public function recentPackage(){
        Log::info("Masuk Terus");
        $result = $this->operationalInterface->recent();
        if(!$result){
            return $this->fail(trans('message.empty'));
        }

        return $this->successWithData(trans('message.success'), $result);
    }

    public function detailPackageCode($code_package){
        $result = $this->operationalInterface->detailCode($code_package);

        if(!$result){
            return $this->fail(trans('message.empty'));
        }

        return $this->successWithData(trans('message.success'), (new PaketTransformer)->transform($result));
    }
}
