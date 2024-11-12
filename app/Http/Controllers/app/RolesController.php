<?php

namespace App\Http\Controllers\app;

use App\Http\Controllers\Controller;
use App\Models\Access;
use App\Models\Company;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class RolesController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $label="Roles";

        // Get model_ids for users with role_id 1, 2, or 3
        $modelIds = DB::table('model_has_roles')
            ->whereIn('role_id', [1, 2, 3])
            ->pluck('model_id');

        foreach($modelIds as $userID){
            $user=User::find($userID);
            $user_id=$user->id;
            $f_name=$user->first_name;
            $l_name=$user->last_name;
            $user_name=$f_name.' '.$l_name;
            $contacts=$user->contacts;
            $id_number=$user->id_number;
            $role=$user->role_type;
            //Companies access
            $companies=Access::where('user_id',$user_id)->get();
            $companies_access=count($companies);

        }

        return view('app.admin.roles',compact('label'));
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
        return $request;
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
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
