<?php

namespace App\Http\Controllers;

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
        else {
            return response([
              "message" => "Student not found",
              "id" => $id
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

        return response([
            $item
        ], 201);
    }
}
