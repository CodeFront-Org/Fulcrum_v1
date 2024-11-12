<?php

namespace App\Http\Controllers;

use App\Models\Access;
use App\Models\Bank;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use App\Models\Company;
use App\Models\Loan;
use App\Models\OldLoan;
use App\Models\OldUser;
use App\Models\Rate;
use App\Models\User;
use App\Models\UserBank;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;

class DbController extends Controller
{

    //*************************************Add Users to new laravel eloquent model *******************************************//
    public function users(){

        DB::beginTransaction();
        try {
            $users=OldUser::all();
            foreach($users as $user){
               //get user company id
            //    $company_name=$user->DESIGNATION;
            //    $company_id=Company::where('name',$company_name)->pluck('id')->first();
               //get user bank details
               $bank_name=$user->BANK_NAME;
               $bank_acc_name=$user->BANK_ACCOUNT_NAME;
               $bank_acc_no=$user->BANK_ACCOUNT_NO;
               $bank_branch=$user->BANK_BRANCH;
               $bank_id=Bank::where('name',$bank_name)->pluck('id')->first();
               //get user role
               $role=$user->APPROVAL_ROLE;
               $company_id=NULL;
               if($role==NULL or $role==""){
                    $user_role="user";
                    //put company id to normal user
                    $name_of_company=$user->DESIGNATION;
                    $company_id=Company::where('name',$name_of_company)->pluck('id')->first();
                }
               elseif($role=="HRO"){$user_role="hro";}
               elseif($role=="FINANCE"){$user_role="finance";}
               elseif($role=="FULCRUM"){$user_role="admin";} 
    
                //Add the user to the new users table
                $new_user=User::create([
                    'first_name'=>$user->FIRST_NAME,
                    'last_name'=>$user->SECOND_NAME,
                    'email'=>$user->email,
                    'gender'=>$user->GENDER,
                    'id_number'=>$user->ID_NUMBER,
                    'pin_certificate'=>$user->PIN_CERTIFICATE,
                    'pin_certificate_photo'=>$user->PIN_CERTIFICATE_PHOTO,
                    'staff_number'=>$user->STAFF_NO,
                    'employment_date'=>$user->DATE_OF_EMPLOYMENT,
                    'employment_type'=>$user->EMPLOYMENT_TYPE,
                    'contract_end'=>$user->CONTRACT_END,
                    'contacts'=>$user->MOBILE_NUMBER,
                    'mobile_login'=>substr($user->MOBILE_NUMBER,-9),
                    'alternative_contacts'=>$user->ALTERNATE_MOBILE,
                    'company_id'=>$company_id,
                    'role_type'=>$user_role,
                    'password'=>Hash::make($user->ID_NUMBER)
                ]);
    
                if($new_user){
                    //Get companies user has access to and add it to company access table
                    $companies = explode("\n", $user->DESIGNATION);
                    // Check if $companies is an array
                     if (is_array($companies)) {
                         foreach($companies as $c){
                              //get company id 
                              $c_id=Company::where('name',$c)->pluck('id')->first();
                              Access::create([
                                  'user_id'=>$new_user->id,
                                  'company_id'=>$c_id
                              ]);
                         }
                     }
                     else{ // just one user and just assign one company id
                        $c_id=Company::where('name',$user->DESIGNATION)->pluck('id')->first();
                        Access::create([
                            'user_id'=>$new_user->id,
                            'company_id'=>$c_id
                        ]);
                     }
    
                     //Associate user with the bank 
                     UserBank::create([
                        'bank_id'=>$bank_id,
                        'user_id'=>$new_user->id,
                        'user_account_name'=>$bank_acc_name,
                        'account_number'=>$bank_acc_no,
                        'branch'=>$bank_branch
                     ]);
    
                     //Assign Roles to the user
                     $role = Role::where('name', $user_role)->first();
                     if ($role) {
                         $new_user->assignRole($user_role);
                     } else {// Handle case where 'user' role doesn't exist
                         return response()->json(['status'=>false,'msg'=>'Role User does not exist']);
                     }
    
                }
            }
            DB::commit()
;            return response()->json([
                'status'=>"Success",
                'message'=>'All User Date migrated successfully'
            ]);
        } catch (\Throwable $th) {
            DB::rollBack();
            return response()->json([
                "status"=>false,
                "message"=>$th->getMessage()
            ]); 
        }
    }


    //*************************************Add companies *******************************************//
    public function company(){
        $companies= Rate::all();
        foreach($companies as $c){
            Company::create([
                'name'=>$c->DESIGNATION,
                'month1'=>$c->MONTH1,
                'month2'=>$c->MONTH2,
                'month3'=>$c->MONTH3,
                'month4'=>$c->MONTH4,
                'month5'=>$c->MONTH5,
                'month6'=>$c->MONTH6,
                'month7'=>$c->MONTH7,
            ]);
        }
        return response()->json([
            'status'=>true,
            'message'=>"Old companies/Designation migrated to new DB"
        ]);
    }



