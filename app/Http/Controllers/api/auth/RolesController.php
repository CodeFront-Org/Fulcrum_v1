<?php

namespace App\Http\Controllers\api\auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RolesController extends Controller
{
    /**
     * Add roles and permission for the app
     * 
     * @param Request $request(roles[])
     */
    public function roles(Request $request){
        $roles=$request->roles;
        // Check if roles exist and are iterable
        if ($roles && is_array($roles)) {
            foreach ($roles as $role) {
                //Add given role
                try {
                    $role= Role::create(['name' => $role]);
                } catch (\Throwable $th) {
                    //throw $th;
                    http_response_code(500); // Set the HTTP status code
                    printf(response()->json(['status'=>false,'msg'=>$th->getMessage()],500)->content());
                }
            }
            return response()->json(['status'=>true,'msg'=>"Roles Created Successfully"]);
        }elseif(is_array($roles)){//Bad format as data is not an array
            return response()->json([
                'status' => false,
                'msg' => 'Data format is wrong. Array of roles is expected'
            ],400);
        }elseif(emptyArray($roles)){
            return response()->json([
                'status' => false,
                'msg' => 'Data provided cannot be null'
            ],400);
        }else{
            return response()->json([
                'status' => false,
                'msg' => 'Server Error. Please try again later...'
            ],500);
        }

        
    }
}
