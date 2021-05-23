<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Position extends Model
{
    /**
     * The accessors to append to the model's array form.
     *
     * @var array
     */
    protected $appends = ['name'];
    /**
     * The attributes that should be visible in arrays.
     *
     * @var array
     */
    protected $visible = ['name'];

    public function raws(){
        return $this->belongsToMany(Raw::class,'raw_firm');
    }

    public function rawFirms(){
        return $this->hasMany(RawFirm::class);
    }

    public function getNameAttribute(){
        return "{$this->section}/{$this->column}/{$this->raw}";
    }
}
