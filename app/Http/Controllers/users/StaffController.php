<?php

namespace App\Http\Controllers\Users;

use App\Http\Controllers\Controller;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Spatie\Permission\Models\Role;

class StaffController extends Controller
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

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        try {
            $staffRole = Role::findByName('staff');
            $teacherRole = Role::findByName('teacher');
        
            $staffAndTeachers = User::select('id','first_name', 'last_name', 'contacts', 'email', 'created_at')
                ->whereHas('roles', function($query) use ($staffRole, $teacherRole) {
                    $query->where('role_id', $staffRole->id)
                          ->orWhere('role_id', $teacherRole->id);
                })
                ->with(['roles' => function($query) {
                    $query->select('name'); // Select only id and name fields
                }])
                ->get();
        
            // Remove the pivot data from the roles so that people dont know the role id and Transform the roles into a single string
            $staffAndTeachers->each(function ($user) {
                $user->role = $user->roles->pluck('name')->first();
                unset($user->roles);
                unset($user->created_at);
        
                // Format the created_at date
                $user->createdAt = Carbon::parse($user->created_at)->format('d M Y, h:i A');
            });
            //Check if the data is empty
            $total=count($staffAndTeachers); 
            if($total!==0){
                return response()->json([
                    "status" => true,
                    "total"=>count($staffAndTeachers),
                    "data" => $staffAndTeachers
                ],200);
            }else{
                return response()->json([
                    "status" => false,
                    "total"=>$total,
                    "message" => "No users found in this category."
                ],404);
            }

        } catch (\Throwable $th) {
            return $this->AppError($th);
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
        //First get the role 
        $role_type=$request->role;
        $role = Role::where('name', $role_type)->first();
        if($role){
            try {
                //Save info
                $save=User::create([
                    'first_name'=>$request->first_name,
                    'last_name'=>$request->last_name,
                    'contacts'=>$request->contacts,
                    'email'=>$request->email,
                    'password'=>Hash::make($request->password)
                ]);
                //Add role
                $save->assignRole($role_type);
                return response()->json([
                    "status"=>"true",
                    "message"=>"Created successfully"
                ],201);
            } catch (\Throwable $th) {
                //throw $th;
                //Check if user exists and return apt error
                if(str_contains($th->getMessage(),"1062 Duplicate entry")){
                    return response()->json([
                        'status'=>"false",
                        "message"=>"This user email or contact already exists"
                    ],404);
                }else{
                    return response()->json([
                        'status'=>"false",
                        "message"=>$th->getMessage()
                    ],400);
                }
            }

        }else{
            return response()->json([
                'status'=>"false",
                'message'=>"The Role does not exists"
            ],400);
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
        try {
            // Find the user by ID
            $user = User::select('id','first_name', 'last_name', 'contacts', 'email', 'created_at')
                ->with(['roles' => function($query) {
                    $query->select('id', 'name'); // Select only id and name fields
                }])
                ->findOrFail($id);

            // Check if the user has any roles
            $userRole=User::find($id);
            if ($userRole->roles->isEmpty()) {
                $user->role = 'No role assigned'; // Or any default message if no roles are found
            } else {
                // Transform the roles into a single string
                unset($user->roles);
                unset($user->created_at);
                $user->role = $userRole->roles->pluck('name')->first(); // Assuming a user has only one role
            }

            // Convert created_at to Carbon and format it
            $user->createdAt = Carbon::parse($user->created_at)->format('d M Y, h:i A');

            return response()->json([
                "status" => true,
                "data" => $user,
            ]);
        } catch (\Throwable $th) {
            return $this->AppError($th);

        }
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
        // Validate the request
        $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'contacts' => 'required|string|max:15',
            'email' => 'required|email|max:255',
            'role' => 'required|string|max:255', // Ensure role is provided and valid
        ]);
    
        // Check if the role exists
        $role = Role::where('name', $request->role)->first();
        if (!$role) {
            return response()->json([
                'status' => false,
                'message' => 'The role does not exist'
            ], 400);
        }
    
        try {
            // Find the user by ID
            $user = User::findOrFail($id);
    
            // Update user details
            $user->first_name = $request->input('first_name');
            $user->last_name = $request->input('last_name');
            $user->contacts = $request->input('contacts');
            $user->email = $request->input('email');
            $user->save();
    
            // Sync user role
            $user->syncRoles([$role->name]); // Assuming one role per user. Adjust if needed.
            $data=$this->index();
            return response()->json([
                'status' => true,
                'message' => 'User updated successfully',
                'data' => $data
            ]);
        } catch (\Throwable $th) {
            return $this->AppError($th);
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
        try {
            //Check if the user exists
            $user=User::find($id);
            if($user){
                $user->delete();
                return response()->json([
                    "status"=>true,
                    "message"=>"Deleted Successfully"
                ],204);
            }else{
                return response()->json([
                    "status"=>true,
                    "message"=>"This user does not exists"
                ],404);
            }
        } catch (\Throwable $th) {
            return $this->AppError($th);
        }
    }
}
