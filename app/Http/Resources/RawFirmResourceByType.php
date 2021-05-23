<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use App\Http\Controllers\Controller;

class RawFirmResourceByType extends JsonResource
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
            'pervichka' => RawFirmResource::collection($this->rawFirms()->where('type', Controller::TYPE_PERVICHKA)->get()),
            'vtorichka' => RawFirmResource::collection($this->rawFirms()->where('type', Controller::TYPE_VTORICHKA)->get()),
            'granula' => RawFirmResource::collection($this->rawFirms()->where('type', Controller::TYPE_GRANULA)->get())
        ];
    }
}
