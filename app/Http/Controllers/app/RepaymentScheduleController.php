<?php

namespace App\Http\Controllers\app;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Company;
use App\Models\Loan;
use barryvdh\DomPDF\Facade\Pdf;
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

        // Header
        $sheet->setCellValue('A1', $company->name . ' - Repayment Schedule');
        $sheet->mergeCells('A1:F1');

        // Table headers
        $headers = ['Customer Name', 'Loan Principal', 'Disbursed Date', 'Interest Rate', 'Installments', 'Repayment Amount'];
        $col = 'A';
        foreach ($headers as $header) {
            $sheet->setCellValue($col . '3', $header);
            $col++;
        }

        // Data
        $row = 4;
        foreach ($loans as $loan) {
            $sheet->setCellValue('A' . $row, $loan->user->first_name . ' ' . $loan->user->last_name);
            $sheet->setCellValue('B' . $row, number_format($loan->requested_loan_amount, 2));
            $sheet->setCellValue('C' . $row, $loan->created_at->format('Y-m-d'));
            $sheet->setCellValue('D' . $row, '10%');
            $sheet->setCellValue('E' . $row, $loan->payment_period);
            $sheet->setCellValue('F' . $row, number_format($loan->amount_payable, 2));
            $row++;
        }

        $writer = new Xlsx($spreadsheet);
        $filename = 'repayment_schedule_' . $company->name . '.xlsx';
        
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="' . $filename . '"');
        $writer->save('php://output');
    }
}