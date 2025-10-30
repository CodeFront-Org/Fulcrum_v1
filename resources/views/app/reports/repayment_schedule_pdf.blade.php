<!DOCTYPE html>
<html>
<head>
    <title>Repayment Schedule - {{ $company->name }}</title>
    <style>
        body { font-family: Arial, sans-serif; font-size: 12px; }
        .header { text-align: center; margin-bottom: 20px; font-size: 16px; font-weight: bold; }
        table { width: 100%; border-collapse: collapse; }
        th, td { border: 1px solid #ddd; padding: 8px; text-align: left; }
        th { background-color: #f2f2f2; font-weight: bold; }
        .amount { text-align: right; }
    </style>
</head>
<body>
    <div class="header">{{ $company->name }} - Repayment Schedule</div>
    
    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Customer Name</th>
                <th>Loan Principal</th>
                <th>Disbursed Date</th>
                <th>Interest Rate</th>
                <th>Installments</th>
                <th>Repayment Amount</th>
            </tr>
        </thead>
        <tbody>
            @foreach($loans as $loan)
            <tr>
                <td>{{ $loan->id}}</td>
                <td>{{ $loan->user->first_name }} {{ $loan->user->last_name }}</td>
                <td class="amount">{{ number_format($loan->requested_loan_amount, 2) }}</td>
                <td>{{ $loan->created_at->format('Y-m-d') }}</td>
              

                {{--add the company model --}}
          
                    @php
                        $company = \App\Models\Company::find($loan->company_id);

                    // Store month fields that are not null
                    $periods = [
                        0,
                        $company->month1,
                        $company->month2,
                        $company->month3,
                        $company->month4,
                        $company->month5,
                        $company->month6,
                        $company->month7,
                        $company->month8,
                        $company->month9,
                        $company->month10,
                        $company->month11,
                        $company->month12
                       ];
                @endphp
               
                 <td>{{ $periods[$loan->payment_period]*100  ?? 'N/A' }}%</td>
                <td>{{ $loan->current_payment_period }}</td>
                <td class="amount">{{ number_format($loan->amount_payable, 2) }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>