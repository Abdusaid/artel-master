<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use Carbon\Carbon;

class RawFirmRawParentResource extends JsonResource
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
            'id'=>$this->id,
            'type'=> $this->type,
            'parent_id'=>$this->parent_id,
            'raw_name' => $request->filtered ? $this->parent_name : $this->raw_name.($this->color_name  ? " (".$this->color_name.")" : ''),
            'firm'=>$this->firm_name,
            //приход новый
            'import_new'=>$this->import_new,
            //приход с ТПА
            'import_tpa' => $this->import_tpa,
            //расход на ТПА (-1)
            'export_tpa' => $this->export_tpa,
            //Расход на Гранулятор (-2)
            'export_gran' => $this->export_gran,
            'export_factory' => $this->export_factory,
            'export_back' => $this->export_back,
            //Остаток на начало
            'balance_before' => $this->imports_before - $this->exports_before,
            //Остаток
            'balance'=> $this->imports_before + $this->import_new + $this->import_tpa
                - $this->exports_before - $this->export_tpa - $this->export_gran - $this->export_factory - $this->export_back,
        ];
    }
}
