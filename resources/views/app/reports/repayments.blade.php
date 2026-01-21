@extends('layouts.app')

@section('css')
    <style>
        .repayment-card {
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

        .stat-card {
            border: none;
            border-radius: 16px;
            padding: 20px;
            transition: transform 0.3s;
        }

        .stat-card:hover {
            transform: translateY(-5px);
        }

        .stat-card.pending {
            background: #fffbeb;
            border: 1px solid #fef3c7;
            color: #92400e;
        }

        .stat-card.paid {
            background: #f0fdf4;
            border: 1px solid #dcfce7;
            color: #166534;
        }

        .stat-card.written-off {
            background: #fef2f2;
            border: 1px solid #fee2e2;
            color: #991b1b;
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
            padding: 12px !important;
            vertical-align: middle !important;
            border-bottom: 1px solid #f1f5f9 !important;
            font-size: 0.85rem;
        }

        .status-pill {
            padding: 4px 10px;
            border-radius: 6px;
            font-weight: 700;
            font-size: 0.7rem;
            text-transform: uppercase;
        }

        .status-0 {
            background: #fef3c7;
            color: #92400e;
        }

        /* Pending */
        .status-1 {
            background: #dcfce7;
            color: #166534;
        }

        /* Paid */
        .status-3 {
            background: #e0f2fe;
            color: #075985;
        }

        /* TopUp */
        .status-4 {
            background: #fee2e2;
            color: #991b1b;
        }

        /* WrittenOff */

        .form-control-modern {
            border-radius: 10px;
            padding: 10px 16px;
            border: 1px solid #e2e8f0;
            font-size: 0.9rem;
        }

        .btn-modern {
            border-radius: 10px;
            font-weight: 600;
            padding: 10px 20px;
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
                <h1 class="h3 mb-0 text-gray-800 font-weight-bold">Repayment Ledger</h1>
                <p class="text-muted mb-0">Monitor and update loan repayment statuses across the organization.</p>
            </div>
            <div class="col-auto">
                <button class="btn btn-outline-primary btn-modern" data-toggle="modal" data-target="#exportModal">
                    <i class="fas fa-file-download mr-2"></i> Export Data
                </button>
            </div>
        </div>

        <!-- Summary Matrix -->
        <div class="row mb-4">
            <div class="col-md-4">
                <div class="stat-card pending shadow-sm">
                    <div class="small font-weight-bold text-uppercase opacity-75">Pending Receivables</div>
                    <div class="h3 mb-0 font-weight-bold">KES {{ number_format($summary['pending']) }}</div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="stat-card paid shadow-sm">
                    <div class="small font-weight-bold text-uppercase opacity-75">Collections to Date</div>
                    <div class="h3 mb-0 font-weight-bold">KES {{ number_format($summary['paid']) }}</div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="stat-card written-off shadow-sm">
                    <div class="small font-weight-bold text-uppercase opacity-75">Bad Debt / Write-offs</div>
                    <div class="h3 mb-0 font-weight-bold">KES {{ number_format($summary['written_off']) }}</div>
                </div>
            </div>
        </div>

        <!-- Filter Form -->
        <div class="filter-glass shadow-sm">
            <form method="GET" action="{{ route('repayments.index') }}">
                <div class="row gx-3 gy-3">
                    <div class="col-md-2">
                        <label class="small font-weight-bold text-muted">Company</label>
                        <select name="company_id" class="form-control form-control-modern">
                            <option value="">All Companies</option>
                            @foreach($companies as $company)
                                <option value="{{ $company->id }}" {{ request('company_id') == $company->id ? 'selected' : '' }}>
                                    {{ $company->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-2">
                        <label class="small font-weight-bold text-muted">User</label>
                        <select name="user_id" class="form-control form-control-modern">
                            <option value="">All Users</option>
                            @foreach($users as $user)
                                <option value="{{ $user->id }}" {{ request('user_id') == $user->id ? 'selected' : '' }}>
                                    {{ $user->first_name }} {{ $user->last_name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-2">
                        <label class="small font-weight-bold text-muted">Month</label>
                        <select name="month" class="form-control form-control-modern">
                            <option value="">All Months</option>
                            @foreach($months as $month)
                                <option value="{{ $month }}" {{ request('month') == $month ? 'selected' : '' }}>{{ $month }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-2">
                        <label class="small font-weight-bold text-muted">Year</label>
                        <select name="year" class="form-control form-control-modern">
                            <option value="">All Years</option>
                            @foreach($years as $year)
                                <option value="{{ $year }}" {{ request('year') == $year ? 'selected' : '' }}>{{ $year }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-2">
                        <label class="small font-weight-bold text-muted">Status</label>
                        <select name="status" class="form-control form-control-modern">
                            <option value="">All Statuses</option>
                            <option value="0" {{ request('status') === '0' ? 'selected' : '' }}>Pending</option>
                            <option value="1" {{ request('status') === '1' ? 'selected' : '' }}>Paid</option>
                            <option value="3" {{ request('status') === '3' ? 'selected' : '' }}>Closed (TopUp)</option>
                            <option value="4" {{ request('status') === '4' ? 'selected' : '' }}>Written-Off</option>
                        </select>
                    </div>
                    <div class="col-md-2 d-flex align-items-end">
                        <button type="submit" class="btn btn-primary btn-block btn-modern shadow-sm"><i
                                class="fas fa-search mr-2"></i>Apply Filters</button>
                    </div>
                </div>
            </form>
        </div>

        @if(session('success'))
            <div class="alert alert-success border-0 shadow-sm mb-4" style="border-radius: 12px;">
                <i class="fas fa-check-circle mr-2"></i> {{ session('success') }}
            </div>
        @endif

        <!-- Main Ledger Table -->
        <div class="card border-0 shadow-sm overflow-hidden" style="border-radius: 16px;">
            <div class="table-responsive">
                <table class="table modern-table mb-0">
                    <thead>
                        <tr>
                            <th>Ref</th>
                            <th>Applicant</th>
                            <th>Target Month</th>
                            <th class="text-right">Instalment</th>
                            <th class="text-center">Status</th>
                            <th>Internal Audit Note</th>
                            <th class="text-center">Manage</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($repayments as $repayment)
                            @php
                                $status = $repayment->status;
                                $pillClass = "status-$status";
                            @endphp
                            <tr>
                                <form action="{{ route('repayments.update', $repayment->id) }}" method="POST">
                                    @csrf @method('PUT')
                                    <td><span class="text-muted small">REC-{{ $repayment->id }}</span></td>
                                    <td>
                                        <div class="font-weight-bold text-gray-800">
                                            {{ $repayment->user->first_name ?? 'Unknown' }}
                                            {{ $repayment->user->last_name ?? '' }}</div>
                                        <div class="text-muted extra-small">Loan ID: #{{ $repayment->loan_id }}</div>
                                    </td>
                                    <td>
                                        <select name="month" class="form-control form-control-sm border-0 bg-light small"
                                            style="width: 120px;">
                                            @foreach($months as $m)
                                                <option value="{{ $m }}" {{ $repayment->month == $m ? 'selected' : '' }}>{{ $m }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </td>
                                    <td class="text-right font-weight-bold text-gray-900">KES
                                        {{ number_format($repayment->installments) }}</td>
                                    <td class="text-center">
                                        <select name="status"
                                            class="form-control form-control-sm border-0 font-weight-bold {{ $pillClass }} small"
                                            style="width: 140px; border-radius: 6px;">
                                            <option value="0" {{ $status == 0 ? 'selected' : '' }}>PENDING</option>
                                            <option value="1" {{ $status == 1 ? 'selected' : '' }}>PAID</option>
                                            <option value="3" {{ $status == 3 ? 'selected' : '' }}>CLOSED (TOP-UP)</option>
                                            <option value="4" {{ $status == 4 ? 'selected' : '' }}>WRITTEN-OFF</option>
                                        </select>
                                    </td>
                                    <td>
                                        <input type="text" name="comments"
                                            class="form-control form-control-sm border-0 bg-light small"
                                            value="{{ $repayment->comments }}" placeholder="Add auditor notes...">
                                    </td>
                                    <td class="text-center">
                                        <button type="submit" class="btn btn-primary btn-sm rounded-lg px-3 shadow-none">
                                            <i class="fas fa-save"></i>
                                        </button>
                                    </td>
                                </form>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        <div class="d-flex justify-content-center mt-4">
            {!! $repayments->appends(request()->input())->links('vendor.pagination.simple-bootstrap-4') !!}
        </div>
    </div>

    <!-- Export Modal -->
    <div class="modal fade glass-modal" id="exportModal" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header bg-primary text-white border-0" style="border-radius: 20px 20px 0 0;">
                    <h5 class="modal-title font-weight-bold"><i class="fas fa-file-invoice mr-2"></i>Generate Portfolio
                        Export</h5>
                    <button class="close text-white" type="button" data-dismiss="modal"><span>&times;</span></button>
                </div>
                <div class="modal-body p-4">
                    <form id="exportForm">
                        <div class="mb-4">
                            <label class="small font-weight-bold text-muted">Acknowledge Entity Scheme</label>
                            <select name="scheme_id" class="form-control form-control-modern" required>
                                <option value="">Select Scheme...</option>
                                @foreach($companies as $company)
                                    <option value="{{ $company->id }}">{{ $company->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="row gx-2">
                            <div class="col-6">
                                <label class="small font-weight-bold text-muted">Start Cycle</label>
                                <input type="date" name="from_date" class="form-control form-control-modern">
                            </div>
                            <div class="col-6">
                                <label class="small font-weight-bold text-muted">End Cycle</label>
                                <input type="date" name="to_date" class="form-control form-control-modern">
                            </div>
                        </div>
                    </form>
                </div>
                <div class="modal-footer border-0 p-4 pt-0 d-flex gap-2">
                    <button type="button" class="btn btn-danger btn-modern flex-grow-1" onclick="exportFile('pdf')">
                        <i class="fas fa-file-pdf mr-1"></i> PDF Report
                    </button>
                    <button type="button" class="btn btn-success btn-modern flex-grow-1 shadow"
                        onclick="exportFile('excel')">
                        <i class="fas fa-file-excel mr-1"></i> Excel Data
                    </button>
                </div>
            </div>
        </div>
    </div>

    <script>
        function exportFile(type) {
            const form = document.getElementById('exportForm');
            const sid = form.scheme_id.value;
            if (!sid) return alert('Please select a target entity.');

            const from = form.from_date.value;
            const to = form.to_date.value;
            let url = type === 'pdf' ? `/repayment-schedule-pdf/${sid}` : `/repayment-schedule-excel/${sid}`;
            if (from || to) url += `?from_date=${from}&to_date=${to}`;

            window.open(url, '_blank');
            $('#exportModal').modal('hide');
        }
    </script>
@endsection