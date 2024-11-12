<?php

namespace App\Http\Controllers\app;

use App\Http\Controllers\Controller;
use App\Models\Company;
use App\Models\Invoice;
use App\Models\Loan;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ReportsController extends Controller
{
    public function all_invoices(Request $request){
        $label="Invoice";
        
        $from=$request->from;
        $to=$request->to;

        if($from and $to){
            $date=Carbon::now();
            // $data = Audit::select('id', 'user_id', 'product_id', 'status', 'comments', 'created_at')
            // ->whereBetween('created_at', [$from, $to])
            // ->get();
        }else{
             $date=Carbon::now();
             $date->format('Y-m-d');
             $month = $date->format('m'); // Extract month
             $year = $date->format('Y');  // Extract year
             $invoices = Invoice::whereMonth('invoice_date', $month)
             ->whereYear('invoice_date', $year)
             ->get();
        }

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

            //Get Loan data for that scheme
            $loan_data=Loan::select('id','requested_loan_amount','payment_period','monthly_installments','approver3_date')
            ->where('company_id',$scheme_id)->where('final_decision',1)->whereMonth('approver3_date', $month)
            ->whereYear('approver3_date', $year)->get();
            if ($loan_data->isEmpty()) {
                continue;
            }
            
            $sum_loan_amt=0;
            $sum_interest=0;
            $sum_amt_payable=0;
            $loan_requests=0;
            $mi=0;
            $status=2;
            $date_paid="N/A";
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
        
        $for_date=$date->format('F Y');
        $date_selected=$date->format('Y-m-d');

        return view('app.reports.all_invoices',compact(
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
            $year = $date->format('Y');  // Get the year (e.g., '2024')
            //check if invoice exists if not create a new invoice number
            $company_id=Company::where('name',$company)->pluck('id')->first();
            $invoice_number="00".$company_id."-".$date->format('F')."-".$year;
            $check=Invoice::where('invoice_number',$invoice_number)->exists();
            if($check){// there is an invoice already created for that month. perfom additional check as required
                //Get Loan data for that scheme
                $loan_data=Loan::select('id','requested_loan_amount','payment_period','monthly_installments','approver3_date')
                ->where('company_id',$company_id)->where('final_decision',1)->whereMonth('approver3_date', $month)
                ->whereYear('approver3_date', $year)->get();

            }else{//Create a new invoice number which just pick the one in the check
                //Get Loan data for that scheme
                $loan_data=Loan::select('id','requested_loan_amount','payment_period','monthly_installments','approver3_date')
                ->where('company_id',$company_id)->where('final_decision',1)->whereMonth('approver3_date', $month)
                ->whereYear('approver3_date', $year)->get();
            }

                $scheme_id=Company::where('name',$company)->pluck('id')->first();
        
                $sum_loan_amt=0;
                $sum_interest=0;
                $sum_amt_payable=0;
                $loan_requests=0;
                $mi=0;
                $status=2;
                $date_paid="N/A";
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
                    $current_date = Carbon::parse($date_m); // Assuming $date is the invoice date
        
                    // Calculate the end date of the repayment period
                    $end_date = $date_issued->copy()->addMonths($period);
        
                    //Inclusive of months 
                    $date_issued = Carbon::parse($l->approver3_date)->startOfMonth(); // Start of the issue month
                    $period = $l->payment_period; // Period in months
                    $end_date = $date_issued->copy()->addMonths($period)->endOfMonth(); // End of the repayment period month
        
                    $current_date = Carbon::parse($date)->endOfMonth(); // Set current date to the end of its month
        
                    // Check if the current date is within the repayment period (inclusive)
                    if ($current_date->between($date_issued, $end_date)) {
                        // The current date is within the repayment period, inclusive of the month
                        // Your logic here
                    } else {
                        // The current date is outside the repayment period
                        continue;
                    }
        
                    // Check if the current date is within the repayment period
                    if ($current_date->between($date_issued, $end_date)) {
                        // The current date is within the repayment period
        
                    } else {
                        // The current date is outside the repayment period
                        continue;
                    }
        
                    //Check Invoice Payment
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
                
                        //Invoice number
                        $invoice_number=$invoices->invoice_number;
                        $invoice_date=Carbon::parse($invoices->invoice_date);
                        $for_date=$invoice_date->format('F Y');
                        $date_selected=$invoice_date->format('Y-m-d');
                    }else{
                        $status=2;
                        $date_paid="N/A";
                
                        //Invoice number
                        $invoice_date=Carbon::parse($request->query('date_selected1'));
                        //$for_date=$invoice_date->format('F Y');
                        $for_date=$from_original->format('F Y')." to ".$to_original->format('F Y');
                        $date_selected=$invoice_date->format('Y-m-d');
                    }
                    
                    $sum_loan_amt+=$req_amt;
                    $sum_interest+=$interest;
                    $sum_amt_payable+=$amt;
                    $tot_monthly_installments+=$installment;
                    $mi+=$installment;
                    $loan_requests++;
                }
                if($sum_amt_payable==0){
                    $status=4;
                }
                array_push($data,[
                    'scheme_id'=>$scheme_id,
                    'mi'=>ceil($mi),
                    'invoice_number'=>$invoice_number,
                    'requests'=>ceil($loan_requests),
                    'loan_amt'=>ceil($sum_loan_amt),
                    'interest'=>ceil($sum_interest),
                    'amt_payable'=>ceil($sum_amt_payable),
                    'status'=>$status,
                    'date_paid'=>$date_paid,
                ]);
        }

        }else{
            $empty=true;
            return view('app.reports.scheme_invoices',compact('label','empty'));
        }


        //return $data;

        $loan_amt=Loan::where('company_id',$scheme_id)->sum('requested_loan_amount');

        //Increement the total Summation for all
        $tot_loan+=$sum_loan_amt;
        $tot_expected_payments+=$sum_amt_payable;
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

        return view('app.reports.scheme_invoices',compact(
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
            $loans=Loan::where('company_id',$id)->whereMonth('approver3_date',$monthNumber)
            ->whereYear("approver3_date",$year)->get();
        }else{//Invoice does not exist
            $status=2;
            $loans=Loan::where('company_id',$id)->whereMonth('approver3_date',$monthNumber)
            ->whereYear("approver3_date",$year)->get();
        }

        foreach($loans as $loan){
            $user_id=$loan->user_id;
            $user=User::find($user_id);
            $name=$user->first_name." ".$user->last_name;
            $loan_amt=$loan->requested_loan_amount;
            $installments=$loan->monthly_installments;
            $sum+=$installments;

            array_push($users,[
                'name'=>$name,
                'contacts'=>$user->contacts,
                'loan_amt'=>$loan_amt,
                'installments'=>$installments
            ]);
        }
        

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
                    'payable_amount'=>ceil($request->tot_expected_payments),
                    'amount_paid'=>ceil($request->amount_paid),
                    'status'=>$request->payment_status,
                    'invoice_date'=>$invoice_date,
                    'date_paid'=>$request->date_paid,
                    'comments'=>$request->comments
                ]);

                if($store){
                    session()->flash('message','The Loan '.$request->invoice_number.' has been marked as Paid.');
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
                    'payable_amount'=>ceil($request->tot_expected_payments),
                    'amount_paid'=>ceil($request->amount_paid),
                    'status'=>$request->payment_status,
                    'invoice_date'=>$invoice_date,
                    'date_paid'=>$request->date_paid,
                    'comments'=>$request->comments

                ]);
                if($update){
                    session()->flash('message','The Loan '.$request->invoice_number.' has been marked as Paid.');
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
                    'payable_amount'=>$request->tot_expected_payments,
                    'status'=>$request->payment_status,
                    'invoice_date'=>$invoice_date,
                ]);

                if($store){
                    session()->flash('message','The Loan '.$request->invoice_number.' has been marked as Unpaid.');
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
                    'date_paid'=>NULL

                ]);
                if($update){
                    session()->flash('message','The Loan '.$request->invoice_number.' has been marked as Unpaid.');
                    return back();
                }else{
                    session()->flash('error','An unexpected errror occured. Please try again later');
                    return back();
                }
            }
        }elseif($type==3){//Partially Paid
            $label="Make Partial Payment";
            return view('app.reports.partial_payment',compact('label'));
        }else{

        }

        return back();

    }



    public function payment_schedule(Request $request){
        $label="Repayment Schedule";

        $data=[];
        $payments_data=[];
        $payment_completion_status=[];

        $loan_data=Loan::select('id','user_id','company_id','requested_loan_amount','payment_period','monthly_installments',
        'amount_payable','approver3_date','final_decision')->where('user_id',Auth::id())->where('final_decision',1)->get();
        foreach($loan_data as $l){
            //check if user is within the repayment range$date_issued = $l->approver3_date;
            $date_issued=$l->approver3_date;
            $date_issued=Carbon::parse($date_issued);
           
            $period = $l->payment_period; // Period in months
            $current_date = $date_issued->copy(); // Assuming $date is the invoice date

            // Calculate the end date of the repayment period
            $end_date = $date_issued->copy()->addMonths($period);

            // Loop through each month from $date_issued to $end_date
            $current = $date_issued->copy(); // Start from the issued date
            $temp_data=[];
            $pay_status1=[];

            while ($current->lessThan($end_date)) {
                
                // Extract the month and year
                $month = $current->format('F'); // Full month name, e.g., "October"
                $year = $current->format('Y'); // Year, e.g., "2024"
                
                //Check if the invoice was paid
                $invoice_number="00".$l->company_id."-".$month."-".$year;
                $check=Invoice::where('invoice_number',$invoice_number)->pluck('id')->first();
                if($check){//Exists
                    $invoice=Invoice::find($check);
                    $status=$invoice->status;
                    if($status==1){//Paid
                            $date=$month." ".$year;
                            $status=$status;
                    }elseif($status==2){//Unpaid
                        $date=$month." ".$year;
                        $pay_status=2;
                        $status=$status;
                    
                    }elseif($status==3){//Partilly Paid
                        $date=$month." ".$year;
                        $pay_status=2;
                        $status=$status;
                        
                    }
                }else{//Does not exists
                    $date=$month." ".$year;
                    $status=2;

                }

                array_push($temp_data,[
                    'installment'=>$l->monthly_installments,
                    'date'=>$date,
                    'status'=>$status
                ]);


                array_push($pay_status1,[
                    'status'=>$status
                ]);
                
                // Move to the next month
                $current->addMonth();
            }

            array_push($payments_data,[
                'loan_id'=>$l->id,
                'data'=>$temp_data
            ]);

            array_push($payment_completion_status,[
                'data'=>$pay_status1
            ]);

            // // Check if the current date is within the repayment period
            // if ($current_date->between($date_issued, $end_date)) {
            //     // The current date is within the repayment period

            // } else {
            //     // The current date is outside the repayment period
            //     continue;
            // }

            foreach ($payment_completion_status as $p) {
                foreach ($p['data'] as $s) { // Loop through the 'data' array
                    if($s['status']!=1){
                        $p_status=2;
                    }else{
                        $p_status=1;
                    }
                }
            }

            array_push($data,[
                'loan_id'=>$l->id,
                'date'=>$date_issued->format('jS F Y'),
                'amount'=>$l->requested_loan_amount,
                'installments'=>$l->monthly_installments,
                'period'=>$l->payment_period,
                'status'=>$p_status,
            ]);
        }

         //return $payments_data;
        //return $data;
        //return $payment_completion_status;

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
        $ii=0;// to track when a company is selected for search

        if($from and $to and !$company_name){// Pick data for all companies within date range

            // //Get Loan data for that scheme
            $loan_data=Loan::select('id','requested_loan_amount','payment_period','monthly_installments','approver3_date')
            ->where('final_decision',1)->whereBetween('approver3_date',[$from,$to] )
            ->get();
            $date=Carbon::now();

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
    //return $loan_data;
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

        }elseif($from and $to and $company_name){//Pick data for a specific company within specified range


            // //Get Loan data for that scheme
            $scheme_id=Company::where('name',$company_name)->pluck('id')->first();
            if(!$scheme_id){
                session()->flash('error','The company selected does not exist.');
                return back();
            }
            $date=Carbon::now();
            if($ii==0){
                $loan_data=Loan::select('id','requested_loan_amount','payment_period','monthly_installments','approver3_date')
                ->where('company_id',$scheme_id)->where('final_decision',1)->whereBetween('approver3_date',[$from,$to] )
                ->get();
                $ii=0;
            }else{
                $ii=1;
            }
            
            $sum_loan_amt=0;
            $sum_interest=0;
            $sum_amt_payable=0;
            $loan_requests=0;
            $mi=0;
            $status=2;
            $date_paid="N/A";

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
                    'scheme'=>$company_name,
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
        

            
        }else{// Pick data for all companies for that month(current) only
             $date=Carbon::now();
             $date->format('Y-m-d');
             $month = $date->format('m'); // Extract month
             $year = $date->format('Y');  // Extract year

            //Get Loan data for that scheme
            $loan_data=Loan::select('id','requested_loan_amount','payment_period','monthly_installments','approver3_date')
            ->where('final_decision',1)->whereMonth('approver3_date', $month)
            ->whereYear('approver3_date', $year)->get();


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
        
        $for_date=$date->format('F Y');
        $date_selected=$date->format('Y-m-d');
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


}
