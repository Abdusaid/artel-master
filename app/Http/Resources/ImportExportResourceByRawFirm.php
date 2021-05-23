<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class ImportExportResourceByRawFirm extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    private $name;
    private $data;

    public function __construct($name, $data)
    {
        $this->name = $name;
        $this->data = $data;
    }


    public function toArray($request)
    {
        return [
            'name' => $this->name,
            'dates' => ImportExportResource::collection($this->data)
        ];
    }
}
