<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\api\SMS;
use App\Http\Controllers\api\MPESA;
use App\Http\Controllers\api\NgrokController;
use App\Http\Controllers\api\Pesapal;
use App\Http\Controllers\api\TextSMS;
use App\Http\Controllers\api\WhatsApp;
use App\Http\Controllers\Users\StaffController;
use App\Http\Controllers\Users\StudentController;
use App\Http\Controllers\AssignmentController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return "hey";
    //return $request->user();
});

//Routes for testing api

// Route::resource('/users',App\Http\Controllers\UserController::class);
// Route::resource('/uploads',App\Http\Controllers\UploadController::class);
// Route::get('/download',[App\Http\Controllers\UploadController::class,'download'])->name('/download');

// /**************************************** Authentication routes: Register, Login, Reset, OTP and check Login statusRoutes **********************************:*/
// Route::post('/register',[\App\Http\Controllers\api\auth\RegisterController::class,'register'])->name('/register');
// Route::post('/register-admin-user',[\App\Http\Controllers\api\auth\RegisterAdminController::class,'registerAdmin'])->name('/register-admin-user');
// Route::post('/login',[\App\Http\Controllers\api\auth\LoginController::class,'authenticate'])->name('/login');
// Route::get('/loginStatus',[\App\Http\Controllers\api\auth\LoginController::class,'checkLoginStatus'])->name('/loginStatus');
// Route::post('/logout',[\App\Http\Controllers\api\auth\LoginController::class,'logout'])->name('/logout');
// //Reset
// Route::post('/resetLink',[\App\Http\Controllers\api\auth\ResetController::class,'resetLink'])->name('/resetLink');
// Route::get('/reset-password/{id}/{token}',[\App\Http\Controllers\api\auth\ResetController::class,'index'])->name('reset-password');//to load reset psw page
// Route::post('/reset-password',[\App\Http\Controllers\api\auth\ResetController::class,'reset'])->name('/reset-password');

// // Roles and Permission Registration
Route::post('/roles',[\App\Http\Controllers\api\auth\RolesController::class,'roles'])->name('/roles');
// /*********************************************************** End Authentication Routes **************************************/


// /******************************************************** DB Routes transform old to new db ************************************************/
Route::post('/db-users',[\App\Http\Controllers\DbController::class,'users'])->name('db-users');
Route::post('/db-company',[\App\Http\Controllers\DbController::class,'company'])->name('db-company');
Route::post('/db-bank',[\App\Http\Controllers\DbController::class,'bank'])->name('db-bank');
Route::post('/db-loans',[\App\Http\Controllers\DbController::class,'loans'])->name('db-loans');
// /**************************************** End Application Routes **********************************:*/