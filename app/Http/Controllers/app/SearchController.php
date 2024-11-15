<?php

namespace App\Http\Controllers\app;

use App\Http\Controllers\Controller;
use App\Models\Access;
use App\Models\Bank;
use App\Models\Company;
use App\Models\User;
use App\Models\UserBank;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SearchController extends Controller
{
    public function search_user(Request $request){
        $label="Users";

        $email=$request->email;
        $user_id=User::where('email',$email)->pluck('id')->first();

        //Give admin access to all companies
        if(Auth::check() && Auth::user()->hasRole('admin')){
            $companies = Company::orderBy('name', 'asc')->get();
            $company_access=Access::select('company_id')->pluck('company_id');
        }else{
            $company_access=Access::select('company_id')->where('user_id',Auth::id())->pluck('company_id');
            $companies = Company::orderBy('name', 'asc')->whereIn('id',$company_access)->get();
        }

        $users = User::where('id',$user_id)
        ->latest('id')
        ->paginate(30)
        ->through(function ($user) {
            $c_id = Access::where('user_id', $user->id)->pluck('company_id')->first();
            $user->designation = Company::where('id', $c_id)->pluck('name')->first();
            
            $b_id = UserBank::where('user_id', $user->id)->pluck('bank_id')->first();
            $user->my_bank = Bank::where('id', $b_id)->pluck('name')->first();
            
            $user->branch = UserBank::where('user_id', $user->id)->pluck('branch')->first();
            $user->acc_no = UserBank::where('user_id', $user->id)->pluck('account_number')->first();
            
            return $user;
        });


        $users_count=count($users);
        $banks=Bank::all();

        return view('app.search.search_user',compact(
            'label',
            'users',
            'companies',
            'users_count',
            'banks',
        ));
    }
}
