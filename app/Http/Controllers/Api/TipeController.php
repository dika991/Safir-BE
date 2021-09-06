<?php

namespace App\Http\Controllers\Api;

use App\Http\Requests\StoreTipePaketRequest;
use App\Interfaces\TipeInt;
use App\Services\TipeService;
use App\Traits\JsonResponse;
use App\Transformers\PaketTransformer;
use App\Transformers\TipeTransformer;
use Illuminate\Http\Request;

class TipeController extends Controller
{

    use JsonResponse;

    /**
     * @var TipeService
     */
    private $tipeService;
    private $tipeInterface;

    /**
     * TipeController constructor.
     * @param TipeInt $tipeInt
     * @param TipeService $tipeService
     */
    public function __construct(
        TipeInt $tipeInt,
        TipeService $tipeService
    )
    {
        $this->tipeService = $tipeService;
        $this->tipeInterface = $tipeInt;
    }

    /**
     * @param $paket_id
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function index($paket_id, Request $request)
    {
        $result = $this->tipeInterface->all($request->all(), $paket_id);
        if($result->isEmpty()){
            return $this->fail(trans('message.empty'), 400);
        }

        return $this->successWithData(
            trans('message.data'),
            (new TipeTransformer)->allData($result)
        );
    }

    /**
     * @param StoreTipePaketRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(StoreTipePaketRequest $request)
    {
        $result = $this->tipeService->store($request->all());
        if($result->isFail())
        {
            return $this->failValidate($result->getMessage(),422);
        }

        return $this->successWithData(
            trans('message.store-data'). ' '. trans('message.success'),
            (new TipeTransformer)->transform($result->getResult())
        );
    }

    /**
     * @param $paket_id
     * @param $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function show($paket_id, $id)
    {
        $result = $this->tipeInterface->show($id);
        if(!$result)
        {
            return $this->fail(trans('message.empty'), 400);
        }

        return $this->successWithData(
            trans('message.data'),
            (new TipeTransformer)->transform($result)
        );
    }

    /**
     * @param StoreTipePaketRequest $request
     * @param $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(StoreTipePaketRequest $request, $paket_id, $id)
    {
        $result = $this->tipeService->setModel($id)->update($request->all());
        if($result->isFail()){
            return $this->fail(trans('message.update-date'). " " . trans('message.failed'), 400);
        }

        return $this->successWithData(
            trans('message.update-data') . " " . trans('message.success'),
            (new PaketTransformer)->transform($result->getResult())
        );
    }

    /**
     * @param $paket_id
     * @param $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy($paket_id, $id)
    {
        $result = $this->tipeService->setModel($id)->destroy();
        if($result->isFail()){
            return $this->fail(trans('message.delete-data'). " " . trans('message.failed'));
        }

        return $this->success($result->getMessage());
    }
}
