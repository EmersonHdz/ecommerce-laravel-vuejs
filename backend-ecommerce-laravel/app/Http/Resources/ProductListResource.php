<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\URL;

class ProductListResource extends JsonResource
{

    public static $wrap = false;
    /**
     * Transform the resource into an array.
     * use to admin dashboard
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'title' => $this->title,
            'image_url' => $this->image ?: null,
            'price' => $this->price,
            'quantity' => $this->quantity,
            'published' => (bool) $this->published,
            'updated_at' => (new \DateTime($this->updated_at))->format('Y-m-d H:i:s'),
        ];
    }
}
