<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class RawFirmResource extends JsonResource
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
            'name' => $this->raw->name.($this->raw->color  ? " (".$this->raw->color->name.")" : ''),
            'quantity' => $this->quantity,
            'valid_quantity' => $this->valid_quantity,
            'position' => $this->position->name
        ];
    }
}
