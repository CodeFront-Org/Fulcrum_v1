<?php

use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

//////////////////////////////////////////create a unique id to be used as psw for users login in with other authentications
$uid=Str::uuid()->toString();
$psw=Hash::make($uid.now());

/////////////////////////////////////////Login User after finding them with id
$user=User::find($id);
Auth::login($user);