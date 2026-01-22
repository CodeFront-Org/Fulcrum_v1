@extends('layouts.app')

@section('css')
    <style>
        .dash-card {
            border: none;
            border-radius: 20px;
            transition: all 0.3s cubic-bezier(.25, .8, .25, 1);
            overflow: hidden;
            background: white;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
        }

        .dash-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04);
        }

        .card-icon {
            width: 54px;
            height: 54px;
            border-radius: 14px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.5rem;
            margin-bottom: 1rem;
        }

        .icon-primary {
            background: rgba(78, 115, 223, 0.1);
            color: #4e73df;
        }

        .icon-success {
            background: rgba(28, 200, 138, 0.1);
            color: #1cc88a;
        }

        .icon-info {
            background: rgba(54, 185, 204, 0.1);
            color: #36b9cc;
        }

        .icon-danger {
            background: rgba(231, 74, 59, 0.1);
            color: #e74a3b;
        }

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
            transform: translateY(-2px);
        }

        .glass-modal .modal-content {
            border: none;
            border-radius: 20px;
            box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.25);
        }

        .btn-modern {
            border-radius: 10px;
            font-weight: 600;
            padding: 10px 20px;
        }
    </style>
@endsection

@section('content')
    <div class="container-fluid py-4">
        @if (session()->has('message'))
            <div id="toast" class="alert alert-success border-0 shadow-sm d-flex align-items-center mb-4" role="alert"
                style="border-radius: 12px;">
                <i class="fas fa-check-circle mr-3 fa-lg"></i>
                <div class="font-weight-bold">{{ session('message') }}</div>
                <button type="button" class="close ml-auto" data-dismiss="alert">&times;</button>
            </div>
        @endif

        @if (session()->has('error'))
            <div id="toast" class="alert alert-danger border-0 shadow-sm d-flex align-items-center mb-4" role="alert"
                style="border-radius: 12px;">
                <i class="fas fa-exclamation-circle mr-3 fa-lg"></i>
                <div class="font-weight-bold">{{ session('error') }}</div>
                <button type="button" class="close ml-auto" data-dismiss="alert">&times;</button>
            </div>
        @endif

        <div class="row align-items-center mb-4">
            <div class="col">
                <h1 class="h3 mb-0 text-gray-800 font-weight-bold">Human Resources Dashboard</h1>
                <p class="text-muted mb-0">Manage employee loan requests and departmental entities.</p>
            </div>
        </div>

        <!-- Quick Stats Matrix -->
        <div class="row mb-4">
            <div class="col-xl-3 col-md-6 mb-4">
                <div class="card dash-card h-100 p-3">
                    <div class="card-icon icon-primary"><i class="fas fa-users-cog"></i></div>
                    <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">Total Employees</div>
                    <div class="h3 mb-0 font-weight-bold text-gray-800">{{ $tot_users }}</div>
                    <div class="mt-2 small text-muted">Across managed entities</div>
                </div>
            </div>

            <div class="col-xl-3 col-md-6 mb-4">
                <div class="card dash-card h-100 p-3">
                    <div class="card-icon icon-success"><i class="fas fa-building"></i></div>
                    <div class="text-xs font-weight-bold text-success text-uppercase mb-1">Managed Entities</div>
                    <div class="h3 mb-0 font-weight-bold text-gray-800">{{ $tot_companies }}</div>
                    <div class="mt-2 small text-muted">Active partner designated entities</div>
                </div>
            </div>

            <div class="col-xl-3 col-md-6 mb-4">
                <div class="card dash-card h-100 p-3">
                    <div class="card-icon icon-info"><i class="fas fa-clipboard-list"></i></div>
                    <div class="text-xs font-weight-bold text-info text-uppercase mb-1">My Recommendations</div>
                    <div class="h3 mb-0 font-weight-bold text-gray-800">{{ $tot_approvals }}</div>
                    <div class="mt-2 small text-muted">Applications you have advanced</div>
                </div>
            </div>

            <div class="col-xl-3 col-md-6 mb-4">
                <div class="card dash-card h-100 p-3">
                    <div class="card-icon icon-danger"><i class="fas fa-undo"></i></div>
                    <div class="text-xs font-weight-bold text-danger text-uppercase mb-1">Returned Requests</div>
                    <div class="h3 mb-0 font-weight-bold text-gray-800">{{ $tot_returned }}</div>
                    <div class="mt-2 small text-muted">Needs applicant attention</div>
                </div>
            </div>
        </div>

        <!-- Recent Activity Table -->
        <div class="card border-0 shadow-sm overflow-hidden" style="border-radius: 16px;">
            <div class="card-header bg-white py-3 border-0 d-flex justify-content-between align-items-center">
                <h6 class="m-0 font-weight-bold text-gray-800">HR Review Queue</h6>
            </div>
            <div class="table-responsive">
                <table class="table modern-table mb-0" id="dashTable">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Applicant</th>
                            <th>Date Applied</th>
                            <th>Loan Details</th>
                            <th>Installment</th>
                            <th class="text-center">Review</th>
                            <th class="text-center">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($loans as $item)
                            <tr>
                                <td><span class="text-muted small">#{{ $item->id }}</span></td>
                                <td>
                                    <div class="font-weight-bold text-gray-800">{{ $item->user->first_name }}
                                        {{ $item->user->last_name }}</div>
                                    <div class="text-muted extra-small">ID: {{ $item->user->id_number }}</div>
                                </td>
                                <td><span class="text-muted small"><i class="far fa-clock mr-1"></i>
                                        {{ $item->created_at->format('M d, Y') }}</span></td>
                                <td>
                                    <div class="font-weight-bold text-primary">KES
                                        {{ number_format($item->requested_loan_amount) }}</div>
                                    <div class="text-muted extra-small">Period: {{ $item->payment_period }} Months</div>
                                </td>
                                <td><span
                                        class="font-weight-bold text-gray-900">{{ number_format($item->monthly_installments) }}</span>
                                </td>
                                <td class="text-center">
                                    <button type="button" class="action-btn" data-toggle="modal"
                                        data-target="#view-m-{{$item->id}}">
                                        <i class='fas fa-eye fa-sm'></i>
                                    </button>
                                </td>
                                <td class="text-center">
                                    <div class="btn-group">
                                        <button class="btn btn-success btn-sm btn-modern px-3 mr-2" data-toggle="modal"
                                            data-target="#approval-m-{{$item->id}}">
                                            Recommend
                                        </button>
                                        <button class="btn btn-outline-danger btn-sm btn-modern px-3" data-toggle="modal"
                                            data-target="#return-m-{{$item->id}}">
                                            Return
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="text-center py-5">
                                    <i class="fas fa-clipboard-check fa-3x text-light mb-3"></i>
                                    <p class="text-muted">No pending HR reviews.</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Modals -->
    @foreach ($loans as $item)
        @include('app.dash.modals.loan_detail', ['loan' => $item])
        @include('app.dash.modals.loan_approve', ['loan' => $item])
        @include('app.dash.modals.loan_return', ['loan' => $item])
    @endforeach
@endsection
