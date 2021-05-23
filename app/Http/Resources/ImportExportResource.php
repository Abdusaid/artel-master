<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class ImportExportResource extends JsonResource
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
            'raw_firm_id' => $this->raw_firm_id,
            'date' => date("d.m.Y, H:i", strtotime($this->created_at)),
            'import_new' => $this->import_new,
            'import_pr' => $this->import_tpa,
            'export_pr' => $this->export_tpa,
            'export_gran' => $this->export_gran,
            'export_factory' => $this->export_factory,
            'export_back' => $this->export_back,
        ];
    }
}
