<?php

use App\Http\Controllers\api\Pesapal;
use App\Http\Controllers\social_login\AuthController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use Laravel\Socialite\Facades\Socialite;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

use App\Http\Controllers\RepaymentController;

Route::get('/repayments', [App\Http\Controllers\app\RepaymentController::class, 'index'])->name('repayments.index');
Route::put('/repayments/{id}', [App\Http\Controllers\app\RepaymentController::class, 'update'])->name('repayments.update');



Route::get('/send-mail',[App\Http\Controllers\MailController::class,'sendEmail'])->name('/send-mail');

Route::get('/(secure)/index.html',function(){
    return redirect('/login');
});

Route::get('/', function () {
    return view('auth.login');
})->middleware('no-cache');;

//Authentication routes
Route::post('/app/login',[App\Http\Controllers\Auth\AuthController::class,'login'])->name('/app/login');
Route::post('/reset', [App\Http\Controllers\Auth\ResetController::class,'send_link'])->name('reset');//to send reset link
Route::get('/reset-password/{id}/{token}', [App\Http\Controllers\Auth\ResetController::class,'index'])->name('reset-password');//to load reset psw page
Route::post('/reset-psw', [App\Http\Controllers\Auth\ResetController::class,'reset'])->name('reset-psw');//to send reset link
//Forgot Password Flow
Route::post('/forgot-psw', [App\Http\Controllers\Auth\ResetController::class,'forgot_psw'])->name('forgot_psw');//to send reset link
Route::get('/forgot-password', function(){
    return view('auth.passwords.email');
});//to help reset password
Route::get('/confirmation-email',function(){
    return view('auth.passwords.confirm');
});

//OTP
Route::get('/otp',[\App\Http\Controllers\Auth\OtpController::class,'otp'])->name('otp');
Route::post('/send-otp',[\App\Http\Controllers\Auth\OtpController::class,'send_otp'])->name('send-otp'); //to be used to send otp
Route::post('/otp/verify',[\App\Http\Controllers\Auth\OtpController::class,'verify'])->name('otp/verify'); //to verify otp sent

Route::middleware(['auth','no-cache'])->group(function () {
//*******************App routes *******************///
Route::get('/dashboard',[\App\Http\Controllers\app\DashboardController::class,'dashboard'])->name('dashboard');
Route::resource('users',\App\Http\Controllers\app\UserController::class)->middleware('admin');
Route::resource('companies',\App\Http\Controllers\app\CompaniesController::class)->middleware('admin');
Route::resource('banks',\App\Http\Controllers\app\BankController::class)->middleware('admin');
Route::resource('roles',\App\Http\Controllers\app\RolesController::class);
Route::resource('loan', \App\Http\Controllers\app\LoanController::class);
Route::POST('/approve',[\App\Http\Controllers\app\ApprovalController::class,'approve'])->name('approve')->middleware('approvers');


Route::get('/view-profile',[\App\Http\Controllers\app\UserController::class,'view-profile'])->name('view_profile');
Route::post('/update-profile',[\App\Http\Controllers\app\UserController::class,'update-profile'])->name('update_profile');


//*******************Add other Admin routes *******************///
Route::get('/admins',[\App\Http\Controllers\app\UserController::class,'getAdmins'])->name('admins');
Route::post('/add-admin',[\App\Http\Controllers\app\UserController::class,'addAdmin'])->name('add-admin');
Route::post('/edit-admin',[\App\Http\Controllers\app\UserController::class,'editAdmin'])->name('edit-admin');
Route::post('/delete-admin',[\App\Http\Controllers\app\UserController::class,'deleteAdmin'])->name('delete-admin');

//*******************end admin routes *******************///


//*******************Report Admin routes *******************///
// Group all routes requiring 'admin' middleware
//Route::middleware('admin')->group(function () {

    // ReportsController routes
    Route::controller(\App\Http\Controllers\app\ReportsController::class)->group(function () {
        Route::get('/all-invoices', 'all_invoices')->name('all-invoices');
        Route::get('/scheme-report/{name}', 'scheme_report')->name('scheme-report');
        Route::get('/invoice-report/{id}', 'invoice_report')->name('invoice-report');
        Route::post('/payment_status', 'payment_status')->name('payment_status');
        Route::post('/partial_payment', 'partial_payment')->name('partial_payment');
        Route::get('/loans', 'loans')->name('loans'); // View loans
        Route::get('/scheme-perfomance', 'scheme_report')->name('scheme-perfomance');
        Route::get('/disbursement-report', 'disbursement_report')->name('disbursement-report');
        Route::get('/profitability-report', 'profitability_report')->name('profitability-report');
    });

    // LoanController routes
    Route::get('/loan-requests', [\App\Http\Controllers\app\LoanController::class, 'loan_requests'])->name('loan-requests');

    // RepaymentScheduleController routes
    Route::controller(\App\Http\Controllers\app\RepaymentScheduleController::class)->group(function () {
        Route::get('/repayment-schedule-pdf/{scheme_id}', 'generatePDF')->name('repayment-schedule-pdf');
        Route::get('/repayment-schedule-excel/{scheme_id}', 'generateExcel')->name('repayment-schedule-excel');
    });
//});

// Routes not requiring 'admin' middleware
Route::get('/payment_schedule', [\App\Http\Controllers\app\ReportsController::class, 'payment_schedule'])->name('payment_schedule');

Route::get('/search-user',[\App\Http\Controllers\app\SearchController::class,'search_user'])->name('search-user');

//*******************End Report Admin routes *******************///

// new reports after the new changes of the partial payments for each employee
Route::get('/loans',[\App\Http\Controllers\app\ReportsController::class,'loans'])->name('loans')->middleware('admin');//View loans 


// end of new report changes

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
});

Auth::routes(); 


