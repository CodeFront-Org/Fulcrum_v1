<?php

namespace App\Http\Controllers\app;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Company;
use App\Models\Loan;
use Barryvdh\DomPDF\Facade\Pdf;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

class RepaymentScheduleController extends Controller
{
    public function generatePDF($scheme_id, Request $request)
    {
        $company = Company::findOrFail($scheme_id);
        $data = $this->getReportData($company, $request);
        $loans = $data['loans'];
        $month_ending = $data['month_ending'];
        $report_date = $data['report_date'];
        $payment_details = $request->payment_details;

        $pdf = Pdf::loadView('app.reports.repayment_schedule_pdf', compact('company', 'loans', 'month_ending', 'report_date', 'payment_details'));
        $pdf->setPaper('A4', 'portrait');
        return $pdf->download('repayment_schedule_' . $company->name . '.pdf');
    }

    private function getReportData($company, Request $request)
    {
        $month = $request->month ?? now()->format('F');
        $year = $request->year ?? now()->year;

        $toDate = \Carbon\Carbon::parse("first day of $month $year")->endOfMonth();
        $fromDate = $toDate->copy()->startOfMonth();

        $query = Loan::with([
            'user',
            'repayments' => function ($q) use ($month, $year) {
                $q->where('month', $month)->where('year', $year);
            }
        ])->whereHas('repayments', function ($q) use ($month, $year) {
            $q->where('month', $month)->where('year', $year);
        })->where('company_id', $company->id)->where('final_decision', 1);

        $loansFiltered = $query->get();

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

        foreach ($loansFiltered as $loan) {
            $disbursedDate = \Carbon\Carbon::parse($loan->approver3_date);
            $reportMonth = $toDate;
            $monthsElapsed = $disbursedDate->diffInMonths($reportMonth) + 1;
            $currentPeriod = min($monthsElapsed, $loan->payment_period);

            $loan->interest_rate = ($periods[$loan->payment_period] ?? 0) * 100;
            $loan->installment_number = $currentPeriod;

            $repaymentInPeriod = $loan->repayments->first();
            $loan->installment_amount = $repaymentInPeriod ? $repaymentInPeriod->installments : $loan->monthly_installments;
            $loan->disbursed_date_formatted = $loan->approver3_date ? \Carbon\Carbon::parse($loan->approver3_date)->format('d/m/Y') : '';
        }

        return [
            'loans' => $loansFiltered,
            'month_ending' => $toDate->format('d/m/Y'),
            'report_date' => $toDate->format('d/m/Y')
        ];
    }

    public function generateExcel($scheme_id, Request $request)
    {
        $company = Company::findOrFail($scheme_id);
        $data = $this->getReportData($company, $request);
        $loans = $data['loans'];
        $month_ending = $data['month_ending'];
        $report_date = $data['report_date'];

        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        // Company name
        $sheet->setCellValue('A1', $company->name);
        $sheet->getStyle('A1')->getFont()->setBold(true);

        // Title
        $sheet->setCellValue('A2', 'Personal staff loans for the month ending ' . $month_ending);
        $sheet->getStyle('A2')->getFont()->setBold(true)->setUnderline(true);

        // Table headers
        $headers = [
            'No.',
            'Customer Name',
            'Company',
            'Loan Principal Amount (Kshs.)',
            'Date Loan Disbursed',
            'Loan Tenor (in months)',
            'Interest Rate',
            'Instalments Number',
            'Loan Repayment Amount (Kshs.) ' . $report_date
        ];

        $col = 'A';
        foreach ($headers as $header) {
            $sheet->setCellValue($col . '4', $header);
            $sheet->getStyle($col . '4')->getFont()->setBold(true);
            $sheet->getStyle($col . '4')->getBorders()->getAllBorders()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
            $col++;
        }

        // Data
        $row = 5;
        $no = 1;
        $total_principal = 0;
        $total_repayment = 0;

        foreach ($loans as $loan) {
            $sheet->setCellValue('A' . $row, $no);
            $sheet->setCellValue('B' . $row, $loan->user->first_name . ' ' . $loan->user->last_name);
            $sheet->setCellValue('C' . $row, $company->name);
            $sheet->setCellValue('D' . $row, $loan->requested_loan_amount);
            $sheet->setCellValue('E' . $row, $loan->disbursed_date_formatted);
            $sheet->setCellValue('F' . $row, $loan->payment_period);
            $sheet->setCellValue('G' . $row, $loan->interest_rate . '%');
            $sheet->setCellValue('H' . $row, $loan->installment_number);
            $sheet->setCellValue('I' . $row, $loan->installment_amount);

            $total_principal += $loan->requested_loan_amount;
            $total_repayment += $loan->installment_amount;

            // Borders
            for ($c = 'A'; $c <= 'I'; $c++) {
                $sheet->getStyle($c . $row)->getBorders()->getAllBorders()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
            }

            $row++;
            $no++;
        }

        // Total row
        $sheet->setCellValue('A' . $row, 'Sub-Total Amount');
        $sheet->setCellValue('D' . $row, $total_principal);
        $sheet->setCellValue('I' . $row, $total_repayment);
        $sheet->getStyle('A' . $row . ':I' . $row)->getFont()->setBold(true);
        for ($c = 'A'; $c <= 'I'; $c++) {
            $sheet->getStyle($c . $row)->getBorders()->getAllBorders()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
        }

        // Payment details
        $row += 2;
        $payment_details = $request->payment_details;
        if ($payment_details) {
            $details_lines = explode("\n", $payment_details);
            foreach ($details_lines as $line) {
                $sheet->setCellValue('A' . $row, trim($line));
                if (str_contains(strtolower($line), 'payment account')) {
                    $sheet->getStyle('A' . $row)->getFont()->setBold(true)->setUnderline(true);
                }
                $row++;
            }
        }

        $row += 1;
        $sheet->setCellValue('A' . $row, 'Josephine Nderitu (Mrs.) ---------------------------------------------');
        $row += 2;
        $sheet->setCellValue('A' . $row, 'Date: -----------------------------------------');
        $row += 2;
        $sheet->setCellValue('A' . $row, 'General Manager - Fulcrum Link Ltd');

        // Auto size columns
        foreach (range('A', 'I') as $col) {
            $sheet->getColumnDimension($col)->setAutoSize(true);
        }

        $writer = new Xlsx($spreadsheet);
        $filename = 'repayment_schedule_' . $company->name . '.xlsx';

        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="' . $filename . '"');
        $writer->save('php://output');
    }

    private function getMonthsBetween($fromDate, $toDate)
    {
        $months = ['January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December'];
        $fromMonth = (int) $fromDate->format('n');
        $toMonth = (int) $toDate->format('n');
        return array_slice($months, $fromMonth - 1, $toMonth - $fromMonth + 1);
    }

    private function getMonthsFrom($fromDate)
    {
        $months = ['January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December'];
        $fromMonth = (int) $fromDate->format('n');
        return array_slice($months, $fromMonth - 1);
    }

    private function getMonthsUntil($toDate)
    {
        $months = ['January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December'];
        $toMonth = (int) $toDate->format('n');
        return array_slice($months, 0, $toMonth);
    }
}