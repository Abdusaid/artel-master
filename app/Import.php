<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Import extends Model
{
    protected $fillable =[
        'raw_firm_id','quantity','new','serial_number','supplier','status', 'container', 'comment'
    ];
    public function rawFirm(){
        return $this->belongsTo(RawFirm::class);
    }

    public function raw(){
        return $this->rawFirm->raw();
    }

    public function fifo(){
        return $this->hasOne(Fifo::class);
    }

    public function firm(){
        return $this->rawFirm->firm();
    }

}
