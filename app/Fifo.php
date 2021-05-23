<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Fifo extends Model
{
    protected $primaryKey = 'import_id';
    protected $fillable =[
        'quantity'
    ];
    public function import(){
        return $this->belongsTo(Import::class);
    }

}
