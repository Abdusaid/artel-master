<?php

namespace App\Http\Controllers\Sklad;

use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\ModelNotFoundException;

use App\Http\Controllers\Controller;
use Response;
use Validator;
use App\Import;
use App\Export;
use App\RawFirm;
use App\Raw;
use App\RawParent;
use App\Fifo;
use Illuminate\Validation\Rule;
use DB;
use App\Http\Resources\ImportResource;
use App\Http\Resources\ImportResourceWithSum;
use App\Http\Resources\ImportExportResourceByRawFirm;
use Carbon\Carbon;
use App\Events\ImportCreated;
use App\Events\ImportUpdated;
use App\Events\ImportDeleted;

class ImportController extends Controller
{

    public function store(Request $request)
    {
        $validation = Validator::make($request->all(), [
            'raw_id'=>'required|exists:raws,id',
            'firm_id'=>'required|exists:firms,id',
            'quantity' => 'required|min:0',
            'type' => 'required|numeric',
            'is_new' => 'required|boolean',
            'container' => $request->type == static::TYPE_PERVICHKA && $request->new ? 'required' : '',
            'comment' => 'max:255',
            'serial_number' => $request->type == static::TYPE_PERVICHKA && $request->new ? 'required' : '',
            'supplier' => $request->type == static::TYPE_PERVICHKA && $request->new ? 'required' : '',
        ]);

        if($validation->fails()){
            return Response::json([
                'code' => static::CODE_VALIDATION,
                'message' => $validation->errors(),
            ], 400);
        }

        try{
            $rawFirm = RawFirm::where([['raw_id', $request->raw_id], ['firm_id', $request->firm_id], ['type', $request->type]])->firstOrFail();
        }catch(ModelNotFoundException $error){
            DB::rollback();
            return Response::json([
                'code' => 20,
                'message' => 'Сырье не найдено'
            ], 400);
        }

        // Start transaction!
        DB::beginTransaction();
        try{
            $import = $rawFirm->imports()->create([
                'quantity'=>$request->quantity,
                'new'=>$request->is_new,
                'serial_number'=> $request->serial_number,
                'supplier'=>$request->supplier,
                'container' => $request->container,
                'comment' => $request->comment,
                'status'=> ($request->type==static::TYPE_PERVICHKA && $request->is_new) ? static::IMPORT_STATUS_WHITE : static::IMPORT_STATUS_GREEN,
            ]);
            $this->onInsert($import);

            if( !$import || !$rawFirm)
            {
                throw new \Exception('Сырье не найдено');
            }

            broadcast(new ImportCreated($import, $rawFirm))->toOthers();
        }catch(\Exception $e)
        {
            DB::rollback();
            return Response::json([
                'code' => 20,
                'message' => $e->getMessage(),
            ], 400);
        }

        DB::commit();
        return new ImportResource($import);
    }

    public static function onInsert($import){

        $import->rawFirm()->update([
            'quantity' => $import->rawFirm->quantity + $import->quantity,
            'valid_quantity' => $import->rawFirm->valid_quantity + ($import->status!=static::IMPORT_STATUS_WHITE ? $import->quantity : 0)
        ]);
    }

    public static function onUpdate($prevImport, $newImport){

        DB::beginTransaction();
        try{
            self::onInsert($newImport);
            self::onDelete($prevImport);

        }catch(\Exception $e)
        {
            DB::rollback();
            return Response::json([
                'code' => 20,
                'message' => $e->getMessage(),
            ], 400);
        }

        DB::commit();

    }

    public static function onDelete($import){
        $import->rawFirm()->update([
            'quantity' => $import->rawFirm->quantity - $import->quantity,
            'valid_quantity' => $import->rawFirm->valid_quantity - ($import->status!=static::IMPORT_STATUS_WHITE ? $import->quantity : 0)
        ]);
    }

