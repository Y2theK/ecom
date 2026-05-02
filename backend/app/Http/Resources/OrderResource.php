<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class OrderResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'user_id' => $this->user_id,
            'total_price' => (float) $this->total_price,
            'items' => $this->whenLoaded('items', function () {
                return $this->items->map(function ($item) {
                    return [
                        'id' => $item->id,
                        'product_id' => $item->product_id,
                        'quantity' => (int) $item->quantity,
                        'price' => (float) $item->price,
                        'product' => $item->relationLoaded('product') && $item->product
                            ? [
                                'id' => $item->product->id,
                                'name' => $item->product->name,
                                'price' => (float) $item->product->price,
                            ]
                            : null,
                    ];
                })->values();
            }),
        ];
    }
}
