<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Item;

class ItemController extends Controller
{
    public function getAll(){
        $items = Item::get()->toJson(JSON_PRETTY_PRINT);
        return response($items, 200);
    }

    public function getById($id){
        if (Item::where('id', $id)->exists()) {
            $item = Item::where('id', $id)->get()->toJson(JSON_PRETTY_PRINT);
            return response($item, 200);
        }
        else {
            return response()->json([
              "message" => "Student not found"
            ], 404);
        }
    }

    public function addNew(Request $request){
        // var_dump($request);exit;
        $item = new Item;
        $item->name = $request->name;
        $item->batch = $request->batch;
        $item->price = $request->price;
        $item->save();

        return response()->json([
            "id" => $item->id
        ], 201);
    }
}
