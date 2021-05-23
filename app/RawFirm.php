<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class RawFirm extends Model
{
    protected $table='raw_firm';

    protected $fillable =[
        'quantity','type','valid_quantity', 'raw_id', 'firm_id', 'position_id'
    ];
    public function raw(){
        return $this->belongsTo(Raw::class);
    }
    public function firm(){
        return $this->belongsTo(Firm::class);
    }
    public function position(){
        return $this->belongsTo(Position::class);
    }
    public function imports(){
        return $this->hasMany(Import::class);
    }
    public function exports(){
        return $this->hasMany(Export::class);
    }

}
