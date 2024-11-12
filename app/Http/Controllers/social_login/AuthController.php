<?php

namespace App\Http\Controllers\social_login;

use App\Http\Controllers\Controller;
use App\Models\User;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Laravel\Socialite\Facades\Socialite;
use Illuminate\Support\Str;

class AuthController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Github Login
    |--------------------------------------------------------------------------
    |
    |
    */
    public function github_redirect(){
        return Socialite::driver('github')->redirect();
    }

    public function github_callback(){
        $user = Socialite::driver('github')->user();
        dd($user);
    }

    /*
    |--------------------------------------------------------------------------
    | Google Login
    |--------------------------------------------------------------------------
    |
    |
    */
    public function redirectToGoogle()
    {
        return Socialite::driver('google')->redirect();
    }

    public function handleGoogleCallback()
    {
        try {
      
            $user = Socialite::driver('google')->user();//get the user data from google
            //dd($user->user['given_name']);
            $finduser = User::where('email', $user->email)->first();
       
            if($finduser){
       
                Auth::login($finduser);
      
                return redirect()->intended('dashboard');
       
            }else{
                $uid=Str::uuid()->toString();
                $psw=Hash::make($uid.now());
                $newUser = User::create([
                    'first_name' => $user->user['given_name'],
                    'last_name' => $user->user['family_name'],
                    'path' => $user->user['picture'],
                    'email' => $user->email,
                    'auth_type'=> 'google',
                    'password' => $psw
                ]);
      
                Auth::login($newUser);
      
                return redirect()->intended('dashboard');
            }
      
        } catch (Exception $e) {
            dd($e->getMessage());
        }
    }

}
