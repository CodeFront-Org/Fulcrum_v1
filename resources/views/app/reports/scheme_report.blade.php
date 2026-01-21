@extends('layouts.app')

@section('css')
    <style>
        .report-card {
            border: none;
            border-radius: 20px;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
            background: white;
        }

        .filter-glass {
            background: #f8fafc;
            border-radius: 16px;
            border: 1px solid #e2e8f0;
            padding: 24px;
            margin-bottom: 2rem;
        }

        .form-control-modern {
            border-radius: 10px;
            padding: 10px 16px;
            border: 1px solid #e2e8f0;
            font-size: 0.9rem;
        }

        .summary-pill {
            padding: 12px 20px;
            border-radius: 12px;
            background: white;
            border: 1px solid #f1f5f9;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.05);
        }

        .summary-label {
            font-size: 0.7rem;
            font-weight: 700;
            text-transform: uppercase;
            color: #64748b;
            margin-bottom: 4px;
        }

        .summary-value {
            font-size: 1.1rem;
            font-weight: 800;
            color: #1e293b;
        }

        .modern-table thead th {
            background-color: #f8fafc !important;
            color: #64748b;
            text-transform: uppercase;
            font-size: 0.7rem;
            font-weight: 700;
            padding: 16px 12px !important;
            border-bottom: 2px solid #e2e8f0 !important;
        }

        .modern-table tbody td {
            padding: 14px 12px !important;
            border-bottom: 1px solid #f1f5f9 !important;
            font-size: 0.85rem;
        }

        .status-badge {
            padding: 4px 10px;
            border-radius: 6px;
            font-weight: 700;
            font-size: 0.7rem;
            text-transform: uppercase;
        }

        .status-paid {
            background: #dcfce7;
            color: #166534;
        }

        .status-unpaid {
            background: #fee2e2;
            color: #991b1b;
        }

        .status-partial {
            background: #fffbeb;
            color: #92400e;
        }

        .btn-modern {
            border-radius: 10px;
            font-weight: 600;
            padding: 8px 20px;
            transition: all 0.2s;
        }

        .glass-modal .modal-content {
            border: none;
            border-radius: 20px;
            box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.25);
        }
    </style>
@endsection

