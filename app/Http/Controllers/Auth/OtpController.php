<?php

namespace App\Http\Controllers\auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Carbon\Carbon;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class OtpController extends Controller
{
    public function otp(Request $request){
        return view('auth.otp') ;
    }

    public function verify(Request $request){
        $mobile=$request->mobile;
        $email=$request->email;
        $otp=$request->otp;
        //Get otp from the database
        $user_id=User::where('email',$email)->orWhere('contacts',$email)->pluck('id')->first();

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
        //Sending OTP
        $email=$request->email;
        $otp_no=mt_rand(10000, 99999);
        $otp_expire=Carbon::now()->addMinutes(5);
        $contacts=User::where('email',$email)->pluck('mpesa_contact')->first();
        $otp="Your JuaSmart OTP is ".$otp_no;
        $phone_number=$contacts;
        $curl = curl_init();
        curl_setopt_array($curl, array(
        CURLOPT_URL => env('SMS_URL'),
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => '',
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 15,
        CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => 'POST',
        CURLOPT_POSTFIELDS =>'{
            "mobile":"'.$phone_number.'",
            "response_type": "json",
            "sender_name":"'.env('SENDER_NAME').'",
            "service_id": 0,
            "message": "'.$otp.'"
        }',
        CURLOPT_HTTPHEADER => array(
            'h_api_key:'.env('SMS_KEY'),
            'Content-Type: application/json'
        ),
        ));

        $response = curl_exec($curl);
        $responseData = json_decode($response, true);

        $status_code = $responseData[0]['status_code'];
        if($status_code==1000){//otp sent successfully

        User::where('email',$email)->update(['otp'=>$otp_no,'otp_expiry'=>$otp_expire]);
        return "1";
        }else{
        return "2"; // otp not sent
        }
    }


}