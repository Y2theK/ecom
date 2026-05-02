<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\ProductResource;
use App\Models\Product;
use App\Traits\ApiResponseTrait;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    use ApiResponseTrait;

    public function index(Request $request): JsonResponse
    {
        $search = is_string($request->query('search')) ? trim($request->query('search')) : null;
        $perPage = (int) $request->query('per_page', 15);

        $products = Product::query()
            ->search($search)
            ->latest('id')
            ->paginate($perPage)
            ->withQueryString();

        $data = [
            'data' => ProductResource::collection($products),
            'meta' => $this->getpaginatedMeta($products),
        ];
        
        return $this->successResponse($data,
            'Products retrieved successfully!'
        );
    }
}
