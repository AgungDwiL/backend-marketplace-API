<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class TransactionResource extends JsonResource
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
            'transaction_time' => $this->transaction_time,
            'buyer' => new UserResource($this->whenLoaded('buyer')),
            'vendor' => new UserResource($this->whenLoaded('vendor')),
            'checkout' => new CheckoutResource($this->whenLoaded('checkout')),
        ];
    }
}