    public function update(Request $request, $id)
    {
        $validation = Validator::make($request->all(), [
            'raw_id'=>'required|exists:raws,id',
            'firm_id'=>'required|exists:firms,id',
            'type' => 'required|numeric',
            'quantity' => 'required|min:0',
            'is_new' => 'required|boolean',
            'comment' => 'max:255',
            'container' => $request->type == static::TYPE_PERVICHKA && $request->new ? 'required' : '',
            'serial_number' => $request->type == static::TYPE_PERVICHKA && $request->new ? 'required' :'',
            'supplier' => $request->type == static::TYPE_PERVICHKA && $request->new ? 'required' : '',
        ]);

        if($validation->fails()){
            return Response::json([
                'code' => static::CODE_VALIDATION,
                'message' => $validation->errors(),
            ], 400);
        }
        try{
            $rawFirm = RawFirm::where([['raw_id', $request->raw_id], ['firm_id', $request->firm_id], ['type', $request->type]])->firstOrFail();
            $import = Import::findOrFail($id);

        }catch(ModelNotFoundException $error){
            return Response::json([
                'code' => 20,
                'message' => 'Сырье не найдено'
            ], 400);
        }
        $this->authorize('update', $import);
        // check if update is possible
        if($request->quantity < $import->quantity && $import->status != Controller::IMPORT_STATUS_WHITE){
            if($rawFirm->valid_quantity < $import->quantity - $request->quantity){
                return Response::json([
                    'code' => static::CODE_IMPORT_UPDATE_NOT_POSSIBLE,
                    'message' => "Невозможно изменить, сырье уже израсходовано!"
                ], 400)->throwResponse();
            }
        }

        // Start transaction!
        DB::beginTransaction();
        try{

            $prevImport = $import->replicate();

            $import->update([
                'raw_firm_id' => $rawFirm->id,
                'quantity'=>$request->quantity,
                'new'=>$request->is_new,
                'container' => $request->container,
                'comment' => $request->comment,
                'serial_number'=> $request->serial_number,
                'supplier'=> $request->supplier
            ]);

            $this->onUpdate($prevImport, $import);

            broadcast(new ImportUpdated($import, $rawFirm))->toOthers();

        }catch(\Exception $e)
        {
            DB::rollback();
            return Response::json([
                'code' => 20,
                'message' => $e->getMessage(),
            ], 400);
        }

        DB::commit();
        return new ImportResource($import);

    }

    public function destroy($id)
    {
        // Start transaction!
        DB::beginTransaction();
        try{
            $import = Import::findOrFail($id);
            $this->authorize('delete', $import);
            // check if delete is possible
            if($import->status != Controller::IMPORT_STATUS_WHITE
                && $import->rawFirm->valid_quantity < $import->quantity ){
                return Response::json([
                    'code' => static::CODE_IMPORT_UPDATE_NOT_POSSIBLE,
                    'message' => "Невозможно удалить, сырье уже израсходовано!"
                ], 400)->throwResponse();
            }

            $rawFirmId = $import->rawFirm->id;
            $this->onDelete($import);

            broadcast(new ImportDeleted($import, RawFirm::findOrFail($rawFirmId)))->toOthers();
            $import->delete();

        }catch(ModelNotFoundException $error)
        {
            DB::rollback();
            return Response::json([
                'code' => 20,
                'message' => $error->getMessage(),
            ], 400);
        }
        DB::commit();
        return new ImportResource($import);
    }

    public function importHistory(Request $request){
        //|date_format:"d.m.Y, H:i"
        $validation = Validator::make($request->all(), [
            'start_date'=>'nullable|date',
            'end_date' => 'nullable|date',
            'raw_firm_id' => 'nullable|exists:raw_firm,id',
            'raw_id'=>'nullable|exists:raws,id',
            'firm_id' => 'nullable|exists:firms,id',
            'parent_id' => 'nullable|exists:raw_parents,id',
            'is_new' => 'nullable|boolean',
            'type' => 'nullable|numeric',
            'status' => 'nullable|numeric',
            'paginate' => 'nullable|boolean'
        ]);
        if($validation->fails()){
            return Response::json([
                'code' => static::CODE_VALIDATION,
                'message' => $validation->errors(),
            ], 400);
        }
        $imports = Import::query();
        $imports = $this->filter($request, $imports);
        $imports = $imports->orderByDesc('id');
        $query = clone $imports;
        if($request->paginate){
            $imports = $imports->paginate(15);
        }else
            $imports = $imports->get();
        return new ImportResourceWithSum($imports, $query);
    }

