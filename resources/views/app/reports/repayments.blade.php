@extends('layouts.app')

@section('content')

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">



    {{-- Filter Form --}}
    @if ($errors->any())
    <div class="alert alert-danger">
        <strong>There were some problems with your input:</strong>
        <ul>
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

    <form method="GET" action="{{ route('repayments.index') }}" class="mb-4 row g-3">
        <div class="col-md-2">
            <label>Company</label>
            <select name="company_id" class="form-control">
                <option value="">-- All Companies --</option>
                @foreach($companies as $company)
                    <option value="{{ $company->id }}" {{ request('company_id') == $company->id ? 'selected' : '' }}>
                        {{ $company->name }}
                    </option>
                @endforeach
            </select>
        </div>
        <div class="col-md-2">
            <label>User</label>
            <select name="user_id" class="form-control">
                <option value="">-- All Users --</option>
                @foreach($users as $user)
                    <option value="{{ $user->id }}" {{ request('user_id') == $user->id ? 'selected' : '' }}>
                        {{ $user->first_name }} {{ $user->last_name }}
                    </option>
                @endforeach
            </select>
        </div>



        <div class="col-md-2">
            <label>Month</label>
            <select name="month" class="form-control">
                <option value="">-- All Months --</option>
                @foreach($months as $month)
                    <option value="{{ $month }}" {{ request('month') == $month ? 'selected' : '' }}>{{ $month }}</option>
                @endforeach
            </select>
        </div>
        <div class="col-md-2">
            <label>Month</label>
              <label>Year</label>
                <select name="year" class="form-control">
                    <option value="">-- All Years --</option>
                    @foreach($years as $year)
                        <option value="{{ $year }}" {{ request('year') == $year ? 'selected' : '' }}>{{ $year }}</option>
                    @endforeach
                </select>
        </div>

        <div class="col-md-2">
            <label>Status</label>
            <select name="status" class="form-control">
                <option value="">-- All Statuses --</option>
                <option value="0" {{ request('status') === '0' ? 'selected' : '' }}>Pending</option>
                <option value="1" {{ request('status') === '1' ? 'selected' : '' }}>Paid</option>
                <option value="3" {{ request('status') === '3' ? 'selected' : '' }}>Closed-byTopUp</option>
                <option value="4" {{ request('status') === '4' ? 'selected' : '' }}>Written-Off</option>
            </select>
        </div>

        <div class="col-md-3 d-flex align-items-end">
            <button type="submit" class="btn btn-primary">Filter</button>
        </div>
    </form>


   <div class="mb-4">
    <h5>Summary (Filtered Totals)</h5>
    <div class="row">
        <div class="col-md-3">
            <div class="alert alert-warning">
                Loan: <strong>{{ number_format($summary['pending']) }}</strong>
            </div>
        </div>
        <div class="col-md-3">
            <div class="alert alert-success">
                 Paid: <strong>{{ number_format($summary['paid']) }}</strong>
            </div>
        </div>
        <div class="col-md-3">
            <div class="alert alert-danger">
                Written-Off: <strong>{{ number_format($summary['written_off']) }}</strong>
            </div>
        </div>
    </div>
 </div>



    {{-- Success Message --}}
    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    {{-- Repayments Table --}}
    <table class="table table-bordered ">
        <thead class="thead-dark">
            <tr>
                <th>ID</th>
                <th>User ID</th>
                <th>Loan ID</th>
                <th>Company</th>
                <th>Month</th>
                <th>Date</th>
                <th>Loan Amount</th>
                <th>Installments</th>
                <th>Period</th>
                <th>Status</th>
                <th>Comments</th>
                <th>Actions</th>
            </tr>
        </thead>
       <tbody>
@foreach($repayments as $repayment)
    @php
        $rowClass = match($repayment->status) {
            0 => 'table-warning',     // Pending - Amber/Orange
            1 => 'table-success',     // Paid - Green
            3 => 'table-primary',     // Closed-byTopUp - Blue
            2 => 'table-secondary', // Closed - Grey           
            4 => 'table-danger',      // Written-Off - Red
            default => ''
        };
    @endphp

    <tr class="{{ $rowClass }}">
        <form action="{{ route('repayments.update', $repayment->id) }}" method="POST">
            @csrf
            @method('PUT')

            <td>{{ $repayment->id }}</td>
            <td>
                <input type="text" readonly class="form-control-plaintext" 
                       value="{{ $repayment->user->first_name ?? ' **'  }} {{ $repayment->user->last_name ?? ' *** '  }}">
            </td>
            <td><input type="text" readonly class="form-control-plaintext" value="{{ $repayment->loan_id }}"></td>
            <td><input type="text" readonly class="form-control-plaintext" value="{{ $repayment->company->name ?? 'N/A' }}"></td>

            <td>
                <select name="month" class="form-control">
                    @foreach($months as $m)
                        <option value="{{ $m }}" {{ $repayment->month == $m ? 'selected' : '' }}>{{ $m }}</option>
                    @endforeach
                </select>
            </td>

            <td><input type="text" readonly class="form-control-plaintext" value="{{ $repayment->created_at }}"></td>
            <td><input type="text" readonly class="form-control-plaintext" value="{{ number_format($repayment->loan_amount) }}"></td>
            <td><input type="text" readonly class="form-control-plaintext" value="{{ number_format($repayment->installments) }}"></td>
            <td><input type="text" readonly class="form-control-plaintext" value="{{ $repayment->period }}"></td>

            <td>
                <select name="status" class="form-control">
                    <option value="0" {{ $repayment->status == 0 ? 'selected' : '' }}>Pending</option>
                    <option value="1" {{ $repayment->status == 1 ? 'selected' : '' }}>Paid</option>
                    <option value="3" {{ $repayment->status == 3 ? 'selected' : '' }}>Closed-byTopUp</option>
                    <option value="4" {{ $repayment->status == 4 ? 'selected' : '' }}>Written-Off</option>
                </select>
            </td>

            <td>
                <input type="text" name="comments" class="form-control" value="{{ $repayment->comments }}">
            </td>

            <td>
                <button type="submit" class="btn btn-sm btn-primary">Update</button>
            </td>
        </form>
    </tr>
@endforeach
</tbody>

    </table>

<div class="d-flex justify-content-center">
    {!! $repayments->links() !!}
</div>


@endsection
