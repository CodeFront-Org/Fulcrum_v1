@extends('layouts.app')

@section('content')
<!-- Advanced Filters -->
<div class="card shadow mb-4">
    <div class="card-header py-3">
        <h6 class="m-0 font-weight-bold text-primary"><i class="fas fa-filter mr-2"></i>Advanced Filters & Sorting</h6>
    </div>
    <div class="card-body">
        <form method="GET" action="{{route('profitability-report')}}" id="filterForm">
            <div class="row">
                <div class="col-md-3 mb-3">
                    <label class="form-label"><i class="fas fa-building mr-1"></i>Scheme</label>
                    <select name="scheme" class="form-control">
                        <option value="">All Schemes</option>
                        @foreach($schemes as $scheme)
                        <option value="{{$scheme->name}}" {{request('scheme') == $scheme->name ? 'selected' : ''}}>{{$scheme->name}}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-2 mb-3">
                    <label class="form-label"><i class="fas fa-money-bill mr-1"></i>Min Amount</label>
                    <input type="number" name="min_amount" class="form-control" placeholder="0" value="{{request('min_amount')}}">
                </div>
                <div class="col-md-2 mb-3">
                    <label class="form-label"><i class="fas fa-money-bill mr-1"></i>Max Amount</label>
                    <input type="number" name="max_amount" class="form-control" placeholder="1000000" value="{{request('max_amount')}}">
                </div>
                <div class="col-md-2 mb-3">
                    <label class="form-label"><i class="fas fa-calendar-day mr-1"></i>From Date</label>
                    <input type="date" name="from_date" class="form-control" value="{{request('from_date')}}">
                </div>
                <div class="col-md-2 mb-3">
                    <label class="form-label"><i class="fas fa-calendar-day mr-1"></i>To Date</label>
                    <input type="date" name="to_date" class="form-control" value="{{request('to_date')}}">
                </div>
                <div class="col-md-1 mb-3 d-flex align-items-end">
                    <button type="submit" class="btn btn-primary btn-block"><i class="fas fa-search"></i></button>
                </div>
            </div>
            <div class="row">
                <div class="col-md-3 mb-3">
                    <label class="form-label"><i class="fas fa-sort mr-1"></i>Sort By</label>
                    <select name="sort_by" class="form-control">
                        <option value="profit" {{request('sort_by') == 'profit' ? 'selected' : ''}}>Highest Profit</option>
                        <option value="margin" {{request('sort_by') == 'margin' ? 'selected' : ''}}>Best Margin %</option>
                        <option value="disbursed" {{request('sort_by') == 'disbursed' ? 'selected' : ''}}>Most Disbursed</option>
                        <option value="loans" {{request('sort_by') == 'loans' ? 'selected' : ''}}>Most Loans</option>
                        <option value="scheme" {{request('sort_by') == 'scheme' ? 'selected' : ''}}>Scheme Name</option>
                    </select>
                </div>
                <div class="col-md-9">
                    <label class="form-label">&nbsp;</label><br>
                    <button type="button" class="btn btn-secondary btn-sm" onclick="clearFilters()"><i class="fas fa-times mr-1"></i>Clear All</button>
                    <button type="button" class="btn btn-success btn-sm" onclick="applyQuickFilter('profitable')"><i class="fas fa-chart-line mr-1"></i>Profitable Only</button>
                    <button type="button" class="btn btn-warning btn-sm" onclick="applyQuickFilter('highMargin')"><i class="fas fa-percentage mr-1"></i>High Margin (>20%)</button>
                    <button type="button" class="btn btn-info btn-sm" onclick="applyQuickFilter('thisYear')"><i class="fas fa-calendar mr-1"></i>This Year</button>
                    <button type="button" class="btn btn-info btn-sm" onclick="applyQuickFilter('lastYear')"><i class="fas fa-calendar mr-1"></i>Last Year</button>
                </div>
            </div>
        </form>
    </div>
