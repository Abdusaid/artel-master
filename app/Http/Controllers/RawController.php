<?php

namespace App\Http\Controllers;

use App\Http\Resources\RawTotalResource;
use Illuminate\Http\Request;
use Response;
use Validator;
use App\Raw;
use App\RawParent;
use App\Firm;
use App\Position;

class RawController extends Controller
{
    public function store(Request $request){

        $validation = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'parent_id' => 'integer|exists:raw_parents,id',
            'color_id' => 'integer|exists:colors,id',
            'firms' => 'array|exists:firms,id',
            'is_pervichka' => 'required_with:firms',
            'is_vtorichka' => 'required_with:firms',
            'is_granula' => 'required_with:firms',

        ]);

        if($validation->fails()){
            return Response::json([
                'code' => Controller::CODE_VALIDATION,
                'message' => $validation->errors(),
            ], 422);
        }
        $raw = new Raw();
        $raw->name = $request->name;
        $raw->parent_raw_id = $request->parent_id;
        $raw->color_id = $request->color_id;
        $raw->quantity = 0;
        $raw->save();

        foreach ($request->firms as $firm){

            // if pervichka
            if($request->is_pervichka){
                $raw->rawFirms()->create([
                    'firm_id' => $firm,
                    'type' => 1,
                    'position_id' => 1

                ]);
            }

            // if vtorichka
            if($request->is_vtorichka){
                $raw->rawFirms()->create([
                    'firm_id' => $firm,
                    'type' => 2,
                    'position_id' => 1

                ]);
            }

            // if granula
            if($request->is_granula){
                $raw->rawFirms()->create([
                    'firm_id' => $firm,
                    'type' => 0,
                    'position_id' => 1
                ]);
            }

        }

        return new RawTotalResource($raw);
    }

    public function update(Request $request, $id){

        $validation = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'parent_id' => 'integer|exists:raw_parents,id',
            'color_id' => 'integer|exists:colors,id',
            'firms' => 'array|exists:firms,id',
            'is_pervichka' => 'required_with:firms',
            'is_vtorichka' => 'required_with:firms',
            'is_granula' => 'required_with:firms',

        ]);

        if($validation->fails()){
            return Response::json([
                'code' => Controller::CODE_VALIDATION,
                'message' => $validation->errors(),
            ], 422);
        }
        $raw = Raw::find($id);
        $raw->name = $request->name;
        $raw->parent_raw_id = $request->parent_id;
        $raw->color_id = $request->color_id;
        $raw->quantity = 0;
        $raw->save();

        $previousFirms = $raw->firms()->distinct()->pluck('firms.id')->toArray();

        $raw->rawFirms()->whereIn('firm_id', array_diff($previousFirms, $request->firms))->delete();


        foreach ($request->firms as $firm){

            // if pervichka
            if($request->is_pervichka){
                $raw->rawFirms()->updateOrCreate([
                    'firm_id' => $firm,
                    'type' => 1
                ]);
            }else{
                $raw->rawFirms()->where([['firm_id', $firm], ['type', 1]])->delete();
            }

            // if vtorichka
            if($request->is_vtorichka){
                $raw->rawFirms()->updateOrCreate([
                    'firm_id' => $firm,
                    'type' => 2
                ]);
            }else{
                $raw->rawFirms()->where([['firm_id', $firm], ['type', 2]])->delete();
            }

            // if granula
            if($request->is_granula){
                $raw->rawFirms()->updateOrCreate([
                    'firm_id' => $firm,
                    'type' => 0
                ]);
            }else{
                $raw->rawFirms()->where([['firm_id', $firm], ['type', 0]])->delete();
            }

        }

        return new RawTotalResource($raw);
    }

    public function storeAsParent(Request $request){

        $validation = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'parent_id' => 'integer|exists:raw_parents,id'
        ]);

        if($validation->fails()){
            return Response::json([
                'code' => 1,
                'message' => $validation->error(),
            ], 400);
        }

        $raw_parent = new RawParent();
        $raw_parent->name = $request->name;
        $raw_parent->parent_id = $request->parent_id;     
        $raw_parent->save();
        $ancestors = RawParent::find($request->parent_id)->ancestors . $raw_parent->id . ",";
        $raw_parent->update(['ancestors' => $ancestors]);

        return response()->json($raw_parent);
    }

    public function updateAsParent(Request $request, $id){
        
        $validation = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'parent_id' => 'integer|exists:raw_parents,id'
        ]);

        if($validation->fails()){
            return Response::json([
                'code' => 1,
                'message' => $validation->error(),
            ], 400);
        }

        $raw_parent = RawParent::find($id);
        $raw_parent->name = $request->name;
        if($raw_parent->parent_id != $request->parent_id){
            $raw_parent->parent_id = $request->parent_id;
            $ancestors = RawParent::find($request->parent_id)->ancestors . $raw_parent->id . ",";
            $raw_parent->update(['ancestors' => $ancestors]);
            $this->refreshAncestors($raw_parent);
        }
        $raw_parent->save();

        return response()->json($raw_parent);
    }

    public function refreshAncestors($parent){
        foreach($parent->children as $child){           
            $child->update(['ancestors' => $parent->ancestors.$child->id.","]);
            $this->refreshAncestors($child);
        }
    }

    public function storeFirm(Request $request){

        $validation = Validator::make($request->all(), [
            'name' => 'required|string|max:255'
        ]);

        if($validation->fails()){
            return Response::json([
                'code' => 1,
                'message' => $validation->error(),
            ], 400);
        }

        $firm = new Firm();
        $firm->name = $request->name;
        $firm->save();

        return response()->json($firm);
    }

    public function updateFirm(Request $request, $id){

        $validation = Validator::make($request->all(), [
            'name' => 'required|string|max:255'
        ]);

        if($validation->fails()){
            return Response::json([
                'code' => 1,
                'message' => $validation->error(),
            ], 400);
        }

        $firm = Firm::find($id);
        $firm->name = $request->name;
        $firm->save();

        return response()->json($firm);
    }

    public function storePosition(Request $request){

        $validation = Validator::make($request->all(), [
            'section' => 'required|string',
            'column' => 'required|integer',
            'row' => 'required|integer'
        ]);

        if($validation->fails()){
            return Response::json([
                'code' => 1,
                'message' => $validation->error(),
            ], 400);
        }

        $position = new Position();
        $position->section = $request->section;
        $position->column = $request->column;
        $position->row = $request->row;
        $position->save();

        return response()->json($position);
    }

    public function updatePosition(Request $request, $id){

        $validation = Validator::make($request->all(), [
            'section' => 'required|string',
            'column' => 'required|integer',
            'row' => 'required|integer'
        ]);

        if($validation->fails()){
            return Response::json([
                'code' => 1,
                'message' => $validation->error(),
            ], 400);
        }

        $position = Position::find($id);
        $position->section = $request->section;
        $position->column = $request->column;
        $position->row = $request->row;
        $position->save();

        return response()->json($position);
    }

    public function delete($id){
        
        $raw = Raw::find($id);
        $raw->delete();
        return $id;
    }

    public function deleteAsParent($id){
        
        $raw_parent = RawParent::find($id);
        $raw_parent->delete();
        return $id;
    }

    public function deleteFirm($id){
        
        $firm = Firm::find($id);
        $firm->delete();
        return $id;
    }

    public function deletePosition($id){
        
        $position = Position::find($id);
        $position->delete();
        return $id;
    }
}
