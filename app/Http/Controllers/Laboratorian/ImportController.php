<?php

namespace App\Http\Controllers\Laboratorian;

use App\RawFirm;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Controllers\Sklad\ImportController as IController;
use App\Http\Resources\ImportResource;
use Response;
use Validator;
use App\Import;
use App\Events\ImportUpdated;
use DB;
class ImportController extends Controller
{

    public function getNewImports(Request $request){
        $imports = Import::query();
        $imports = $imports->orderByDesc('id');
        $imports = IController::filter($request, $imports);

        $imports = $imports->paginate(15);
        return ImportResource::collection($imports);
    }

    public function changeStatus(Request $request, $id){
        $validation = Validator::make($request->all(), [
            'status' => 'required|integer|between:0,3',
            'comment' => 'nullable'
        ]);
        $import = Import::findOrFail($id);
        $this->authorize('changeStatus', $import);
        $prevImport = $import->replicate();
        if($validation->fails() || !$import ){
            return Response::json([
                'code' => static::CODE_VALIDATION,
                'message' => $validation->errors()
            ], 422)->throwResponse();
        }

        // if changing from checked status to non-checked if it is possible
        if($request->status == Controller::IMPORT_STATUS_WHITE && $import->status != Controller::IMPORT_STATUS_WHITE){
            if($import->rawFirm->valid_quantity < $import->quantity){
                return Response::json([
                    'code' => static::CODE_IMPORT_UPDATE_NOT_POSSIBLE,
                    'message' => "Невозможно изменить, сырье уже израсходовано!"
                ], 400)->throwResponse();
            }

        }

        if(!($request->status == $import->status && $request->comment == $import->comment)){
            // Start transaction!
            DB::beginTransaction();
            try{
                $import->update([
                    'status'=>$request->status,
                    'comment'=>$request->comment
                ]);

                IController::onUpdate($prevImport, $import);
                broadcast(new ImportUpdated($import, $import->rawFirm))->toOthers();
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

        return new ImportResource($import);
    }
}