</div>
<!-- Summary Cards -->
<div class="row mb-4">
    <div class="col-xl-3 col-md-6 mb-4">
        <div class="card border-left-success shadow h-100 py-2">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <div class="text-xs font-weight-bold text-success text-uppercase mb-1">Total Profit</div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800">KES {{number_format(collect($data)->sum('profit'))}}</div>
                    </div>
                    <div class="col-auto">
                        <i class="fas fa-chart-line fa-2x text-gray-300"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-xl-3 col-md-6 mb-4">
        <div class="card border-left-primary shadow h-100 py-2">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">Total Disbursed</div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800">KES {{number_format(collect($data)->sum('total_disbursed'))}}</div>
                    </div>
                    <div class="col-auto">
                        <i class="fas fa-money-bill-wave fa-2x text-gray-300"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-xl-3 col-md-6 mb-4">
        <div class="card border-left-info shadow h-100 py-2">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <div class="text-xs font-weight-bold text-info text-uppercase mb-1">Avg Margin</div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800">{{number_format(collect($data)->avg('profit_margin_percent'), 2)}}%</div>
                    </div>
                    <div class="col-auto">
                        <i class="fas fa-percentage fa-2x text-gray-300"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-xl-3 col-md-6 mb-4">
        <div class="card border-left-warning shadow h-100 py-2">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">Best Performer</div>
                        <div class="h6 mb-0 font-weight-bold text-gray-800">{{collect($data)->sortByDesc('profit_margin_percent')->first()->scheme_name ?? 'N/A'}}</div>
                    </div>
                    <div class="col-auto">
                        <i class="fas fa-trophy fa-2x text-gray-300"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Main Report Table -->
<div class="card shadow mb-4">
    <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
        <h6 class="m-0 font-weight-bold text-primary">
            Profitability Analysis by Scheme
            @if(isset($filtersApplied) && $filtersApplied)
                <span class="badge badge-info ml-2"><i class="fas fa-filter"></i> Filtered</span>
            @else
                <span class="badge badge-secondary ml-2"><i class="fas fa-list"></i> All Data</span>
            @endif
        </h6>
        <div class="dropdown no-arrow">
            <a class="dropdown-toggle" href="#" role="button" id="dropdownMenuLink" data-toggle="dropdown">
                <i class="fas fa-ellipsis-v fa-sm fa-fw text-gray-400"></i>
            </a>
            <div class="dropdown-menu dropdown-menu-right shadow">
                <a class="dropdown-item" href="#" onclick="exportTable()">Export to Excel</a>
                <a class="dropdown-item" href="#" onclick="showChart()">View Chart</a>
            </div>
        </div>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-bordered table-hover" id="dataTable" width="100%" cellspacing="0">
                <thead class="thead-dark">
                    <tr>
                        <th><i class="fas fa-building mr-1"></i>Scheme</th>
                        <th><i class="fas fa-money-bill mr-1"></i>Disbursed</th>
                        <th><i class="fas fa-coins mr-1"></i>Expected</th>
                        <th><i class="fas fa-chart-line mr-1"></i>Profit</th>
                        <th><i class="fas fa-percentage mr-1"></i>Margin %</th>
                        <th><i class="fas fa-list-ol mr-1"></i>Loans</th>
                        <th><i class="fas fa-star mr-1"></i>Performance</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($data as $item)
                    <tr>
                        <td><span class="badge badge-primary">{{$item->scheme_name}}</span></td>
                        <td class="font-weight-bold">KES {{number_format($item->total_disbursed)}}</td>
                        <td class="text-info">KES {{number_format($item->total_expected)}}</td>
                        <td class="{{$item->profit > 0 ? 'text-success' : 'text-danger'}} font-weight-bold">
                            <i class="fas {{$item->profit > 0 ? 'fa-arrow-up' : 'fa-arrow-down'}}"></i>
                            KES {{number_format($item->profit)}}
                        </td>
                        <td>
                            <span class="badge {{$item->profit_margin_percent > 20 ? 'badge-success' : ($item->profit_margin_percent > 10 ? 'badge-warning' : 'badge-danger')}}">
                                {{$item->profit_margin_percent}}%
                            </span>
                        </td>
                        <td><span class="badge badge-info">{{$item->loan_count}}</span></td>
                        <td>
                            @if($item->profit_margin_percent > 20)
                                <span class="badge badge-success"><i class="fas fa-star"></i> Excellent</span>
                            @elseif($item->profit_margin_percent > 10)
                                <span class="badge badge-warning"><i class="fas fa-thumbs-up"></i> Good</span>
                            @else
                                <span class="badge badge-danger"><i class="fas fa-exclamation"></i> Poor</span>
                            @endif
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Custom Pagination -->
@if($pagination['last_page'] > 1)
<div class="card shadow mb-4">
    <div class="card-body">
        <div class="row align-items-center">
            <div class="col-md-6">
                <p class="mb-0 text-muted">Showing {{$pagination['from']}} to {{$pagination['to']}} of {{$pagination['total']}} results</p>
            </div>
            <div class="col-md-6">
                <nav>
                    <ul class="pagination justify-content-end mb-0">
                        @if($pagination['current_page'] > 1)
                        <li class="page-item">
                            <a class="page-link" href="{{request()->fullUrlWithQuery(['page' => 1])}}">
                                <i class="fas fa-angle-double-left"></i> First
                            </a>
                        </li>
                        <li class="page-item">
                            <a class="page-link" href="{{request()->fullUrlWithQuery(['page' => $pagination['current_page'] - 1])}}">
                                <i class="fas fa-angle-left"></i> Prev
                            </a>
                        </li>
                        @endif
                        
                        @for($i = max(1, $pagination['current_page'] - 2); $i <= min($pagination['last_page'], $pagination['current_page'] + 2); $i++)
                        <li class="page-item {{$i == $pagination['current_page'] ? 'active' : ''}}">
                            <a class="page-link" href="{{request()->fullUrlWithQuery(['page' => $i])}}">{{$i}}</a>
                        </li>
                        @endfor
                        
                        @if($pagination['current_page'] < $pagination['last_page'])
                        <li class="page-item">
                            <a class="page-link" href="{{request()->fullUrlWithQuery(['page' => $pagination['current_page'] + 1])}}">
                                Next <i class="fas fa-angle-right"></i>
                            </a>
                        </li>
                        <li class="page-item">
                            <a class="page-link" href="{{request()->fullUrlWithQuery(['page' => $pagination['last_page']])}}">
                                Last <i class="fas fa-angle-double-right"></i>
                            </a>
                        </li>
                        @endif
                    </ul>
                </nav>
            </div>
        </div>
    </div>
