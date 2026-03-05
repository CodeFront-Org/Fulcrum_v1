<!DOCTYPE html>
<html>
<head>
    <title>Repayment Schedule - {{ $company->name }}</title>
    <style>
        body { font-family: 'Times New Roman', Times, serif; font-size: 10pt; line-height: 1.4; color: black; margin: 0; padding: 0.5in; }
        .company-name { font-weight: bold; font-family: Arial, sans-serif; font-size: 12pt; margin-bottom: 20px; text-transform: uppercase; }
        .report-title { font-weight: bold; font-size: 11pt; text-decoration: underline; margin-bottom: 20px; }
        table { width: 100%; border-collapse: collapse; margin-bottom: 15px; }
        th, td { border: 1px solid black; padding: 4px 6px; font-size: 9pt; vertical-align: middle; }
        th { font-weight: bold; text-align: left; }
        .text-right { text-align: right; }
        .text-center { text-align: center; }
        .subtotal-row td { font-weight: bold; }
        .note { margin-top: 15px; font-size: 9.5pt; font-style: normal; }
        .payment-account { margin-top: 20px; font-size: 9.5pt; }
        .payment-account strong { text-decoration: underline; }
        .signature-section { margin-top: 30px; }
        .signature-line { margin-top: 15px; }
    </style>
</head>
<body>
    <div class="company-name">{{ $company->name }}</div>
    
    <div class="report-title">Personal staff loans for the month ending {{ $month_ending }}</div>
    
    <table>
        <thead>
            <tr>
                <th style="width: 30px;">No.</th>
                <th>Customer Name</th>
                <th>Company</th>
                <th class="text-right">Loan Principal Amount (Kshs.)</th>
                <th class="text-center">Date Loan Disbursed</th>
                <th class="text-center">Loan Tenor (in months)</th>
                <th class="text-center">Interest Rate</th>
                <th class="text-center">Instalments Number</th>
                <th class="text-right">Loan Repayment Amount (Kshs.)<br>{{ $report_date }}</th>
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
        <strong>Payment account</strong><br>
        Account Name: Fulcrum Link Ltd<br>
        Account No. <strong>4904000017</strong><br>
        Bank: NCBA<br>
        Branch: ABC
    </div>

    <div class="signature-section">
        <div class="signature-line">Josephine Nderitu (Mrs.) -----------------------------------------------------------------</div>
        <div class="signature-line">Date: -------------------------------------------------------------</div>
        <div class="signature-line" style="margin-top: 25px;">General Manager - Fulcrum Link Ltd</div>
    </div>
</body>
</html>
