@extends('layouts.app')

@section('css')
    <style>
        .modern-table {
            border-collapse: collapse;
            width: 100%;
            background: white;
            border-radius: 12px;
            overflow: hidden;
        }

        .modern-table thead th {
            background-color: #f8fafc !important;
            color: #64748b;
            text-transform: uppercase;
            font-size: 0.7rem;
            font-weight: 700;
            letter-spacing: 0.025em;
            padding: 16px 12px !important;
            border-bottom: 2px solid #e2e8f0 !important;
        }

        .modern-table tbody td {
            padding: 14px 12px !important;
            vertical-align: middle !important;
            border-bottom: 1px solid #f1f5f9 !important;
            color: #334155;
            font-size: 0.85rem;
        }

        .status-pill {
            padding: 4px 12px;
            border-radius: 9999px;
            font-size: 0.75rem;
            font-weight: 600;
        }

        .pill-pending {
            background: #fef9c3;
            color: #854d0e;
        }

        .pill-approved {
            background: #dcfce7;
            color: #15803d;
        }

        .pill-returned {
            background: #fee2e2;
            color: #991b1b;
        }

        .level-badge {
            font-size: 0.7rem;
            font-weight: 800;
            padding: 2px 8px;
            border-radius: 4px;
            text-transform: uppercase;
        }

        .level-hr {
            background: #eff6ff;
            color: #1d4ed8;
        }

        .level-finance {
            background: #f5f3ff;
            color: #6d28d9;
        }

        .level-admin {
            background: #fff7ed;
            color: #c2410c;
        }

        .level-cleared {
            background: #ecfdf5;
            color: #047857;
        }

        .action-btn {
            width: 32px;
            height: 32px;
            border-radius: 8px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            transition: all 0.2s;
            border: 1px solid #e2e8f0;
            background: white;
            color: #64748b;
        }

        .action-btn:hover {
            background: #f8fafc;
            color: #4e73df;
            border-color: #4e73df;
            transform: translateY(-2px);
        }

        .glass-modal .modal-content {
            border: none;
            border-radius: 20px;
            box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.25);
        }

        .info-label {
            font-size: 0.75rem;
            font-weight: 700;
            color: #64748b;
            text-transform: uppercase;
            margin-bottom: 0;
        }

        .info-value {
            font-size: 0.95rem;
            color: #1e293b;
            font-weight: 500;
        }

        .approval-step {
            padding: 12px;
            border-radius: 12px;
            background: #f8fafc;
            margin-bottom: 10px;
            border-left: 4px solid #e2e8f0;
        }

        .step-approved {
            border-left-color: #10b981;
            background: #f0fdf4;
        }

        .step-pending {
            border-left-color: #f59e0b;
            background: #fffbeb;
        }

        .step-returned {
            border-left-color: #ef4444;
            background: #fef2f2;
        }
    </style>
@endsection

