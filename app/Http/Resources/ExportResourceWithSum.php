<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\ResourceCollection;
use App\Http\Controllers\Controller;
use App\Http\Resources\ImportResource;
class ExportResourceWithSum extends ResourceCollection
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
            'data' => ExportResource::collection($this->resource),
            'sum'=> $this->query != null ? [
                'all_pr' => (clone $this->query)->where('to', Controller::TO_PRODUCTION)->get()->sum('quantity'),
                'all_gran' => (clone $this->query)->where('to', Controller::TO_GRANULATOR)->get()->sum('quantity'),
                'all_factory' => (clone $this->query)->where('to', Controller::TO_FACTORY)->get()->sum('quantity'),
                'all_back' => (clone $this->query)->where('to', Controller::TO_BACK)->get()->sum('quantity'),

                'pervichka_pr' => (clone $this->query)->whereHas('rawFirm', function($query){
                    $query->where('type',Controller::TYPE_PERVICHKA);
                })->where('to', Controller::TO_PRODUCTION)->get()->sum('quantity'),
                'pervichka_gran'=> (clone $this->query)->whereHas('rawFirm', function($query){
                    $query->where('type',Controller::TYPE_PERVICHKA);
                })->where('to', Controller::TO_GRANULATOR)->get()->sum('quantity'),
                'pervichka_factory'=> (clone $this->query)->whereHas('rawFirm', function($query){
                    $query->where('type',Controller::TYPE_PERVICHKA);
                })->where('to', Controller::TO_FACTORY)->get()->sum('quantity'),
                'pervichka_back'=> (clone $this->query)->whereHas('rawFirm', function($query){
                    $query->where('type',Controller::TYPE_PERVICHKA);
                })->where('to', Controller::TO_BACK)->get()->sum('quantity'),

                'vtorichka_pr'=>(clone $this->query)->whereHas('rawFirm', function($query){
                    $query->where('type',Controller::TYPE_VTORICHKA);
                })->where('to', Controller::TO_PRODUCTION)->get()->sum('quantity'),
                'vtorichka_gran'=>(clone $this->query)->whereHas('rawFirm', function($query){
                    $query->where('type',Controller::TYPE_VTORICHKA);
                })->where('to', Controller::TO_GRANULATOR)->get()->sum('quantity'),
                'vtorichka_factory'=>(clone $this->query)->whereHas('rawFirm', function($query){
                    $query->where('type',Controller::TYPE_VTORICHKA);
                })->where('to', Controller::TO_FACTORY)->get()->sum('quantity'),
                'vtorichka_back'=>(clone $this->query)->whereHas('rawFirm', function($query){
                    $query->where('type',Controller::TYPE_VTORICHKA);
                })->where('to', Controller::TO_BACK)->get()->sum('quantity'),

                'granula_pr'=>(clone $this->query)->whereHas('rawFirm', function($query){
                    $query->where('type',Controller::TYPE_GRANULA);
                })->where('to', Controller::TO_PRODUCTION)->get()->sum('quantity'),
                'granula_gran'=>(clone $this->query)->whereHas('rawFirm', function($query){
                    $query->where('type',Controller::TYPE_GRANULA);
                })->where('to', Controller::TO_GRANULATOR)->get()->sum('quantity'),
                'granula_factory'=>(clone $this->query)->whereHas('rawFirm', function($query){
                    $query->where('type',Controller::TYPE_GRANULA);
                })->where('to', Controller::TO_FACTORY)->get()->sum('quantity'),
                'granula_back'=>(clone $this->query)->whereHas('rawFirm', function($query){
                    $query->where('type',Controller::TYPE_GRANULA);
                })->where('to', Controller::TO_BACK)->get()->sum('quantity'),
            ] : '',
        ];
    }
}
