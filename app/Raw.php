<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Raw extends Model
{
    protected $hidden = ['pivot'];

    public function parent(){
        return $this->belongsTo(RawParent::class,'parent_raw_id');
    }
    public function firms(){
        return $this->belongsToMany(Firm::class,'raw_firm');
    }
    // public function positions(){
    //     return $this->belongsToMany(Position::class,'raw_firm');
    // }
    public function imports(){
        return $this->hasManyThrough(Import::class, RawFirm::class);
    }
    public function exports(){
        return $this->hasManyThrough(Export::class, RawFirm::class);
    }
    public function rawFirms(){
        return $this->hasMany(RawFirm::class);
    }

    public function color(){
        return $this->belongsTo(Color::class, 'color_id');
    }

    // public function parentRecursive()
    // {
    //     return $this->parent()->with('parentRecursive');
    // }
}
