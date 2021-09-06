<?php

namespace App\Traits;

trait JsonResponse
{
    public function success($msg, $code = 200)
    {
        return response()->json([
            'meta' => [
                'code' => $code,
                'message' => $msg,
                'status' => true
            ]
        ], $code);
    }

    public function fail($msg, $code = 400)
    {
        return response()->json([
            'meta' => [
                'code' => $code,
                'message' => $msg,
                'status' => false
            ]
        ], $code);
    }

    public function responseWithCondition($data, $successMsg, $failMsg)
    {
        return $data ? $this->success($successMsg) : $this->fail($failMsg);
    }

    public function successWithData($msg, $data, $code = 200){
        return response()->json([
            'meta' => [
                'code' => $code,
                'message' => $msg,
                'status' => true
            ],
            'data' => $data
        ], $code);
    }

    public function failValidate ($errors, $code = 422){
        return response()->json([
            'meta' => [
                'code' => $code,
                'message' => $errors,
                'status' => false
            ]
        ], $code);
    }
}
