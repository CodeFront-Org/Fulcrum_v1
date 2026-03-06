<!DOCTYPE html>
<html>
<head>
    <title>Repayment Schedule - {{ $company->name }}</title>
    <style>
        @page {
            margin: 20pt 30pt;
        }
        body { 
            font-family: 'Times New Roman', Times, serif; 
            font-size: 11pt; 
            line-height: 1.3; 
            color: black; 
            margin: 0; 
            padding: 0; 
        }
        .company-name { font-weight: bold; font-family: Arial, sans-serif; font-size: 14pt; margin-bottom: 10px; text-transform: uppercase; }
        .report-title { font-weight: bold; font-size: 12pt; text-decoration: underline; margin-bottom: 15px; }
        table { width: 100%; border-collapse: collapse; margin-bottom: 15px; table-layout: fixed; }
        th, td { border: 1px solid black; padding: 4px 6px; font-size: 9.5pt; vertical-align: middle; word-wrap: break-word; }
        th { font-weight: bold; text-align: left; background-color: #f2f2f2; }
        .text-right { text-align: right; }
        .text-center { text-align: center; }
        .subtotal-row td { font-weight: bold; background-color: #f9f9f9; }
        .note { margin-top: 10px; font-size: 10pt; }
        .payment-account { margin-top: 15px; font-size: 10pt; }
        .payment-account strong { text-decoration: underline; }
        .signature-section { margin-top: 25px; }
        .signature-line { margin-top: 10px; }
    </style>
</head>
<body>
    <div class="company-name">{{ $company->name }}</div>
    
    <div class="report-title">Personal staff loans for the month ending {{ $month_ending }}</div>
    
    <table>
        <thead>
            <tr>
                <th style="width: 3%;">No.</th>
                <th style="width: 20%;">Customer Name</th>
                <th style="width: 12%;">Company</th>
                <th class="text-right" style="width: 13%;">Loan Principal Amount (Kshs.)</th>
                <th class="text-center" style="width: 10%;">Date Loan Disbursed</th>
                <th class="text-center" style="width: 9%;">Loan Tenor (in months)</th>
                <th class="text-center" style="width: 8%;">Interest Rate</th>
                <th class="text-center" style="width: 10%;">Instalments Number</th>
                <th class="text-right" style="width: 15%;">Loan Repayment Amount (Kshs.)<br>{{ $report_date }}</th>
            </tr>
        </thead>
        <tbody>
            @php $total_principal = 0; $total_repayment = 0; @endphp
            @foreach($loans as $index => $loan)
                @php 
                    $total_principal += $loan->requested_loan_amount; 
                    $total_repayment += $loan->installment_amount;
                @endphp
                <tr>
                    <td class="text-center">{{ $index + 1 }}</td>
                    <td>{{ $loan->user->first_name }} {{ $loan->user->last_name }}</td>
                    <td>{{ $company->name }}</td>
                    <td class="text-right">{{ number_format($loan->requested_loan_amount) }}</td>
                    <td class="text-center">{{ $loan->disbursed_date_formatted }}</td>
                    <td class="text-center">{{ $loan->payment_period }}</td>
                    <td class="text-center">{{ $loan->interest_rate }}%</td>
                    <td class="text-center">{{ $loan->installment_number }}</td>
                    <td class="text-right">{{ number_format($loan->installment_amount) }}</td>
                </tr>
            @endforeach
            <tr class="subtotal-row">
                <td colspan="3">Sub-Total Amount</td>
                <td class="text-right">{{ number_format($total_principal) }}</td>
                <td colspan="4"></td>
                <td class="text-right">{{ number_format($total_repayment) }}</td>
            </tr>
        </tbody>
    </table>


    <div class="payment-account">
        {!! nl2br(e($payment_details)) !!}
    </div>

    <div class="signature-section">
        <div class="signature-line">Josephine Nderitu (Mrs.) -----------------------------------------------------------------</div>
        <div class="signature-line">Date: -------------------------------------------------------------</div>
        <div class="signature-line" style="margin-top: 25px;">General Manager - Fulcrum Link Ltd</div>
    </div>
</body>
</html>
