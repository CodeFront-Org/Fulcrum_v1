<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Logged;
use App\Models\User;
use App\Providers\RouteServiceProvider;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Facades\Lockout;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;

class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    use AuthenticatesUsers;
 
    protected function authenticated(Request $request, $user)
    {

                //Check if Login attempts are reached
                $limits=$this->limit($request->ip());
                if($limits){
                    return response()->json(['status'=>false,'msg'=>"Login Attempts Exceeded. Please Try again in: $limits seconds"],401);
                }else{
                    // Get login input (mobile or email) and password
                    $login = $request->input('mobile'); // 'mobile' input field can hold either mobile or email
                    $password = $request->input('password');

                    // Check if the input is an email address
                    $fieldType = filter_var($login, FILTER_VALIDATE_EMAIL) ? 'email' : 'mobile';

                    // Prepare credentials array dynamically
                    $credentials = [
                        $fieldType => $login,
                        'password' => $password
                    ];
                    if (Auth::attempt($credentials)) {
                        $user = Auth::user();
                        //Log user Data for login
                        $this->logUserData($user);
                        //Delete any db logs for wrong login attempts
                        $id=Logged::where('ip',$request->ip())->pluck('id')->first();
                        Logged::find($id)->delete();
                        return response()->json(['status'=>true,'msg'=>'Logged in successfully'],200);
                    }else{
                        return response()->json(['status'=>false,'msg'=>'Wrong Mobile or Passcode'],401);
                    }
                }

        // Logs activity for user login timestamp
        $currentDateTime = Carbon::now();
        $currentDateTimeMilliseconds = $currentDateTime->format('d F Y H:i:s:v');
        Log::channel('user_login')->notice($user->email." of DB_ID ".$user->id." Logged in at: ".$currentDateTimeMilliseconds);
        User::where('id',$user->id)->update(['last_login'=>$currentDateTimeMilliseconds]);
        // Insert login log into database
        // DB::table('login_logs')->insert([
        //     'user_id' => $user->id,
        //     'login_time' => now(),
        // ]);
    }

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected $redirectTo = RouteServiceProvider::HOME;

    /**
     * Create a new controller instance.
     * 
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }

    protected function sendFailedLoginResponse(Request $request)
    {
        RateLimiter::hit($this->throttleKey($request));

        throw ValidationException::withMessages([
            $this->username() => [trans('auth.failed')],
        ]);
    }

    protected function throttleKey(Request $request)
    {
        return Str::lower($request->input($this->username())) . '|' . $request->ip();
    }

    protected function hasTooManyLoginAttempts(Request $request)
    {
        return RateLimiter::tooManyAttempts($this->throttleKey($request), env('Login_Attempts')); //Number of login attempts
    }

    protected function incrementLoginAttempts(Request $request)
    {
        RateLimiter::hit($this->throttleKey($request));
    }

    protected function lockoutTime()
    {
        return env('Lock_Time'); // Lockout duration in minutes
    }

    protected function sendLockoutResponse(Request $request)
    {
        $seconds = RateLimiter::availableIn($this->throttleKey($request));

        throw ValidationException::withMessages([
            $this->username() => [trans('auth.throttle', ['seconds' => $seconds])],
        ])->status(429);
    }


}
