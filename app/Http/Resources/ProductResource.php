<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class ProductResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'description' => $this->description,
            'image' => $this->image,
            'sizes' => [
                [
                    'name' => 'Camera trong nhà',
                    'extra' => 150000
                ],
                [
                    'name' => 'Camera ngoài trời',
                    'extra' => 170000
                ]
            ],
            'toppings' => ['yes', 'no'],
            'price' => $this->price
        ];
    }
}
