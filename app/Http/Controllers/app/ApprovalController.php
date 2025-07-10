<?php

namespace App\Http\Controllers\app;

use App\Http\Controllers\Controller;
use App\Models\Access;
use App\Models\Company;
use App\Models\Loan;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use App\Mail\ApproveLoanMail;
use App\Models\Repayment;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

class ApprovalController extends Controller
{
    public function approve(Request $request){
        $type=$request->type;
        //Check role of who is approving
        $role_type=auth()->user()->role_type;
        if($role_type=='hro'){
            $approval_level=2;
            $level=1;
            $next_approver="finance";
        }
        if($role_type=='finance'){
            $approval_level=3;
            $level=2;
            $next_approver="admin";
        }
        if($role_type=='admin'){
            $approval_level=4;
            $level=3;
            $next_approver="";
        }

        if($type==1){ //Approve
            try {
                $loan_id=$request->loan_id;
                $company_id=Loan::where('id',$loan_id)->pluck('company_id')->first();
                $desc=$request->desc;
                Loan::where('id',$loan_id)->update([
                    'approval_level'=>$approval_level,
                    'approver'.$level.'_id'=>Auth::id(),
                    'approver'.$level.'_action'=>'RECOMMENDED',
                    'approver'.$level.'_comments'=>$desc,
                    'approver'.$level.'_date'=>date('Y-m-d H:i:s')
                ]);

                //Check if its admin and make final approval
                $role_type=auth()->user()->role_type;
                if($role_type=='admin'){
                    Loan::where('id',$loan_id)->update([
                        'final_decision'=>1,
                        'mpesa_transaction'=>$request->trx,
                        'progress'=>0,
                        'approval_level'=>0
                    ]);

                //Remove attachments
                $get_file=Loan::where('id',$loan_id)->pluck('supporting_doc_file')->first();
                $url='uploads/supporting_docs/'.$get_file;
                if(file_exists($url)){
                    @unlink($url); //Removes the file
                }
                Loan::where('id',$loan_id)->update(['supporting_doc_file'=>NULL]);

                //Update Repayments
                //Algorithim
                $loan_id=$request->loan_id;
                $loan=Loan::find($loan_id);
                $loan_amount=$loan->requested_loan_amount;
                $installments=$loan->monthly_installments;
                $period=$loan->payment_period;
                $company_id=$loan->company_id;
                $loan_user_id=$loan->user_id;

                $date=Carbon::now();
                $cut_off_day= Company::where('id',$company_id)->pluck('cut_off_day')->first();

                if($date->day > $cut_off_day){
                    //If the current day is greater than the cut off day, then payment is pushed to next month
                    $date->addMonth();
                }

                for($i=1; $i<=$period;$i++){
                    $date->format('F Y');
                    $month=$date->format('F');
                    $year=$date->format('Y');
                    
                    Repayment::create([
                        'user_id'=>$loan_user_id,
                        'loan_id'=>$loan_id,
                        'company_id'=>$company_id,
                        'loan_amount'=>$loan_amount,
                        'installments'=>$installments,
                        'month'=>$month,
                        'year'=>$year,
                        'period'=>$period
                    ]);

                    $date->addMonth();
                }

                }

                //Send Email to the next approver
                $link=env('APP_URL');
                $hrs=Access::where('company_id',$company_id)->get();
                $emails=[];
                foreach($hrs as $hr){
                    $hr_id=$hr->user_id;
                        $check=User::where('id',$hr_id)->exists();
                        if(!$check){
                            continue;
                        }
                    $hr_user=User::find($hr_id);
                    $role=$hr_user->role_type;
                    if($role==$next_approver){
                        $email=$hr_user->email ?? $hr_user->alternative_contacts;
                        //array_push($email,['email'=>$email]);
                        //send mail
                        $recipient=$email;
                         Mail::to($recipient)->send(new ApproveLoanMail($link, $recipient));
                    }
                }



                session()->flash('message',"The loan $loan_id has been approved successfully");
                return back();
            } catch (\Throwable $th) {
                return $th;
                session()->flash('error',"An error occurred while approving LoanID $loan_id. Please try again later");
                return back();
            }
        }elseif($type==2){ //Reject
            //Check role of who is approving
            $role_type=auth()->user()->role_type;
            if($role_type=='hro'){
                $approval_level=1;
                $level=1;
            }
            if($role_type=='finance'){
                $approval_level=2;
                $level=2;
            }
            if($role_type=='admin'){
                $approval_level=3;
                $level=3;
            }
            try {
                $loan_id=$request->loan_id;
                $desc=$request->desc;
                Loan::where('id',$loan_id)->update([
                    'approval_level'=>$approval_level, //remains same so that we know who returned the loan easily
                    'approver'.$level.'_id'=>Auth::id(),
                    'approver'.$level.'_action'=>'RETURNED',
                    'final_decision'=>2,
                    'approver'.$level.'_comments'=>$desc,
                    'approver'.$level.'_date'=>date('Y-m-d H:i:s')
                ]);
                //Remove attachments
                $get_file=Loan::where('id',$loan_id)->pluck('supporting_doc_file')->first();
                $url='uploads/supporting_docs/'.$get_file;
                if(file_exists($url)){
                    @unlink($url); //Removes the file
                }
                Loan::where('id',$loan_id)->update(['supporting_doc_file'=>NULL]);
                session()->flash('message',"The loan $loan_id has been Returned.");
                return back();
            } catch (\Throwable $th) {
                session()->flash('error',"An error occurred while returning LoanID $loan_id. Please try again later");
                return back();
            }
        }
    }

}
