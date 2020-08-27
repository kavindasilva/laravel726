<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    public function saveExtended($model_object){
        try{
            $model_object->save();
            return true;
        }
        catch(\Exception $e){
            // throw $e;
            return response([
                "err" => true,
                "message" => $e->getMessage()
            ]);
        }
    }
}
