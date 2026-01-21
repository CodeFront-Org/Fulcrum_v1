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
        $label = "Roles";

        // Fetch users who have roles (excluding plain users maybe? No, let's fetch all managed roles)
        // For simplicity, let's fetch users who have an entry in company_access or have a specific role_type

        $users = User::whereNotNull('role_type')
            ->where('role_type', '!=', 'user')
            ->orWhereIn('id', Access::pluck('user_id'))
            ->get()
            ->map(function ($user) {
                $user->companies_count = Access::where('user_id', $user->id)->count();
                $user->companies_list = Access::where('user_id', $user->id)
                    ->join('companies', 'company_access.company_id', '=', 'companies.id')
                    ->select('companies.name', 'company_access.id as access_id')
                    ->get();
                return $user;
            });

        return view('app.admin.roles', compact('label', 'users'));
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
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'role_type' => 'required',
            'company' => 'required|exists:companies,name',
        ]);

        $company = Company::where('name', $request->company)->first();
        $user = User::find($request->user_id);

        // Update role_type
        $role_map = [
            '1' => 'user',
            '2' => 'hro',
            '3' => 'finance',
            '4' => 'admin',
            '5' => 'approver',
        ];

        $role_name = $role_map[$request->role_type] ?? 'user';
        $user->update(['role_type' => $role_name]);

        // Assign Spatie Role (Ensure it exists first)
        \Spatie\Permission\Models\Role::firstOrCreate(['name' => $role_name]);
        $user->syncRoles([$role_name]);

        // Assign Company Access
        Access::updateOrCreate([
            'user_id' => $user->id,
            'company_id' => $company->id,
        ]);

        session()->flash('message', 'Role and Company Access assigned successfully.');
        return back();
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
        try {
            Access::where('id', $id)->delete();
            session()->flash('message', 'Company access removed successfully.');
            return back();
        } catch (\Throwable $th) {
            session()->flash('error', 'Error removing access: ' . $th->getMessage());
            return back();
        }
    }
}
