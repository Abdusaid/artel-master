<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class RawParent extends Model
{
    protected $fillable = ['name', 'parent_id', 'ancestors'];

    public function children(){
        return $this->hasMany(RawParent::class,'parent_id','id');
    }
    public function parent()
    {
       return $this->belongsTo(RawParent::class);
    }
    public function raws(){
        return $this->hasMany(Raw::class,'parent_raw_id','id');
    }
    public function parentRecursive()
    {
        return $this->parent()->with('parentRecursive');
    }

    public function rawFirms(){
        return $this->belongsToMany(RawFirm::class, 'raw_firm_raw_parent');
    }

    public function firms(){
        return $this->belongsToMany(Firm::class, 'firm_raw_parent');
    }

    public function leaves(){
        return Raw::whereHas('parent', function($query){
            $query->where('ancestors','LIKE',$this->ancestors != null ? $this->ancestors : $this->id."%");
        });
    }
}
