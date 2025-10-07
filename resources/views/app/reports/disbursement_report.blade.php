@extends('layouts.app')

@section('content')
<!-- Advanced Filters -->
<div class="card shadow mb-4">
    <div class="card-header py-3">
        <h6 class="m-0 font-weight-bold text-primary"><i class="fas fa-filter mr-2"></i>Advanced Filters</h6>
    </div>
    <div class="card-body">
        <form method="GET" action="{{route('disbursement-report')}}" id="filterForm">
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
                    <label class="form-label"><i class="fas fa-calendar mr-1"></i>Year</label>
                    <select name="year" class="form-control">
                        <option value="">All Years</option>
                        @foreach($years as $year)
                        <option value="{{$year->year}}" {{request('year') == $year->year ? 'selected' : ''}}>{{$year->year}}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-2 mb-3">
                    <label class="form-label"><i class="fas fa-calendar-alt mr-1"></i>Month</label>
                    <select name="month" class="form-control">
                        <option value="">All Months</option>
                        <option value="1" {{request('month') == '1' ? 'selected' : ''}}>January</option>
                        <option value="2" {{request('month') == '2' ? 'selected' : ''}}>February</option>
                        <option value="3" {{request('month') == '3' ? 'selected' : ''}}>March</option>
                        <option value="4" {{request('month') == '4' ? 'selected' : ''}}>April</option>
                        <option value="5" {{request('month') == '5' ? 'selected' : ''}}>May</option>
                        <option value="6" {{request('month') == '6' ? 'selected' : ''}}>June</option>
                        <option value="7" {{request('month') == '7' ? 'selected' : ''}}>July</option>
                        <option value="8" {{request('month') == '8' ? 'selected' : ''}}>August</option>
                        <option value="9" {{request('month') == '9' ? 'selected' : ''}}>September</option>
                        <option value="10" {{request('month') == '10' ? 'selected' : ''}}>October</option>
                        <option value="11" {{request('month') == '11' ? 'selected' : ''}}>November</option>
                        <option value="12" {{request('month') == '12' ? 'selected' : ''}}>December</option>
                    </select>
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
                <div class="col-md-12">
                    <button type="button" class="btn btn-secondary btn-sm" onclick="clearFilters()"><i class="fas fa-times mr-1"></i>Clear Filters</button>
                    <button type="button" class="btn btn-info btn-sm" onclick="applyQuickFilter('thisMonth')"><i class="fas fa-calendar mr-1"></i>This Month</button>
                    <button type="button" class="btn btn-info btn-sm" onclick="applyQuickFilter('lastMonth')"><i class="fas fa-calendar mr-1"></i>Last Month</button>
                    <button type="button" class="btn btn-info btn-sm" onclick="applyQuickFilter('thisYear')"><i class="fas fa-calendar mr-1"></i>This Year</button>
                </div>
            </div>
        </form>
    </div>
</div>
<!-- Summary Cards -->
<div class="row mb-4">
    <div class="col-xl-3 col-md-6 mb-4">
        <div class="card border-left-primary shadow h-100 py-2">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">Total Disbursed</div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800">{{number_format(collect($data)->sum('total_disbursed'))}}</div>
                    </div>
                    <div class="col-auto">
                        <i class="fas fa-money-bill-wave fa-2x text-gray-300"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-xl-3 col-md-6 mb-4">
        <div class="card border-left-success shadow h-100 py-2">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <div class="text-xs font-weight-bold text-success text-uppercase mb-1">Total Loans</div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800">{{number_format(collect($data)->sum('loan_count'))}}</div>
                    </div>
                    <div class="col-auto">
                        <i class="fas fa-handshake fa-2x text-gray-300"></i>
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
                        <div class="text-xs font-weight-bold text-info text-uppercase mb-1">Active Schemes</div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800">{{collect($data)->pluck('scheme_name')->unique()->count()}}</div>
                    </div>
                    <div class="col-auto">
                        <i class="fas fa-building fa-2x text-gray-300"></i>
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
                        <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">Avg Per Loan</div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800">
                            @if(collect($data)->sum('loan_count') > 0)
                                {{number_format(collect($data)->sum('total_disbursed') / collect($data)->sum('loan_count'))}}
                            @else
                                0
                            @endif
                        </div>
                    </div>
                    <div class="col-auto">
                        <i class="fas fa-calculator fa-2x text-gray-300"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Main Report Table -->
