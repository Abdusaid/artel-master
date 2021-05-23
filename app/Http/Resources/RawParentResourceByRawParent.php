<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use App\Http\Controllers\RawFilterController;
class RawParentResourceByRawParent extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return $this->resource->map(function ($item) use ($request) {
            $request->merge(['firm_id' => $item->firm_id,'parent_id'=>$item->parent_id, 'type'=>$item->type]);
            $rawF = RawFilterController::getRawParentsByFirm($request);
            return [
                'raw_name' => $item->name.($item->color_name ? ' ('.$item->color_name.')' : ''),
                'id' =>$item->id,
                // 'parent_id' => $item->parent_id,
                'hasChild' => $item->children,
                'color'=> $item->color_name,
                //приход новый
                'import_new'=>$item->import_new,
                //приход с ТПА
                'import_tpa' => $item->import_tpa,
                //расход на ТПА (-1)
                'export_tpa' => $item->export_tpa,
                //Расход на Гранулятор (-2)
                'export_gran' => $item->export_gran,
                'export_factory' => $item->export_factory,
                'export_back' => $item->export_back,

                //Остаток на начало
                'balance_before' => $item->imports_before - $item->exports_before,
                //Остаток
                'balance'=> $item->imports_before + $item->import_new + $item->import_tpa
                    - $item->exports_before - $item->export_tpa - $item->export_gran - $item->export_factory - $item->export_back,
                'children'=> $item->children != 0 ? new RawParentResourceByRawParent($rawF) : [],
            ];
        });
    }
}
