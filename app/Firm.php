<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Firm extends Model
{
    protected $hidden = ['pivot'];

    public function raws(){
        return $this->belongsToMany(Raw::class,'raw_firm');
    }
    public function rawFirms(){
        return $this->hasMany(RawFirm::class);
    }

    public function rawParents(){
        return $this->belongsToMany(RawParent::class, 'firm_raw_parent');
    }
}
