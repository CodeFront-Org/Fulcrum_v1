 @extends('layouts.app') 

@section('content')
    
 <div class="row mt-3">

                        <ul class="nav nav-pills navtab-bg nav-justified">
                            <li class="nav-item">
                                <a href="{{route('loan.index',['p'=>1])}}"  class="nav-link bg-success no-hover-style">
                                    Info
                                    {{-- <i class="fa fa-check-circle bg-success" style="font-size:10px"></i> --}}
                                </a>
                            </li>
                            <li class="nav-item">
                                <a  class="nav-link active no-hover-style">
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
                                        <div  class="modal-dialog1 modal-dialog-centered1 col-12 responsive1">
                                                    <h5 class="modal-title" id="exampleModalLabel">My Salary Details</h5>
                                            <div class="modal-content">
                                                <div class="modal-body">
                                                    <form action="{{route('loan.store')}}" method="POST">
                                                        @csrf
                                                        @method('POST')
                                                        <input type="hidden" name="type" value="2">
                                                <div class="row mb-2">
                                                    <div class="col-md-6">
                                                    <div class="mb-3">
                                                        <label class="form-label">Gross Salary</label>
                                                        <input
                                                        name="gross"
                                                        value="{{$gross}}"
                                                        type="number"
                                                        class="form-control"
                                                        placeholder="Gross Salary"
                                                        required
                                                        pattern="^[1-9]\d*(\.\d+)?$" 
                                                        title="Please enter a valid positive number"
                                                        />
                                                    </div>
                                                    </div>
                                                    <div class="col-md-6">
                                                    <div class="mb-3">
                                                        <label class="form-label">Net Salary</label>
                                                        <input
                                                        name="net"
                                                        type="text"
                                                        value="{{$net}}"
                                                        class="form-control"
                                                        placeholder="Net Salary"
                                                        pattern="^[1-9]\d*(\.\d+)?$" 
                                                        title="Please enter a valid positive number"
                                                        min="1"
                                                        required
                                                      />
                                                      
                                                    </div>
                                                    </div>
                                                </div>
                                                <div class="row">

                                                <div class="col-md-6 mb-2">
                                                    <div class="mb-3">
                                                        <label class="form-label">Other Allowances</label>
                                                        <input
                                                        name="allowance"
                                                        type="text"
                                                        value="{{$allowance}}"
                                                        class="form-control"
                                                        placeholder="Other Allowances"
                                                        pattern="^[1-9]\d*(\.\d+)?$" 
                                                        title="Please enter a valid positive number"
                                                        min="1"
                                                        required
                                                        />
                                                    </div>
                                                    </div>

                                                    <div class="col-md-6 mb-3">
                                                        <label for="field-11" class="form-label">Do you have an Outstanding Loan</label>
                                                        <select name="outstanding_loan" class="form-control form-select" id="field-11" required>
                                                            <option value="0" {{$outstanding_loan == 1 ? 'selected' : ''}}>No</option>
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
                                                        </div>

                                                </div>

                                                </div>
                                                <div class="modal-footer">
                                                    <!-- <button class="btn btn-secondary" type="button" data-dismiss="modal">Cancel</button> -->
                                                   <a href="{{route('loan.index',['p'=>1])}}"><button class="btn btn-secondary" type="button" data-dismiss="modal">
                                                        <i class="fa fa-arrow-left"></i>Back</button></a> 
                                                        <button class="btn btn-primary" type="submit">Next  <i class="fa fa-arrow-right"></i></button>
                                                </div>

                                            </form>
                                            </div>
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