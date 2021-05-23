<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\ResourceCollection;
use App\Http\Controllers\Controller;
use App\Firm;

class RawParentResourceByFirm extends ResourceCollection
{
    private $rawFirms, $parent_name;
    public function __construct($firm, $rawFirms, $parent_name = '')
    {
        $this->collection = $firm;
        $this->rawFirms = $rawFirms;
        $this->parent_name = $parent_name;
    }
    public function toArray($request)
    {
        if($request->has('firm_id'))
            return [
                'firm_name'=>$this->collection->first()->name,
                'firm_id'=> $this->collection->first()->id,
                'parent_name'=>$this->parent_name,
                'pervichka'=> new RawParentResourceByRawParent($this->rawFirms->where('type', Controller::TYPE_PERVICHKA)->where('firm_id', $this->collection->first()->id)),
                'vtorichka' => new RawParentResourceByRawParent($this->rawFirms->where('type', Controller::TYPE_VTORICHKA)->where('firm_id', $this->collection->first()->id)),
                'granula' => new RawParentResourceByRawParent($this->rawFirms->where('type', Controller::TYPE_GRANULA)->where('firm_id', $this->collection->first()->id))
            ];

        return $this->collection->map(function (Firm $firm) {
            return
                [
                    'firm_name'=>$firm->name,
                    'firm_id'=> $firm->id,
                    'parent_name'=>$this->parent_name,
                    'pervichka'=> new RawParentResourceByRawParent($this->rawFirms->where('type', Controller::TYPE_PERVICHKA)->where('firm_id', $firm->id)),
                    'vtorichka' => new RawParentResourceByRawParent($this->rawFirms->where('type', Controller::TYPE_VTORICHKA)->where('firm_id', $firm->id)),
                    'granula' => new RawParentResourceByRawParent($this->rawFirms->where('type', Controller::TYPE_GRANULA)->where('firm_id', $firm->id))
                ];
        });

    }
}
