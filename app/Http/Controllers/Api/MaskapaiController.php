<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreMaskapaiRequest;
use App\Interfaces\MaskapaiInt;
use App\Models\Maskapai;
use App\Services\MaskapaiService;
use App\Traits\JsonResponse;
use App\Transformers\MaskapaiTransformer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class MaskapaiController extends Controller
{
    use JsonResponse;

    private $maskapaiService;
    private $maskapaiInterface;

    public function __construct(
        MaskapaiInt $maskapaiInt,
        MaskapaiService $maskapaiService
    )
    {
        $this->maskapaiService = $maskapaiService;
        $this->maskapaiInterface =$maskapaiInt;
    }

    public function list(Request $request){
        $result = $this->maskapaiInterface->allMaskapai($request->all());
        if($result->isEmpty()){
            return $this->fail(trans('message.empty'), 404);
        }
        return $this->successWithData(trans('message.data'), $result);
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(Request $request)
    {
        $result = $this->maskapaiInterface->all($request->all());
        if($result->isEmpty()){
            return $this->fail(trans('message.empty'), 404);
        }

        return $this->successWithData(trans('message.data'),
            (new MaskapaiTransformer)->paginator($result)
        );
    }

    /**
     * @param StoreMaskapaiRequest $request
     */
    public function store(StoreMaskapaiRequest $request)
    {
        $result = $this->maskapaiService->store($request->all());
        if($result->isFail()){
            return $this->fail(trans('message.store-data').' '. trans('message.failed'));
        }

        return $this->successWithData(
            trans('message.store-data'). ' '. trans('message.success'),
            (new MaskapaiTransformer)->transform($result->getResult())
        );
    }

    /**
     * @param $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function show($id)
    {
        $result = $this->maskapaiInterface->show($id);
        if(!$result){
            return $this->fail(trans('message.empty'), 404);
        }

        return $this->successWithData(
            trans('message.data'),
            (new MaskapaiTransformer)->transform($result)
        );
    }

    /**
     * @param StoreMaskapaiRequest $request
     * @param Maskapai $maskapai
     */
    public function update(StoreMaskapaiRequest $request, $maskapai)
    {
        $result = $this->maskapaiService->setModel($maskapai)->update($request->all());
        if($result->isFail()){
            return $this->fail(trans("message.update-data"). " " . trans("message.failed"));
        }

        return $this->successWithData(
            trans("message.update-data"). " " . trans('success'),
            (new MaskapaiTransformer)->transform($result->getResult())
        );
    }

    /**
     * @param Maskapai $maskapai
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy($id)
    {
        $result = $this->maskapaiService->setModel($id)->destroy();
        if($result->isFail()){
            return $this->fail(trans("message.destroy-data") . " " . trans("message.failed"));
        }

        return $this->successWithData(
            trans('message.destroy-data'. ' '. trans('message.success')),
            $result->getMessage()
        );

    }
}
