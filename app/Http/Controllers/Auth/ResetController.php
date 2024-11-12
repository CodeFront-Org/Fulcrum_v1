<?php

namespace App\Http\Controllers\auth;

use App\Http\Controllers\app\SmsController;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;
use App\Models\User;
use App\Mail\ResetPasswordMail;
use App\Mail\ForgotMyPassword;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Hash;
class ResetController extends Controller
{

    protected $smsController;

    // Inject SmsController in the constructor
    public function __construct(SmsController $smsController)
    {
        $this->smsController = $smsController;
    }

    public function index($id,$token){
        $tkn=User::where('id',$id)->where('reset_code',$token)->pluck('reset_code')->first();
        $user=User::find($id);
        //check if token match
        if($tkn!==$token){
            abort(404);
        }else{
            // Check if the token has expired
            if (Carbon::now()->greaterThan($user->reset_expiry)) {
                // Token has expired
                return response()->json(['error' => 'Token is Invalid or has expired'], 401);
            }
        }
        
        if($tkn){//Token exists
            $user=User::find($id);
            $name=$user->first_name;
            $email=$user->email;
            return view('auth.reset',compact('token','name','id','email'));
        }else{
            abort(404);
        }

    }

    public function send_link(Request $request){
        $login= $request->email1; //Can be either email or mobile number
        // Check if the input is an email address
        $fieldType = filter_var($login, FILTER_VALIDATE_EMAIL) ? 'email' : 'mobile_login';
        if($fieldType=='mobile_login'){
            $login=substr($login,-9);
        }

        $user_id = User::where('email', $login)->orWhere($fieldType,$login)->pluck('id')->first();
        $user=User::find($user_id);
        $email=$user->email;

        if ($user) { //user email in db then send reset mail
            $token = Str::random(20);
            $reset_expire=Carbon::now()->addMinutes(30);

            //Send Mail to email provided
            $id=User::where('email',$email)->pluck('id')->first();
            $link=env('APP_URL').'/reset-password/'.$id.'/'.$token;
            $recipient=$email;
            Mail::to($recipient)->send(new ResetPasswordMail($link, $recipient));
            //update token in db
            User::where('email',$email)->update(['reset_code'=>$token,'reset_expiry'=>$reset_expire]);


            return "Yes";
        } else {//user email not in db
            return "No";
        }
    }

    public function forgot_psw(Request $request){
        // $email= $request->email1;
        // $user = User::where('email', $email)->first();
        $login= $request->email1; //Can be either email or mobile number
        // Check if the input is an email address
        $fieldType = filter_var($login, FILTER_VALIDATE_EMAIL) ? 'email' : 'mobile_login';
        if($fieldType=='mobile_login'){
            $login=substr($login,-9);
        }

        $user_id = User::where('email', $login)->orWhere($fieldType,$login)->pluck('id')->first();
        $user=User::find($user_id);
        $email=$user->email;

        if ($user) { //user email in db then send reset mail
            $token = Str::random(20);
            $reset_expire=Carbon::now()->addMinutes(30);
            //Send Mail to email provided
            $id=User::where('email',$email)->pluck('id')->first();
            $link=env('APP_URL').'/reset-password/'.$id.'/'.$token;
            $recipient=$email;
            Mail::to($recipient)->send(new ForgotMyPassword($link, $recipient));
            //update token in db
            User::where('email',$email)->update(['reset_code'=>$token,'reset_expiry'=>$reset_expire]);

            //Send SMS
            $msg = "Dear {$user->first_name},\n" .
            "Use the link below to reset your password.\n" .
            "{$link} \n\n".
            "Fulcrum";
            $this->smsController->send_sms($user->contacts,$msg);

            return "Yes";
        } else {//user email not in db
            return "No";
        }
    }

    public function reset(Request $request){

        $validatedData = $request->validate([
            'psw' => 'required|string|min:8|confirmed',
            'id' => 'required|exists:users,id',
        ]);

        $id=$request->id;
        //Check if token has expired and exists in the database
        $expire=User::where('id',$id)->pluck('reset_expiry')->first();
        if(Carbon::now()->gt($expire)){
        return '3';
        }else{//token has not expired
        //change password
        $psw=$request->psw;
        $newpsw= Hash::make($psw);
        User::where('id',$id)->update(['password'=>$newpsw,'reset_code'=>'']);
        $user = User::find($id);
        //login user so as to be able to redirect to home page
        Auth::login($user);//Logging User
        return '1';
        }
    }
}