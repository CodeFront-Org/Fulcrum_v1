<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ErrorController extends Controller
{
    /**
     * Error control method to catch all error messages and give appropriate responses
     *
     * @param $err which is the error thrown
     */
    public function AppError($th){
        if(str_contains($th->getMessage(),"No query results for model")){
            return response()->json([
                "status" => false,
                "message" => "The record does not exist"
            ], 404);
        }elseif(str_contains($th->getMessage(),"1062 Duplicate entry")){
            return response()->json([
                'status'=>"false",
                "message"=>"This record already exists"
            ],400);
        }else{
            return response()->json([
                'status'=>"false",
                "message"=>$th->getMessage()
            ],500); 
        }
    }
}
