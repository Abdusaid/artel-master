<?php

namespace App\Http\Controllers;
use App\Color;
use App\Http\Controllers\Controller;

use App\Http\Resources\ColorResource;
use App\Http\Resources\RawParentResource;
use App\Http\Resources\RawTotalResource;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Response;
use DB;
use Validator;
use App\Firm;
use App\Raw;
use App\RawParent;
use App\RawFirm;
use App\Position;
use Carbon\Carbon;
use App\Http\Resources\RawFirmResource;
use App\Http\Resources\RawResource;
use App\Http\Resources\RawFirmResourceByType;
use App\Http\Resources\FirmResource;

use App\Http\Resources\RawFirmResourceByFirm;

use App\Http\Resources\RawParentResourceByRawParent;
use App\Http\Resources\RawParentResourceByFirm;


class RawFilterController extends Controller
{
    public function getFirms(Request $request){
        $firms = Firm::all();

        return FirmResource::collection($firms);
    }

    public function getRaws(){
        $raws = Raw::orderBy('name')->get();
        return RawTotalResource::collection($raws);
    }

    public function getFirmRaws(Request $request, $id){

        $raws = Raw::whereHas('rawFirms', function($query) use ($id, $request){
            $query->where('firm_id', $id);
            if($request->has('type'))
                $query->where('type', $request->type);
        })->orderBy('name')->get();
        return RawResource::collection($raws);
    }

    public function getFirmBalance(Request $request, $id){
        $firm = Firm::find($id);

        return new RawFirmResourceByType($firm);
    }

    public function getRawParents(Request $request){
        $raw_parents = DB::table('raw_parents')->orderByRaw(DB::raw('CAST(ancestors AS signed)'))->get();
        return RawParentResource::collection($raw_parents);
    }

    public function getColors(){
        return ColorResource::collection(Color::all());
    }

