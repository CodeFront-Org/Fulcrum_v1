@extends('layouts.app')

@section('content')
    
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
                                    {{-- <i class="fa fa-check-circle bg-success" style="font-size: 10px"></i> --}}
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link active no-hover-style">
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
                                                    <h5 class="modal-title" id="exampleModalLabel">Loan Details</h5>
                                            <div class="modal-content">
                                                <div class="modal-body">
                                                    <form action="{{route('loan.store')}}" method="POST" enctype="multipart/form-data">
                                                        @csrf
                                                        @method('POST')
                                                        <input type="hidden" name="type" value="3">
                                                <div class="row mb-2">
                                                    <div class="col-md-6 mb-3">
                                                    <div class="">
                                                        <label class="form-label">Requested Loan Amount</label>
                                                        <input
                                                        name="loan"
                                                        type="number"
                                                        class="form-control"
                                                        placeholder="Loan Amount"
                                                        value="{{$loan_amt}}"
                                                        pattern="^[1-9]\d*(\.\d+)?$" 
                                                        title="Please enter a valid positive number"
                                                        min="1"
                                                        required
                                                        id="loanAmt"
                                                        />
                                                    </div> 
                                                    </div>

                                                    <div class="col-md-6">
                                                        <label for="field-11" class="form-label">Repayment period in months</label>
                                                        <select name="period" class="form-control form-select" id="field-period" required>
                                                            <option value="" disabled selected>Select a period</option> 
                                                            @foreach ($periods as $item)
                                                                <option {{$period==$loop->index+1?'selected':''}} value="{{$loop->index+1}}" data-val={{$item}} data-description="{{$loop->index+1}}">{{$loop->index+1}}</option>
                                                            @endforeach
                                                        </select>
                                                    </div>

                                                    <div class="col-md-6">
                                                        <div>
                                                            <label class="form-label">Monthly Installment</label>
                                                            <input
                                                            name="installments"
                                                            type="number"
                                                            readonly
                                                            class="form-control"
                                                            value="{{$installment}}"
                                                            placeholder="Payable installment"
                                                            required
                                                            id="M_I"
                                                            
                                                            />
                                                        </div>
                                                        </div>


                                                        <div class="col-md-6">
                                                            <div>
                                                                <label class="form-label">Payslip</label>
                                                                @if ($is_upload)
                                                                    <input
                                                                    type="file"
                                                                    name="payslip"
                                                                    class="form-control"
                                                                    placeholder="Attach payslip"
                                                                    accept=".pdf, .jpg, .jpeg, .png"
                                                                    />
                                                                    <i class="text-success" style="font-size: 14px">Attached: {{$file_name}} click choose file to change</i>
                                                                @else
                                                                <input
                                                                    type="file"
                                                                    name="payslip"
                                                                    class="form-control"
                                                                    placeholder="Attach payslip"
                                                                    accept=".pdf, .jpg, .jpeg, .png"
                                                                    required
                                                                    />
                                                                    <i class="text-danger" style="font-size: 14px">Please attach your payslip</i>
                                                                @endif
                                                            </div>
                                                            </div>

                                                <div class="col-md-12 mt-1">
                                                    <div class="mb-3">
                                                        <label for="field-2" class="form-label">Reason for Loan</label>
                                                        <textarea id="textarea" name="desc" class="form-control" required maxlength="3000" rows="3" placeholder="Reason for loan">{{$reason}}</textarea>
                                                    </div>
                                                </div>
                                                </div>

                                                </div>
                                                <div class="modal-footer">
                                                    <!-- <button class="btn btn-secondary" type="button" data-dismiss="modal">Cancel</button> -->
                                                   <a href="{{route('loan.index',['p'=>2])}}"><button class="btn btn-secondary" type="button" data-dismiss="modal">
                                                    <i class="fa fa-arrow-left"></i>Back</button></a> 
                                                    <button class="btn btn-primary" type="submit">Next  <i class="fa fa-arrow-right"></i></button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </form>
                                    <!-- End NewStaff Modal-->

                            </div>
                        </div>
    </div>
    <script>
        document.querySelector('.form-select').addEventListener('change', function() {
            var loanDetails = document.querySelectorAll('.loan-details');
            if (this.value == '1') { // Show the divs when "Yes" is selected
                loanDetails.forEach(function(detail) {
                    detail.style.display = 'block';
                });
            } else { // Hide the divs when "NO" is selected
                loanDetails.forEach(function(detail) {
                    detail.style.display = 'none';
                });
            }
        });
    </script>
    
    <script>
    $(document).ready(function() {
                // Function to format a number as a comma-separated string
        function formatNumber(num) {
            return num.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",");
        }

        // Function to calculate EMI
        function calculateEMI() {
            // Get the selected option
            var selectedOption = $('#field-period').find('option:selected');
            var selectedValue = $('#field-period').val();
            var selectedValue = selectedOption.data('val');
            var description = selectedOption.data('description');

            // Convert values
            var r = parseFloat(selectedValue);  // Convert to float
            var n = parseInt(description, 10);  // Convert to integer
            var loan = parseFloat($('#loanAmt').val());  // Convert to float

            // Calculate EMI
            var EMI = loan * ((r * Math.pow((1 + r), n)) / (Math.pow((1 + r), n) - 1));
            
            // Check for potential division by zero
            if (isFinite(EMI)) {
                EMI = EMI.toFixed(2); // round to 2 decimal places
                $("#M_I").val(EMI);  // Update the EMI value in the target field
            }
        }

        // Trigger calculation when the period changes
        $('#field-period').change(function() {
            calculateEMI();
        });

        // Trigger calculation when the loan amount changes
        $('#loanAmt').on('input', function() {
            calculateEMI();
        });
    });
    </script>
    



    
@endsection