@section('content')
    <div class="container-fluid py-4">
        <div class="row align-items-center mb-4">
            <div class="col">
                <h1 class="h3 mb-0 text-gray-800 font-weight-bold">Scheme Performance Report</h1>
                <p class="text-muted mb-0">Invoice summaries and collection tracking for partner entities.</p>
            </div>
        </div>

        @if (session()->has('message'))
            <div class="alert alert-success border-0 shadow-sm mb-4" style="border-radius: 12px;">
                <i class="fas fa-check-circle mr-2"></i> {{ session('message') }}
            </div>
        @endif

        <!-- Filter Console -->
        <div class="filter-glass shadow-sm">
            <form method="GET" action="#">
                <div class="row gx-3 gy-3">
                    <div class="col-md-3">
                        <label class="small font-weight-bold text-muted">Organization Scheme</label>
                        @php
                            $companies = \App\Models\Company::select('id', 'name')->get();
                        @endphp
                        <input type="text" list="regnoo" name='company' class="form-control form-control-modern"
                            placeholder="Type scheme name..." required autocomplete="off">
                        <datalist id="regnoo">
                            @foreach ($companies as $company)
                                <option value="{{ $company->name }}">{{ $company->name }}</option>
                            @endforeach
                        </datalist>
                    </div>
                    <div class="col-md-2">
                        <label class="small font-weight-bold text-muted">From Month</label>
                        <select name="from" class="form-control form-control-modern custom-select" required>
                            @foreach(['January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December'] as $idx => $m)
                                <option value="{{sprintf('%02d', $idx + 1)}}">{{$m}}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-2">
                        <label class="small font-weight-bold text-muted">To Month</label>
                        <select name="to" class="form-control form-control-modern custom-select" required>
                            @foreach(['January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December'] as $idx => $m)
                                <option value="{{sprintf('%02d', $idx + 1)}}">{{$m}}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-2">
                        <label class="small font-weight-bold text-muted">Year</label>
                        <select name="year" class="form-control form-control-modern custom-select" required>
                            <option value="2024">2024</option>
                            <option value="2023">2023</option>
                        </select>
                    </div>
                    <div class="col-md-3 d-flex align-items-end">
                        <button type="submit" class="btn btn-primary btn-block btn-modern shadow-sm"><i
                                class="fas fa-sync-alt mr-2"></i>Generate Analytics</button>
                    </div>
                </div>
            </form>
        </div>

        <!-- Metric Grid -->
        <div class="row mb-4">
            <div class="col-xl-3 col-md-6 mb-3">
                <div class="summary-pill shadow-sm">
                    <div class="summary-label">Gross Requested</div>
                    <div class="summary-value">KES {{number_format($tot_loan)}}</div>
                </div>
            </div>
            <div class="col-xl-3 col-md-6 mb-3">
                <div class="summary-pill shadow-sm">
                    <div class="summary-label">Expected Collections</div>
                    <div class="summary-value">KES {{number_format($tot_expected_payments)}}</div>
                </div>
            </div>
            <div class="col-xl-3 col-md-6 mb-3">
                <div class="summary-pill shadow-sm">
                    <div class="summary-label">Total Amount Paid</div>
                    <div class="summary-value text-success">KES {{number_format($tot_amt_paid)}}</div>
                </div>
            </div>
            <div class="col-xl-3 col-md-6 mb-3">
                <div class="summary-pill shadow-sm">
                    <div class="summary-label">Monthly Run Rate</div>
                    <div class="summary-value text-primary">KES {{number_format($tot_monthly_installments)}}</div>
                </div>
            </div>
        </div>

        <!-- Ledger Card -->
        <div class="card report-card shadow-sm overflow-hidden mb-4">
            <div class="card-header bg-white py-3 border-0">
                <h6 class="m-0 font-weight-bold text-gray-800">Invoice Registry: <span
                        class="text-primary">{{$for_date}}</span></h6>
            </div>
            <div class="table-responsive">
                <table class="table modern-table mb-0" id="schemeTable">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Invoice Reference</th>
                            <th>Requests</th>
                            <th class="text-right">MI (Expected)</th>
                            <th class="text-right">Principal</th>
                            <th class="text-right">Interest</th>
                            <th class="text-center">Status</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($data as $item)
                            <tr>
                                <td><span class="text-muted small">{{$loop->index + 1}}</span></td>
                                <td><a href="{{route('invoice-report', ['id' => $item['invoice_number']])}}"
                                        class="font-weight-bold text-primary">{{$item['invoice_number']}}</a></td>
                                <td class="text-center"><span class="badge badge-light px-2">{{$item['requests']}}</span></td>
                                <td class="text-right">KES {{number_format($item['mi'])}}</td>
                                <td class="text-right">KES {{number_format($item['loan_amt'])}}</td>
                                <td class="text-right">KES {{number_format($item['interest'])}}</td>
                                <td class="text-center">
                                    @if ($item['status'] == 1) <span class="status-badge status-paid">Paid</span> @endif
                                    @if ($item['status'] == 2) <span class="status-badge status-unpaid">Unpaid</span> @endif
                                    @if ($item['status'] == 3) <span class="status-badge status-partial">Partial</span> @endif
                                </td>
                                <td>
                                    <button class="btn btn-outline-primary btn-sm btn-modern px-3" data-toggle="modal"
                                        data-target="#approval-m-{{$item['invoice_number']}}">
                                        Update
                                    </button>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Modals -->
    @foreach ($data as $item)
        <div class="modal fade glass-modal" id="approval-m-{{$item['invoice_number']}}" tabindex="-1" role="dialog"
            aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered" role="document">
                <div class="modal-content">
                    <div class="modal-header bg-primary text-white border-0" style="border-radius: 20px 20px 0 0;">
                        <h5 class="modal-title font-weight-bold">Collection Update: #{{$item['invoice_number']}}</h5>
                        <button class="close text-white" type="button" data-dismiss="modal"><span>&times;</span></button>
                    </div>
                    <form action="{{route('payment_status')}}" method="POST">
                        @csrf
                        <input type="hidden" name="invoice_number" value="{{$item['invoice_number']}}">
                        <input type="hidden" name="company_id" value="{{$item['scheme_id']}}">
                        <input type="hidden" name="loan_requests" value="{{$item['requests']}}">
                        <input type="hidden" name="loan_amt" value="{{$tot_loan}}">
                        <input type="hidden" name="tot_expected_payments" value="{{$tot_expected_payments}}">
                        <input type="hidden" name="tot_amt_paid" value="{{$tot_amt_paid}}">
                        <input type="hidden" name="invoice_date" value="{{$date_selected}}">

                        <div class="modal-body p-4">
                            <div class="mb-4">
                                <label class="small font-weight-bold text-muted text-uppercase d-block mb-1">Current
                                    Status</label>
                                <select name="payment_status" class="form-control form-control-modern custom-select"
                                    id="payStatus-{{$item['invoice_number']}}"
                                    onchange="toggleInputs('{{$item['invoice_number']}}')">
                                    <option value="" selected disabled>Select updated status...</option>
                                    <option value="1">Fully Settled</option>
                                    <option value="2">Outstanding (Unpaid)</option>
                                    <option value="3">Partially Settled</option>
                                </select>
                            </div>

                            <div id="paymentFields-{{$item['invoice_number']}}" style="display: none;">
                                <div class="row gx-2 mb-3">
                                    <div class="col-6">
                                        <label class="small font-weight-bold text-muted">Amount Received</label>
                                        <input type="number" name="amount_paid" class="form-control form-control-modern"
                                            placeholder="0.00">
                                    </div>
                                    <div class="col-6">
                                        <label class="small font-weight-bold text-muted">Transaction Date</label>
                                        <input type="date" name="date_paid" class="form-control form-control-modern">
                                    </div>
                                </div>
                                <div class="mb-0">
                                    <label class="small font-weight-bold text-muted">Audit Comments</label>
                                    <textarea name="desc" class="form-control form-control-modern" rows="3"
                                        placeholder="Reference code or deposit details..."></textarea>
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer border-0 p-4 pt-0">
                            <button class="btn btn-light btn-modern flex-grow-1" type="button"
                                data-dismiss="modal">Cancel</button>
                            <button class="btn btn-primary btn-modern shadow flex-grow-1" type="submit">Submit Update</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    @endforeach

    <script>
        function toggleInputs(id) {
            const status = document.getElementById('payStatus-' + id).value;
            const fields = document.getElementById('paymentFields-' + id);
            fields.style.display = (status === '1' || status === '3') ? 'block' : 'none';
        }
    </script>
@endsection