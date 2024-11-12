<?php

namespace App\Http\Controllers\app;

use App\Http\Controllers\Controller;
use App\Models\Company;
use App\Models\Invoice;
use App\Models\Loan;
use App\Models\Repayment;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ReportsController extends Controller
{
    public function all_invoices(Request $request){
        $label="Repayments";
        
        $month=$request->month;
        $year=$request->year;
        $company=$request->company;
        $check=false;

        if(!$company and $year and $month){
            $month=date('F', strtotime($month . ' 1'));
            $year=$year;
            $for_date=$month." ".$year;
        }elseif($year and $month and $company){
            $month=date('F',strtotime($month.' 1'));
            $year=$year;
            $for_date=$month." ".$year;
            $check=true;
            $s_id=Company::where('name',$company)->pluck('id')->first();
        }else{
            $date=Carbon::now();
            $month=$date->format('F');
            $year=$date->format('Y');
            $for_date=$month." ".$year;
        }

        $data=[];
        $schemes=Company::all();

        //Summations
        $tot_expected_payments=0;
        $tot_amt_paid=0;
        $tot_monthly_installments=0;

        //New Algo
        foreach($schemes as $s){
            $scheme_id=$s->id;
            $scheme_name=Company::where('id',$scheme_id)->pluck('name')->first();
            $payments=Repayment::where('company_id',$scheme_id)->where('month',$month)->where('year',$year)->get();
            
            if(Repayment::where('company_id',$scheme_id)->where('month',$month)->where('year',$year)->where('status',0)->exists()){
                $status=0;
            }
            if(Repayment::where('company_id',$scheme_id)->where('month',$month)->where('year',$year)->where('status',1)->exists()){
                $status=1;
            }
            if(Repayment::where('company_id',$scheme_id)->where('month',$month)->where('year',$year)->where('status',2)->exists()){
                $status=2;
            }

            $tot_expected_payment=Repayment::where('company_id',$scheme_id)->where('month',$month)->where('year',$year)->sum('installments');
            $tot_amt_paid1=Repayment::where('company_id',$scheme_id)->where('month',$month)->where('year',$year)->where('status',1)->sum('installments');
            if($tot_expected_payment>1){
                array_push($data,[
                    'scheme'=>$scheme_name,
                    'mi'=>$tot_expected_payment,
                    'status'=>$status
                ]);
            }
            $tot_expected_payments+=$tot_expected_payment;
            $tot_amt_paid+=$tot_amt_paid1;
            $tot_monthly_installments=$tot_expected_payments;
            if($check and $s_id==$s->id){
                $data=[];
                $tot_expected_payments=Repayment::where('company_id',$scheme_id)->where('month',$month)->where('year',$year)->sum('installments');
                $tot_monthly_installments=$tot_expected_payments;
                array_push($data,[
                    'scheme'=>$scheme_name,
                    'mi'=>$tot_expected_payment,
                    'status'=>$status
                ]);
                break;
            }
        }
        //End to new algo

        //$for_date=$date->format('F Y');
        //$date_selected=$date->format('Y-m-d');

        return view('app.reports.all_invoices',compact(
            'label',
            'data',
            'tot_expected_payments',
            'tot_amt_paid',
            'for_date',
            //'date_selected',
            'tot_monthly_installments'
        ));
    }


    public function scheme_report(Request $request){
        $label="";
        $data=[]; 

        $from=$request->from;
        $to=$request->to;
        $date=$request->query('date_selected');
        $date_m=$request->query('date_selected');
        $company=$request->company;
        $company_name=$request->company;
        $from=Carbon::parse($from)->startOfMonth();     
        $to=Carbon::parse($to);
        //$to=$to->addMonth();
        $from_original=Carbon::parse($from);
        $to_original=Carbon::parse($to);

        //Summations
        $tot_loan=0;
        $tot_expected_payments=0;
        $tot_amt_paid=0;
        $tot_monthly_installments=0;

        //CHECK IF THERE IS DATA
        if($from and $company and $to){
            $empty=false;

        //Loop throough the months 
        if ($to->lessThan($from)) {
            $to->addYear();
        }

        $months = [];
        while ($from->lessThanOrEqualTo($to)) {
            $months[] = $from->copy();  // Month and year (e.g., "January 2024")
            $from->addMonth();
        }

        // Output the months
        foreach ($months as $date) {
            $month = $date->format('m'); // Get month as a two-digit number (e.g., '01' for January)
            $month_f=$date->format('F');
            $year = $date->format('Y');  // Get the year (e.g., '2024')
            //Summations
            $sum_loan_amt=0;
            $sum_interest=0;
            $sum_amt_payable=0;
            $loan_requests=0;
            $mi=0;
            $status=0;
            $date_paid="N/A";
            //check if invoice exists if not create a new invoice number
            $company_id=Company::where('name',$company)->pluck('id')->first();
            $invoice_number="00".$company_id."-".$date->format('F')."-".$year;
            $check=Invoice::where('invoice_number',$invoice_number)->exists();
            if($check){// there is an invoice already created for that month. perfom additional check as required

                //Get date paid
                $date_paid=Invoice::where('invoice_number',$invoice_number)->where('status',1)->pluck('date_paid')->first();
                if($date_paid){
                    $date_paid=Carbon::parse($date_paid);
                    $date_paid=$date_paid->format('jS F Y');
                }else{
                    $date_paid="N/A";
                }
                $invoice_date=Carbon::parse(Invoice::where('invoice_number',$invoice_number)->pluck('invoice_date')->first());

                $for_date=$invoice_date->format('F Y');
                $date_selected=$invoice_date->format('Y-m-d');

                //Get Loan data for that scheme
                $loan_data=Loan::select('id','requested_loan_amount','payment_period','monthly_installments','approver3_date')
                ->where('company_id',$company_id)->where('final_decision',1)->whereMonth('approver3_date', $month)
                ->whereYear('approver3_date', $year)->get();

            }else{//Create a new invoice number which just pick the one in the check

                $invoice_date=Carbon::parse($request->query('date_selected1'));
                //$for_date=$invoice_date->format('F Y');
                $for_date=$from_original->format('F Y')." to ".$to_original->format('F Y');
                $date_selected=$invoice_date->format('Y-m-d');

                //Get Loan data for that scheme
                $loan_data=Loan::select('id','requested_loan_amount','payment_period','monthly_installments','approver3_date')
                ->where('company_id',$company_id)->where('final_decision',1)->whereMonth('approver3_date', $month)
                ->whereYear('approver3_date', $year)->get();
            }

                $scheme_id=Company::where('name',$company)->pluck('id')->first();

                //New Algo
                $loan_requests=Repayment::where('company_id',$scheme_id)->where('month',$month_f)->where('year',$year)->count();
                $mi=Repayment::where('company_id',$scheme_id)->where('month',$month_f)->where('year',$year)->sum('installments');

                //$sum_loan_amt=Repayment::where('month',$month_f)->where('year',$year)->sum('loan_amount');
                $sum_loan_amt=Loan::where('company_id',$company_id)->where('final_decision',1)->whereMonth('approver3_date',$month)->
                        whereYear('approver3_date',$year)->sum('requested_loan_amount');
                $amt_paid=Repayment::where('company_id',$company_id)->where('month',$month_f)->where('year',$year)->where('status',1)->sum('installments');

                if(Repayment::where('company_id',$scheme_id)->where('month',$month_f)->where('year',$year)->where('status',0)->exists()){
                    $status=0;
                }
                if(Repayment::where('company_id',$scheme_id)->where('month',$month_f)->where('year',$year)->where('status',1)->exists()){
                    $status=1;
                }
                if(Repayment::where('company_id',$scheme_id)->where('month',$month_f)->where('year',$year)->where('status',2)->exists()){
                    $status=2;
                }

                $mi=Repayment::where('company_id',$company_id)->where('month',$month_f)->where('year',$year)->sum('installments');

                array_push($data,[
                    'scheme_id'=>$scheme_id,
                    'mi'=>ceil($mi),
                    'invoice_number'=>$invoice_number,
                    'requests'=>ceil($loan_requests),
                    'loan_amt'=>ceil($sum_loan_amt),
                    'interest'=>ceil(1),
                    'amt_payable'=>ceil(1),
                    'status'=>$status,
                    'amount_paid'=>$amt_paid,
                    'date_paid'=>$date_paid,
                ]);

                $tot_loan+=$sum_loan_amt;
                $tot_monthly_installments+=$mi;
                $tot_amt_paid+=$amt_paid;
                //End to new Algo
        }
        //return $tot_monthly_installments1;

        }else{
            $empty=true;
            return view('app.reports.scheme_invoices',compact('label','empty'));
        }

        return view('app.reports.scheme_invoices',compact(
            'label',
            'data',
            'tot_loan',
            //'tot_expected_payments',
            'tot_amt_paid',
            //'pl',
            //'summary',
            //'net',
            //'netpl',
            'for_date',
            'tot_monthly_installments',
            'date_selected',
            'empty',
            'company_name'
        ));
    }




    public function invoice_report(Request $request,$id){
        $label="";
        $users=[];

        //get month and year
        $invoice_number=$request->invoice_number;
        $pick_date=explode('-',$invoice_number);
        $month=$pick_date[1];
        $year=$pick_date[2];

        //Date to be used for where filters
        $monthNumber=date('m', strtotime($month . ' 1'));

        $company=Company::where('id',$id)->pluck('name')->first();
        $date=$month." ".$year;

        //Check if invoice exists
        $check=Invoice::where('invoice_number',$invoice_number)->exists();
        $sum=0;
        if($check){
            $status=Invoice::where('invoice_number',$invoice_number)->pluck('status')->first();
            $rs=Repayment::where('company_id',$id)->where('month',$month)->where('year',$year)->get();
            //$loans=Loan::where('company_id',$id)->whereMonth('approver3_date',$monthNumber)
            //->whereYear("approver3_date",$year)->get();
        }else{//Invoice does not exist
            $status=0;
            $rs=Repayment::where('company_id',$id)->where('month',$month)->where('year',$year)->get();
            // $loans=Loan::where('company_id',$id)->whereMonth('approver3_date',$monthNumber)
            // ->whereYear("approver3_date",$year)->get();
        }
        //return count($loans);

        //Get month and year from invoice number
        $a=explode('-',$invoice_number);
        $month=$a[1];
        $year=$a[2];

        foreach($rs as $loan){
            $user_id=$loan->user_id;
            $user=User::find($user_id);
            $name=$user->first_name." ".$user->last_name;
            $loan_amt=$loan->loan_amount;
            $installments=$loan->installments;

            //Check if user has a partial payment status
            $p_status=Repayment::where('id',$loan->id)->pluck('status')->first();
            if($p_status==2){
                $user_partial_status=true;
            }else{
                $sum+=$installments;
                $user_partial_status=false;
            }

            array_push($users,[
                'name'=>$name,
                'contacts'=>$user->contacts,
                'loan_amt'=>$loan_amt,
                'installments'=>$installments,
                'user_partial_status'=>$user_partial_status
            ]);
        }

        //return $users;
       
        return view('app.reports.invoice_report',compact(
            'label',
            'users',
            'sum',
            'invoice_number',
            'company',
            'date',
            'status'
        ));
    }




    public function payment_status(Request $request){
        $label="";

                    
        //create invoice date that is for that month starting 5th
        $c=explode('-',$request->invoice_number);
        $month=$c[1];
        $month_f=$c[1];
        $month=date('m', strtotime($month . ' 1'));
        $year=$c[2];
        $invoice_date=$year."-".$month."-05";

        $type=$request->payment_status;
        if($type==1){//Paid
            //check if invoice exists first
            $check=Invoice::where('invoice_number',$request->invoice_number)->exists();
            if(!$check){
                $store=Invoice::create([
                    'invoice_number'=>$request->invoice_number,
                    'company_id'=>$request->company_id,
                    'staff_id'=>Auth::id(),
                    'loan_requests'=>$request->loan_requests,
                    'loan_amount'=>$request->loan_amt,
                    'payable_amount'=>0,//ceil($request->tot_expected_payments),
                    'amount_paid'=>ceil($request->amount_paid),
                    'status'=>$request->payment_status,
                    'invoice_date'=>$invoice_date,
                    'date_paid'=>$request->date_paid,
                    'comments'=>$request->comments
                ]);

                if($store){
                    //Update Repayments
                    Repayment::where('company_id',$request->company_id)->where('month',$month_f)->where('year',$year)->update([
                        'invoice_id'=>$store->id,
                        'status'=>1
                    ]);
                    //
                    session()->flash('message','The Invoice '.$request->invoice_number.' has been marked as Paid.');
                    return back();
                }else{
                    session()->flash('error','An unexpected errror occured. Please try again later');
                    return back();
                }
            }else{
                $update=Invoice::where('invoice_number',$request->invoice_number)->update([
                    'invoice_number'=>$request->invoice_number,
                    'company_id'=>$request->company_id,
                    'staff_id'=>Auth::id(),
                    'loan_requests'=>$request->loan_requests,
                    'loan_amount'=>$request->loan_amt,
                    'payable_amount'=>0,//ceil($request->tot_expected_payments),
                    'amount_paid'=>ceil($request->amount_paid),
                    'status'=>$request->payment_status,
                    'invoice_date'=>$invoice_date,
                    'date_paid'=>$request->date_paid,
                    'comments'=>$request->comments

                ]);
                if($update){
                    //Update Repayments
                    Repayment::where('company_id',$request->company_id)->where('month',$month_f)->where('year',$year)->update([
                        //'invoice_id'=>$store->id,
                        'status'=>1
                    ]);
                    //
                    session()->flash('message','The Invoice '.$request->invoice_number.' has been marked as Paid.');
                    return back();
                }else{
                    session()->flash('error','An unexpected errror occured. Please try again later');
                    return back();
                }
            }
        }elseif($type==2){//Unpaid
            //check if invoice exists first
            $check=Invoice::where('invoice_number',$request->invoice_number)->exists();
            if(!$check){
                $store=Invoice::create([
                    'invoice_number'=>$request->invoice_number,
                    'company_id'=>$request->company_id,
                    'staff_id'=>Auth::id(),
                    'loan_requests'=>$request->loan_requests,
                    'loan_amount'=>$request->loan_amt,
                    'payable_amount'=>0,//$request->tot_expected_payments,
                    'status'=>$request->payment_status,
                    'invoice_date'=>$invoice_date,
                ]);

                if($store){
                    //Update Repayments
                    Repayment::where('company_id',$request->company_id)->where('month',$month_f)->where('year',$year)->update([
                        'invoice_id'=>$store->id,
                        'status'=>0
                    ]);
                    //
                    session()->flash('message','The Invoice '.$request->invoice_number.' has been marked as Unpaid.');
                    return back();
                }else{
                    session()->flash('error','An unexpected errror occured. Please try again later');
                    return back();
                }
            }else{
                $update=Invoice::where('invoice_number',$request->invoice_number)->update([
                    'staff_id'=>Auth::id(),
                    'amount_paid'=>0,
                    'status'=>$request->payment_status,
                    'date_paid'=>NULL,
                    'status'=>0

                ]);
                if($update){
                    //Update Repayments
                    Repayment::where('company_id',$request->company_id)->where('month',$month_f)->where('year',$year)->update([
                        //'invoice_id'=>$store->id,
                        'status'=>0
                    ]);
                    //
                    session()->flash('message','The Invoice '.$request->invoice_number.' has been marked as Unpaid.');
                    return back();
                }else{
                    session()->flash('error','An unexpected errror occured. Please try again later');
                    return back();
                }
            }
        }elseif($type==3){//Partially Paid
            $label="Make Partial Payment";
            $users=[];
            $id=$request->company_id;
    
            //get month and year
            $invoice_number=$request->invoice_number;
            $pick_date=explode('-',$invoice_number);
            $month=$pick_date[1];
            $year=$pick_date[2];
            //Date to be used for where filters
            $monthNumber=date('m', strtotime($month . ' 1'));
    
            $company=Company::where('id',$id)->pluck('name')->first();
            $date=$month." ".$year;
    
            //Check if invoice exists
            $check=Invoice::where('invoice_number',$invoice_number)->exists();
            $sum=0;
            if($check){
                $status=Invoice::where('invoice_number',$invoice_number)->pluck('status')->first();
                $rs=Repayment::where('company_id',$id)->where('month',$month)->where('year',$year)->get();
                // $loans=Loan::where('company_id',$id)->whereMonth('approver3_date',$monthNumber)
                // ->whereYear("approver3_date",$year)->get();
            }else{//Invoice does not exist
                $status=2;
                $rs=Repayment::where('company_id',$id)->where('month',$month)->where('year',$year)->get();
                // $loans=Loan::where('company_id',$id)->whereMonth('approver3_date',$monthNumber)
                // ->whereYear("approver3_date",$year)->get();
            }
    
            foreach($rs as $loan){
                $user_id=$loan->user_id;
                $user=User::find($user_id);
                $name=$user->first_name." ".$user->last_name;
                $loan_amt=$loan->loan_amount;
                $installments=$loan->installments;
                $sum+=$installments;
                $status=Repayment::where('id',$loan->id)->pluck('status')->first();
    
                array_push($users,[
                    'id'=>$loan->user_id,
                    'repayment_id'=>$loan->id,
                    'loan_id'=>$loan->id,
                    'name'=>$name,
                    'contacts'=>$user->contacts,
                    'loan_amt'=>$loan_amt,
                    'installments'=>$installments,
                    'status'=>$status
                ]);
            }
            //return $users;
    
            return view('app.reports.partial_payment',compact(
                'label',
                'users',
                'sum',
                'invoice_number',
                'company',
                'date',
                'status'
            ));
        }else{

        }

        return back();

    }



    public function payment_schedule(Request $request){
        $label="Repayment Schedule";

        $data=[];
        $payments_data=[];
        $temp_data=[];
        $payment_completion_status=[];

        $loan_data=Loan::select('id','user_id','company_id','requested_loan_amount','payment_period','monthly_installments',
        'amount_payable','approver3_date','final_decision')->where('user_id',Auth::id())->where('final_decision',1)->get();
        foreach($loan_data as $l){
            //check if user is within the repayment range$date_issued = $l->approver3_date;
            $date_issued=$l->approver3_date;
            $date_issued=Carbon::parse($date_issued);
            $temp_data=[];
           
            $period = $l->payment_period; // Period in months
            $current_date = $date_issued->copy(); // Assuming $date is the invoice date

            //New Algo
            $repayments=Repayment::where('loan_id',$l->id)->where('user_id',Auth::id())->get();
            foreach($repayments as $r){
                $date=$r->month." ".$r->year;
                $installments=$r->installments;
                $status=$r->status;

                array_push($temp_data,[
                    'loan_id'=>$l->id,
                    'date'=>$date,
                    'installments'=>$installments,
                    'status'=>$status
                ]);
            }
            array_push($payments_data,[
                'loan_id'=>$l->id,
                'data'=>$temp_data
            ]);
            //End algo

            $p_status=Repayment::where('loan_id',$l->id)->where('status',1)->sum('status');
            if($p_status==$l->payment_period){
                $status=1;
            }else{
                $status=0;
            }

            array_push($data,[
                'loan_id'=>$l->id,
                'date'=>$date_issued->format('jS F Y'),
                'amount'=>$l->requested_loan_amount,
                'installments'=>$l->monthly_installments,
                'period'=>$l->payment_period,
                'status'=>$status,
            ]);
        }
        //return response()->json(['msg'=>$payments_data]);
        return view('app.reports.payment_schedule',compact('label','data','payments_data'));
    }
    

    public function loans(Request $request){
        $label="";
        
        $from=$request->from;
        $to=$request->to;
        $company_name=$request->company;

        $data=[];
        $schemes=Company::all();

        //Summations
        $tot_loan=0;
        $tot_expected_payments=0;
        $tot_amt_paid=0;
        $tot_monthly_installments=0;

        foreach ($schemes as $s) {
            $scheme_id=$s->id;
            $scheme_name=$s->name;
            
            $sum_loan_amt=0;
            $sum_interest=0;
            $sum_amt_payable=0;
            $loan_requests=0;
            $mi=0;
            $status=2;
            $date_paid="N/A";


        if($from and $to and !$company_name){// Pick data for all companies within date range

            
            $from=Carbon::parse($from);
            $to=Carbon::parse($to);

            $m=$from->format('F');
            $n=$to->format('F');
            if($m==$n){
                $for_date=$from->format('F Y');
            }else{
                $for_date=$from->format('F Y')." to ".$to->format('F Y');
            }
            $date_selected=$to->format('Y-m-d');

            // //Get Loan data for that scheme
            $loan_data=Loan::select('id','requested_loan_amount','payment_period','monthly_installments','approver3_date')
            ->where('company_id',$scheme_id)->where('final_decision',1)->whereBetween('approver3_date',[$from,$to])
            ->get();
            $date=Carbon::now();

        }elseif($from and $to and $company_name){//Pick data for a specific company within specified range

            $from=Carbon::parse($from);
            $to=Carbon::parse($to);

            $m=$from->format('F');
            $n=$to->format('F');
            if($m==$n){
                $for_date=$from;
            }else{
                $for_date=$from->format('F Y')." to ".$to->format('F Y');
            }

            
            $date_selected=$to->format('Y-m-d');

            // //Get Loan data for that scheme
            $scheme_id=Company::where('name',$company_name)->pluck('id')->first();
            if(!$scheme_id){
                session()->flash('error','The company selected does not exist.');
                return back();
            }
            $date=Carbon::now();
            if($scheme_id==$s->id){
                $loan_data=Loan::select('id','requested_loan_amount','payment_period','monthly_installments','approver3_date')
                ->where('company_id',$scheme_id)->where('final_decision',1)->whereBetween('approver3_date',[$from,$to] )
                ->get();
            }else{
                continue;
            }

            
        }else{// Pick data for all companies for that month(current) only
             $date=Carbon::now();
             $date->format('Y-m-d');
             $month = $date->format('m'); // Extract month
             $year = $date->format('Y');  // Extract year

        
             $for_date=$date->format('F Y');
             $date_selected=$date->format('Y-m-d');

            //Get Loan data for that scheme
            $loan_data=Loan::select('id','requested_loan_amount','payment_period','monthly_installments','approver3_date')
            ->where('company_id',$scheme_id)->where('final_decision',1)->whereMonth('approver3_date', $month)
            ->whereYear('approver3_date', $year)->get();

        }

            foreach($loan_data as $l){
                $req_amt=$l->requested_loan_amount;
                $period=$l->payment_period;
                $installment=$l->monthly_installments;
                $amt=$period*$installment;
                $interest=$amt-$req_amt;

                //check if user is within the repayment range$date_issued = $l->approver3_date;
                $date_issued=$l->approver3_date;
                $date_issued = Carbon::parse($date_issued);
                $period = $l->payment_period; // Period in months
                $current_date = Carbon::parse($date); // Assuming $date is the invoice date

                // Calculate the end date of the repayment period
                $end_date = $date_issued->copy()->addMonths($period);

                // Check if the current date is within the repayment period
                if ($current_date->between($date_issued, $end_date)) {
                    // The current date is within the repayment period

                } else {
                    // The current date is outside the repayment period
                    continue;
                }
                

                //Check Invoice Payment
                $month = $date->format('m'); // Extract month
                $year = $date->format('Y');  // Extract year
                $invoices = Invoice::where('company_id',$scheme_id)->whereMonth('invoice_date', $month)
                ->whereYear('invoice_date', $year)
                ->first();
                if($invoices){
                    $status=$invoices->status;
                    $invoice_amt_paid=$invoices->amount_paid;
                    $tot_amt_paid=$invoice_amt_paid;
                    $date_paid=$invoices->date_paid;
                    if($date_paid){
                        $date_paid=Carbon::parse($date_paid);
                        $date_paid=$date_paid->format('jS F Y');
                    }else{
                        $date_paid="N/A";
                    }
                }else{
                    $status=2;
                    $date_paid="N/A";
                }
                
                $sum_loan_amt+=$req_amt;
                $sum_interest+=$interest;
                $sum_amt_payable+=$amt;
                $tot_monthly_installments+=$installment;
                $mi+=$installment;
                $loan_requests++;
            }

            if (!$loan_data->isEmpty()) {

                array_push($data,[
                    'scheme_id'=>$scheme_id,
                    'scheme'=>$scheme_name,
                    'mi'=>ceil($mi),
                    'requests'=>ceil($loan_requests),
                    'loan_amt'=>ceil($sum_loan_amt),
                    'interest'=>ceil($sum_interest),
                    'amt_payable'=>ceil($sum_amt_payable),
                    'status'=>$status,
                    'date_paid'=>$date_paid,
                ]);
            }

            $users=Loan::where('company_id',$scheme_id)->count();
            $loan_amt=Loan::where('company_id',$scheme_id)->sum('requested_loan_amount');

            //Increement the total Summation for all
            $tot_loan+=$sum_loan_amt;
            $tot_expected_payments+=$sum_amt_payable;
        }

        $pl=$tot_expected_payments-$tot_loan;
        if($pl>0){
            $net=true;
            $summary="Profit";
            $netpl=$tot_expected_payments-$tot_loan;
        }else{
            $net=false;
            $summary="Loss";
            $netpl=$tot_expected_payments-$tot_loan;
        }
        //return $data;
        return view('app.reports.loans',compact(
            'label',
            'data',
            'tot_loan',
            'tot_expected_payments',
            'tot_amt_paid',
            'pl',
            'summary',
            'net',
            'netpl',
            'for_date',
            'date_selected',
            'tot_monthly_installments'
        ));  
    }



    public function scheme_perfomance(Request $request){
        $label="";

        return view('app.reports.scheme_report',compact('label'));
    }


    public function partial_payment(Request $request){
        $type=$request->payment_status;
        $company_id=Company::where('name',$request->company)->pluck('id')->first();
        $invoice_number=$request->invoice_number;
        //Get month and year
        $m=explode('-',$invoice_number);
        $month=$m[1];
        $year=$m[2];

        $loan_id=$request->loan_id;
        
        if($type==0){//Make a partial payment
            //Create invoice for partial payment if does not exist
            $check=Invoice::where('invoice_number',$invoice_number)->exists();
            if(!$check){
                Invoice::create([
                    'invoice_number'=>$request->invoice_number,
                    'company_id'=>$request->company_id,
                    'staff_id'=>Auth::id(),
                    'loan_requests'=>$request->loan_requests,
                    'loan_amount'=>$request->loan_amt,
                    'payable_amount'=>0,//$request->tot_expected_payments,
                    'status'=>2,
                    'invoice_date'=>Carbon::now(),
                ]);
            }else{
                Invoice::where('invoice_number',$invoice_number)->update([
                    'status'=>2,
                ]);
            }
            //return $loan_id;
            //Update Repayments
            Repayment::where('id',$request->repayment_id)->update([
                'status'=>2,
                'comments'=>$request->desc
            ]);

            //Mark the remaining users as paid
            Repayment::where('company_id',$company_id)->where('month',$month)->where('year',$year)->where('status', '!=', 2)->update([
                'status'=>1,
            ]);
        }elseif($type==1){// Make Full payment
            //Create invoice for Full payment if does not exist
            $check=Invoice::where('invoice_number',$invoice_number)->exists();
            if(!$check){
                Invoice::create([
                    'invoice_number'=>$request->invoice_number,
                    'company_id'=>$request->company_id,
                    'staff_id'=>Auth::id(),
                    'loan_requests'=>0,//$request->loan_requests,
                    'loan_amount'=>0,//$request->loan_amt,
                    'payable_amount'=>0,//$request->tot_expected_payments,
                    'status'=>$request->payment_status,
                    'invoice_date'=>Carbon::now(),
                ]);
            }else{
                // Invoice::where('invoice_number',$invoice_number)->update([
                //     'status'=>1,
                // ]);
            }
            //Update Repayments
            Repayment::where('id',$request->repayment_id)->update([
                'status'=>1,
                'comments'=>$request->desc
            ]);
            // Check if the remaining user has any partial payment so as to mark the invoice as fully paid
            $check=Repayment::where('company_id',$company_id)->where('month',$month)->where('year',$year)->where('status',2)->exists();
            if(!$check){//Mark the invoice to be fully paid
                Invoice::where('invoice_number',$invoice_number)->update([
                    'status'=>1
                ]);
                session()->flash('message','The Invoice '.$request->installments.' has been marked as Fully paid.');
                return back();
            }
        }
        session()->flash('message','The loan '.$request->installments.' has been marked as Partially paid.');
        return back();
    }


}
