<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\URL;

class OrderListResource extends JsonResource
{
    /**
     * Transform the resource collection into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
          return [
        'id' => $this->id,
        'status' => $this->status,
        'payment_status' => $this->payment?->status,
        'total_price' => $this->total_price,
        'number_of_items' => $this->items_count,
        'customer' => $this->user ? [
            'id' => $this->user->id,
            'first_name' => optional($this->user->customer)->first_name,
            'last_name' => optional($this->user->customer)->last_name,
        ] : null,
        'created_at' => (new \DateTime($this->created_at))->format('Y-m-d H:i:s'),
        'updated_at' => (new \DateTime($this->updated_at))->format('Y-m-d H:i:s'),
    ];
    }
}