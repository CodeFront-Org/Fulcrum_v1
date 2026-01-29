<?php

namespace App\Http\Controllers\app;

use App\Http\Controllers\Controller;
use App\Models\Access;
use App\Models\Company;
use App\Models\Loan;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function dashboard()
    {
        //Get user role so as to redirect the correct view accordingly
        if (Auth::check()) {
            $role = Auth::user()->role_type;
            if ($role == 'user') {
                return $this->userDash();
            } elseif ($role == 'hro') {
                return $this->hroDash();
            } elseif ($role == 'finance') {
                return $this->financeDash();
            } elseif ($role == 'admin' || $role == 'approver') {
                return $this->adminDash();
            }

        } else {
            return redirect('/login');
        }
    }


    //User user dash
    public function userDash()
    {
        $label = "Dashboard";

        $loans = Loan::where('user_id', Auth::id())
            ->where(function ($query) {
                $query->where('progress', 5)
                    ->orWhere('progress', 0);
            })
            ->latest()
            ->get()
            ->map(function ($loan) {
                // Adding user name as an additional field
                $loan->company = Company::where('id', $loan->company_id)->pluck('name')->first();
                return $loan;
            });

        $tot_request = count($loans);
        $tot_amt = Loan::select('requested_loan_amount')->where('user_id', Auth::id())->where('final_decision', 1)->sum('requested_loan_amount');
        $approved = count(Loan::select('requested_loan_amount')->where('user_id', Auth::id())->where('final_decision', 1)->get());
        $returned = count(Loan::select('requested_loan_amount')->where('user_id', Auth::id())->where('final_decision', 2)->get());
        $des = Access::where('user_id', Auth::id())->pluck('company_id')->first();
        $des = Company::where('id', $des)->pluck('name')->first();
        return view('app.dash.user', compact(
            'label',
            'loans',
            'tot_request',
            'tot_amt',
            'approved',
            'returned',
            'des'
        ));
    }


    //User hro
    public function hroDash()
    {
        $label = "Dashboard";

        $company_ids = Access::where('user_id', Auth::id())->pluck('company_id')->toArray();

        //Dash variables
        $tot_users = Access::whereIn('company_id', $company_ids)->count();
        $tot_companies = Access::where('user_id', Auth::id())->count();
        $tot_approvals = Loan::where('approver1_id', Auth::id())->where('approver1_action', 'RECOMMENDED')->count();
        $tot_returned = Loan::where('approver1_id', Auth::id())->where('approver1_action', 'returned')->count();

        //$loans=Loan::where('approval_level',1)->where('final_decision',0)->whereIn('company_id', $company_ids)->where('progress',5)->get();

        $loans = Loan::where(function ($query) use ($company_ids) { // Pass $company_ids here
            $query->where('approval_level', 1)
                ->where('final_decision', 0)
                ->whereIn('company_id', $company_ids)
                ->where('progress', 5);
        })
            ->latest()
            ->get()
            ->map(function ($loan) {
                // Adding company name as an additional field
                $loan->company = Company::where('id', $loan->company_id)->pluck('name')->first();
                return $loan;
            });

        return view('app.dash.hro', compact(
            'label',
            'loans',
            'tot_users',
            'tot_companies',
            'tot_approvals',
            'tot_returned'
        ));

    }


    //User finance
    public function financeDash()
    {
        $label = "Dashboard";

        $company_ids = Access::select('company_id')->where('user_id', Auth::id())->get();

        //Dash variables
        $tot_users = Access::whereIn('company_id', $company_ids)->count();
        $tot_companies = Access::where('user_id', Auth::id())->count();
        $tot_approvals = Loan::where('approver2_id', Auth::id())->where('approver2_action', 'RECOMMENDED')->count();
        $tot_returned = Loan::where('approver2_id', Auth::id())->where('approver2_action', 'returned')->count();

        //$loans=Loan::where('approval_level',2)->where('final_decision',0)->whereIn('company_id', $company_ids)->where('progress',5)->get();

        $loans = Loan::where(function ($query) use ($company_ids) { // Pass $company_ids here
            $query->where('approval_level', 2)
                ->where('final_decision', 0)
                ->whereIn('company_id', $company_ids)
                ->where('progress', 5);
        })
            ->latest()
            ->get()
            ->map(function ($loan) {
                // Adding company name as an additional field
                $loan->company = Company::where('id', $loan->company_id)->pluck('name')->first();
                return $loan;
            });

        return view('app.dash.finance', compact(
            'label',
            'loans',
            'tot_users',
            'tot_companies',
            'tot_approvals',
            'tot_returned'
        ));

    }

    //User Admin
    public function adminDash()
    {
        $label = "Dashboard";

        if (Auth::user()->role_type == 'admin' || Auth::user()->role_type == 'approver') {
            $company_ids = Company::pluck('id')->toArray();
        } else {
            $company_ids = Access::where('user_id', Auth::id())->pluck('company_id')->toArray();
        }

        //Dash variables
        $tot_users = User::count();
        $tot_companies = Company::count();
        $tot_approvals = Loan::where('final_decision', 1)->count();
        $tot_returned = Loan::where('final_decision', 2)->count();

        //$loans=Loan::where('approval_level',3)->where('final_decision',0)->whereIn('company_id', $company_ids)->where('progress',5)->latest()->get();

        $loans = Loan::where(function ($query) use ($company_ids) { // Pass $company_ids here
            $query->where('approval_level', 3)
                ->where('final_decision', 0)
                ->whereIn('company_id', $company_ids)
                ->where('progress', 5);
        })
            ->latest()
            ->get()
            ->map(function ($loan) {
                // Adding company name as an additional field
                $loan->company = Company::where('id', $loan->company_id)->pluck('name')->first();
                return $loan;
            });

        return view('app.dash.admin', compact(
            'label',
            'loans',
            'tot_users',
            'tot_companies',
            'tot_approvals',
            'tot_returned'
        ));
    }

}
