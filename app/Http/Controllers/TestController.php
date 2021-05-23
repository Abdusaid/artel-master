<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class TestController extends Controller
{


    public function index(){
        $rem = DB::table('raw_firm')
            ->join('raws', 'raws.id', 'raw_firm.raw_id')
            ->join('imports', 'raw_firm.id', 'imports.raw_firm_id')
            ->join('exports', 'raw_firm.id', 'exports.raw_firm_id')
            ->select('raws.name as name', 'SUM(CASE WHEN imports.new=1 THEN imports.quantity END)')->get();

        return $rem;
    }
}
