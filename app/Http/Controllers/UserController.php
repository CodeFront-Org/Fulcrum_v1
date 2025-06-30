<?php

namespace App\Http\Controllers;

use App\Models\Staff;
use App\Models\User;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $users=User::select('id','first_name','last_name','contacts','email','role_type','created_at')->orderByDesc('id')->get();
        if($users){
            return response()->json([$users],200);
        }else{
            return response()->json(["status"=>"fail","msg"=>"Could not fetch users"],404);
        }
        
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $first_name=$request->first_name;
        $last_name=$request->last_name;
        $contacts=$request->contacts;
        $email=$request->email;
        $role_type=$request->role_type;
        $password=$request->password;

        // Validate the request data
        $validator = Validator::make($request->all(), [
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'contacts' => 'required|string|max:255',
            'email' => 'required|email|unique:users',
            'role_type' => 'required|string|max:255',
            'password' => 'required|string',
        ]);

        // Check if validation fails
        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'errors' => $validator->errors(),
            ], 422); // Unprocessable Entity
        }
        $save=User::create([
            'first_name'=>$first_name,
            'last_name'=>$last_name,
            'contacts'=>$contacts,
            'email'=>$email,
            'role_type'=>$role_type,
            'password'=>Hash::make($password)
        ]);
        if($save){
            return response()->json([
                'status'=>true,
                'msg'=>'User Saved Successfully'
            ],200);
        }else{
            return response()->json([
                'status'=>false,
                'msg'=>'Failed'
            ],500);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $first_name=$request->first_name;
        $last_name=$request->last_name;
        $contacts=$request->contacts;
        $email=$request->email;
        $role_type=$request->role_type;

        try {
            $update=User::where('id',$id)->update([
                'first_name'=>$first_name,
                'last_name'=>$last_name,
                'contacts'=>$contacts,
                'email'=>$email,
                'role_type'=>$role_type,
            ]);
            if($update){
                return response()->json(["status"=>true,"msg"=>"Record Update Succesfully"],200);
            }else{// When user submit an id that has been deleted or does not exists
                return response()->json(["status"=>false,"error"=>"Cannot Find User To Be Updated. Check If The User Exists."],404);
            }
        } catch (QueryException $ex) {
            if ($ex->errorInfo[1] === 1062) { // MySQL error code for duplicate entry
                return response()->json(['error' => 'The email provided already exists in the database.'], 400);
            } else {
                return response()->json(['error' => 'A database error occurred. Please try again later.'], 500);
            }
        } catch (\Throwable $th) {
            // Handle other types of exceptions
            return response()->json(['error' => 'An unexpected error occurred. Please try again later.'], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        if(User::find($id)){//check if user exists
            try {
                $del=User::find($id)->delete();
                if($del){
                    return response()->json(['msg','Deleted Successfully'],200);
                }
            } catch (QueryException $ex) {
                return response()->json(['error' => 'A database error occurred. Please try again later.'], 500);
            } catch (\Throwable $th) {
                // Handle other types of exceptions
                return response()->json(['error' => 'An unexpected error occurred. Please try again later...'], 500);
            }
        }else{
            return response()->json(['error','Could not find User. Please check if the user exists.'],404);
        }
    }
    //end of file
}
