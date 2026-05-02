<?php

namespace App\Traits;

use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Http\JsonResponse;

trait ApiResponseTrait
{
    public function successResponse(mixed $data, string $message = 'Success', int $code = 200): JsonResponse
    {
        $response = [
            'code' => $code,
            'success' => true,
            'message' => $message,
            'time' => now()->toISOString(),
            'data' => $data,
        ];

        return response()->json($response, $code);
    }

    public function errorResponse(string $message, int $code = 400): JsonResponse
    {
        $response = [
            'code' => $code,
            'success' => false,
            'message' => $message,
            'time' => now()->toISOString(),
        ];

        return response()->json($response, $code);
    }

    public function getpaginatedMeta(LengthAwarePaginator $paginator): array
    {
        return [
            'current_page' => $paginator->currentPage(),
            'last_page' => $paginator->lastPage(),
            'per_page' => $paginator->perPage(),
            'total' => $paginator->total(),
        ];
    }
}