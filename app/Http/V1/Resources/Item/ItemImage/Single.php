<?php

namespace App\Http\V1\Resources\Item\ItemImage;

use Illuminate\Http\Resources\Json\JsonResource;

class Single extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     */
    public function toArray($request): array
    {
        return [
            'id' => $this->id,
            'uploadId' => $this->upload->id,
            'url' => $this->url,
            'thumbnail' => $this->small_url,
        ];
    }
}
