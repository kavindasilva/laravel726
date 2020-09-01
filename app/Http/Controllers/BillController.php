<?php

namespace App\Http\Controllers;
/**
 * created using
 * php artisan make:controller BillController
 */

use Illuminate\Http\Request;
use App\Bill;
use App\Item;

class BillController extends Controller
{
    public function getAll(){
        $bills = Bill::get();
        $res = [];
        foreach($bills as $b){
            $res[] = $b->extendedResult();
        }
        return response($res, 200);
    }
    /**
    {
        "id": 2,
        "cashier": 1,
        "total": 12,
        "created_at": null,
        "updated_at": null,
        "items": [
            {
                "id": 1,
                "name": "sample-db",
                "batch": "x001",
                "price": 100,
                "created_at": null,
                "updated_at": null,
                "pivot": {
                    "bill_id": 2,
                    "item_id": 1,
                    "qty": 1.2
                }
            }
        ]
    }
     */

    public function getById($id){
        if (Bill::where('id', $id)->exists()) {
            $bill = Bill::find($id);
            return response($bill->extendedResult(), 200);
        }
        return response([
                "message" => "Bill not found",
                "id" => $id
            ], 404);
    }

    public function addNew(Request $request){
        $bill = new Bill;
        $bill->cashier = $request->cashier;
        $bill->total = $request->total; // @TODO: make dynamic
        // $bill->itemsa = $request->items;

        $res = $this->saveExtended($bill);
        if(true!==$res){
            return $res;
        }

        foreach($request->items as $itm){
            $b_item = new \App\BillItem;
            $b_item->bill_id = $bill->id;
            $b_item->item_id = $itm["item_id"];
            $b_item->qty = $itm["qty"];

            if( !Item::where('id', $b_item->item_id)->exists() ){
                return response([
                    "message" => "Item not found",
                    "id" => $b_item->item_id
                ], 409);
            }

            $res = $this->saveExtended($b_item);
            if(true!==$res){
                return $res;
            }
        }

        return response([
            $bill->extendedResult()
        ], 201);
    }

    public function updateItem(Request $request, $id) {
        if (Bill::where('id', $id)->exists()) {
            $bill = Bill::find($id);
            $bill->name = isset($request->name) ? $request->name : $bill->name;
            $bill->batch = isset($request->batch) ? $request->batch : $bill->batch;
            $bill->price = isset($request->price) ? $request->price : $bill->price;

            $res = $this->saveExtended($bill);
            if(true!==$res){
                return $res;
            }

            return response([
                "message" => "records updated successfully",
                "data" => $bill
            ], 200);
        }
        return response([
                "message" => "Bill not found",
                "id" => $id
            ], 404);
    }

    public function deleteItem(Request $request, $id) {
        if (Bill::where('id', $id)->exists()) {
            $bill = Bill::find($id);
            $bill->delete();
            return response([
                "deleted" => true,
                "data" => $bill
            ], 200);
        }
        return response([
                "message" => "Bill not found",
                "id" => $id
            ], 404);
    }
}
