<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreHotelRequest;
use App\Interfaces\HotelInt;
use App\Services\HotelService;
use App\Traits\JsonResponse;
use App\Transformers\HotelTransformer;
use Illuminate\Http\Request;

class HotelController extends Controller
{
    use JsonResponse;
    private $hotelService;
    private $hotelInterface;

    public function __construct(
        HotelInt $hotelInt,
        HotelService $hotelService
    )
    {
        $this->hotelInterface = $hotelInt;
        $this->hotelService = $hotelService;
    }

    /**
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(Request $request)
    {
        $result = $this->hotelInterface->all($request->all());
        if($result->isEmpty()){
            return $this->fail(trans('message.empty'),400);
        }

        return $this->successWithData(
            trans('message.data') . ' ' . trans('message.success'),
            (new HotelTransformer)->paginator($result)
        );
    }

    public function listHotel(Request $request){
        $result = $this->hotelInterface->list($request->all());
        if($result->isEmpty()){
            return $this->fail(trans('message.empty'),400);
        }

        return $this->successWithData(
            trans('message.data') . ' ' . trans('message.success'),
            $result
        );
    }

    /**
     * @param $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function show($id){
        $result = $this->hotelInterface->show($id);
        if($result == null){
            return $this->fail(trans('message.empty'));
        }

        return $this->successWithData(
            trans('message.data'). ' '. trans('message.success'),
            (new HotelTransformer)->transform($result)
        );
    }

    /**
     * @param StoreHotelRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(StoreHotelRequest $request)
    {
        $result = $this->hotelService->store($request->all());
        if ($result->isFail())
        {
            return $this->fail(trans('message.store-data'). ' '.trans('message.failed'));
        }

        return $this->successWithData(
            trans('message.store-data'). ' '.trans('message.success'),
            (new HotelTransformer)->transform($result->getResult())
        );
    }

    /**
     * @param StoreHotelRequest $request
     * @param $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(StoreHotelRequest $request, $id)
    {
        $result = $this->hotelService->setModel($id)->update($request->all());

        if($result->isFail())
        {
            return $this->fail(trans('message.update-data'). ' '. trans('message.failed'));
        }

        return $this->successWithData(
            trans('message.update-data'). ' '. trans('message.success'),
            (new HotelTransformer)->transform($result->getResult())
        );
    }

    /**
     * @param $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy($id)
    {
        $result = $this->hotelService->setModel($id)->destroy();

        if($result->isFail())
        {
            return $this->fail(trans('message.destroy-data'). ' '. trans('message.failed'));
        }

        return $this->successWithData(
            trans('message.destroy-data'). ' '. trans('message.success'),
            $result->getResult()
        );
    }
}
