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
    <div class="col-xl-3 col-md-6 mb-4">
        <div class="card border-left-primary shadow h-100 py-2">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">Total Requests</div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800">{{$tot_request}}</div>
                    </div>
                    <div class="col-auto">
                        <i class="fas fa- fa-2x text-gray-300"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-xl-3 col-md-6 mb-4">
        <div class="card border-left-success shadow h-100 py-2">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <div class="text-xs font-weight-bold text-success text-uppercase mb-1">Total Loan Issued</div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800">{{ number_format((float) $tot_amt) }}</div>
                    </div>
                    <div class="col-auto">
                        <i class="fas fa-dollar-sign fa-2x text-gray-300"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Remaining 2 cards omitted for brevity -->
</div>

<!-- Example of formatted loan values in modal -->
<span>{{ number_format((float) $item->gross_salary) }}</span>
<span>{{ number_format((float) $item->net_salary) }}</span>
<span>{{ number_format((float) $item->other_allowances) }}</span>
<span class="text-danger">{{ number_format((float) $item->outstanding_loan_balance) }}</span>
<span>{{ number_format((float) $item->requested_loan_amount) }}</span>
<span>{{ number_format((float) $item->monthly_installments) }}</span>

@endsection