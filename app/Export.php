<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Export extends Model
{
    protected $fillable =[
        'raw_firm_id','quantity','to','status', 'comment'
    ];

    public function rawFirm(){
        return $this->belongsTo(RawFirm::class);
    }
    public function raw(){
        return $this->rawFirm->raw();
    }

    public function fifos(){
        return $this->hasMany(ExportFifo::class);
    }

    public function firm(){
        return $this->rawFirm->firm();
    }

}
