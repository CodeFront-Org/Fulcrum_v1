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

<form method="GET" action="#">
    <div class="row">

    <div class="row">
        <div class="mb-3 col-md-3">
            <label for="To">Schemes</label>
            @php
            use App\Models\Company;
                $companies = Company::select('id','name')->get();

            @endphp

            <input type="text" list="regnoo" parsley-trigger="change" required class="form-control"
            id="reg_no" name='company' value="" placeholder="Select company ..." aria-label="Recipient's username"
            id="top-search" autocomplete="off" />
    
    <datalist id="regnoo">
        @foreach ($companies as $company)
            <option value="{{ $company->name }}">{{ $company->name }}</option>
        @endforeach
    </datalist>
        </div>
        <div class="mb-3 col-md-3">
            <label for="from">From:</label>
            <input type="date" class="form-control" name="from" data-provide="w" required placeholder="From: ">
        </div>
        <div class="mb-3 col-md-3">
            <label for="To">To:</label>
            <input type="date" class="form-control" name="to" required data-provide="datepicker1" placeholder="To: ">
        </div>        
        <div class="mb-3 col-md-3" style="margin-top: 2.65%">
            <button type="submit" class="btn btn-primary waves-effect waves-light">Submit</button>
        </div>
        
    </div>


</form>

@if ($empty)
    <div class="row">
        <div class="col-xd-3">No data: Please use the filters above to fetch data.</div>
    </div>
@else
<div class="card shadow mb-4">
    <div class="card-header py-3 text-center">
        <h6 class="m-0 font-weight-bold text-primary">
              {{$company_name}} Loan Requests for period {{$for_date}}
            </h6>
          </div>
          <div class="card-body">
            <div class="table-responsive">
                <table
                  class="table table-bordered"
                  id="dataTable"
                  width="100%"
                  cellspacing="0"
                  style="font-size:13px;text-align:center;white-space:nowrap;"
                >
                <thead>
                  <tr>
                    <th>#</th>
                    <th>Invoice ID</th>
                    {{-- <th>Requests</th> --}}
                    <th>Tot Loan Amount</th>
                    <th>Monthly Installment</th>
                    <th>Status</th>
                    <th>Amount Paid</th>
                    <th>Date paid</th>
                    <th>Payments</th>
                  </tr>
                </thead>
                <tbody>
                    @foreach ($data as $item)
                        <tr>
                            <td>{{$loop->index+1}}. </td>
                            <td><a href="{{route('invoice-report',['id'=>$item['scheme_id'],'invoice_number'=>$item['invoice_number']])}}">{{$item['invoice_number']}}</a></td>
                            {{-- <td>{{$item['requests']}}</td> --}}
                            <td>{{number_format($item['loan_amt'])}}</td>
                            <td>{{number_format($item['mi'])}}</td>
                            @if ($item['status']==1)
                            <td class="text-success">Paid</td>
                            @endif
                            @if ($item['status']==0)
                                <td class="text-danger">Not Paid</td>
                            @endif
                            @if ($item['status']==2)
                                <td class="text-warning">Partially Paid</td>
                            @endif
                            @if ($item['status']==4)
                                <td class="text-success">N/A</td>
                            @endif
                            <td>{{number_format($item['amount_paid'])}}</td>
                            <td>{{$item['date_paid']}}</td>
                            <td>
                                <button type="button" class="btn btn-success btn-sm" data-toggle="modal" data-target="#approval-m-{{$item['invoice_number']}}">
                                Action <i class='fas fa-check-circle' aria-hidden='true'></i>
                                </button>
                            </td>
                        </tr>
                    @endforeach
                    <tr>
                        <td colspan="7" style="text-align:right;font-weight:bold">Total Loan Requested</td>
                        <td colspan="2">{{number_format($tot_loan)}}</td>
                    </tr>
                    <tr>
                        <td colspan="7" style="text-align:right;font-weight:bold">Expected Monthly Installments</td>
                        <td colspan="2">{{number_format($tot_monthly_installments)}}</td>
                    </tr>
                    <tr>
                        <td colspan="7" style="text-align:right;font-weight:bold">Total Amount Paid</td>
                        <td colspan="2">{{number_format($tot_amt_paid)}}</td>
                    </tr>
                    <tr>
                        <td colspan="7" style="text-align:right;font-weight:bold">Balance</td>
                        <td colspan="2">{{number_format($tot_monthly_installments-$tot_amt_paid)}}</td>
                    </tr>
                    {{-- <tr>
                        <td colspan="7" style="text-align:right;font-weight:bold">Net<span class="text-success"> Profit</span> / <span class="text-danger">Loss</span></td>
                        @if ($net)
                            <td colspan="2" class="text-success">{{number_format($netpl)}}</td>
                        @else
                            <td colspan="2" class="text-danger">{{number_format($netpl)}}</td>  
                        @endif
                    </tr>
                    <tr style="font-weight:bolder;">
                        <td colspan="7" style="text-align:right;font-weight:bold">Report summary</td>
                        @if ($net)
                            <td colspan="2" style="border:solid 2px black" class="text-success">Profit</td>
                        @else
                        <td colspan="2" style="border:solid 2px black" class="text-danger">Loss</td>  
                        @endif
                    </tr> --}}

                </tbody>
              </table>
            </div>
          </div>
        </div>



