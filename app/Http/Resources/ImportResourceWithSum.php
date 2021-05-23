<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\ResourceCollection;
use App\Http\Controllers\Controller;
use App\Http\Resources\ImportResource;

class ImportResourceWithSum extends ResourceCollection
{
    /**
     * @var
     */
    private $query;

    /**
     * Create a new resource instance.
     *
     * @param  mixed  $resource
     * @return void
     */
    public function __construct($resource, $query = null)
    {
        // Ensure you call the parent constructor
        parent::__construct($resource);
        $this->resource = $resource;
        $this->query = $query;
    }
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {

        return [
            'data' => ImportResource::collection($this->resource),
            'sum'=> $this->query != null ? [
                'all_new' => (clone $this->query)->where('new', true)->get()->sum('quantity'),
                'all' => (clone $this->query)->where('new', false)->get()->sum('quantity'),
                'pervichka_new' => (clone $this->query)->whereHas('rawFirm', function($query){
                    $query->where('type',Controller::TYPE_PERVICHKA);
                })->where('new', true)->get()->sum('quantity'),
                'pervichka'=> (clone $this->query)->whereHas('rawFirm', function($query){
                    $query->where('type',Controller::TYPE_PERVICHKA);
                })->where('new', false)->get()->sum('quantity'),
                'vtorichka_new'=>(clone $this->query)->whereHas('rawFirm', function($query){
                    $query->where('type',Controller::TYPE_VTORICHKA);
                })->where('new', true)->get()->sum('quantity'),
                'vtorichka'=>(clone $this->query)->whereHas('rawFirm', function($query){
                    $query->where('type',Controller::TYPE_VTORICHKA);
                })->where('new', false)->get()->sum('quantity'),
                'granula_new'=>(clone $this->query)->whereHas('rawFirm', function($query){
                    $query->where('type',Controller::TYPE_GRANULA);
                })->where('new', true)->get()->sum('quantity'),
                'granula'=>(clone $this->query)->whereHas('rawFirm', function($query){
                    $query->where('type',Controller::TYPE_GRANULA);
                })->where('new', false)->get()->sum('quantity'),
            ] : '',
        ];
    }
}
