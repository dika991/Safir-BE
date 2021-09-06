<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StorePaketRequest;
use App\Interfaces\OperationalInt;
use App\Models\FilePaket;
use App\Models\FotoPaket;
use App\Models\Paket;
use App\Services\OperationalService;
use App\Traits\JsonResponse;
use App\Transformers\PaketTransformer;
use Illuminate\Http\Request;

class OperationalController extends Controller
{
    use JsonResponse;

    private $operationalService;
    private $operationalInterface;

    /**
     * Constructor
     *
     * @param OperationalInt $operationalInterface
     * @param OperationalService $operationalService
     */
    public function __construct(
        OperationalInt $operationalInterface,
        OperationalService $operationalService
    ) {
        $this->operationalInterface = $operationalInterface;
        $this->operationalService = $operationalService;
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(Request $request)
    {
        $result = $this->operationalInterface->all($request->all());
        if ($result->isEmpty()) {
            return $this->fail(trans('message.empty'), 404);
        }

        return $this->successWithData(trans('message.data'),
            (new PaketTransformer)->paginator($result));
    }

    /**
     * @param $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function show($id)
    {
        $result = $this->operationalInterface->show($id);
        if (!$result) {
            return $this->fail(trans('message.empty'), 404);
        }

        return $this->successWithData(
            trans('message.data'),
            (new PaketTransformer)->transform($result)
        );
    }

    /**
     * @param StorePaketRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(StorePaketRequest $request)
    {
        $result = $this->operationalService->store($request->all());
        if ($result->isFail()) {
            return $this->fail(trans('message.store-data') . ' ' . trans('message.failed'));
        }

        return $this->successWithData(
            trans('message.store-data') . ' ' . trans('message.success'),
            (new PaketTransformer)->transform($result->getResult())
        );
    }

    /**
     * @param StorePaketRequest $request
     * @param $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(StorePaketRequest $request, $id)
    {
        $result = $this->operationalService->setModel($id)->update($request->all());
        if ($result->isFail()) {
            return $this->fail(trans("message.update-data") . " " . trans("message.failed"));
        }

        return $this->successWithData(
            trans("message.update-data") . " " . trans('success'),
            (new PaketTransformer)->transform($result->getResult())
        );
    }

    /**
     * @param $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy($id)
    {
        $result = $this->operationalService->setModel($id)->destroy();
        if ($result->isFail()) {
            return $this->fail(trans("message.destroy-data") . " " . trans("message.failed"));
        }

        return $this->successWithData(
            trans('message.destroy-data') . ' ' . trans('message.success'),
            $result->getMessage()
        );
    }

    public function listPhoto($paket_id)
    {
        $paket = Paket::with('photo')->findOrFail($paket_id);
        return $this->successWithData('List Photo', $paket);
    }

    public function postPhoto($paket_id, Request $request)
    {
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

    public function postDeletePhoto($paket_id, $foto_id)
    {
        $file = FotoPaket::where('id_paket', $paket_id)->where('id', $foto_id)->first();
        if (!$file) {
            return $this->fail("Image not found.", 404);
        }

        $image_path = $file->path;
        $path = "/storage/" . str_replace('public/', '', $image_path);
        if (file_exists(public_path() . $path)) {
            unlink(public_path() . $path);
        }
        $file->delete();

        return $this->success("Delete Foto Success", 200);
    }

    public function getFile($paket_id)
    {
        $paket = FilePaket::where('id_paket', $paket_id)->first();
        return $this->successWithData('file paket', $paket);
    }

    public function postFile($paket_id, Request $request)
    {
        $validatedData = $request->validate([
            'file' => 'required|mimes:pdf|max:10000',
           ]);

        $paket = Paket::findOrFail($paket_id);
        $exist = FilePaket::where('id_paket', $paket_id)->get();
        $name = $request->file('file')->getClientOriginalName();

        $path = $request->file('file')->store('public/files/' . $paket->code);

        $save = new FilePaket;
        $save->id_paket = $paket_id;
        $save->name = $name;
        $save->path = $path;
        $save->url = config('app.url') . "/storage/" . str_replace('public/', '', $path);
        $save->save();

        if ($exist) {
            foreach ($exist as $e) {
                $file_path = $e->path;
                $path = "/storage/" . str_replace('public/', '', $file_path);
                if (file_exists(public_path() . $path)) {
                    unlink(public_path() . $path);
                }
                $e->delete();
            }
        }

        return $this->successWithData('Upload Success', $save);
    }
}
