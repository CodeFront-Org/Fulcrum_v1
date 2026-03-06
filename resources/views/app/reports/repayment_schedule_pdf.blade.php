<!DOCTYPE html>
<html>
<head>
    <title>Repayment Schedule - {{ $company->name }}</title>
    <style>
        @page {
            margin: 30pt 40pt 50pt 40pt;
        }
        body { 
            font-family: 'Helvetica', 'Arial', sans-serif; 
            font-size: 10.5pt; 
            line-height: 1.4; 
            color: #333; 
            margin: 0; 
            padding: 0; 
        }
        
        /* Header Section */
        .pdf-header {
            border-bottom: 2px solid #1a4d8c;
            padding-bottom: 10px;
            margin-bottom: 25px;
            height: 70px;
        }
        .logo-container {
            float: left;
            width: 150px;
        }
        .logo-container img {
            max-height: 60px;
            width: auto;
        }
        .header-text {
            float: right;
            text-align: right;
            width: 400px;
        }
        .company-name { 
            font-weight: 800; 
            font-size: 18pt; 
            color: #1a4d8c;
            margin: 0;
            text-transform: uppercase;
        }
        .report-type {
            font-size: 11pt;
            color: #666;
            margin-top: 5px;
            font-weight: 600;
        }
        .clear { clear: both; }

        /* Report Metadata */
        .report-info {
            margin-bottom: 20px;
        }
        .report-title { 
            font-weight: bold; 
            font-size: 13pt; 
            color: #2c3e50;
            margin-bottom: 5px;
        }
        .report-date-meta {
            font-size: 9.5pt;
            color: #555;
        }

        /* Table Styling */
        table { 
            width: 100%; 
            border-collapse: collapse; 
            margin-bottom: 20px; 
            table-layout: fixed;
            box-shadow: 0 1px 3px rgba(0,0,0,0.1);
        }
        th { 
            background-color: #1a4d8c; 
            color: white; 
            font-weight: 700; 
            text-align: left; 
            padding: 10px 6px;
            font-size: 8.5pt;
            text-transform: uppercase;
            border: 1px solid #1a4d8c;
        }
        td { 
            border: 1px solid #dee2e6; 
            padding: 4px 6px; 
            font-size: 8.5pt; 
            vertical-align: middle; 
            word-wrap: break-word; 
        }
        tr:nth-child(even) { background-color: #f8f9fa; }
        
        .text-right { text-align: right; }
        .text-center { text-align: center; }
        
        .subtotal-row {
            background-color: #e9ecef !important;
            font-weight: bold;
        }
        .subtotal-row td {
            border-top: 2px solid #1a4d8c;
            color: #1a4d8c;
            font-size: 9pt;
        }

        /* Bottom Sections */
        .details-wrapper {
            margin-top: 20px;
        }
        .payment-account { 
            width: 45%;
            float: left;
            background: #f1f4f9;
            padding: 10px;
            border-radius: 8px;
            border-left: 4px solid #1a4d8c;
            font-size: 9pt;
        }
        .signature-section { 
            width: 45%;
            float: right;
            margin-top: 0;
        }
        .signature-box {
            border-top: 1px solid #999;
            margin-top: 30px;
            padding-top: 6px;
            font-size: 9pt;
        }
        .signature-label {
            font-weight: bold;
            color: #2c3e50;
        }
        
        /* Footer/Pagination */
        .footer {
            position: fixed;
            bottom: -30px;
            left: 0;
            right: 0;
            height: 30px;
            font-size: 8pt;
            color: #999;
            text-align: center;
            border-top: 1px solid #eee;
            padding-top: 5px;
        }
    </style>
</head>
<body>
    <div class="pdf-header">
        <div class="logo-container">
            @php
                $path = public_path('images/logo.jpeg');
                $type = pathinfo($path, PATHINFO_EXTENSION);
                $data = file_get_contents($path);
                $base64 = 'data:image/' . $type . ';base64,' . base64_encode($data);
            @endphp
            <img src="{{ $base64 }}" alt="Logo">
        </div>
        <div class="header-text">
            <div class="company-name">{{ $company->name }}</div>
            <div class="report-type">Financial Repayment Schedule</div>
        </div>
        <div class="clear"></div>
    </div>
    
    <div class="report-info">
        <div class="report-title">Staff Loan Repayment Portfolio</div>
        <div class="report-date-meta">
            Period Ending: <strong>{{ $month_ending }}</strong> | Generated: {{ date('d M Y') }}
        </div>
    </div>
    
    <table>
        <thead>
            <tr>
                <th style="width: 5%;">#</th>
                <th style="width: 25%;">Customer Name</th>
                <th class="text-right" style="width: 15%;">Principal (KSH)</th>
                <th class="text-center" style="width: 12%;">Disbursed</th>
                <th class="text-center" style="width: 9%;">Tenor</th>
                <th class="text-center" style="width: 8%;">Rate</th>
                <th class="text-center" style="width: 9%;">Inst. #</th>
                <th class="text-right" style="width: 17%;">Payable (KSH)</th>
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
                    <td class="text-center text-muted">{{ $index + 1 }}</td>
                    <td style="font-weight: bold;">
                        {{ $loan->user->first_name . ' ' . $loan->user->last_name }}
                    </td>
                    <td class="text-right">{{ number_format($loan->requested_loan_amount) }}</td>
                    <td class="text-center">{{ $loan->disbursed_date_formatted }}</td>
                    <td class="text-center">{{ $loan->payment_period }} Mo</td>
                    <td class="text-center">{{ $loan->interest_rate }}%</td>
                    <td class="text-center">{{ $loan->installment_number }}</td>
                    <td class="text-right" style="font-weight: bold;">{{ number_format($loan->installment_amount) }}</td>
                </tr>
            @endforeach
            <tr class="subtotal-row">
                <td colspan="2" class="text-right">TOTAL PORTFOLIO AMOUNT</td>
                <td class="text-right">{{ number_format($total_principal) }}</td>
                <td colspan="4"></td>
                <td class="text-right">{{ number_format($total_repayment) }}</td>
            </tr>
        </tbody>
    </table>

    <div class="details-wrapper">
        <div class="payment-account">
            <div style="font-weight: bold; color: #1a4d8c; margin-bottom: 8px; text-decoration: underline;">REMITTANCE INSTRUCTIONS</div>
            {!! nl2br(e($payment_details)) !!}
        </div>

        <div class="signature-section">
            <div class="signature-box">
                <div class="signature-label">Authorized Signatory</div>
                <div style="margin-top: 4px;">Josephine Nderitu (Mrs.)</div>
                <div style="font-size: 8.5pt; color: #666;">General Manager - Fulcrum Link Ltd</div>
            </div>
            <div class="signature-box" style="margin-top: 25px;">
                <div class="signature-label">Date of Approval</div>
                <div style="color: #999;">__________________________</div>
            </div>
        </div>
        <div class="clear"></div>
    </div>

    <div class="footer">
        This is an automated financial report generated by the Fulcrum Credit Management System. Page 1 of 1
    </div>
</body>
</html>
