<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\app\SmsController;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Cache;
use App\Models\Logged;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Mail\VerifyOTP;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class AuthController extends Controller
{
    protected $smsController;

    // Inject SmsController in the constructor
    public function __construct(SmsController $smsController)
    {
        $this->smsController = $smsController;
    }
    // Login function
    public function login(Request $request){
        //Check if Login attempts are reached
        $limits=$this->limit($request->ip());
        if($limits){
            return response()->json(['status'=>false,'msg'=>"Login Attempts Exceeded. Please Try again in: $limits seconds"],401);
        }else{
                //Check if Login attempts are reached
                $limits=$this->limit($request->ip());
                if($limits){
                    return response()->json(['status'=>false,'msg'=>"Login Attempts Exceeded. Please Try again in: $limits seconds"],401);
                }else{
                    // Get login input (mobile or email) and password
                    $login = $request->input('contacts'); // 'mobile' input field can hold either mobile or email
                    $password = $request->input('password');

                    // Check if the input is an email address
                    $fieldType = filter_var($login, FILTER_VALIDATE_EMAIL) ? 'email' : 'mobile_login';
                    if($fieldType=='mobile_login'){
                        $login=substr($login,-9);
                    }

                    // Prepare credentials array dynamically
                    $credentials = [
                        $fieldType => $login,
                        'password' => $password
                    ];
                }

            if (Auth::attempt($credentials)) {
                $user = Auth::user();
                // Check if contract has expired
                $contract_type=$user->employment_type;
                if(!$contract_type=='PERMANENT'){
                    $expiry_date=$user->contract_end;
                    if (Carbon::now()->greaterThan(Carbon::parse($expiry_date))) {
                        return response()->json(['status'=>false,'msg'=>'Your Contract has ended. Please Contact the admin'],401);
                    }
                }


                //Log user Data for login
                $this->logUserData($user);
                $this->sendOTP($user);
                //Delete any db logs for wrong login attempts
                $id=Logged::where('ip',$request->ip())->pluck('id')->first();
                Logged::find($id)->delete();
                //Get user role and redirect accordingly
                $role=$user->role_type;
                if($role=='user'){
                    $url='/user/home';
                }elseif($role=='hro'){
                    $url='/hr/home';
                }elseif($role=='finance'){
                    $url='/finance/home';
                }elseif($role=='admin'){
                    $url='/admin/home';
                }
                return response()->json(['status'=>true,'msg'=>'otp_sent'],200);
            }else{
                return response()->json(['status'=>false,'msg'=>'Wrong Mobile or Passcode'],401);
            }
        }
    }

    public function logUserData($user){
        $currentDateTime = Carbon::now();
        $currentDateTimeMilliseconds = $currentDateTime->format('d F Y H:i:s');
        Log::channel('user_login')->notice($user->email." of DB_ID ".$user->id." Logged in at: ".$currentDateTimeMilliseconds);
        $currentDateTimeFormatted = $currentDateTime->format('Y-m-d H:i:s');
        User::where('id',$user->id)->update(['last_login'=>$currentDateTimeFormatted,'is_verified'=>1]);
    }

    public function sendOTP($user){
        // 1. Generate a 5-digit OTP
        $otp = rand(10000, 99999);
        //$otp=12345;
        $hashedOtp = Hash::make($otp);

        User::where('id',$user->id)->update([
            'otp'=>$hashedOtp,
            'otp_expiry'=>now()->addMinutes(5),
            'is_verified'=>1
        ]);

        // 4. Send the OTP to the user (via email or SMS)
        $msg = "Your Verification code is,\n" .
        "$otp \n".
        "Expires in 5 minutes.\n".
        "Fulcrum Link.";
        $this->smsController->send_sms($user->contacts,$msg);
        //$this->smsController->send_sms('0797965680',$msg);

        // sending via email:
        Mail::to($user->email)->send(new VerifyOTP($otp,$user->email));
        //Mail::to("muchenemartin00@gmail.com")->send(new VerifyOTP($otp,"muchenemartin00@gmail.com"));



        return; //response()->json(['message' => 'OTP sent successfully']);
    }

    /**
     * Putting a limit on number of wrong logins attempt for same IP
     */
    public function limit($ip){
        $check=Logged::where('ip',$ip)->exists();
        if($check){
            $id=Logged::where('ip',$ip)->pluck('id')->first();
            $record=Logged::find($id);
            $attempt=$record->attempts;
            if($attempt>env('Login_Attempts')){//Max number of login attempt
                //Check if it has expired. For now i use 10 seconds
                $time_created=$record->updated_at;
                // Get the current time
                $current_time = Carbon::now();

                // Calculate the difference in seconds
                $time_difference = $current_time->diffInSeconds($time_created);

                // Check if the time difference is greater than env('Lock_Time') seconds
                if ($time_difference > env('Lock_Time')) { //Means the session has expired and we can allow user to attempt for another login. Delete old record
                    $record->delete();
                } else {
                    return env('Lock_Time')-$time_difference; //to get time in decreasing order 
                }
            }else{
                Logged::where('ip',$ip)->update(['attempts'=>($attempt+1)]);
                return false; 
            }
        }else{//Create a new record for new IP login attempt
            Logged::create(['ip'=>$ip]);
        }
    }

}
