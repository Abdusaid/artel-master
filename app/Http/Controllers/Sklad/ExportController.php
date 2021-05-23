<?php

namespace App\Http\Controllers\Sklad;

use App\ExportFifo;
use App\Http\Resources\FirmResource;
use App\Http\Resources\ImportResource;
use App\Http\Resources\RawFirmResource;
use App\Http\Resources\RawFirmResourceByType;
use App\Http\Resources\RawResource;
use App\Import;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Validator;
use Response;
use App\Export;
use App\Raw;
use App\RawFirm;
use App\Fifo;
use App\Firm;
use App\Http\Resources\ExportResource;
use App\Http\Resources\ExportResourceWithSum;
use DB;
use Carbon\Carbon;
use App\Events\ExportCreated;
use App\Events\ExportUpdated;
use App\Events\ExportDeleted;
use App\Events\ExportRequested;

class ExportController extends Controller
{

    public function index(Request $request){
        $validation = Validator::make($request->all(), [
            'start_date'=>'nullable|date',
            'end_date' => 'nullable|date',
            'raw_firm_id' => 'nullable|exists:raw_firm,id',
            'firm_id' => 'nullable|exists:firms,id',
            'raw_id'=>'nullable|exists:raws,id',
            'parent_id' => 'nullable|exists:raw_parents,id',
            'to' => 'nullable|numeric',
            'type' => 'nullable|numeric',
            'status' => 'nullable|boolean',
            'paginate' => 'nullable|boolean',
        ]);
        if($validation->fails()){
            return Response::json([
                'code' => static::CODE_VALIDATION,
                'message' => $validation->errors(),
            ], 400);
        }
        $exports = $this->filter($request, Export::query());
        $exports = $exports->orderByDesc('id');
        $query = clone $exports;
        if($request->paginate){
            $exports = $exports->paginate(15);
        }else
            $exports = $exports->get();

        return new ExportResourceWithSum($exports, $query);
    }

    public function getRequestedExports(){
        $exports = Export::where('status', 0)->orderByDesc('id')->get();
        return ExportResource::collection($exports);
    }

