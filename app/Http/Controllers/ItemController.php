<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Item;

class ItemController extends Controller
{
    public function getAll(){
        $students = Item::get()->toJson(JSON_PRETTY_PRINT);
        return response($students, 200);
    }
}