<div class="card shadow mb-4">
    <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
        <h6 class="m-0 font-weight-bold text-primary">Monthly Disbursement Report</h6>
        <div class="dropdown no-arrow">
            <a class="dropdown-toggle" href="#" role="button" id="dropdownMenuLink" data-toggle="dropdown">
                <i class="fas fa-ellipsis-v fa-sm fa-fw text-gray-400"></i>
            </a>
            <div class="dropdown-menu dropdown-menu-right shadow">
                <a class="dropdown-item" href="#" onclick="exportTable()">Export to Excel</a>
            </div>
        </div>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-bordered table-hover" id="dataTable" width="100%" cellspacing="0">
                <thead class="thead-light">
                    <tr>
                        <th><i class="fas fa-building mr-1"></i>Scheme</th>
                        <th><i class="fas fa-calendar mr-1"></i>Year</th>
                        <th><i class="fas fa-calendar-alt mr-1"></i>Month</th>
                        <th><i class="fas fa-money-bill mr-1"></i>Total Disbursed</th>
                        <th><i class="fas fa-list-ol mr-1"></i>Loan Count</th>
                        <th><i class="fas fa-chart-bar mr-1"></i>Avg Amount</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($data as $item)
                    <tr>
                        <td><span class="badge badge-primary">{{$item->scheme_name}}</span></td>
                        <td>{{$item->year ?? 'N/A'}}</td>
                        <td>{{$item->month_name ?? 'N/A'}}</td>
                        <td class="text-success font-weight-bold">KES {{number_format($item->total_disbursed)}}</td>
                        <td><span class="badge badge-info">{{$item->loan_count}}</span></td>
                        <td>KES {{$item->loan_count > 0 ? number_format($item->total_disbursed / $item->loan_count) : '0'}}</td>
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
                                <i class="fas fa-angle-double-left"></i>
                            </a>
                        </li>
                        <li class="page-item">
                            <a class="page-link" href="{{request()->fullUrlWithQuery(['page' => $pagination['current_page'] - 1])}}">
                                <i class="fas fa-angle-left"></i>
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
                                <i class="fas fa-angle-right"></i>
                            </a>
                        </li>
                        <li class="page-item">
                            <a class="page-link" href="{{request()->fullUrlWithQuery(['page' => $pagination['last_page']])}}">
                                <i class="fas fa-angle-double-right"></i>
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
    window.location.href = '{{route("disbursement-report")}}';
}

function applyQuickFilter(type) {
    const now = new Date();
    let fromDate, toDate;
    
    switch(type) {
        case 'thisMonth':
            fromDate = new Date(now.getFullYear(), now.getMonth(), 1);
            toDate = new Date(now.getFullYear(), now.getMonth() + 1, 0);
            break;
        case 'lastMonth':
            fromDate = new Date(now.getFullYear(), now.getMonth() - 1, 1);
            toDate = new Date(now.getFullYear(), now.getMonth(), 0);
            break;
        case 'thisYear':
            fromDate = new Date(now.getFullYear(), 0, 1);
            toDate = new Date(now.getFullYear(), 11, 31);
            break;
    }
    
    document.querySelector('input[name="from_date"]').value = fromDate.toISOString().split('T')[0];
    document.querySelector('input[name="to_date"]').value = toDate.toISOString().split('T')[0];
    document.getElementById('filterForm').submit();
}

function exportTable() {
    // Simple CSV export
    let csv = 'Scheme,Year,Month,Total Disbursed,Loan Count\n';
    @foreach($data as $item)
    csv += '{{$item->scheme_name}},{{$item->year}},{{$item->month_name}},{{$item->total_disbursed}},{{$item->loan_count}}\n';
    @endforeach
    
    const blob = new Blob([csv], { type: 'text/csv' });
    const url = window.URL.createObjectURL(blob);
    const a = document.createElement('a');
    a.setAttribute('hidden', '');
    a.setAttribute('href', url);
    a.setAttribute('download', 'disbursement_report.csv');
    document.body.appendChild(a);
    a.click();
    document.body.removeChild(a);
}
</script>
@endsection