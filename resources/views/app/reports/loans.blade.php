@extends('layouts.app')

@section('content')


<form method="GET" action="#">
    <div class="row">

    <div class="row">
        <div class="mb-3 col-md-3">
            <label for="To">Schemes</label>
            @php
            use App\Models\Company;
                $companies = Company::select('id','name')->get();

            @endphp
 
            <input type="text" list="regnoo" parsley-trigger="change"  class="form-control"
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
            <input type="date" class="form-control"required name="from" data-provide="w" placeholder="From: ">
        </div>
        <div class="mb-3 col-md-3">
            <label for="To">To:</label>
            <input type="date" class="form-control" requirede name="to" data-provide="datepicker1" placeholder="To: ">
        </div>
        <div class="mb-3 col-md-3" style="margin-top: 2.65%">
            <button type="submit" class="btn btn-primary waves-effect waves-light">Submit</button>
        </div>
        
    </div>


</form>

<button id="excelbtn" style="width:90px" type="button" class="btn btn-success"><i class="fa fa-file-excel bg-success"></i> excel </button>
                <div class="card shadow mb-4">
                <div class="card-header py-3 text-center">
                    <h6 class="m-0 font-weight-bold text-primary">
                          All Schemes Loan Requests for period {{$for_date}}
                        </h6>
                      </div>
                      <div class="card-body">
                        <div class="table-responsive">
                            <table
                              class="table table-bordered"
                              id="salestable"
                              width="100%"
                              cellspacing="0"
                              style="font-size:13px;text-align:center;white-space:nowrap;"
                            >
                            <thead>
                              <tr>
                                <th>#</th>
                                <th>Scheme</th>
                                <th>Loan Requests</th>
                                {{-- <th>Monthly Installment</th> --}}
                                <th>Total Loan Amount</th>
                                <th>Total Interest</th>
                                <th>Amount Payable</th>
                                {{-- <th>Status</th>
                                <th>Date paid</th> --}}
                              </tr>
                            </thead>
                            <tbody>
                                @foreach ($data as $item)
                                    <tr>
                                        <td>{{$loop->index+1}}. </td>
                                        <td>{{$item['scheme']}}</td>
                                        <td>{{$item['requests']}}</td>
                                        {{-- <td>{{number_format($item['mi'])}}</td> --}}
                                        <td>{{number_format($item['loan_amt'])}}</td>
                                        <td>{{number_format($item['interest'])}}</td>
                                        <td>{{number_format($item['amt_payable'])}}</td>
                                        {{-- @if ($item['status']==1)
                                            <td class="text-success">Paid</td>
                                        @endif
                                        @if ($item['status']==2)
                                            <td class="text-danger">Not Paid</td>
                                        @endif
                                        @if ($item['status']==3)
                                            <td class="text-warning">Paid</td>
                                        @endif
                                        <td>{{$item['date_paid']}}</td>
                                    </tr> --}}
                                @endforeach

                            <tr>
                                <td colspan="5" style="text-align:right;font-weight:bold">Total Loan Requested</td>
                                <td colspan="1" style="text-align: left">{{number_format($tot_loan)}}</td>
                            </tr>
                            <tr>
                                <td colspan="5" style="text-align:right;font-weight:bold">Total Expected Repayment</td>
                                <td colspan="1" style="text-align: left">{{number_format($tot_expected_payments)}}</td>
                            </tr>
                            {{-- <tr>
                                <td colspan="5" style="text-align:right;font-weight:bold">Expected Monthly Installments</td>
                                <td colspan="1" style="text-align: left">{{number_format($tot_monthly_installments)}}</td>
                            </tr> --}}
                            {{-- <tr>
                                <td colspan="5" style="text-align:right;font-weight:bold">Total Amount Paid</td>
                                <td colspan="1" style="text-align: left">{{number_format($tot_amt_paid)}}</td>
                            </tr> --}}
                            {{-- <tr>
                                <td colspan="5" style="text-align:right;font-weight:bold">Net<span class="text-success"> Profit</span> / <span class="text-danger">Loss</span></td>
                                @if ($net1
                                    <td colspan="2" class="text-success">{{number_format($netpl)}}</td>
                                @else
                                    <td colspan="2" class="text-danger">{{number_format($netpl)}}</td>  
                                @endif
                            </tr>
                            <tr style="font-weight:bolder;">
                                <td colspan="5" style="text-align:right;font-weight:bold">Report summary</td>
                                @if ($net1
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

<script src="https://cdn.jsdelivr.net/gh/linways/table-to-excel@v1.0.4/dist/tableToExcel.js"></script>

<script type="text/javascript">
$(document).ready(function () {
    $("#excelbtn").click(function(){
        TableToExcel.convert(document.getElementById("salestable"), {
            name: "Scheme Loans.xlsx",
            sheet: {
            name: "Sheet1"
            }
        });
        });
});
</script>
@endsection