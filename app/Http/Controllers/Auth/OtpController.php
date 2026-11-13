<?php

namespace App\Http\Controllers\auth;

use App\Http\Controllers\app\SmsController;
use App\Http\Controllers\Controller;
use App\Mail\VerifyOTP;
use Illuminate\Http\Request;
use Carbon\Carbon;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class OtpController extends Controller
{
    protected $smsController;

    public function __construct(SmsController $smsController)
    {
        $this->smsController = $smsController;
    }

    public function otp(Request $request){
        return view('auth.otp') ;
    }

    public function verify(Request $request){
        $mobile=$request->mobile;
        $email=$request->email;
        $otp=$request->otp;
        //Get otp from the database
        $user_id=User::where('email',$email)->orWhere('contacts',$email)->orWhere('mobile_login',substr($email,-9))->pluck('id')->first();

        $user=User::find($user_id);

        $otp_db=$user->otp;
        $is_verified=$user->is_verified;

        //check expration
        $expire=$user->otp_expiry;//User::where('email',$email)->orWhere('contacts',$email)->pluck('otp_expiry')->first();
        if(Carbon::now()->gt($expire)){
        return '3'; 
        }
        //return 1;
        if(Hash::check($otp,$otp_db) && $is_verified==1){
            User::where('id',$user->id)->update(['otp'=>NULL,'is_verified'=>0]);
            Auth::login($user);//Logging User
            return '1';
        }if($otp!==$otp_db){
            return '2';
        }
    }

    public function send_otp(Request $request){
        $login = $request->email;
        $user = User::where('email', $login)
            ->orWhere('contacts', $login)
            ->orWhere('mobile_login', substr($login, -9))
            ->first();

        if (!$user) {
            return "2";
        }

        $otp = rand(10000, 99999);
        $hashedOtp = Hash::make($otp);
        $otpExpiryTime = Carbon::now()->addMinutes(15);

        User::where('id', $user->id)->update([
            'otp' => $hashedOtp,
            'otp_expiry' => $otpExpiryTime,
            'is_verified' => 1
        ]);

        // 1. Send OTP via Email first
        try {
            if (!empty($user->email)) {
                Mail::to($user->email)->send(new VerifyOTP($otp, $user->email));
            }
        } catch (\Throwable $e) {
            Log::error("Failed to send OTP email to {$user->email}: " . $e->getMessage());
        }

        // 2. Send OTP via Mobile Phone (SMS)
        try {
            if (!empty($user->contacts)) {
                $msg = "Your Verification code is,\n" .
                    "$otp \n" .
                    "Expires at $otpExpiryTime.\n" .
                    "Fulcrum Link.";
                $this->smsController->send_sms($user->contacts, $msg);
            }
        } catch (\Throwable $e) {
            Log::error("Failed to send OTP SMS to {$user->contacts}: " . $e->getMessage());
        }

        return "1";
    }


}