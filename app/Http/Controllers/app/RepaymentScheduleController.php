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
        $query = Loan::with('user')->where('company_id', $scheme_id)->where('final_decision', 1);
        
        if ($request->from_date || $request->to_date) {
            $loanIds = \App\Models\Repayment::query();
            
            if ($request->from_date) {
                $fromDate = \Carbon\Carbon::parse($request->from_date);
                $loanIds->where(function($q) use ($fromDate) {
                    $q->where('year', '>', $fromDate->year)
                      ->orWhere(function($subQ) use ($fromDate) {
                          $subQ->where('year', $fromDate->year)
                               ->whereIn('month', ['September', 'October', 'November', 'December']);
                      });
                });
            }
            
            if ($request->to_date) {
                $toDate = \Carbon\Carbon::parse($request->to_date);
                $loanIds->where(function($q) use ($toDate) {
                    $q->where('year', '<', $toDate->year)
                      ->orWhere(function($subQ) use ($toDate) {
                          $subQ->where('year', $toDate->year)
                               ->whereIn('month', ['September', 'October', 'November', 'December']);
                      });
                });
            }
            
            $validLoanIds = $loanIds->pluck('loan_id')->unique();
            $query->whereIn('id', $validLoanIds);
        }
        
        $loans = $query->get();
        
        // Calculate current payment period for each loan
        foreach ($loans as $loan) {
            $paidCount = \App\Models\Repayment::where('loan_id', $loan->id)
                ->where('status', 1)
                ->count();
            
            $currentPeriod = $paidCount + 1;
            $currentPeriod = min($currentPeriod, $loan->payment_period);
            $loan->current_payment_period = $currentPeriod . '/' . $loan->payment_period;
        }
        
       $pdf = Pdf::loadView('app.reports.repayment_schedule_pdf', compact('company', 'loans'));
        return $pdf->download('repayment_schedule_' . $company->name . '.pdf');
    }

    public function generateExcel($scheme_id, Request $request)
    {
        $company = Company::findOrFail($scheme_id);
        $query = Loan::with('user')->where('company_id', $scheme_id)->where('final_decision', 1);
        
        if ($request->from_date) {
            $query->where('created_at', '>=', $request->from_date);
        }
        if ($request->to_date) {
            $query->where('created_at', '<=', $request->to_date);
        }
        
        $loans = $query->get();

        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        // Company name
        $sheet->setCellValue('B1', strtoupper($company->name));
        
        // Title
        $sheet->setCellValue('B3', 'REPAYMENT SCHEDULE');
        
        // Subtitle
        $monthEnding = \Carbon\Carbon::parse($request->to_date)->format('d F Y');
        $sheet->setCellValue('B5', 'Personal staff loans for the month ending ' . $monthEnding);
        
        // Table headers
        $sheet->setCellValue('A7', 'No.');
        $sheet->setCellValue('B7', 'Customer Name');
        $sheet->setCellValue('C7', 'Company');
        $sheet->setCellValue('D7', 'Loan Principal Amount (Kshs.)');
        $sheet->setCellValue('E7', 'Date Loan Disbursed');
        $sheet->setCellValue('F7', 'Loan Tenor (in months)');
        $sheet->setCellValue('G7', 'Interest Rate');
        $sheet->setCellValue('H7', 'Instalments Number');
        $reportDate = $request->to_date ? \Carbon\Carbon::parse($request->to_date)->format('d-m-Y') : now()->format('d-m-Y');
        $sheet->setCellValue('I7', 'Loan Repayment Amount (Kshs.)        ' . $reportDate);

        // Data
        $row = 8;
        $no = 1;
        foreach ($loans as $loan) {
            $paidCount = \App\Models\Repayment::where('loan_id', $loan->id)->where('status', 1)->count();
            $currentPeriod = min($paidCount + 1, $loan->payment_period);
            
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
            
            $interestRate = $periods[$loan->payment_period] ?? 0;
            
            $sheet->setCellValue('A' . $row, $no);
            $sheet->setCellValue('B' . $row, $loan->user->first_name . ' ' . $loan->user->last_name);
            $sheet->setCellValue('C' . $row, $company->name);
            $sheet->setCellValue('D' . $row, $loan->requested_loan_amount);
            $sheet->setCellValue('E' . $row, $loan->created_at->format('d/m/Y'));
            $sheet->setCellValue('F' . $row, $loan->payment_period);
            $sheet->setCellValue('G' . $row, $interestRate);
            $sheet->setCellValue('H' . $row, $currentPeriod);
            $sheet->setCellValue('I' . $row, $loan->amount_payable);
            
            $row++;
            $no++;
        }

        // Total row
        $sheet->setCellValue('B' . $row, 'Total Loan Repayments for month ending ' . $monthEnding);
        $sheet->setCellValue('D' . $row, '=SUM(D8:D' . ($row - 1) . ')');
        $sheet->setCellValue('I' . $row, '=SUM(I8:I' . ($row - 1) . ')');
        
        // Payment details
        $row += 3;
        $sheet->setCellValue('B' . $row, 'Payment account');
        $row++;
        $sheet->setCellValue('B' . $row, 'Account Name: Fulcrum Link Ltd');
        $row++;
        $sheet->setCellValue('B' . $row, 'Account No. 4904000017');
        $row++;
        $sheet->setCellValue('B' . $row, 'Bank: NCBA');
        $row++;
        $sheet->setCellValue('B' . $row, 'Branch: ABC');
        $row += 2;
        $sheet->setCellValue('B' . $row, 'Josephine Nderitu (Mrs.) ---------------------------------------------');
        $row += 2;
        $sheet->setCellValue('B' . $row, 'Date: -----------------------------------------');
        $row += 2;
        $sheet->setCellValue('B' . $row, 'General Manager - Fulcrum Link Ltd');

        $writer = new Xlsx($spreadsheet);
        $filename = 'repayment_schedule_' . $company->name . '.xlsx';
        
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="' . $filename . '"');
        $writer->save('php://output');
    }
}