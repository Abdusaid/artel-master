<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class ImportResource extends JsonResource
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
            'raw_id' => $this->raw->id,
            'raw_name' => $this->raw->name.($this->raw->color_id  ? " (".$this->raw->color->name.")" : ''),
            'firm_id' => $this->firm->id,
            'firm_name' => $this->firm->name,
            'quantity' => $this->quantity,
            'type' => $this->rawFirm->type,
            'firm'=>$this->rawFirm->firm->name,
            'isNew' => $this->new,
            'seria' => $this->serial_number,
            'status' => $this->status,
            'supplier' => $this->supplier,
            'container' => $this->container,
            'comment' => $this->comment,
            'date' => date("d.m.y, H:i", strtotime($this->created_at))
        ];
    }
}