    public static function filter(Request $request, $imports){
        if($request->has('start_date')){
            if($request->start_date != null){
                $start_date = new Carbon($request->start_date);
                $start_date->format('Y-m-d');
                $imports = $imports->whereDate('imports.created_at','>=',$start_date);

            }
        }
        if($request->has('end_date')){
            if($request->end_date != null){
                $end_date = new Carbon($request->end_date);
                $end_date->format('Y-m-d');
                $imports = $imports->whereDate('imports.created_at','<=',$end_date);
            }
        }
        if($request->has('raw_firm_id')){
            if($request->raw_firm_id != null){
                $raw_firm_id = $request->raw_firm_id;
                $imports = $imports->where('raw_firm_id', $raw_firm_id);
            }
        }
        if($request->has('firm_id')){
            if($request->firm_id != null){
                $firm_id = $request->firm_id;
                $imports = $imports->whereHas('rawFirm', function($query)use ($firm_id){
                    $query->whereHas('firm', function($qur) use ($firm_id){
                        $qur->where('id', $firm_id);
                    });
                });
            }
        }
        if($request->has('type')){
            if($request->type != null){
                $imports = $imports->whereHas('rawFirm', function($query)use($request){
                    $query->where('type', $request->type);
                });

            }
        }
        if($request->has('raw_id')){
            if($request->raw_id != null){
                $raw_id = $request->raw_id;
                $imports = $imports->whereHas('rawFirm', function($query)use ($raw_id){
                    $query->whereHas('raw', function($qur) use ($raw_id){
                        $qur->where('id', $raw_id);
                    });
                });
            }
        }
        if($request->has('parent_id')){
            if($request->parent_id != null){
                $parent_id = $request->parent_id;
                $parent = RawParent::find($parent_id);
                $imports = $imports->whereHas('rawFirm', function($query) use ($parent){
                    $query->whereHas('raw', function($query) use ($parent){
                        $query->whereHas('parent', function($query) use ($parent){
                            $query->where('ancestors','LIKE',$parent->ancestors."%");
                        });
                    });
                });
            }
        }
        if($request->has('search')){
            if($request->search != null || $request->search != ''){
                $imports = $imports->whereHas('rawFirm', function($query) use($request){
                    $query->whereHas('raw', function($query) use ($request){
                        $query->where('name', "LIKE", "%$request->search%");
                    });
                });
                // })->orWhere('supplier', "LIKE", "%$request->search%");
            }
        }
        if($request->has('is_new')){
            if($request->is_new != null){
                $imports = $imports->where('new', $request->is_new);
            }
        }
        if($request->has('status')){
            $imports = $imports->where('status',$request->status);
        }
        return $imports;
    }

    public function filterExport(Request $request, $exports){
        if($request->has('start_date')){
            if($request->start_date != null){
                $start_date = new Carbon($request->start_date);
                $start_date->format('Y-m-d');
                $exports = $exports->whereDate('exports.created_at','>=',$start_date);

            }
        }
        if($request->has('end_date')){
            if($request->end_date != null){
                $end_date = new Carbon($request->end_date);
                $end_date->format('Y-m-d');
                $exports = $exports->whereDate('exports.created_at','<=',$end_date);
            }
        }
        if($request->has('raw_firm_id')){
            if($request->raw_firm_id != null){
                $raw_firm_id = $request->raw_firm_id;
                $exports = $exports->where('raw_firm_id', $raw_firm_id);
            }
        }
        if($request->has('firm_id')){
            if($request->firm_id != null){
                $firm_id = $request->firm_id;
                $exports = $exports->whereHas('rawFirm', function($query)use ($firm_id){
                    $query->whereHas('firm', function($qur) use ($firm_id){
                        $qur->where('id', $firm_id);
                    });
                });
            }
        }
        if($request->has('type')){
            if($request->type != null){
                $exports = $exports->whereHas('rawFirm', function($query)use($request){
                    $query->where('type', $request->type);
                });

            }
        }
        if($request->has('raw_id')){
            if($request->raw_id != null){
                $raw_id = $request->raw_id;
                $exports = $exports->whereHas('rawFirm', function($query)use ($raw_id){
                    $query->whereHas('raw', function($qur) use ($raw_id){
                        $qur->where('id', $raw_id);
                    });
                });
            }
        }
        if($request->has('parent_id')){
            if($request->parent_id != null){
                $parent_id = $request->parent_id;
                $parent = RawParent::find($parent_id);
                $exports = $exports->whereHas('rawFirm', function($query) use ($parent){
                    $query->whereHas('raw', function($query) use ($parent){
                        $query->whereHas('parent', function($query) use ($parent){
                            $query->where('ancestors','LIKE',$parent->ancestors."%");
                        });
                    });
                });
            }
        }
        if($request->has('search')){
            if($request->search != null || $request->search != ''){
                $exports = $exports->whereHas('rawFirm', function($query) use($request){
                    $query->whereHas('raw', function($query) use ($request){
                        $query->where('name', "LIKE", "%$request->search%");
                    });
                });
                // })->orWhere('supplier', "LIKE", "%$request->search%");
            }
        }
        if($request->has('to')){
            if($request->to != null){
                $exports = $exports->where('to', $request->to);
            }
        }
        if($request->has('status')){
            $exports = $exports->where('status',$request->status);
        }

        return $exports;
    }

