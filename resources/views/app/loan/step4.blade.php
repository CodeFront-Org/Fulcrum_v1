@extends('layouts.app')

@section('content')
@if (session()->has('message'))
    <div id="toast" class="alert text-center alert-success alert-dismissible w-100 fade show" role="alert">
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        {{ session('message') }}
    </div>
@endif

@if (session()->has('error'))
    <div id="toast" class="alert text-center alert-danger alert-dismissible w-100 fade show" role="alert">
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        {{ session('error') }}
    </div>
@endif

<div class="row">
        <ul class="nav nav-pills navtab-bg nav-justified">
            <li class="nav-item">
                <a href="{{route('loan.index',['p'=>1])}}"  class="nav-link bg-success no-hover-style">
                    Info
                    {{-- <i class="fa fa-check-circle bg-success"></i> --}}
                </a>
            </li>
            <li class="nav-item">
                <a href="{{route('loan.index',['p'=>2])}}"  class="nav-link bg-success no-hover-style">
                    Salary
                    {{-- <i class="fa fa-check-circle bg-success"></i> --}}
                </a>
            </li>
            <li class="nav-item">
                <a href="{{route('loan.index',['p'=>3])}}" class="nav-link bg-success no-hover-style">
                    Loan 
                    {{-- <i class="fa fa-check-circle bg-success"></i> --}}
                </a>
            </li>
            <li class="nav-item">
                <a href="{{route('loan.index',['p'=>4])}}"  class="nav-link active no-hover-style">
                    Terms
                </a>
            </li>
            
        </ul>

        <div class="tab-content" style="font-family: 'Roboto', sans-serif;">
            <div class="tab-pane active" id="home1">
                <div class="modal-dialog1 modal-dialog-centered1 col-12 responsive">
                    <h5 class="modal-title" id="exampleModalLabel">Employee Declaration</h5>
                    <div class="modal-content p-4">
                        <div class="modal-body">
                            <form action="{{route('loan.store')}}" method="POST">
                                @csrf
                                @method('POST')
                                <input type="hidden" name="type" value="4">
                                <input type="hidden" name="loan_id" value="{{$loan_id}}">
                                <div class="row mb-4">
                                    <ol>
                                        <li>I certify this information is true and correct and authorize Fulcrum Link Limited to contact any source for confirmation.</li>
                                        <li>I agree to be bound by the terms and conditions of this facility. I understand Fulcrum Link Limited reserves the right to decline this application without giving reasons.</li>
                                        <li>I understand that this application will go through a vetting process and should my loan be approved, a loan account of the amount disbursed will be created in my name.</li>
                                        <li>I understand that the interest of this loan will be applied at the prevailing fixed interest rate of 8% per month for the entire period.</li>
                                        <li>I instruct Fulcrum Link Limited to credit the loan amount approved to my mobile number.</li>
                                    </ol>
                                    <div class="col-md-12 mt-3 mb-3">
                                        <label for="mobileNumber" class="form-label">Mobile Number</label>
                                        <input type="text" id="mobileNumber" readonly value="{{$contacts}}" name="mobileNumber" class="form-control" placeholder="Enter your mobile number">
                                    </div>
                                    {{-- <p class="mt-3">upon approval or my bank account as below:</p>
                                    <div class="col-md-12 mb-3">
                                        <label for="bankName" class="form-label">Bank Name</label>
                                        <input type="text" id="bankName"readonly value="{{$bank_name}}" name="bankName" class="form-control" placeholder="BANK_ACCOUNT_NAME">
                                    </div>
                                    <div class="col-md-12 mb-3">
                                        <label for="bankAccount" class="form-label">Bank Account</label>
                                        <input type="text" id="bankAccount" readonly value="{{$bank_acc}}" name="bankAccount" class="form-control" placeholder="Bank Account">
                                    </div>
                                    <div class="col-md-12 mb-3">
                                        <label for="bankBranch" class="form-label">Bank Branch</label>
                                        <input type="text" id="bankBranch"readonly value="{{$bank_branch}}" name="bankBranch" class="form-control" placeholder="BANK_BRANCH">
                                    </div> --}}
                                    
                                    <div class="col-md-12 mb-4">
                                        <label for="agreement" class="form-label">I AGREE TO THE TERMS ABOVE (1-5)</label>
                                        <select id="agreement" name="agreement" class="form-control form-select" required>
                                            <option value="" selected disabled>Choose...</option>
                                            <option value="1">I Agree</option>
                                            <option value="2">I Disagree</option>
                                        </select>
                                    </div>
                                </div>

                                <!-- Employee's Irrevocable Authority Section -->
                                <div class="row mb-4">
                                    <h5 class="font-weight-bold">Employee's Irrevocable Authority</h5>
                                    <p>
                                        I <b>{{$user_name}}</b>
                                        , whose particulars are above, give my employer instructions to deduct the monthly loan repayments of KSH <b>{{number_format($installment)}}</b> (<b>{{$installment_in_words}}</b>)
                                        
                                        per month from my salary and remit the same to Fulcrum Link Limited until the loan is fully repaid and confirmed in writing by Fulcrum Link Limited.
                                    </p>
                                    <p>
                                        In the event of termination from the employment for any reason whatsoever, my employer will transfer any final settlement that are not regulated by any prevailing statute within the laws of Kenya to Fulcrum Link Limited to offset the outstanding loan balance if any.
                                    </p>
                                    <p>
                                        In the event my final dues are not sufficient to clear the loan, I am personally responsible for clearing the loan directly with Fulcrum Link Limited and will not be held responsible.
                                    </p>
                                    <div class="col-md-12 mb-4">
                                        <label for="irrevocableAgreement" class="form-label">I AGREE TO THE TERMS ABOVE</label>
                                        <select id="irrevocableAgreement" name="irrevocableAgreement" class="form-control form-select" required>
                                            <option value="" selected disabled>Choose...</option>
                                            <option value="1">I Agree</option>
                                            <option value="2">I Disagree</option>
                                        </select>
                                    </div>
                                </div>
                                <!-- End of Employee's Irrevocable Authority Section -->

                                <div class="modal-footer">
                                    <a href="{{route('loan.index',['p'=>3])}}"><button class="btn btn-secondary" type="button" data-dismiss="modal">
                                     <i class="fa fa-arrow-left"></i>Back</button></a> 
                                    <button type="submit" class="btn btn-primary">Submit Loan</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
</div>

@endsection
