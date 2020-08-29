<?php

namespace App\Http\Controllers;
/**
 * created using
 * php artisan make:controller BillController
 */

use Illuminate\Http\Request;
use App\Bill;

class BillController extends Controller
{
    public function getAll(){
        $bills = Bill::get();
        // return \Response::json($bills, 200); // this also works
        return response($bills->extendedResult(), 200);
    }

    public function getById($id){
        if (Bill::where('id', $id)->exists()) {
            // $bill = Bill::where('id', $id);
            $bill = Bill::find($id);
            // return response($bill->get(), 200);
            // return response($bill, 200);
            // return response($bill->items, 200);
            return response($bill->extendedResult(), 200);
        }
        return response([
                "message" => "Bill not found",
                "id" => $id
            ], 404);
    }

    public function addNew(Request $request){
        $bill = new Bill;
        $bill->name = $request->name;
        $bill->batch = $request->batch;
        $bill->price = $request->price;
        // (!$bill->save()){}

        $res = $this->saveExtended($bill);
        if(true!==$res){
            return $res;
        }

        return response([
            $bill
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
