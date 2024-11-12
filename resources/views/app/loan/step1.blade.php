@extends('layouts.app')

@section('content')
    
<div class="row mt-3">

                        <ul class="nav nav-pills navtab-bg nav-justified">
                            <li class="nav-item">
                                <a class="nav-link active no-hover-style">
                                    Info
                                </a>
                            </li>
                            <li class="nav-item">
                                <a  class="nav-link bg-warning no-hover-style">
                                    Salary
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link bg-warning no-hover-style">
                                    Loan 
                                </a>
                            </li>
                            <li class="nav-item">
                                <a  class="nav-link bg-warning no-hover-style">
                                    Terms
                                </a>
                            </li>
                        </ul>
                        <div class="tab-content">
                            <div class="tab-pane active" id="home1">

                                    <div>
                                        <div  class="modal-dialog1 modal-dialog-centered1 col-12 responsive1" >
                                                    <h5 class="modal-title" id="exampleModalLabel">My Personal Details</h5>
                                            <div class="modal-content">
                                                <div class="modal-body">
                                                <form action="{{route('loan.store')}}" method="POST">
                                                    @csrf
                                                    @method('POST')
                                                    <input type="hidden" name="type" value="1">
                                                <div class="row mb-2">
                                                    <div class="col-md-6">
                                                    <div class="mb-3">
                                                        <label class="form-label">Applicant ID</label>
                                                        <input
                                                        name="applicant_id"
                                                        type="text"
                                                        value="{{$applicant_id}}"
                                                        class="form-control"
                                                        placeholder="Applicant ID"
                                                        readonly
                                                        required
                                                        />
                                                    </div>
                                                    </div>
                                                    <div class="col-md-6">
                                                    <div class="mb-3">
                                                        <label class="form-label">First Name</label>
                                                        <input
                                                        name="first_name"
                                                        type="text"
                                                        value="{{$first_name}}"
                                                        class="form-control"
                                                        placeholder="first name"
                                                        readonly
                                                        required
                                                        />
                                                    </div>
                                                    </div>
                                                </div>
                                                <div class="row">

                                                <div class="col-md-6 mb-2">
                                                    <div class="mb-3">
                                                        <label class="form-label">Second Name</label>
                                                        <input
                                                        name="second_name"
                                                        type="text"
                                                        value="{{$last_name}}"
                                                        class="form-control"
                                                        placeholder="second name"
                                                        readonly
                                                        required
                                                        />
                                                    </div>
                                                    </div>

                                                    <div class="col-md-6">
                                                    <div class="mb-3">
                                                        <label class="form-label">Contacts</label>
                                                        <input
                                                        name="contacts"
                                                        type="text"
                                                        class="form-control"
                                                        placeholder="contacts"
                                                        value="{{$contacts}}"
                                                        readonly
                                                        required
                                                        />
                                                    </div>
                                                    </div>

                                                    <div class="col-md-6 mb-2">
                                                    <div class="mb-3">
                                                        <label class="form-label">Contract Expires</label>
                                                        <input
                                                        name="contract"
                                                        type="text"
                                                        class="form-control"
                                                        value="{{$contract_end}}"
                                                        readonly
                                                        placeholder="expiry"
                                                        required
                                                        />
                                                    </div>
                                                    </div>


                                                    <div class="col-md-6 mb-2">
                                                        <div class="mb-3">
                                                            <label class="form-label">Designation</label>
                                                            <input
                                                            name="company"
                                                            type="text"
                                                            class="form-control"
                                                            value="{{$company}}"
                                                            placeholder="designation"
                                                            readonly
                                                            required
                                                            />
                                                        </div>
                                                        </div> 
{{-- 
                                                    <div class="col-md-6 mb-3">
                                                        <label for="field-11" class="form-label">Do you have an Outstanding Loan</label>
                                                        <select name="outstanding_loan" class="form-control form-select" id="field-11" required>
                                                            <option value="0" {{$outstanding_loan == 0 ? 'selected' : ''}}>No</option>
                                                            <option value="1" {{$outstanding_loan == 1 ? 'selected' : ''}}>Yes</option>
                                                        </select>
                                                    </div>
                                                        <div class="col-md-6 mb-2 loan-details" style="display: none;">
                                                            <div class="mb-3">
                                                                <label class="form-label">If Yes Name Financial Institution</label>
                                                                <input name="financial_institution" value="{{$outstanding_loan_org}}" type="text" class="form-control"
                                                                pattern="[A-Za-z\s]+"  
                                                                title="Please enter letters only" placeholder="Name of Financial Institution" />
                                                            </div>
                                                        </div>
                                                    
                                                        <div class="col-md-6 mb-2 loan-details" style="display: none;">
                                                            <div class="mb-3">
                                                                <label class="form-label">Outstanding Balance</label>
                                                                <input name="loan_bal" type="text" value="{{$outstanding_loan_balance}}" min="1"  pattern="^[1-9]\d*(\.\d+)?$" title="Please enter a valid positive number"class="form-control" placeholder="Outstanding loan balance" />
                                                            </div>
                                                        </div> --}}
                                                        


                                                    <div class="col-md-6">
                                                    <div class="mb-3">
                                                        <label class="form-label">Next Of KIN</label>
                                                        <input
                                                        name="kin"
                                                        type="text"
                                                        class="form-control"
                                                        value="{{$kin}}"
                                                        placeholder="next of kin names"
                                                        pattern="[A-Za-z\s]+"  
                                                        title="Please enter letters only"
                                                        required
                                                        />
                                                    </div>
                                                    </div>


                                                    <div class="col-md-6">
                                                    <div class="mb-3">
                                                        <label class="form-label">KIN Contacts</label>
                                                        <input
                                                        name="kin_mobile"
                                                        type="tel"
                                                        class="form-control"
                                                        value="{{$kin_contacts}}"
                                                        placeholder="next of kin contacts"
                                                        pattern="[0-9]{9,12}" 
                                                        inputmode="numeric" 
                                                        title="Please enter a valid phone number e.g 07123.. or 2547...."
                                                        required
                                                      />
                                                    </div>
                                                    </div>

                                                </div>

                                                </div>
                                                <div class="modal-footer">
                                                    <button class="btn btn-primary" type="submit">Next  <i class="fa fa-arrow-right"></i></button>
                                                </div>
                                            </div>
                                        </form>
                                        </div>
                                    </div>
                                    <!-- End NewStaff Modal-->

                            </div>
                        </div>
    </div>

    <script>
        function toggleLoanDetails() {
            var loanDetails = document.querySelectorAll('.loan-details');
            var selectValue = document.querySelector('.form-select').value;
    
            if (selectValue == '1') { // Show the divs when "Yes" is selected
                loanDetails.forEach(function(detail) {
                    detail.style.display = 'block';
                });
            } else { // Hide the divs when "NO" is selected
                loanDetails.forEach(function(detail) {
                    detail.style.display = 'none';
                });
            }
        }
    
        // Run the function on page load to set the initial state
        document.addEventListener('DOMContentLoaded', function() {
            toggleLoanDetails();
        });
    
        // Attach the function to the change event of the select input
        document.querySelector('.form-select').addEventListener('change', function() {
            toggleLoanDetails();
        });
    </script>

@endsection