    public function importExportByRawFirm(Request $request){
        $validation = Validator::make($request->all(), [
            'start_date'=>'nullable|date',
            'end_date' => 'nullable|date',
            'raw_firm_id' => 'required|exists:raw_firm,id',
            'parent_id'=>$request->filtered == 1 ? 'required|exists:firm_raw_parent,raw_parent_id' : '',
        ]);
        if($validation->fails()){
            return Response::json([
                'code' => static::CODE_VALIDATION,
                'message' => $validation->errors(),
            ], 400);
        }
        try{
            $rawFirm = RawFirm::findOrFail($request->raw_firm_id);
        }catch(ModelNotFoundException $error){
            return Response::json([
                'code' => 20,
                'message' => 'Сырье не найдено'
            ], 400);
        }
        $parent_id = $request->parent_id;
        $imports = Import::query();
        $exports = Export::query();

        if($request->filtered == 1){
            $request->replace($request->only(['start_date', 'end_date']));
            $imports = $this->filter($request, $imports)->join('raw_firm', 'raw_firm.id','imports.raw_firm_id')
                ->join('raws','raws.id','raw_firm.raw_id')
                ->join('firms','firms.id','raw_firm.firm_id')
                ->join('raw_parents as raw_parent','raw_parent.id','raws.parent_raw_id')
                ->join('firm_raw_parent','firm_raw_parent.firm_id','=','raw_firm.firm_id')
                ->join('raw_parents','raw_parents.id', '=','firm_raw_parent.raw_parent_id')
                ->select('imports.id','imports.raw_firm_id','imports.created_at',
                    DB::raw('(CASE WHEN imports.new = 1 THEN imports.quantity ELSE 0 END) as import_new'),
                    DB::raw('(CASE WHEN imports.new = 0 THEN imports.quantity ELSE 0 END) as import_tpa'),
                    DB::raw('0 as export_tpa'), DB::raw('0 as export_gran'), DB::raw('0 as export_factory'), DB::raw('0 as export_back'),
                    DB::raw('1 as is_import'))
                ->whereColumn('raw_parent.ancestors','LIKE',DB::raw("CONCAT(raw_parents.ancestors, '%')"))
                ->where('raw_firm.type', $rawFirm->type)->where('firms.id',$rawFirm->firm_id)->where('raw_parents.id', $parent_id);
            $exports = $this->filterExport($request, $exports)->join('raw_firm', 'raw_firm.id','exports.raw_firm_id')
                ->join('raws','raws.id','raw_firm.raw_id')
                ->join('firms','firms.id','raw_firm.firm_id')
                ->join('raw_parents as raw_parent','raw_parent.id','raws.parent_raw_id')
                ->join('firm_raw_parent','firm_raw_parent.firm_id','=','raw_firm.firm_id')
                ->join('raw_parents','raw_parents.id', '=','firm_raw_parent.raw_parent_id')
                ->select('exports.id','exports.raw_firm_id','exports.created_at',
                    DB::raw('0 as import_new'),
                    DB::raw('0 as import_tpa'),
                    DB::raw('(CASE WHEN exports.to = -1 THEN exports.quantity ELSE 0 END) as export_tpa'),
                    DB::raw('(CASE WHEN exports.to = -2 THEN exports.quantity ELSE 0 END) as export_gran'),
                    DB::raw('(CASE WHEN exports.to = -3 THEN exports.quantity ELSE 0 END) as export_factory'),
                    DB::raw('(CASE WHEN exports.to = -4 THEN exports.quantity ELSE 0 END) as export_back'),
                    DB::raw('0 as is_import')
                )
                ->whereColumn('raw_parent.ancestors','LIKE',DB::raw("CONCAT(raw_parents.ancestors, '%')"))
                ->where('raw_firm.type', $rawFirm->type)->where('firms.id',$rawFirm->firm_id)->where('raw_parents.id', $parent_id);

            $name = RawParent::findOrFail($parent_id)->name;
        }else{
            $imports = $this->filter($request, $imports)->select('id','raw_firm_id','created_at',
                DB::raw('(CASE WHEN imports.new = 1 THEN imports.quantity ELSE 0 END) as import_new'),
                DB::raw('(CASE WHEN imports.new = 0 THEN imports.quantity ELSE 0 END) as import_tpa'),
                DB::raw('0 as export_tpa'), DB::raw('0 as export_gran'), DB::raw('0 as export_factory'), DB::raw('0 as export_back'),
                DB::raw('1 as is_import'));
            $exports = $this->filterExport($request, $exports)->select('id','raw_firm_id','created_at',
                DB::raw('0 as import_new'),
                DB::raw('0 as import_tpa'),
                DB::raw('(CASE WHEN exports.to = -1 THEN exports.quantity ELSE 0 END) as export_tpa'),
                DB::raw('(CASE WHEN exports.to = -2 THEN exports.quantity ELSE 0 END) as export_gran'),
                DB::raw('(CASE WHEN exports.to = -3 THEN exports.quantity ELSE 0 END) as export_factory'),
                DB::raw('(CASE WHEN exports.to = -4 THEN exports.quantity ELSE 0 END) as export_back'),
                DB::raw('0 as is_import')
            );
            $name = $rawFirm->raw->name;
        }

        $merge = $imports->union($exports)->orderByDesc('created_at');
        return new ImportExportResourceByRawFirm($name, $merge->get());
    }

}
