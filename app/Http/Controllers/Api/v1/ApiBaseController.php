<?php

namespace App\Http\Controllers\Api\v1;

use Illuminate\Http\JsonResponse;
use Throwable;

abstract class ApiBaseController
{
    protected function handleThrowable(Throwable $e): JsonResponse
    {
        $data = [
            'status'  => 'error',
            'message' => $e->getMessage(),
        ];

        if (config('app.debug')) {
            $data['error'] = class_basename($e);
            $data['trace'] = [
                'file' => $e->getFile(),
                'line' => $e->getLine(),
            ];
        }

        return response()->json($data, 500, [], JSON_UNESCAPED_UNICODE);
    }
}