    //*************************************Add Banks distinctly*******************************************//
        public function bank()
        {
            $data = [];
            // To get distinct bank names sorted alphabetically
            $distinctBankNames = OldUser::select('bank_name')->distinct()->orderBy('bank_name', 'asc')->get();
            //to check if standard charted has been repeated
            $check=false;
            //check repetition of kcb bank
            $kcb=false;
        
            foreach ($distinctBankNames as $b) {
                // Skip unwanted bank names
                if ($b->bank_name == '' || $b->bank_name == 'sales@fulcrumafrica.com' || $b->bank_name == 'Techsavanna' || $b->bank_name == 'XXXX BANK' or $b->bank_name=='NA') {
                    continue;
                }
        
                // Default bank name as it is
                $name = $b->bank_name;
        
                // Normalize bank names
                if ($b->bank_name == 'KCB' || $b->bank_name == 'KCB BANK' || $b->bank_name == 'KENYA COMMERCIAL BANK') {
                    if($kcb){continue;}
                    $name = "KCB BANK";
                    $kcb=true;
                } elseif ($b->bank_name == 'STANDARD CHARTERED BANK' or $b->bank_name == 'STANDARD CHARTERED  BANK') {
                    if($check){continue;}
                    $name = "STANDARD CHARTERED BANK";
                    $check=true;
                }

                //Store to new Bank DB
                Bank::create([
                    'name'=>$name
                ]);
        
                //array_push($data, ["name" => $name]);
                //return $data;
            }
            return response()->json([
                'status'=>'success',
                'message'=>'Banks Inserted successfully'
            ]);
        
            
        }


    //************************************* Migrate old loan data to new db*******************************************//
        public function loans(){
            //Get the distinct user  from the old loan app db
            DB::beginTransaction();
            try {
                $old=OldLoan::all();
                foreach($old as $o){
                    //pick data from old db
                    $f_name=$o->FIRST_NAME;
                    $s_name=$o->SECOND_NAME;
                    $des=$o->DESIGNATION;
                    //pick data from new user db
                    $user_id=User::where('first_name',$f_name)->where('last_name',$s_name)->pluck('id')->first();
                    //Get company id
                    $company_id=Access::where('user_id',$user_id)->pluck('company_id')->first();
                    //Check approval level so as to give final decision on loan 
                    $check=$o->CURRENT_APPROVAL_LEVEL;
                    if($check=="COMPLETE"){
                        $final_dec=1;
                    }elseif($check=="RETURNED"){
                        $final_dec=2;
                    }else{
                        $final_dec=0;
                    }
                    //Check terms and consent_to_irrevocable_authority
                    $terms=$o->AGREE_TO_THE_TERMS_AND_CONDITIONS;
                    if($terms=="YES"){
                        $term=1;
                    }else{
                        $term=0;
                    }
                    $consent=$o->CONSENT_TO_IRREVOCABLE_AUTHORITY;
                    if($consent=="YES"){
                        $consent1=1;
                    }else{
                        $consent1=0;
                    }
                    //Store data in new loan table
                    if(!empty($user_id)){
                    $loan=Loan::create([
                        'user_id'=>$user_id,
                        'applicant_id'=>$o->APPLICANT_ID,
                        'company_id'=>$company_id,
                        'contacts'=>$o->MOBILE_NUMBER,
                        'alternative_contacts'=>$o->ALTERNATE_MOBILE,
                        'kin'=>$o->NEXT_OF_KIN,
                        'kin_contacts'=>$o->NEXT_OF_KIN_CONTACT,
                        'gross_salary'=>$o->GROSS_SALARY,
                        'net_salary'=>$o->NET_SALARY,
                        'other_allowances'=>$o->OTHER_ALLOWANCE,
                        'outstanding_loan'=>$o->OUTSTANDING_LOAN,
                        'outstanding_loan_org'=>$o->OUTSTANDING_LOAN_INSTITUTION,
                        'outstanding_loan_balance'=>$o->OUTSTANDING_LOAN_BALANCE,
                        'requested_loan_amount'=>$o->REQUESTED_LOAN_AMOUNT,
                        'PAYMENT_PERIOD'=>$o->PAYMENT_PERIOD,
                        'monthly_installments'=>$o->MONTHLY_INSTALMENTS,
                        'loan_reason'=>$o->LOAN_REASON,
                        'supporting_doc_file'=>$o->SUPPORTING_DOCUMENTS,
                        'agreed_terms'=>$term,
                        'consent_to_irrevocable_authority'=>$consent1,
                        //'approval_level'=>$o->0,
                        'approver1_id'=>$o->APPROVER1,
                        'approver1_action'=>$o->APPROVER1_ACTION,
                        'approver1_comments'=>$o->APPROVER1_COMMENT,
                        'approver1_date'=>$o->APPROVER1_ACTION_DATETIME,
                        'approver2_id'=>$o->APPROVER2,
                        'approver2_action'=>$o->APPROVER2_ACTION,
                        'approver2_comments'=>$o->APPROVER2_COMMENT,
                        'approver2_date'=>$o->APPROVER2_ACTION_DATETIME,
                        'approver3_id'=>$o->APPROVER3,
                        'approver3_action'=>$o->APPROVER3_ACTION,
                        'approver3_comments'=>$o->APPROVER3_COMMENT,
                        'approver3_date'=>$o->APPROVER3_ACTION_DATETIME,
                        'final_decision'=>$final_dec,
                        'mpesa_transaction'=>$o->TRANSACTION_CODE,
                        'created_at'=>$o->DATE_TIME
                    ]);

                $loan->timestamps = false;
                $loan->save();
                }
                }

                DB::commit();
                return response()->json([
                    "status"=>true,
                    "message"=>"Migrated Loan data to new db successfully"
                ]);


            } catch (\Throwable $th) {
                DB::rollBack();
                return response()->json([
                    "status"=>false,
                    "message"=>$th->getMessage()
                ]); 
            }
        }

} 
