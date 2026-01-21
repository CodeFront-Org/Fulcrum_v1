@extends('layouts.app')

@section('css')
    <style>
        .report-card { border: none; border-radius: 20px; box-shadow: 0 4px 6px -1px rgba(0,0,0,0.1); background: white; transition: all 0.3s; }
        .filter-section { background: #f8fafc; border-radius: 16px; padding: 24px; border: 1px solid #e2e8f0; }
        .form-control-modern { border-radius: 10px; padding: 10px 16px; border: 1px solid #e2e8f0; font-size: 0.9rem; }
        
        .stat-widget { padding: 20px; border-radius: 16px; background: white; border: 1px solid #f1f5f9; display: flex; align-items: center; }
        .stat-icon { width: 48px; height: 48px; border-radius: 12px; display: flex; align-items: center; justify-content: center; font-size: 1.25rem; margin-right: 16px; }
        .icon-blue { background: rgba(78, 115, 223, 0.1); color: #4e73df; }
        .icon-green { background: rgba(16, 185, 129, 0.1); color: #10b981; }
        .icon-cyan { background: rgba(6, 182, 212, 0.1); color: #0891b2; }
        .icon-amber { background: rgba(245, 158, 11, 0.1); color: #d97706; }

        .modern-table thead th {
            background-color: #f8fafc !important;
            color: #64748b;
            text-transform: uppercase;
            font-size: 0.7rem;
            font-weight: 700;
            letter-spacing: 0.025em;
            padding: 16px 15px !important;
            border-bottom: 2px solid #e2e8f0 !important;
        }
        .modern-table tbody td { padding: 12px 15px !important; vertical-align: middle !important; border-bottom: 1px solid #f1f5f9 !important; font-size: 0.85rem; color: #334155; }
        
        .scheme-badge { padding: 4px 10px; border-radius: 6px; background: #eff6ff; color: #1d4ed8; font-weight: 700; font-size: 0.75rem; }
        .btn-modern { border-radius: 10px; font-weight: 600; font-size: 0.85rem; padding: 8px 16px; transition: all 0.2s; }
        .pagination .page-link { border: none; border-radius: 8px; margin: 0 3px; color: #64748b; font-weight: 600; }
        .pagination .page-item.active .page-link { background: #4e73df; color: white; }
    </style>
@endsection

@section('content')
    <div class="container-fluid py-4">
        <div class="row align-items-center mb-4">
            <div class="col">
                <h1 class="h3 mb-0 text-gray-800 font-weight-bold">Financial Disbursement Analysis</h1>
                <p class="text-muted mb-0">Track and analyze loan distribution across various schemes and timelines.</p>
            </div>
            <div class="col-auto">
                <button class="btn btn-outline-primary btn-modern" onclick="exportTable()">
                    <i class="fas fa-file-export mr-2"></i> Export Data
                </button>
            </div>
        </div>

        <!-- Advanced Filters -->
        <div class="filter-section mb-4 shadow-sm">
            <h6 class="font-weight-bold text-gray-800 mb-3"><i class="fas fa-sliders-h mr-2"></i>Discovery Filters</h6>
            <form method="GET" action="{{ route('disbursement-report') }}" id="filterForm">
                <div class="row gx-2">
                    <div class="col-md-3 mb-2">
                        <label class="small font-weight-bold text-muted">Organization Scheme</label>
                        <select name="scheme" class="form-control form-control-modern border-0 shadow-sm">
                            <option value="">All Schemes</option>
                            @foreach($schemes as $scheme)
                                <option value="{{$scheme->name}}" {{request('scheme') == $scheme->name ? 'selected' : ''}}>{{$scheme->name}}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-2 mb-2">
                        <label class="small font-weight-bold text-muted">Year</label>
                        <select name="year" class="form-control form-control-modern border-0 shadow-sm">
                            <option value="">All Years</option>
                            @foreach($years as $year)
                                <option value="{{$year->year}}" {{request('year') == $year->year ? 'selected' : ''}}>{{$year->year}}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-2 mb-2">
                        <label class="small font-weight-bold text-muted">Month</label>
                        <select name="month" class="form-control form-control-modern border-0 shadow-sm">
                            <option value="">All Months</option>
                            @foreach(['January','February','March','April','May','June','July','August','September','October','November','December'] as $idx => $m)
                                <option value="{{$idx+1}}" {{request('month') == ($idx+1) ? 'selected' : ''}}>{{$m}}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-2 mb-2">
                        <label class="small font-weight-bold text-muted">From Date</label>
                        <input type="date" name="from_date" class="form-control form-control-modern border-0 shadow-sm" value="{{request('from_date')}}">
                    </div>
                    <div class="col-md-2 mb-2">
                        <label class="small font-weight-bold text-muted">To Date</label>
                        <input type="date" name="to_date" class="form-control form-control-modern border-0 shadow-sm" value="{{request('to_date')}}">
                    </div>
                    <div class="col-md-1 mb-2 d-flex align-items-end">
                        <button type="submit" class="btn btn-primary btn-block py-2 shadow-sm" style="border-radius: 10px;"><i class="fas fa-filter"></i></button>
                    </div>
                </div>
                <div class="mt-3 d-flex align-items-center">
                    <span class="small text-muted mr-3">Quick Presets:</span>
                    <button type="button" class="btn btn-light btn-sm btn-modern mr-2" onclick="applyQuickFilter('thisMonth')">Current Month</button>
                    <button type="button" class="btn btn-light btn-sm btn-modern mr-2" onclick="applyQuickFilter('lastMonth')">Last Month</button>
                    <button type="button" class="btn btn-light btn-sm btn-modern mr-2" onclick="applyQuickFilter('thisYear')">Full Year</button>
                    <button type="button" class="btn btn-link text-danger btn-sm ml-auto" onclick="clearFilters()">Reset All</button>
                </div>
            </form>
        </div>

        <!-- Summary Matrix -->
        <div class="row mb-4">
            <div class="col-xl-3 col-md-6 mb-3">
                <div class="stat-widget shadow-sm">
                    <div class="stat-icon icon-blue"><i class="fas fa-hand-holding-usd"></i></div>
                    <div>
                        <div class="small text-muted font-weight-bold text-uppercase">Total Disbursed</div>
                        <div class="h4 mb-0 font-weight-bold">KES {{number_format(collect($data)->sum('total_disbursed'))}}</div>
                    </div>
                </div>
            </div>
            <div class="col-xl-3 col-md-6 mb-3">
                <div class="stat-widget shadow-sm">
                    <div class="stat-icon icon-green"><i class="fas fa-clipboard-list"></i></div>
                    <div>
                        <div class="small text-muted font-weight-bold text-uppercase">Success Count</div>
                        <div class="h4 mb-0 font-weight-bold text-gray-800">{{number_format(collect($data)->sum('loan_count'))}} Loans</div>
                    </div>
                </div>
            </div>
            <div class="col-xl-3 col-md-6 mb-3">
                <div class="stat-widget shadow-sm">
                    <div class="stat-icon icon-cyan"><i class="fas fa-sitemap"></i></div>
                    <div>
                        <div class="small text-muted font-weight-bold text-uppercase">Active Portfolios</div>
                        <div class="h4 mb-0 font-weight-bold">{{collect($data)->pluck('scheme_name')->unique()->count()}} Entities</div>
                    </div>
                </div>
            </div>
            <div class="col-xl-3 col-md-6 mb-3">
                <div class="stat-widget shadow-sm">
                    <div class="stat-icon icon-amber"><i class="fas fa-percentage"></i></div>
                    <div>
                        <div class="small text-muted font-weight-bold text-uppercase">Avg. Ticket Size</div>
                        <div class="h4 mb-0 font-weight-bold text-gray-800">
                            {{ collect($data)->sum('loan_count') > 0 ? 'KES '.number_format(collect($data)->sum('total_disbursed') / collect($data)->sum('loan_count')) : 'KES 0' }}
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Report Table -->
        <div class="card report-card overflow-hidden">
            <div class="card-header bg-white py-3 border-0 d-flex justify-content-between align-items-center">
                <h6 class="m-0 font-weight-bold text-gray-800">Distribution Ledger</h6>
                <span class="badge badge-light p-2 font-weight-bold">Data Set: {{ count($data) }} Entries</span>
            </div>
            <div class="table-responsive">
                <table class="table modern-table mb-0" id="reportTable">
                    <thead>
                        <tr>
                            <th>Organization Scheme</th>
                            <th>Cycle (Year)</th>
                            <th>Month</th>
                            <th class="text-right">Total Disbursed</th>
                            <th class="text-center">Count</th>
                            <th class="text-right">Avg / Loan</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($data as $item)
                        <tr>
                            <td><span class="scheme-badge">{{$item->scheme_name}}</span></td>
                            <td><span class="font-weight-bold">{{$item->year ?? '-'}}</span></td>
                            <td><span class="text-primary">{{$item->month_name ?? '-'}}</span></td>
                            <td class="text-right font-weight-bold text-gray-900">KES {{number_format($item->total_disbursed)}}</td>
                            <td class="text-center"><span class="badge badge-pill badge-light">{{$item->loan_count}}</span></td>
                            <td class="text-right text-muted small">KES {{$item->loan_count > 0 ? number_format($item->total_disbursed / $item->loan_count) : '0'}}</td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6" class="text-center py-5 text-muted">No disbursement records found for the selected criteria.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Pagination -->
        @if($pagination['last_page'] > 1)
        <div class="d-flex justify-content-between align-items-center mt-4 bg-white p-3 rounded shadow-sm border">
            <div class="small text-muted">Showing <b>{{$pagination['from']}}</b> to <b>{{$pagination['to']}}</b> of {{$pagination['total']}} entries</div>
            <nav>
                <ul class="pagination pagination-sm mb-0">
                    <li class="page-item {{ $pagination['current_page'] == 1 ? 'disabled' : '' }}">
                        <a class="page-link" href="{{ request()->fullUrlWithQuery(['page' => $pagination['current_page'] - 1]) }}"><i class="fas fa-chevron-left"></i></a>
                    </li>
                    @for($i = max(1, $pagination['current_page'] - 2); $i <= min($pagination['last_page'], $pagination['current_page'] + 2); $i++)
                        <li class="page-item {{ $i == $pagination['current_page'] ? 'active' : '' }}">
                            <a class="page-link" href="{{ request()->fullUrlWithQuery(['page' => $i]) }}">{{ $i }}</a>
                        </li>
                    @endfor
                    <li class="page-item {{ $pagination['current_page'] == $pagination['last_page'] ? 'disabled' : '' }}">
                        <a class="page-link" href="{{ request()->fullUrlWithQuery(['page' => $pagination['current_page'] + 1]) }}"><i class="fas fa-chevron-right"></i></a>
                    </li>
                </ul>
            </nav>
        </div>
        @endif
    </div>

    <script>
        function clearFilters() { window.location.href = '{{route("disbursement-report")}}'; }
        
        function applyQuickFilter(type) {
            const now = new Date();
            let from, to;
            if(type === 'thisMonth') { from = new Date(now.getFullYear(), now.getMonth(), 1); to = new Date(now.getFullYear(), now.getMonth() + 1, 0); }
            else if(type === 'lastMonth') { from = new Date(now.getFullYear(), now.getMonth() - 1, 1); to = new Date(now.getFullYear(), now.getMonth(), 0); }
            else if(type === 'thisYear') { from = new Date(now.getFullYear(), 0, 1); to = new Date(now.getFullYear(), 11, 31); }
            
            document.querySelector('input[name="from_date"]').value = from.toISOString().split('T')[0];
            document.querySelector('input[name="to_date"]').value = to.toISOString().split('T')[0];
            document.getElementById('filterForm').submit();
        }

        function exportTable() {
            let csv = 'Scheme,Year,Month,Total Disbursed,Loan Count,Average\n';
            @foreach($data as $item)
                csv += '"{{$item->scheme_name}}",{{$item->year}},"{{$item->month_name}}",{{$item->total_disbursed}},{{$item->loan_count}},{{$item->loan_count > 0 ? $item->total_disbursed/$item->loan_count : 0}}\n';
            @endforeach
            const blob = new Blob([csv], { type: 'text/csv' });
            const url = window.URL.createObjectURL(blob);
            const a = document.createElement('a');
            a.href = url;
            a.download = 'disbursements_{{date("Ymd")}}.csv';
            a.click();
        }
    </script>
@endsection