    public function remainder(Request $request){
        $import_date = '';
        $export_date = '';
        $start_date = '';
        if($request->has('start_date')){
            if($request->start_date != null){
                $start_date = new Carbon($request->start_date);
                $start_date = $start_date->format('Y-m-d');
                $import_date = " and date(imports.created_at) >= '".$start_date."'";
                $export_date = " and date(exports.created_at) >= '".$start_date."'";
            }
        }
         if($request->has('end_date')){
            if($request->end_date != null){
                $end_date = new Carbon($request->end_date);
                $end_date = $end_date->format('Y-m-d');
                $import_date = $import_date." and date(imports.created_at) <= '".$end_date."'";
                $export_date = $export_date." and date(exports.created_at) <= '".$end_date."'";
            }
        }
        $rawFirms = RawFirm::join('raws','raws.id','raw_firm.raw_id')
        ->join('firms','firms.id','raw_firm.firm_id')
        ->join('raw_parents as raw_parent','raw_parent.id','raws.parent_raw_id')
        ->leftJoin('colors', 'raws.color_id', 'colors.id');

       $rawFirms = $rawFirms->select(['raw_firm.id','raw_firm.type','firms.name as firm_name',
            DB::raw('IFNULL((SELECT SUM(quantity) FROM imports WHERE raw_firm.id=imports.raw_firm_id and new=1'.$import_date.'), 0) as import_new'),
            DB::raw('IFNULL((SELECT SUM(quantity) FROM imports WHERE raw_firm.id=imports.raw_firm_id and new=0'.$import_date.'), 0) as import_tpa'),
            DB::raw('IFNULL((SELECT SUM(quantity) FROM exports WHERE raw_firm.id=exports.raw_firm_id and `to`=-1'.$export_date.'), 0) as export_tpa'),
            DB::raw('IFNULL((SELECT SUM(quantity) FROM exports WHERE raw_firm.id=exports.raw_firm_id and `to`=-2'.$export_date.'), 0) as export_gran'),
            DB::raw('IFNULL((SELECT SUM(quantity) FROM exports WHERE raw_firm.id=exports.raw_firm_id and `to`=-3'.$export_date.'), 0) as export_factory'),
            DB::raw('IFNULL((SELECT SUM(quantity) FROM exports WHERE raw_firm.id=exports.raw_firm_id and `to`=-4'.$export_date.'), 0) as export_back'),
            DB::raw('IFNULL((SELECT SUM(quantity) FROM imports WHERE raw_firm.id=imports.raw_firm_id and date(created_at) < "'.$start_date.'"), 0) as imports_before'),
            DB::raw('IFNULL((SELECT SUM(quantity) FROM exports WHERE raw_firm.id=exports.raw_firm_id and date(created_at) < "'.$start_date.'"), 0) as exports_before'),

        ]);
        if($request->has('filtered')){
            if($request->filtered == 1){
                $rawFirms = $rawFirms
                ->join('firm_raw_parent','firm_raw_parent.firm_id','=','raw_firm.firm_id')
                ->join('raw_parents','raw_parents.id', '=','firm_raw_parent.raw_parent_id')
                ->whereColumn('raw_parent.ancestors','LIKE',DB::raw("CONCAT(raw_parents.ancestors, '%')"));
                $rawFirms = $rawFirms->select(['raw_firm.id','raw_firm.type','firms.name as firm_name',
                DB::raw('SUM(IFNULL((SELECT SUM(quantity) FROM imports WHERE raw_firm.id=imports.raw_firm_id and new=1 and raw_parent.ancestors like CONCAT(raw_parents.ancestors, \'%\')'.$import_date.'), 0)) as import_new'),
                DB::raw('SUM(IFNULL((SELECT SUM(quantity) FROM imports WHERE raw_firm.id=imports.raw_firm_id and new=0 and raw_parent.ancestors like CONCAT(raw_parents.ancestors, \'%\')'.$import_date.'), 0)) as import_tpa'),
                DB::raw('SUM(IFNULL((SELECT SUM(quantity) FROM exports WHERE raw_firm.id=exports.raw_firm_id and `to`=-1 and raw_parent.ancestors like CONCAT(raw_parents.ancestors, \'%\')'.$export_date.'), 0)) as export_tpa'),
                DB::raw('SUM(IFNULL((SELECT SUM(quantity) FROM exports WHERE raw_firm.id=exports.raw_firm_id and `to`=-2 and raw_parent.ancestors like CONCAT(raw_parents.ancestors, \'%\')'.$export_date.'), 0)) as export_gran'),
                DB::raw('SUM(IFNULL((SELECT SUM(quantity) FROM exports WHERE raw_firm.id=exports.raw_firm_id and `to`=-3 and raw_parent.ancestors like CONCAT(raw_parents.ancestors, \'%\')'.$export_date.'), 0)) as export_factory'),
                DB::raw('SUM(IFNULL((SELECT SUM(quantity) FROM exports WHERE raw_firm.id=exports.raw_firm_id and `to`=-4 and raw_parent.ancestors like CONCAT(raw_parents.ancestors, \'%\')'.$export_date.'), 0)) as export_back'),
                DB::raw('SUM(IFNULL((SELECT SUM(quantity) FROM imports WHERE raw_firm.id=imports.raw_firm_id and raw_parent.ancestors like CONCAT(raw_parents.ancestors, \'%\') and  date(created_at) < "'.$start_date.'"), 0)) as imports_before'),
                DB::raw('SUM(IFNULL((SELECT SUM(quantity) FROM exports WHERE raw_firm.id=exports.raw_firm_id and raw_parent.ancestors like CONCAT(raw_parents.ancestors, \'%\') and date(created_at) < "'.$start_date.'"), 0)) as exports_before'),
            ]);
            }
        }


        if($request->has('firm_id')){
            if($request->firm_id != null){
                $rawFirms = $rawFirms->where('firms.id', $request->firm_id);
            }
        }

        return ($rawFirms);
    }
    public function getRawsByFirm(Request $request){
        $rawFirms = $this->remainder($request);

        if($request->filtered == 1){
            // $rawFirms = $rawFirms->where('raw_par.id','=','raws.parent_raw_id');
            $rawFirms= $rawFirms->addSelect('raw_parents.name as parent_name', 'raw_parents.id as parent_id');
            $rawFirms = $rawFirms->groupBy('raw_firm.type', 'firms.id','raw_parents.id');
        }
        else{
            $rawFirms= $rawFirms->addSelect('raw_parent.name as parent_name','raws.name as raw_name', 'colors.name as color_name', 'raw_parent.id as parent_id');
            $rawFirms = $rawFirms->groupBy('type', 'firms.id', 'raws.id');
        }

        $rawFirms = $rawFirms->get();

        return new RawFirmResourceByFirm($rawFirms);
    }

