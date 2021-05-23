<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class RawParentResource extends JsonResource
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
            'name' => $this->name,
            'id' =>$this->id,
            'parent_id' => $this->parent_id,
            'ancestors' => $this->ancestors
        ];
    }
}
