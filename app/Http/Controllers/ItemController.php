<?php

namespace App\Http\Controllers;
/**
 * created using
 * php artisan make:controller ItemController
 */

use Illuminate\Http\Request;
use App\Item;

class ItemController extends Controller
{
    public function getAll(){
        $items = Item::get();
        // return \Response::json($items, 200); // this also works
        return response($items, 200);
    }

    public function getById($id){
        if (Item::where('id', $id)->exists()) {
            $item = Item::where('id', $id)->get();
            return response($item, 200);
        }
        return response([
                "message" => "Item not found",
                "id" => $id
            ], 404);
    }

    public function addNew(Request $request){
        $item = new Item;
        $item->name = $request->name;
        $item->batch = $request->batch;
        $item->price = $request->price;
        // (!$item->save()){}

        $res = $this->saveExtended($item);
        if(true!==$res){
            return $res;
        }

        return response([
            $item
        ], 201);
    }

    public function updateItem(Request $request, $id) {
        if (Item::where('id', $id)->exists()) {
            $item = Item::find($id);
            $item->name = isset($request->name) ? $request->name : $item->name;
            $item->batch = isset($request->batch) ? $request->batch : $item->batch;
            $item->price = isset($request->price) ? $request->price : $item->price;

            $res = $this->saveExtended($item);
            if(true!==$res){
                return $res;
            }
                
            return response([
                "message" => "records updated successfully",
                "data" => $item
            ], 200);
        }
        return response([
                "message" => "Item not found",
                "id" => $id
            ], 404);
    }
}