    public function filter(Request $request, $exports){
        if($request->has('start_date')){
            if($request->start_date != null){
                $start_date = new Carbon($request->start_date);
                $start_date->format('Y-m-d H:i');
                $exports = $exports->whereDate('created_at','>=',$start_date);

            }
        }
        if($request->has('end_date')){
            if($request->end_date != null){
                $end_date = new Carbon($request->end_date);
                $end_date->format('Y-m-d H:i');
                $exports = $exports->whereDate('created_at','<=',$end_date);
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

    public function store(Request $request){

        $rawFirm = $this->checkStore($request);

        DB::beginTransaction();
        try {
            $export = new Export;
            $export->rawFirm()->associate($rawFirm);
            $export->quantity = $request->quantity;
            $export->status = 1;
            $export->to = $request->to;
            $export->comment = $request->comment;
            $export->save();
            $this->onInsert($export);

            broadcast(new ExportCreated($export, $rawFirm))->toOthers();

        }catch(\Exception $e){
            DB::rollback();
            return Response::json([
                'code' => static::CODE_DB_TRANSACTION,
                'message' => $e->getMessage(),
            ], 400);
        }

        DB::commit();
        return new ExportResource($export);
    }

    public function onInsert($export){
        $export->rawFirm()->update([
            'quantity' => $export->rawFirm->quantity - $export->quantity,
            'valid_quantity' => $export->rawFirm->valid_quantity - $export->quantity
        ]);
    }

    public function onUpdate($prevExport, $newExport){

        DB::beginTransaction();
        try{
            $this->onDelete($prevExport);
            $this->onInsert($newExport);

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

    public function onDelete($export){
        $export->rawFirm()->update([
            'quantity' => $export->rawFirm->quantity + $export->quantity,
            'valid_quantity' => $export->rawFirm->valid_quantity + $export->quantity
        ]);
    }

    public function storeAsRequest(Request $request){

        $rawFirm = $this->checkStore($request, 0);

        DB::beginTransaction();
        try{
            $export = new Export;
            $export->rawFirm()->associate($rawFirm);
            $export->rawFirm->quantity -= $request->quantity;
            $export->rawFirm->valid_quantity -= $request->quantity;
            $export->rawFirm->save();
            $export->quantity = $request->quantity;
            $export->status = 0;
            $export->comment = $request->comment;
            $export->to = $request->to;
            $export->save();

            broadcast(new ExportRequested($export, $export->rawFirm))->toOthers();
        }catch(\Exception $e){
            DB::rollback();
            return Response::json([
                'code' => static::CODE_DB_TRANSACTION,
                'message' => $e->getMessage(),
            ], 400);
        }
        DB::commit();

        return new ExportResource($export);
    }

    public function checkStore(Request $request){

        $validation = Validator::make($request->all(), [
            'raw_id' => 'required|exists:raws,id',
            'firm_id' => 'required|exists:firms,id',
            'quantity' => 'required|numeric|gt:0',
            'type' => 'required|integer|between:0,2',
            'to' => 'required|integer|between:-4,-1',
            'comment' => 'max:255'
        ]);


        // check for validation error
        if($validation->fails() ){
            return Response::json([
                'code' => static::CODE_VALIDATION,
                'message' => $validation->errors()
            ], 400)->throwResponse();
        }

        //check if raws quantity is enough
        try{
            $rawFirm = RawFirm::where([['raw_id', $request->raw_id], ['firm_id', $request->firm_id], ['type', $request->type], ['valid_quantity', '>=', $request->quantity]])->firstOrFail();
        }catch(ModelNotFoundException $error){
            return Response::json([
                'code' => static::CODE_EXPORT_NOT_ENOUGH,
                'message' => 'Недостаточно сырья на складе'
            ], 400)->throwResponse();
        }

        return $rawFirm;

    }


    public function update(Request $request, $id){

        $validation = Validator::make($request->all(), [
            'raw_id' => 'required|exists:raws,id',
            'firm_id' => 'required|exists:firms,id',
            'quantity' => 'required|numeric|gt:0',
            'type' => 'required|integer|between:0,2',
            'to' => 'required|integer|between:-4,-1',
            'comment' => 'max:255'
        ]);

        $export = Export::find($id);
        $this->authorize('update', $export);
        $prevExport = $export->replicate();
        // check for validation error
        if($validation->fails() || !$export ){
            return Response::json([
                'code' => static::CODE_VALIDATION,
                'message' => $validation->errors()
            ], 400)->throwResponse();
        }

        //check if raws quantity is enough
        try{
            $rawFirm = RawFirm::where([['raw_id', $request->raw_id], ['firm_id', $request->firm_id], ['type', $request->type]])->firstOrFail();

            $required = ($rawFirm->id == $prevExport->rawFirm->id) ? $request->quantity-$export->quantity : $request->quantity;

            if($rawFirm->valid_quantity < $required){
                throw new ModelNotFoundException("Error");
            }


        }catch(ModelNotFoundException $error){
            return Response::json([
                'code' => static::CODE_EXPORT_NOT_ENOUGH,
                'message' => 'Недостаточно сырья на складе'
            ], 400)->throwResponse();
        }

        DB::beginTransaction();
        try {

            $export->update([
                'raw_firm_id' => $rawFirm->id,
                'to' => $request->to,
                'quantity' => $request->quantity,
                'comment' => $request->comment
            ]);

            $this->onUpdate($prevExport, $export);

            broadcast(new ExportUpdated($export, $rawFirm))->toOthers();
        }catch(\Exception $e){
            DB::rollback();
            return Response::json([
                'code' => static::CODE_DB_TRANSACTION,
                'message' => $e->getMessage(),
            ], 400);
        }

        DB::commit();

        return new ExportResource($export);
    }

    public function confirm($id){
        $export = Export::find($id);
        $export->update([
            'status' => 1
        ]);
        broadcast(new ExportUpdated($export, $export->rawFirm))->toOthers();
        return new ExportResource($export);
    }

    public function delete($id){
        DB::beginTransaction();
        try{
            $export = Export::find($id);
            $this->authorize('delete', $export);
            $rawFirmId = $export->rawFirm->id;

            $this->onDelete($export);
            broadcast(new ExportDeleted($export, RawFirm::findOrFail($rawFirmId)))->toOthers();
            $export->delete();
        }catch(ModelNotFoundException $error)
        {
            DB::rollback();
            return Response::json([
                'code' => 20,
                'message' => $error->getMessage(),
            ], 400);
        }
        DB::commit();
        return new ExportResource($export);
    }

    public function getQuantity(Request $request){
        try{
            $rawFirm = RawFirm::where([['raw_id', $request->raw_id], ['firm_id', $request->firm_id], ['type', $request->type]])->firstOrFail();
            return Response::json([
                'data' => $rawFirm->valid_quantity
            ]);
        }catch(ModelNotFoundException $error){
            return Response::json([
                'code' => 0,
                'message' => 'Сырье не найдено'
            ], 400)->throwResponse();
        }
    }

}
