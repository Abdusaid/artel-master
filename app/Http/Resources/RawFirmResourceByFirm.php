<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use App\Http\Controllers\Controller;

class RawFirmResourceByFirm extends JsonResource
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
            'pervichka' => RawFirmRawParentResource::collection($this->where('type', Controller::TYPE_PERVICHKA)),
            'vtorichka' => RawFirmRawParentResource::collection($this->where('type', Controller::TYPE_VTORICHKA)),
            'granula' => RawFirmRawParentResource::collection($this->where('type', Controller::TYPE_GRANULA)),
            'sum_rest_start' => $this->sum('rest_start'),
            'sum_import_new' => $this->sum('import_new'),
            'sum_import_tpa' => $this->sum('import_tpa'),
            'sum_export_tpa' => $this->sum('export_tpa'),
            'sum_export_gran' => $this->sum('export_gran'),
            'sum_export_factory' => $this->sum('export_factory'),
            'sum_export_back' => $this->sum('export_back'),
            'sum_rest_all' => $this->sum('rest_all')

        ];
    }
}