<!-- Approve Modal-->   
@foreach ($data as $item)            
<div class="modal fade" id="approval-m-{{$item['invoice_number']}}" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
aria-hidden="true">
<div class="modal-dialog modal-dialog-centered" role="document">
   <div class="modal-content">
       <div class="modal-header">
           <h5 class="modal-title text-success" id="exampleModalLabel">Invoice ID:  {{$item['invoice_number']}}</h5>
           <button class="close" type="button" data-dismiss="modal" aria-label="Close">
               <span aria-hidden="true">×</span>
           </button>
       </div>
       <div class="modal-body">
           <form action="{{route('payment_status')}}" method="POST">
              @method('POST')
              @csrf
              <input type="hidden" name="invoice_number" value="{{$item['invoice_number']}}">
              <input type="hidden" name="company_id" value="{{$item['scheme_id']}}">
              <input type="hidden" name="loan_requests" value="{{$item['requests']}}">
              <input type="hidden" name="loan_amt" value="{{$tot_loan}}">
              {{-- <input type="hidden" name="tot_expected_payments" value="{{$tot_expected_payments}}"> --}}
              <input type="hidden" name="tot_amt_paid" value="{{$tot_amt_paid}}">
              <input type="hidden" name="invoice_date" value="{{$date_selected}}">
              <div class="row">
               <div class="col-md-12">
                   <div class="mb-3">
                       <label class="form-label">Payment Status</label>
                       <select name="payment_status" class="form-control form-select">
                           <option value="" selected disabled>Select one</option>
                           <option value="1">Paid</option>
                           <option value="2">UnPaid</option>
                           <option value="3">Partially Paid</option>
                       </select>
                   </div>
               </div>
           </div>
           
           <div class="row" style="display: none;">
               <div class="col-md-12">
                   <div class="mb-3">
                       <label class="form-label">Amount Paid</label>
                       <input type="number" name="amount_paid" min="1" class="form-control">
                   </div>
               </div>
           </div>

           
           <div class="row" style="display: none;">
            <div class="col-md-12">
                <div class="mb-3">
                    <label class="form-label">Date Paid</label>
                    <input type="date" name="date_paid" class="form-control">
                </div>
            </div>
        </div>
           
           <div class="row" style="display: none;">
               <label for="labell" class="text-danger">Click Proceed to continue.</label>
           </div>
           
           <div class="row" style="display: none;">
               <div class="col-md-12 mt-1">
                   <div class="mb-3">
                       <label for="field-2" class="form-label">Comments</label>
                       <textarea id="textarea" name="desc" class="form-control"  maxlength="3000" rows="3" placeholder="Comments"></textarea>
                   </div>
               </div>
           </div>

       </div>
       <div class="modal-footer">
           <button class="btn btn-secondary" type="button" data-dismiss="modal">Cancel</button>
           <button class="btn btn-success" type="submit">Proceed</button>
       </div>
   </div>
</form>
</div>
</div>
@endforeach  
<!-- End Approval Modal--> 
@endif




<script>
document.getElementById('paymentStatus').addEventListener('change', function () {
var selectedValue = this.value;
var amountPaidRow = document.getElementById('amountPaidRow');
var datePaidRow = document.getElementById('datePaidRow');
var messageLabel = document.getElementById('messageLabel');
var commentsRow = document.getElementById('commentsRow');
var commentsField = document.getElementById('textarea');

// Hide all sections by default
amountPaidRow.style.display = 'none';
datePaidRow.style.display = 'none';
messageLabel.style.display = 'none';
commentsRow.style.display = 'none';
commentsField.removeAttribute('required'); // Remove required if hidden

if (selectedValue === "1") { // Paid
amountPaidRow.style.display = 'block';
datePaidRow.style.display = 'block';
commentsRow.style.display = 'block';
commentsField.setAttribute('required', 'required'); // Add required if shown
} else if (selectedValue === "2") { // Unpaid
// Only buttons will show, no additional action needed
} else if (selectedValue === "3") { // Partially Paid
messageLabel.style.display = 'block';
}
});

</script>

@endsection