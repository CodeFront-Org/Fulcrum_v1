<?php

namespace App\Http\Controllers\app;

use App\Http\Controllers\Controller;
use App\Models\Access;
use App\Models\Bank;
use App\Models\Company;
use App\Models\Loan;
use App\Models\User;
use App\Models\UserBank;
use Illuminate\Support\Facades\Mail;
use App\Mail\ApproveLoanMail;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoanController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $label="Loan Application";

        //Check Loan Eligibilty based on contract expiry
        $contract=User::where('id',Auth::id())->pluck('contract_end')->first();
        $contract_type=User::where('id',Auth::id())->pluck('employment_type')->first();

        if(!$contract_type=='PERMANENT'){
            // Convert contract end date to Carbon instance
            $contractEndDate = Carbon::parse($contract);

            // Check if the contract end date is less than 60 days from now
            if ($contractEndDate->isBefore(Carbon::now()->addDays(60))) {
                // The contract end date is less than 60 days from now
                // Perform necessary actions (e.g., deny loan eligibility)
                session()->flash('error','You are not eligible for a loan, your contract ends in less than 60 days.');
                return back();
            }
        }

     //   $2y$10$hQZd02.k3YUWyf.YNdc0YuegUQ5rvxQ5cqsnisquZY8FUwQdC.4.m
     //  $2y$10$H1Urn6dDotkv0oaFVH7d6O/CoVkrIpJzDGymXU65YmxaJymeVN42O
        //Get the loan progress
        if($request->p){
             $p=$request->p;
        }else{
            $p=Loan::where('user_id',Auth::id())->pluck('progress')->last();
        }
        if($p==2){
            $p=2;
        }
        if($p==0 || empty($p) || $p==NULL || $p==5){//This means user has not yet started any steps
            //get data to be in disabled input
            $user_id=Auth::id();
            $first_name=User::where('id',$user_id)->pluck('first_name')->first();
            $last_name=User::where('id',$user_id)->pluck('last_name')->first();
            $contacts=User::where('id',$user_id)->pluck('contacts')->first();
            $applicant_id=User::where('id',$user_id)->pluck('id_number')->first();
            $contract_end=User::where('id',$user_id)->pluck('contract_end')->first();
            $company_id=Access::where('user_id',Auth::id())->pluck('company_id')->first();
            $company=Company::where('id',$company_id)->pluck('name')->first();
            $kin='';
            $kin_contacts='';


            $outstanding_loan="";
            $outstanding_loan_org="";
            $outstanding_loan_balance="";

            return view('app.loan.step1',compact(
                'label',
                'first_name',
                'last_name',
                'contacts',
                'applicant_id',
                'contract_end',
                'company',
                'kin',
                'kin_contacts',
                'outstanding_loan',
                'outstanding_loan_org',
                'outstanding_loan_balance'
            ));
        }elseif($p==1){ //Means user submitted step one that contained NEXT OF KIN Details.
            $loan = Loan::where('user_id', Auth::id())->whereIn('progress', ['1', '2', '3', '4'])->orderBy('id', 'desc')->first();
            $user_id=Auth::id();
            $first_name=User::where('id',$user_id)->pluck('first_name')->first();
            $last_name=User::where('id',$user_id)->pluck('last_name')->first();
            $contacts=User::where('id',$user_id)->pluck('contacts')->first();
            $applicant_id=User::where('id',$user_id)->pluck('id_number')->first();
            $contract_end=User::where('id',$user_id)->pluck('contract_end')->first();
            $company_id=Access::where('user_id',Auth::id())->pluck('company_id')->first();
            $company=Company::where('id',$company_id)->pluck('name')->first();
            $kin=$loan->kin;
            $kin_contacts=$loan->kin_contacts;


            $outstanding_loan=$loan->outstanding_loan;
            $outstanding_loan_org=$loan->outstanding_loan_org;
            $outstanding_loan_balance=$loan->outstanding_loan_balance; 

            return view('app.loan.step1',compact(
                'label',
                'first_name',
                'last_name',
                'contacts',
                'applicant_id',
                'contract_end',
                'company',
                'kin',
                'kin_contacts',
                'outstanding_loan',
                'outstanding_loan_org',
                'outstanding_loan_balance'
            ));
        }elseif($p==2){
            $loan = Loan::where('user_id', Auth::id())->whereIn('progress', ['1', '2', '3', '4'])->orderBy('id', 'desc')->first();
            $gross=$loan->gross_salary;
            $net=$loan->net_salary;
            $allowance=$loan->other_allowances;
            $outstanding_loan=$loan->outstanding_loan;
            $outstanding_loan_org=$loan->outstanding_loan_org;
            $outstanding_loan_balance=$loan->outstanding_loan_balance; 
            return view('app.loan.step2',compact(
                'label',
                'gross',
                'net',
                'allowance',
                'outstanding_loan',
                'outstanding_loan_org',
                'outstanding_loan_balance'
            ));
        }elseif($p==3){
            $loan = Loan::where('user_id', Auth::id())->whereIn('progress', ['1', '2', '3', '4'])->orderBy('id', 'desc')->first();
            $loan_amt=$loan->requested_loan_amount;
            $period=$loan->payment_period;
            $installment=$loan->monthly_installments;
            $reason=$loan->loan_reason;
            //Check for doc uploads
            $file_name=$loan->file_name;
            $file_path=$loan->supporting_doc_file;
            if($file_name==NULL || empty($file_name)){$is_upload=0; $file_name="";}else{$is_upload=1;}

            // Get Payment periods
            $company_id=Access::where('user_id',Auth::id())->pluck('company_id')->first();
            // Retrieve the company data
            $company = Company::find($company_id);

            // Store month fields that are not null
            $periods = array_filter([
                'month1' => $company->month1,
                'month2' => $company->month2,
                'month3' => $company->month3,
                'month4' => $company->month4,
                'month5' => $company->month5,
                'month6' => $company->month6,
                'month7' => $company->month7,
                'month8' => $company->month8,
                'month9' => $company->month9,
                'month10' => $company->month10,
                'month11' => $company->month11,
                'month12' => $company->month12
            ]);
            return view('app.loan.step3',compact(
                'label',
                'loan_amt',
                'period',
                'installment',
                'reason',
                'is_upload',
                'file_name',
                'periods',
            ));
        }elseif($p==4){
            $loan = Loan::where('user_id', Auth::id())->whereIn('progress', ['1', '2', '3', '4'])->orderBy('id', 'desc')->first();
            $contacts=$loan->contacts;
            //Getting bank details
            // $bank=UserBank::where('user_id',Auth::id())->first();
            // $bank_name=Bank::where('id',$bank->bank_id)->pluck('name')->first();
            // $bank_acc=$bank->account_number;
            // $bank_branch=$bank->branch;
            // $user_name=$bank->user_account_name;
            $loan_id=$loan->id;
            //Installment
            $installment=$loan->monthly_installments;
            $installment_in_words=strtoupper($this->numberToWords($installment));

            $user_name=User::find(Auth::id())->first_name.' '.User::find(Auth::id())->last_name;


            return view('app.loan.step4',compact(
                'label',
                'loan_id',
                'contacts',
                // 'bank_name',
                // 'bank_acc',
                //'bank_branch',
                'user_name',
                'installment',
                'installment_in_words',
            ));
        }


        
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        // Use type to know where the update is coming from so as one can store info as per the progress
        $type=$request->type;
        if($type==1){//Store request for step 1
            //First check to see if the user has a recent loan application to avoid creatinf new duplicates
            if(Loan::where('user_id', Auth::id())->whereIn('progress', [1, 2, 3, 4])->exists()){ // Loan appliation already exists so just update the submitted details
                $loan_id=Loan::where('user_id',Auth::id())->whereIn('progress',[1, 2, 3, 4])->pluck('id')->last();
                Loan::where('id',$loan_id)->update([
                    'kin'=>$request->kin,
                    'kin_contacts'=>$request->kin_mobile,
                    // 'outstanding_loan'=>$request->outstanding_loan,
                    // 'outstanding_loan_org'=>$request->outstanding_loan==0?NULL:$request->financial_institution,
                    // 'outstanding_loan_balance'=>$request->outstanding_loan==0?NULL:$request->loan_bal,
                ]);
                //Redirect user to step 2
                return redirect()->route('loan.index',['p'=>2]);
            }else{ // proceed to create new loan application
                Loan::create([
                    'user_id'=>Auth::id(),
                    'applicant_id'=>$request->applicant_id,
                    'company_id'=>Company::where('name',$request->company)->pluck('id')->first(),
                    'contacts'=>$request->contacts,
                    'alternative_contacts'=>User::where('id',Auth::id())->pluck('alternative_contacts')->first(),
                    'kin'=>$request->kin,
                    'kin_contacts'=>$request->kin_mobile,
                    // 'outstanding_loan'=>$request->outstanding_loan,
                    // 'outstanding_loan_org'=>$request->outstanding_loan==0?NULL:$request->financial_institution,
                    // 'outstanding_loan_balance'=>$request->outstanding_loan==0?NULL:$request->loan_bal,
                    'progress'=>2
                ]);
                //Redirect user to step 2
                return redirect()->route('loan.index');
            }
        }elseif($type==2){ // Loan appliation already exists so just update the submitted details
            $loan_id=Loan::where('user_id',Auth::id())->whereIn('progress',[1, 2, 3, 4])->pluck('id')->last();
            Loan::where('id',$loan_id)->update([
                'gross_salary'=>$request->gross,
                'net_salary'=>$request->net,
                'other_allowances'=>$request->allowance,
                'outstanding_loan'=>$request->outstanding_loan,
                'outstanding_loan_org'=>$request->outstanding_loan==0?NULL:$request->financial_institution,
                'outstanding_loan_balance'=>$request->outstanding_loan==0?NULL:$request->loan_bal,
                'progress'=>3,
            ]);

            return redirect()->route('loan.index');

        }elseif($type==3){
            $loan_id=Loan::where('user_id',Auth::id())->whereIn('progress',[1, 2, 3, 4])->pluck('id')->last();
            $upd=Loan::where('id',$loan_id)->update([
                'requested_loan_amount'=>$request->loan,
                'payment_period'=>$request->period,
                'monthly_installments'=>ceil($request->installments),
                'amount_payable'=>ceil($request->installments*$request->period),
                'loan_reason'=>$request->desc,
                'progress'=>4, 
            ]);
            if($upd){// Upload supproting document
                $file = $request->file("payslip");
                if($file){  //Check if form was submitted with the file
                    $file_name = $file->getClientOriginalName();
                    //Delete Old Image if it exists
                    $id=Auth::id();
                    if(Loan::where('user_id',$id)->where('file_name',$file_name)->latest()->exists()){  //Check if doc exists
                        $get_file=Loan::where('user_id',$id)->latest()->value('supporting_doc_file'); //Get file_name or path of file in db column defined as path so that you can define it in url below
                        $loan_id=Loan::where('user_id',$id)->pluck('id')->last();
                        $url='uploads/supporting_docs/'.$get_file;
                        if(file_exists($url)){
                            @unlink($url); //Removes the file
                        }
                        Loan::where('id',$loan_id)->update(['supporting_doc_file'=>NULL,'file_name'=>NULL]);
                    } 
                    //Add New File image
                    $fileName=time()."_".$id."_".$file->getClientOriginalName();
                    $file->move('uploads/supporting_docs/',$fileName);

                    Loan::where('id',$loan_id)->update(['supporting_doc_file'=>$fileName,'file_name'=>$file_name]);
                }
            }

            return redirect()->route('loan.index');
            
        }elseif($type==4){//Process the final step of submitting the loan
            $agreement=$request->agreement;
            $irrevocableAgreement=$request->irrevocableAgreement;
            //Check if the user actuall submitted with the terms checked
            if($agreement==1 and $irrevocableAgreement==1){// Proceed to submit the loan request
                $loan_id=$request->loan_id;
                $submit=Loan::where('id',$loan_id)->update([
                    'approval_level'=>1,
                    'progress'=>5,
                    'agreed_terms'=>1,
                    'consent_to_irrevocable_authority'=>1,
                ]);
                if($submit){
                    //Send mail to hr for approval
                    $link=env('APP_URL');
                    $company_id=Access::where('user_id',Auth::id())->pluck('company_id')->first();
                    $hrs=Access::select('id','user_id','company_id')->where('company_id',$company_id)->get();
                    $emails=[];
                    foreach($hrs as $hr){
                        $hr_id=$hr->user_id;
                        $check=User::where('role_type','hro')->where('id',$hr_id)->exists();
                        if(!$check){
                            continue;
                        }
                        $hr_user=User::find($hr_id);
                        $role=$hr_user->role_type;
                        if($role=='hro'){
                            $email=$hr_user->email ?? $hr_user->alternative_contacts;
                            //array_push($email,['email'=>$email]);
                            //send mail
                            $recipient=$email;
                            Mail::to($recipient)->send(new ApproveLoanMail($link, $recipient));
                        }
                    }
                    session()->flash('message','Your Loan request has been submitted successfully. Awaiting Approval.');
                   return redirect()->route('dashboard');
                }else{
                    session()->flash('error','Oops! An Error occurred while submitting your request. Please try again later.');
                    return back();
                }
            }else{
                session()->flash('error','You must agree to the terms and Irrevocable Agreement to continue with the loan submission.');
                return back();
            }
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }


    public function numberToWords($number)
    {
        $number=round($number);
        $words = '';
    
        $ones = array(
            0 => 'zero', 1 => 'one', 2 => 'two', 3 => 'three', 4 => 'four',
            5 => 'five', 6 => 'six', 7 => 'seven', 8 => 'eight', 9 => 'nine',
            10 => 'ten', 11 => 'eleven', 12 => 'twelve', 13 => 'thirteen', 14 => 'fourteen',
            15 => 'fifteen', 16 => 'sixteen', 17 => 'seventeen', 18 => 'eighteen', 19 => 'nineteen'
        );
    
        $tens = array(
            20 => 'twenty', 30 => 'thirty', 40 => 'forty', 50 => 'fifty',
            60 => 'sixty', 70 => 'seventy', 80 => 'eighty', 90 => 'ninety'
        );
    
        if ($number < 20) {
            $words = $ones[$number];
        } elseif ($number < 100) {
            $words = $tens[floor($number / 10) * 10];
            $remainder = $number % 10;
            if ($remainder > 0) {
                $words .= '-' . $ones[$remainder];
            }
        } elseif ($number < 1000) {
            $words = $ones[floor($number / 100)] . ' hundred';
            $remainder = $number % 100;
            if ($remainder > 0) {
                $words .= ' ' . $this->numberToWords($remainder);
            }
        } elseif ($number < 1000000) {
            $words = $this->numberToWords(floor($number / 1000)) . ' thousand';
            $remainder = $number % 1000;
            if ($remainder > 0) {
                $words .= ' ' . $this->numberToWords($remainder);
            }
        } elseif ($number < 10000000) {
            $words = $this->numberToWords(floor($number / 1000000)) . ' million';
            $remainder = $number % 1000000;
            if ($remainder > 0) {
                $words .= ' ' . $this->numberToWords($remainder);
            }
        } else {
            $words = 'number too large to convert to words';
        }
    
        return $words;
    }


    public function loan_requests(Request $request){
        $label="Loan Requests";
        $page_number=$request->page;

        if($page_number==1){
            $page_number=1;
        }elseif($page_number>1){
            $page_number=(($page_number-1)*50)+1;
        }else{
            $page_number=1;
        }
        //$loans=[];

        $data = Loan::whereNotIn('progress', [1, 2, 3, 4])->latest('id')->paginate(50);

        $data->getCollection()->transform(function ($d) {
            $user = User::find($d->user_id); // Use `find` instead of `findOrFail` to avoid throwing an error if user is not found
            $date = Carbon::parse($d->created_at)->format('jS F Y');
            $scheme = Company::where('id', $d->company_id)->pluck('name')->first();
            //Checking approval level
            if($d->approval_level==0 and ($d->final_decision==1 || $d->final_decision==2 || $d->final_decision==3)){
                $level=5;
            }else{
                $level=$d->approval_level;
            }
        
            return [
                'loan_id' => $d->id,
                'user_name' => $user ? $user->first_name . " " . $user->last_name : 'N/A',
                'email' => $user ? $user->email : 'N/A',
                'date' => $date,
                'amount' => $d->requested_loan_amount,
                'installments' => $d->monthly_installments,
                'period' => $d->payment_period,
                'scheme' => $scheme,
                'status'=>$d->final_decision,
                'level'=>$level
            ];
        });

        //return $loans;


        $loans = Loan::where(function($query) {
            $query->where('progress', 5)
                  ->orWhere('progress', 0);
        })
        ->latest()->paginate(50)
        
        ->map(function ($loan) {
            // Adding user name as an additional field
            $loan->company=Company::where('id',$loan->company_id)->pluck('name')->first();    
            return $loan;
        });

        //return $loans;

        return view('app.admin.loan_requests',compact('label','loans','page_number','data'));
    }

}
