<?php

namespace App\Http\Controllers\app;

use App\Http\Controllers\Controller;
use App\Models\Company;
use App\Models\User;
use Illuminate\Support\Str;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use App\Mail\ResetPasswordMail;
use App\Http\Controllers\app\SmsController;
use App\Models\Access;
use App\Models\Bank;
use App\Models\UserBank;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use App\Mail\PasswordChangedNotification;

class UserController extends Controller
{
    protected $smsController;

    // Inject SmsController in the constructor
    public function __construct(SmsController $smsController)
    {
        $this->smsController = $smsController;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $page_number = $request->page;

        if ($page_number == 1) {
            $page_number = 1;
        } elseif ($page_number > 1) {
            $page_number = (($page_number - 1) * 30) + 1;
        } else {
            $page_number = 1;
        }


        $label = "Users";

        //Give admin access to all companies
        if (Auth::check() && (Auth::user()->hasRole('admin') || Auth::user()->hasRole('approver'))) {
            $companies = Company::orderBy('name', 'asc')->get();
            $company_access = Access::select('company_id')->pluck('company_id');
        } else {
            $company_access = Access::select('company_id')->where('user_id', Auth::id())->pluck('company_id');
            $companies = Company::orderBy('name', 'asc')->whereIn('id', $company_access)->get();
        }

        $users = User::where('role_type', 'user')->whereIn('company_id', $company_access)
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



        $users_count = count($users);
        $banks = Bank::all();

        return view('app.admin.users', compact(
            'label',
            'users',
            'companies',
            'users_count',
            'banks',
            'page_number'
        ));
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
        //return $request; 
        //Get Company ID   hezronkipkoech10@gmail.com   254706699259  706699259   
        $company_id = Company::where('name', $request->company)->pluck('id')->first();  //  $2y$10$oNUk8B8fXVzy8AVriE6siujKdrLNI7kbfCUsWaNeo/NmKVFYzalya
        if (!$company_id) {
            session()->flash('error', 'The Selected Company Does Not EXIST. Please select a valid company');
            return back();
        }

        try {
            //Save User Info
            //dummy password
            $psw = Str::random(40);
            $user = User::create([
                'first_name' => $request->first_name,
                'last_name' => $request->last_name,
                'email' => $request->email,
                'contacts' => $request->contacts,
                'mobile_login' => substr($request->contacts, -9),
                'id_number' => $request->id_number,
                'gender' => $request->gender,
                'pin_certificate' => $request->pin,
                'staff_number' => $request->staff_no,
                'employment_date' => $request->employment_date,
                'employment_type' => $request->emp_type == 1 ? 'PERMANENT' : 'CONTRACT',
                'contract_end' => $request->contract_end,
                'role_type' => 'user',
                'company_id' => $company_id,
                'password' => Hash::make($psw)
            ]);
            //Assign Role user
            $user->assignRole('user');

            //Access to company
            Access::create([
                'user_id' => $user->id,
                'company_id' => $company_id
            ]);

            //Add to Bank 
            // $user_name=$request->first_name." ".$request->last_name;
            // UserBank::create([
            //     'bank_id'=>$request->bank_id,
            //     'user_id'=>$user->id,
            //     'user_account_name'=>$user_name,
            //     'account_number'=>$request->acc_no,
            //     'branch'=>$request->branch,
            // ]);

            //Create Token to be sent to Email and SMS for next auth
            $token = Str::random(10);
            $reset_expire = Carbon::now()->addMinutes(5);

            //Send Mail to email provided
            $user_id = $user->id;
            $link = env('APP_URL') . '/reset-password/' . $user_id . '/' . $token;
            $recipient = $request->email;
            Mail::to($recipient)->send(new ResetPasswordMail($link, $recipient));

            //Send SMS
            $msg = "Dear {$request->first_name},\n" .
                "Thank you for creating an account with us. " .
                "Use the link below to create your password.\n" .
                "{$link} \n\n" .
                "Fulcrum Link";
            $this->smsController->send_sms($request->contacts, $msg);

            //update token in db
            User::where('id', $user_id)->update(['reset_code' => $token, 'reset_expiry' => $reset_expire]);

            session()->flash('message', 'User Registered Successfully. We have sent a link via mail and SMS for the user to setup their account ');
            return back();

        } catch (\Throwable $th) {
            //return $th->getMessage();
            //Check if user exists and return apt error
            if (str_contains($th->getMessage(), "1062 Duplicate entry")) {
                session()->flash('error', 'The User already exist. Please check for duplicate entries fields and try again.');
                return back();
            } else {
                session()->flash('error', $th->getMessage());
                return back();
            }
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
        $company_id = Company::where('name', $request->company)->pluck('id')->first();  //  $2y$10$oNUk8B8fXVzy8AVriE6siujKdrLNI7kbfCUsWaNeo/NmKVFYzalya
        if (!$company_id) {
            session()->flash('error', 'The Selected Company Does Not EXIST. Please select a valid company');
            return back();
        }

        try {
            $user = User::where('id', $id)->update([
                'first_name' => $request->first_name,
                'last_name' => $request->last_name,
                'email' => $request->email,
                'contacts' => $request->contacts,
                'mobile_login' => substr($request->contacts, -9),
                'id_number' => $request->id_number,
                'gender' => $request->gender,
                'pin_certificate' => $request->pin,
                'staff_number' => $request->staff_no,
                'employment_date' => $request->employment_date,
                'employment_type' => $request->emp_type == 1 ? 'PERMANENT' : 'CONTRACT',
                'contract_end' => $request->contract_end,
                'role_type' => 'user',
                'company_id' => $company_id,
            ]);

            if ($user) {
                //Access to company
                Access::where('user_id', $id)->update([
                    //'user_id'=>$user->id,
                    'company_id' => $company_id
                ]);

                session()->flash('message', 'The user data has been updated successfully.');
                return back();
            }


        } catch (\Throwable $th) {
            //throw $th;                
            //Check if user exists and return apt error
            if (str_contains($th->getMessage(), "1062 Duplicate entry")) {
                session()->flash('error', 'The User already exist. Please check for duplicate entries fields and try again.');
                return back();
            } else {
                session()->flash('error', $th->getMessage());
                return back();
            }
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
            User::find($id)->delete();
            return;
        } catch (\Throwable $th) {
            throw $th;
        }
    }


    ////////////////////////////////////////////// Admin Method to do the Cruds ///////////////////////////////////////
    public function addAdmin(Request $request)
    {
        //Check Authentication
        if (Auth::check() && (auth()->user()->role_type === 'admin' || auth()->user()->role_type === 'approver')) {
            return $request;
            //Get Company ID
            // $company_id=Company::where('name',$request->company)->pluck('id')->first();
            // if(!$company_id){
            //     session()->flash('error','The Selected Company Does Not EXIST. Please select a valid company');
            //     return back();
            // }

            try {
                //Save User Info
                //dummy password
                $psw = Str::random(40);
                $user = User::create([
                    'first_name' => $request->first_name,
                    'last_name' => $request->last_name,
                    'email' => $request->email,
                    'contacts' => $request->contacts,
                    'id_number' => $request->id_number,
                    'gender' => $request->gender,
                    'pin_certificate' => $request->pin,
                    'staff_number' => $request->staff_no,
                    'employment_date' => $request->employment_date,
                    'employment_type' => $request->emp_type,
                    'contract_end' => $request->contract_end,
                    'role_type' => 'user',
                    'password' => Hash::make($psw)
                ]);
                //Assign Role user
                $user->assignRole('user');

                //Access to company
                // Access::create([
                //     'user_id'=>$user->id,
                //     'company_id'=>$company_id
                // ]);

                //Create Token to be sent to Email and SMS for next auth
                $token = Str::random(10);
                $reset_expire = Carbon::now()->addMinutes(5);

                //Send Mail to email provided
                $user_id = $user->id;
                $link = env('APP_URL') . '/reset-password/' . $user_id . '/' . $token;
                $recipient = $request->email;
                Mail::to($recipient)->send(new ResetPasswordMail($link, $recipient));

                //Send SMS
                $msg = "Dear {$request->first_name},\n" .
                    "Thank you for creating an account with us. " .
                    "Use the link below to create your password.\n" .
                    "{$link} \n\n" .
                    "Fulcrum Link";
                //$this->smsController->send_sms($request->contacts,$msg);


                //update token in db
                User::where('id', $user_id)->update(['reset_code' => $token, 'reset_expiry' => $reset_expire]);

                session()->flash('message', 'User Registered Successfully. We have sent a link via mail and SMS for the user to setup their account ');
                return back();

            } catch (\Throwable $th) {
                return $th->getMessage();
            }
        } else {
            abort(401);
        }
    }

    public function getAdmins(Request $request)
    {
        //Check Authentication
        if (Auth::check() && (auth()->user()->role_type === 'admin' || auth()->user()->role_type === 'approver')) {
            $label = "Administrators";

            return view('app.admin.admin', compact('label'));
        } else {
            abort(401);
        }
    }

    public function editAdmin(Request $request)
    {
        //Check Authentication
        if (Auth::check() && (auth()->user()->role_type === 'admin' || auth()->user()->role_type === 'approver')) {

        } else {
            abort(401);
        }
    }


    public function deleteAdmin(Request $request)
    {
        //Check Authentication
        if (Auth::check() && (auth()->user()->role_type === 'admin' || auth()->user()->role_type === 'approver')) {

        } else {
            abort(401);
        }
    }

    /**
     * Reset a user's password.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function resetPassword(Request $request, $id)
    {
        $request->validate([
            'password' => 'required|min:8|confirmed',
        ]);

        try {
            $user = User::findOrFail($id);
            $user->password = Hash::make($request->password);
            $user->save();

            $admin = Auth::user();
            $adminName = $admin->first_name . ' ' . $admin->last_name;

            // 1. Send Notification Email
            Mail::to($user->email)->send(new PasswordChangedNotification($user, $adminName));

            // 2. Create Audit Log
            Log::channel('activity')->info("Password Reset Action", [
                'target_user_id' => $user->id,
                'target_user_email' => $user->email,
                'admin_id' => $admin->id,
                'admin_name' => $adminName,
                'timestamp' => now()->toDateTimeString(),
                'ip_address' => request()->ip()
            ]);

            session()->flash('message', 'Password reset successfully for ' . $user->first_name . '. Notification email sent.');
            return back();
        } catch (\Throwable $th) {
            session()->flash('error', 'Error resetting password: ' . $th->getMessage());
            return back();
        }
    }
}