@section('content')
    <div class="container-fluid py-4">
        <div class="row align-items-center mb-4">
            <div class="col">
                <h1 class="h3 mb-0 text-gray-800 font-weight-bold">Loan Requests</h1>
                <p class="text-muted mb-0">Monitor and track the lifecycle of employee loan applications.</p>
            </div>
        </div>

        <div class="card border-0 shadow-sm" style="border-radius: 16px;">
            <div class="card-header bg-white py-3 border-0 d-flex justify-content-between align-items-center">
                <h6 class="m-0 font-weight-bold text-gray-800">Application Queue</h6>
                <span class="badge badge-light text-muted px-3 py-2">Total: {{ $data->total() }}</span>
            </div>
            <div class="table-responsive">
                <table class="table modern-table mb-0" id="loanRequestsTable">
                    <thead>
                        <tr>
                            <th style="width: 50px;">#</th>
                            <th>Loan ID</th>
                            <th>Applicant</th>
                            <th>Applied Date</th>
                            <th>Amount</th>
                            <th>Period</th>
                            <th>Level</th>
                            <th>Status</th>
                            <th class="text-center">View</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($data as $loan)
                            <tr>
                                <td class="text-muted small">
                                    {{ $loop->iteration + ($data->currentPage() - 1) * $data->perPage() }}</td>
                                <td><code class="text-primary font-weight-bold">#{{ $loan['loan_id'] }}</code></td>
                                <td>
                                    <div class="font-weight-bold text-gray-800">{{ $loan['user_name'] }}</div>
                                    <div class="text-muted small">{{ $loan['email'] }}</div>
                                </td>
                                <td><span class="text-muted small"><i class="far fa-calendar-alt mr-1"></i>
                                        {{ $loan['date'] }}</span></td>
                                <td><span class="font-weight-bold text-gray-900">KES {{ number_format($loan['amount']) }}</span>
                                </td>
                                <td><span class="text-muted small">{{ $loan['period'] }} Months</span></td>
                                <td>
                                    @php
                                        $levels = [1 => 'HR', 2 => 'Finance', 3 => 'Admin', 5 => 'Cleared'];
                                        $levelClass = [1 => 'hr', 2 => 'finance', 3 => 'admin', 5 => 'cleared'];
                                    @endphp
                                    <span class="level-badge level-{{ $levelClass[$loan['level']] ?? 'default' }}">
                                        {{ $levels[$loan['level']] ?? 'Unknown' }}
                                    </span>
                                </td>
                                <td>
                                    @if ($loan['status'] == 1)
                                        <span class="status-pill pill-approved"><i class="fas fa-check-circle mr-1"></i>
                                            Approved</span>
                                    @elseif($loan['status'] == 0)
                                        <span class="status-pill pill-pending"><i class="fas fa-clock mr-1"></i> Pending</span>
                                    @elseif($loan['status'] == 2)
                                        <span class="status-pill pill-returned"><i class="fas fa-undo-alt mr-1"></i> Returned</span>
                                    @endif
                                </td>
                                <td class="text-center">
                                    <button type="button" class="action-btn" data-toggle="modal"
                                        data-target="#view-m-{{ $loan['loan_id'] }}">
                                        <i class='fas fa-eye fa-sm'></i>
                                    </button>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <div class="card-footer bg-white border-0 py-4">
                <div class="d-flex justify-content-end">
                    {{ $data->appends(request()->except('page'))->links('vendor.pagination.simple-bootstrap-4') }}
                </div>
            </div>
        </div>
    </div>

    @foreach ($loans as $item)
        <div class="modal fade glass-modal" id="view-m-{{ $item->id }}" tabindex="-1" role="dialog" aria-hidden="true">
            <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
                <div class="modal-content">
                    <div class="modal-header border-0 pb-0">
                        <h5 class="modal-title font-weight-bold text-primary">Loan Details: #{{ $item->id }}</h5>
                        <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">×</span>
                        </button>
                    </div>
                    <div class="modal-body p-4">
                        <!-- Approval Progress -->
                        <div class="mb-4">
                            <h6 class="text-gray-800 font-weight-bold mb-3"><i
                                    class="fas fa-tasks mr-2 text-primary"></i>Approval
                                Pipeline</h6>
                            @php
                                $approval_steps = [
                                    ['label' => 'HR Decision', 'id' => $item->approver1_id, 'action' => $item->approver1_action, 'level' => 1, 'comments' => $item->approver1_comments],
                                    ['label' => 'Finance Decision', 'id' => $item->approver2_id, 'action' => $item->approver2_action, 'level' => 2, 'comments' => $item->approver2_comments],
                                    ['label' => 'Admin Decision', 'id' => $item->approver3_id, 'action' => $item->approver3_action, 'level' => 3, 'comments' => $item->approver3_comments],
                                ];
                            @endphp

                            <div class="row">
                                @foreach ($approval_steps as $step)
                                    <div class="col-md-4">
                                        @php
                                            $isDecisionMade = $step['action'] == 'RECOMMENDED' || $step['action'] == 1;
                                            $isReturned = $item->final_decision == 2 && $item->approval_level == $step['level'];
                                            $statusClass = $isDecisionMade ? 'step-approved' : ($isReturned ? 'step-returned' : 'step-pending');
                                        @endphp
                                        <div class="approval-step {{ $statusClass }}">
                                            <div class="info-label">{{ $step['label'] }}</div>
                                            <div class="small font-weight-bold">
                                                @if ($isDecisionMade)
                                                    <span class="text-success"><i class="fas fa-check mr-1"></i> Approved</span>
                                                    <div class="text-muted x-small">By:
                                                        {{ \App\Models\User::find($step['id'])?->first_name ?? 'System' }}</div>
                                                @elseif($isReturned)
                                                    <span class="text-danger"><i class="fas fa-undo mr-1"></i> Returned</span>
                                                    <div class="text-danger small mt-1 x-small">"{{ $step['comments'] }}"</div>
                                                @else
                                                    <span class="text-warning"><i class="fas fa-clock mr-1"></i> Pending</span>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>

                        <!-- Loan Info -->
                        <div class="bg-light p-4 rounded-xl" style="border-radius: 15px;">
                            <h6 class="text-gray-800 font-weight-bold mb-3 d-flex align-items-center">
                                <i class="fas fa-file-invoice-dollar mr-2 text-primary"></i>Financial Summary
                            </h6>
                            <div class="row mb-3">
                                <div class="col-md-4 mb-3">
                                    <div class="info-label">Requested Amount</div>
                                    <div class="info-value text-primary">KES {{ number_format($item->requested_loan_amount) }}
                                    </div>
                                </div>
                                <div class="col-md-4 mb-3">
                                    <div class="info-label">Monthly Installment</div>
                                    <div class="info-value">KES {{ number_format($item->monthly_installments) }}</div>
                                </div>
                                <div class="col-md-4 mb-3">
                                    <div class="info-label">Repayment Period</div>
                                    <div class="info-value">{{ $item->payment_period }} Months</div>
                                </div>
                                <div class="col-md-4">
                                    <div class="info-label">Gross Salary</div>
                                    <div class="info-value">KES {{ number_format($item->gross_salary) }}</div>
                                </div>
                                <div class="col-md-4">
                                    <div class="info-label">Net Salary</div>
                                    <div class="info-value text-success">KES {{ number_format($item->net_salary) }}</div>
                                </div>
                                <div class="col-md-4">
                                    <div class="info-label">Scheme / Designation</div>
                                    <div class="info-value">{{ $item->company }}</div>
                                </div>
                            </div>

                            <h6 class="text-gray-800 font-weight-bold mb-3 border-top pt-3 mt-1">Applicant Context</h6>
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <div class="info-label">Reason for Loan</div>
                                    <div class="info-value small text-muted">{{ $item->loan_reason }}</div>
                                </div>
                                <div class="col-md-3 mb-3">
                                    <div class="info-label">Next of Kin</div>
                                    <div class="info-value small">{{ $item->kin }}</div>
                                </div>
                                <div class="col-md-3 mb-3">
                                    <div class="info-label">KIN Contact</div>
                                    <div class="info-value small">{{ $item->kin_contacts }}</div>
                                </div>

                                <div class="col-md-12">
                                    <div class="d-flex align-items-center justify-content-between bg-white p-3 rounded border">
                                        <div class="small">
                                            <span class="font-weight-bold text-gray-700">Supporting Documentation</span>
                                            <p class="mb-0 text-muted x-small">Click to verify attachment</p>
                                        </div>
                                        <a href="{{ asset('uploads/supporting_docs/' . $item->supporting_doc_file) }}"
                                            class="btn btn-outline-primary btn-sm px-4" target="_blank">
                                            <i class="fas fa-paperclip mr-2"></i> View Attachment
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer border-0 p-4">
                        <button class="btn btn-light btn-modern px-5" type="button" data-dismiss="modal">Close Review</button>
                    </div>
                </div>
            </div>
        </div>
    @endforeach
@endsection