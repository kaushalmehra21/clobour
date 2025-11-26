<?php

namespace App\Traits;

trait ApiResponseTrait
{
    protected function success(string $message = '', $data = []): array
    {
        return [
            'success' => true,
            'message' => $message,
            'data' => $data,
        ];
    }

    protected function error(string $message = '', $errors = [], int $status = 422): \Illuminate\Http\JsonResponse
    {
        return response()->json([
            'success' => false,
            'message' => $message,
            'data' => $errors,
        ], $status);
    }
}