</div>
@endif

<script>
function clearFilters() {
    document.getElementById('filterForm').reset();
    window.location.href = '{{route("profitability-report")}}';
}

function applyQuickFilter(type) {
    const now = new Date();
    
    // Clear existing filters first
    document.getElementById('filterForm').reset();
    
    switch(type) {
        case 'profitable':
            // Add hidden input for profitable only filter
            let profitableInput = document.createElement('input');
            profitableInput.type = 'hidden';
            profitableInput.name = 'profitable_only';
            profitableInput.value = '1';
            document.getElementById('filterForm').appendChild(profitableInput);
            document.querySelector('select[name="sort_by"]').value = 'profit';
            break;
        case 'highMargin':
            // Add hidden input for high margin filter
            let marginInput = document.createElement('input');
            marginInput.type = 'hidden';
            marginInput.name = 'high_margin';
            marginInput.value = '1';
            document.getElementById('filterForm').appendChild(marginInput);
            document.querySelector('select[name="sort_by"]').value = 'margin';
            break;
        case 'thisYear':
            const thisYearStart = new Date(now.getFullYear(), 0, 1);
            const thisYearEnd = new Date(now.getFullYear(), 11, 31);
            document.querySelector('input[name="from_date"]').value = thisYearStart.toISOString().split('T')[0];
            document.querySelector('input[name="to_date"]').value = thisYearEnd.toISOString().split('T')[0];
            break;
        case 'lastYear':
            const lastYearStart = new Date(now.getFullYear() - 1, 0, 1);
            const lastYearEnd = new Date(now.getFullYear() - 1, 11, 31);
            document.querySelector('input[name="from_date"]').value = lastYearStart.toISOString().split('T')[0];
            document.querySelector('input[name="to_date"]').value = lastYearEnd.toISOString().split('T')[0];
            break;
    }
    
    document.getElementById('filterForm').submit();
}

function exportTable() {
    let csv = 'Scheme,Total Disbursed,Total Expected,Profit,Profit Margin %,Loan Count\n';
    @foreach($data as $item)
    csv += '{{$item->scheme_name}},{{$item->total_disbursed}},{{$item->total_expected}},{{$item->profit}},{{$item->profit_margin_percent}},{{$item->loan_count}}\n';
    @endforeach
    
    const blob = new Blob([csv], { type: 'text/csv' });
    const url = window.URL.createObjectURL(blob);
    const a = document.createElement('a');
    a.setAttribute('hidden', '');
    a.setAttribute('href', url);
    a.setAttribute('download', 'profitability_report.csv');
    document.body.appendChild(a);
    a.click();
    document.body.removeChild(a);
}

function showChart() {
    alert('Chart functionality coming soon!');
}
</script>
@endsection