<?php

namespace App\Http\Controllers;

use App\Models\StdClass;
use App\Models\Subject;
use Illuminate\Http\Request;

class AdminController extends Controller
{
    public function addTerm(Request $request){
        return "term";
    }

    public function addClass(Request $request){
        try {
            StdClass::create($request->all()); //Store all data directly from the request. Make sure you incoming request data looks exactly as column data 
            return response()->json([
                "status"=>true,
                "message"=>"Class added successfully"
            ]);
        } catch (\Throwable $th) {
            return response()->json([
                "status"=>false,
                "message"=>$th->getMessage()
            ]);   
        }
    }

    public function addSubject(Request $request){
        try {
            Subject::create($request->all()); //Store all data directly from the request. Make sure you incoming request data looks exactly as column data 
            return response()->json([
                "status"=>true,
                "message"=>"Subject added successfully"
            ]);
        } catch (\Throwable $th) {
            return response()->json([
                "status"=>false,
                "message"=>$th->getMessage()
            ]);   
        }
    }

}
