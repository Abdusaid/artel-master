<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ExportFifo extends Model
{

    public function export(){
        return $this->belongsTo(Export::class);
    }
}