    public static function getRawParentsByFirm(Request $request){
        $validation = Validator::make($request->all(), [
            'parent_id'=>'exists:raw_parents,id',
            'type'=>'numeric|between:0,2',
            'firm_id'=>'exists:firms,id',
            'start_date'=>'date',
            'end_date' => 'date',
        ]);

        if($validation->fails()){
            return Response::json([
                'code' => static::CODE_VALIDATION,
                'message' => $validation->errors(),
            ], 400)->throwResponse();
        }

        $import_date = '';
        $export_date = '';
        $start_date = '';
        if($request->has('start_date')){
            if($request->start_date != null){
                $start_date = new Carbon($request->start_date);
                $start_date = $start_date->format('Y-m-d');
                $import_date = " and date(imports.created_at) >= '".$start_date."'";
                $export_date = " and date(exports.created_at) >= '".$start_date."'";
            }
        }
        if($request->has('end_date')){
            if($request->end_date != null){
                $end_date = new Carbon($request->end_date);
                $end_date = $end_date->format('Y-m-d');
                $import_date = $import_date." and date(imports.created_at) <= '".$end_date."'";
                $export_date = $export_date." and date(exports.created_at) <= '".$end_date."'";
            }
        }

        $rawFirms = RawFirm::join('firms', 'firms.id', 'raw_firm.firm_id')
            ->join('raws','raws.id', 'raw_firm.raw_id')
            ->leftJoin('colors', 'colors.id','raws.color_id')
            ->join('raw_parents','raw_parents.id','raws.parent_raw_id')
            ->join('raw_parents as raw_par','raw_parents.ancestors','LIKE', DB::raw("CONCAT(raw_par.ancestors, '%')"));

        if($request->filled('parent_id')){
            $conditionWithParentId ='(raw_parents.id = raw_par.id and raws.parent_raw_id = '.$request->parent_id.')';
            $rawFirms = $rawFirms
                ->select('raw_firm.type', 'firms.id as firm_id','firms.name as firm_name','raw_par.id as parent_id',
                    DB::raw('(CASE WHEN '.$conditionWithParentId.' THEN raw_firm.id ELSE raw_par.id END) as id'),
                    DB::raw('(CASE WHEN NOT '.$conditionWithParentId.' THEN raw_par.name ELSE raws.name END) as name'),
                    DB::raw('(CASE WHEN '.$conditionWithParentId.' THEN colors.name ELSE null END) as color_name'),
                    DB::raw('SUM(raw_firm.quantity) as quantity'),
                    DB::raw('SUM(raw_firm.valid_quantity) as valid_quantity'),
                    DB::raw('(CASE WHEN '.$conditionWithParentId.' THEN 0 ELSE 1 END) as children'));

            $rawFirms = $rawFirms->where(function($query) use ($request){
                $query->where('raw_par.parent_id', $request->parent_id)
                ->orWhere(function($query) use ($request){
                    $query->where('raws.parent_raw_id', $request->parent_id)
                    ->whereColumn('raw_par.id','raw_parents.id');
                });
            });
            $rawFirms = $rawFirms->groupBy('firms.id','raw_firm.type', DB::raw('(CASE WHEN NOT '.$conditionWithParentId.' THEN raw_par.id ELSE raw_firm.raw_id END)'));
        }else{
            $rawFirms = $rawFirms
                ->select('raw_firm.type', 'firms.id as firm_id','firms.name as firm_name','raw_par.id as parent_id', 'raw_par.id as id',
                    'raw_par.name  as name',
                    DB::raw('null as color_name'),
                    DB::raw('SUM(raw_firm.quantity) as quantity'),
                    DB::raw('SUM(raw_firm.valid_quantity) as valid_quantity'),
                    DB::raw('1 as children'));

            $rawFirms = $rawFirms->whereNull('raw_par.parent_id');
            $rawFirms = $rawFirms->groupBy('firms.id','raw_firm.type','raw_par.id');
        }

        $rawFirms = $rawFirms->addSelect([
            DB::raw('SUM(IFNULL((SELECT SUM(quantity) FROM imports WHERE raw_firm.id=imports.raw_firm_id and imports.new=1 and raw_parents.ancestors like CONCAT(raw_par.ancestors, \'%\')'.$import_date.'), 0)) as import_new'),
            DB::raw('SUM(IFNULL((SELECT SUM(quantity) FROM imports WHERE raw_firm.id=imports.raw_firm_id and imports.new=0 and raw_parents.ancestors like CONCAT(raw_par.ancestors, \'%\')'.$import_date.'), 0)) as import_tpa'),
            DB::raw('SUM(IFNULL((SELECT SUM(quantity) FROM exports WHERE raw_firm.id=exports.raw_firm_id and exports.to=-1 and raw_parents.ancestors like CONCAT(raw_par.ancestors, \'%\')'.$export_date.'), 0)) as export_tpa'),
            DB::raw('SUM(IFNULL((SELECT SUM(quantity) FROM exports WHERE raw_firm.id=exports.raw_firm_id and exports.to=-2 and raw_parents.ancestors like CONCAT(raw_par.ancestors, \'%\')'.$export_date.'), 0)) as export_gran'),
            DB::raw('SUM(IFNULL((SELECT SUM(quantity) FROM exports WHERE raw_firm.id=exports.raw_firm_id and exports.to=-3 and raw_parents.ancestors like CONCAT(raw_par.ancestors, \'%\')'.$export_date.'), 0)) as export_factory'),
            DB::raw('SUM(IFNULL((SELECT SUM(quantity) FROM exports WHERE raw_firm.id=exports.raw_firm_id and exports.to=-4 and raw_parents.ancestors like CONCAT(raw_par.ancestors, \'%\')'.$export_date.'), 0)) as export_back'),
            DB::raw('SUM(IFNULL((SELECT SUM(quantity) FROM imports WHERE raw_firm.id=imports.raw_firm_id and raw_parents.ancestors like CONCAT(raw_par.ancestors, \'%\') and date(created_at) < "'.$start_date.'"), 0)) as imports_before'),
            DB::raw('SUM(IFNULL((SELECT SUM(quantity) FROM exports WHERE raw_firm.id=exports.raw_firm_id and raw_parents.ancestors like CONCAT(raw_par.ancestors, \'%\') and date(created_at) < "'.$start_date.'"), 0)) as exports_before'),
            // DB::raw('SUM(IFNULL((SELECT SUM(quantity) FROM imports WHERE raw_firm.id=imports.raw_firm_id and raw_parents.ancestors like CONCAT(raw_par.ancestors, \'%\') and date(created_at) < "'.$start_date.'"), 0)) -
            //          SUM(IFNULL((SELECT SUM(quantity) FROM exports WHERE raw_firm.id=exports.raw_firm_id and raw_parents.ancestors like CONCAT(raw_par.ancestors, \'%\') and date(created_at) < "'.$start_date.'"), 0)) as balance_before'),

        ]);

        if($request->filled('type')){
            $rawFirms = $rawFirms->where('raw_firm.type', $request->type);
        }
        if($request->filled('firm_id')){
            $rawFirms = $rawFirms->where('firms.id', $request->firm_id);
        }

        $request->merge(['filtered' => true]);

        $rawFirms = $rawFirms->get();

        return $rawFirms;
    }
    public function getAllRawParentsByFirm(Request $request){
        $parent_name = '';

        if($request->filled('parent_id')){
            $parent_name = RawParent::find($request->parent_id)->name;
        }

        $firms = Firm::all();
        if($request->filled('firm_id')){
            $firms = $firms->where('id', $request->firm_id);
        }
        $rawFirms = $this->getRawParentsByFirm($request);

        return new RawParentResourceByFirm($firms, $rawFirms, $parent_name);

    }
}
