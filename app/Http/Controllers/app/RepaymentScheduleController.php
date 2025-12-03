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
            if ($request->from_date) {
                $query->where('approver3_date', '>=', $request->from_date);
            }
            if ($request->to_date) {
                $query->where('approver3_date', '<=', $request->to_date);
            }
            
            $query->whereHas('repayments', function($repaymentQ) use ($request) {
                if ($request->from_date && $request->to_date) {
                    $fromDate = \Carbon\Carbon::parse($request->from_date);
                    $toDate = \Carbon\Carbon::parse($request->to_date);
                    
                    $repaymentQ->where(function($dateQ) use ($fromDate, $toDate) {
                        $dateQ->whereBetween('year', [$fromDate->year, $toDate->year])
                              ->where(function($monthQ) use ($fromDate, $toDate) {
                                  if ($fromDate->year == $toDate->year) {
                                      $monthQ->whereIn('month', $this->getMonthsBetween($fromDate, $toDate));
                                  } else {
                                      $monthQ->where(function($q) use ($fromDate, $toDate) {
                                          $q->where('year', $fromDate->year)
                                            ->whereIn('month', $this->getMonthsFrom($fromDate))
                                            ->orWhere(function($q2) use ($toDate) {
                                                $q2->where('year', $toDate->year)
                                                   ->whereIn('month', $this->getMonthsUntil($toDate));
                                            });
                                      });
                                  }
                              });
                    });
                }
            });
        }
        
        $loans = $query->get();
        
        $fromDate = $request->from_date ? \Carbon\Carbon::parse($request->from_date) : null;
        $toDate = $request->to_date ? \Carbon\Carbon::parse($request->to_date) : null;
        
        // Calculate current payment period for each loan
        foreach ($loans as $loan) {
            $repaymentInPeriod = \App\Models\Repayment::where('loan_id', $loan->id)
                ->where(function($q) use ($fromDate, $toDate) {
                    if ($fromDate && $toDate) {
                        $q->whereBetween('year', [$fromDate->year, $toDate->year])
                          ->whereIn('month', $this->getMonthsBetween($fromDate, $toDate));
                    }
                })
                ->first();
            
            $currentPeriod = $repaymentInPeriod ? $repaymentInPeriod->period : 1;
            $loan->current_payment_period = $currentPeriod . '/' . $loan->payment_period;
        }
        
       $pdf = Pdf::loadView('app.reports.repayment_schedule_pdf', compact('company', 'loans'));
        return $pdf->download('repayment_schedule_' . $company->name . '.pdf');
    }

    public function generateExcel($scheme_id, Request $request)
    {
        $company = Company::findOrFail($scheme_id);
        $query = Loan::with('user')->where('company_id', $scheme_id)->where('final_decision', 1);
        
        if ($request->from_date || $request->to_date) {
            if ($request->from_date) {
                $query->where('approver3_date', '>=', $request->from_date);
            }
            if ($request->to_date) {
                $query->where('approver3_date', '<=', $request->to_date);
            }
            
            $query->whereHas('repayments', function($repaymentQ) use ($request) {
                if ($request->from_date && $request->to_date) {
                    $fromDate = \Carbon\Carbon::parse($request->from_date);
                    $toDate = \Carbon\Carbon::parse($request->to_date);
                    
                    $repaymentQ->where(function($dateQ) use ($fromDate, $toDate) {
                        $dateQ->whereBetween('year', [$fromDate->year, $toDate->year])
                              ->where(function($monthQ) use ($fromDate, $toDate) {
                                  if ($fromDate->year == $toDate->year) {
                                      $monthQ->whereIn('month', $this->getMonthsBetween($fromDate, $toDate));
                                  } else {
                                      $monthQ->where(function($q) use ($fromDate, $toDate) {
                                          $q->where('year', $fromDate->year)
                                            ->whereIn('month', $this->getMonthsFrom($fromDate))
                                            ->orWhere(function($q2) use ($toDate) {
                                                $q2->where('year', $toDate->year)
                                                   ->whereIn('month', $this->getMonthsUntil($toDate));
                                            });
                                      });
                                  }
                              });
                    });
                }
            });
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
        $fromDate = $request->from_date ? \Carbon\Carbon::parse($request->from_date) : null;
        $toDate = $request->to_date ? \Carbon\Carbon::parse($request->to_date) : null;
        
        foreach ($loans as $loan) {
            $repaymentInPeriod = \App\Models\Repayment::where('loan_id', $loan->id)
                ->where(function($q) use ($fromDate, $toDate) {
                    if ($fromDate && $toDate) {
                        $q->whereBetween('year', [$fromDate->year, $toDate->year])
                          ->whereIn('month', $this->getMonthsBetween($fromDate, $toDate));
                    }
                })
                ->first();
            
            $currentPeriod = $repaymentInPeriod ? $repaymentInPeriod->period : 1;
            
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
            $sheet->setCellValue('E' . $row, $loan->approver3_date ? \Carbon\Carbon::parse($loan->approver3_date)->format('d/m/Y') : '');
            $sheet->setCellValue('F' . $row, $loan->payment_period);
            $sheet->setCellValue('G' . $row, $interestRate);
            $installmentAmount = $repaymentInPeriod ? $repaymentInPeriod->installments : $loan->monthly_installments;
            
            $sheet->setCellValue('H' . $row, $currentPeriod);
            $sheet->setCellValue('I' . $row, $installmentAmount);
            
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
    
    private function getMonthsBetween($fromDate, $toDate)
    {
        $months = ['January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December'];
        $fromMonth = (int)$fromDate->format('n');
        $toMonth = (int)$toDate->format('n');
        return array_slice($months, $fromMonth - 1, $toMonth - $fromMonth + 1);
    }
    
    private function getMonthsFrom($fromDate)
    {
        $months = ['January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December'];
        $fromMonth = (int)$fromDate->format('n');
        return array_slice($months, $fromMonth - 1);
    }
    
    private function getMonthsUntil($toDate)
    {
        $months = ['January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December'];
        $toMonth = (int)$toDate->format('n');
        return array_slice($months, 0, $toMonth);
    }
}