<?php


namespace App\Http\Controllers\app;
use App\Models\Repayment;

use App\Http\Controllers\Controller;
use App\Models\Access;
use App\Models\Bank;
use App\Models\Company;
use App\Models\Loan;
use App\Models\User;
use App\Models\UserBank;
use Illuminate\Support\Facades\Mail;
use App\Mail\ApproveLoanMail;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;



class RepaymentController extends Controller
{
    
        public function index(Request $request)
        {
            // $query = \App\Models\Repayment::with('company');
              $query = Repayment::with(['company', 'user']);

                // Apply filters
                if ($request->company_id) {
                    $query->where('company_id', $request->company_id);
                }

                if ($request->month) {
                    $query->where('month', $request->month);
                }

                if ($request->status !== null && $request->status !== '') {
                    $query->where('status', $request->status);
                }

    //             $repayments = Repayment::with(['company', 'user']) // ← added 'user'
    // ->when($request->company_id, fn($q) => $q->where('company_id', $request->company_id))
    // ->when($request->month, fn($q) => $q->where('month', $request->month))
    // ->when($request->status !== null && $request->status !== '', fn($q) => $q->where('status', $request->status))
    // ->orderBy('users.first_name') // ← sorting by user first name
    // ->select('repayments.*') 
    // ->paginate(20)
    // ->appends($request->all());

   $repayments = Repayment::with(['company', 'user'])
    ->join('users', 'repayments.user_id', '=', 'users.id')
    ->when($request->company_id, fn($q) => $q->where('repayments.company_id', $request->company_id))
    ->when($request->month, fn($q) => $q->where('repayments.month', $request->month))
    ->when($request->year, fn($q) => $q->where('repayments.year', $request->year))
    ->when($request->status !== null && $request->status !== '', fn($q) => $q->where('repayments.status', $request->status))
    ->when($request->user_id, fn($q) => $q->where('repayments.user_id', $request->user_id)) // ✅ User filter added
    ->orderBy('users.first_name', 'asc')
    ->select('repayments.*')
    ->paginate(20)
    ->appends($request->all());







// Clone query for summary (to avoid pagination limitation)
$summaryQuery = (clone $query)->selectRaw('status, SUM(installments) as total')
    ->groupBy('status')
    ->pluck('total', 'status');

// Normalize result into summary array
$summary = [
    'pending' => $summaryQuery[0] ?? 0,
    'paid' => $summaryQuery[1] ?? 0,
    'written_off' => $summaryQuery[4] ?? 0,
];




                $companies = \App\Models\Company::all();

                // Generate current month + 6 future months
                $months = collect(range(-6, 5))->map(fn($i) => \Carbon\Carbon::now()->addMonths($i)->format('F'));
                // Generate current year + 5 future years
                $years = collect(range(now()->year - 2, now()->year + 1));

                $users = \App\Models\User::orderBy('first_name')->get();


                
            $label = 'Loan Repayments';
            return view('app.reports.repayments',compact('repayments', 'companies', 'months', 'summary', 'label', 'years','users'));
        }



public function update(Request $request, $id)
{
    $repayment = Repayment::findOrFail($id);

    $validated = $request->validate([
        'month' => 'required|string',
        'status' => 'required|in:0,1,3,4',
        'comments' => 'nullable|string',
    ]);

    $repayment->update($validated);

    return redirect()->back()->with('success', 'Repayment updated successfully.');
}



}// End of RepaymentController