<?php

namespace App\Http\Resources;

use App\Http\Controllers\Controller;
use Illuminate\Http\Resources\Json\JsonResource;

class RawTotalResource extends JsonResource
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
            'color_id' => $this->color_id,
            'color_name' => $this->color_id ? $this->color->name : '',
            'parent_id' => $this->parent_raw_id,
            'parent_name' => $this->parent_raw_id ? $this->parent->name : '',
            'firms' => $this->firms()->distinct()->pluck('firms.id'),
            'firm_names' => $this->firms()->distinct()->pluck('name'),
            'is_pervichka' => $this->rawFirms()->where('type', Controller::TYPE_PERVICHKA)->count() > 0 ? true : false,
            'is_vtorichka' => $this->rawFirms()->where('type', Controller::TYPE_VTORICHKA)->count() > 0 ? true : false,
            'is_granula' => $this->rawFirms()->where('type', Controller::TYPE_GRANULA)->count() > 0 ? true : false,
        ];
    }
}
