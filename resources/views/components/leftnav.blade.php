<ul class="navbar-nav bg-gradient-primary sidebar sidebar-dark accordion" id="accordionSidebar">

    <!-- Sidebar - Brand  toggled-->
    <a class="sidebar-brand d-flex align-items-center justify-content-center" href="/dashboard">
        <div class="sidebar-brand-icon rotate-n-15">
            <i class="fas fa-usd"></i>
        </div>
        <div class="sidebar-brand-text mx-3">Fulcrum LTD </div> <span id="showLog" style="font-size:12px;display:none">Fulcrum LTD </span>
    </a>
    
    <!-- Divider -->
    <hr class="sidebar-divider my-0">
    
    <!------------------------- Admin Left nav ------------------------------>
        <li class="nav-item">
            <a class="nav-link" href="{{route('dashboard')}}">
                <i class="fas fa-fw fa-tachometer-alt"></i>
                <span>Dashboard</span></a>
        </li>
        
        @if(in_array(auth()->user()->role_type, ['admin','finance','hro','2','3','4']))
        <li class="nav-item">
            <a class="nav-link" href="{{route('users.index')}}">
                <i class="fas fa-mail-bulk"></i>
                <span>Users</span></a>
        </li>
        @endif
        
        @if(in_array(auth()->user()->role_type, ['admin','4']))
        {{-- <li class="nav-item">
            <a class="nav-link" href="{{route('admins')}}">
                <i class="fas fa-user-lock"></i>
                <span>Admin</span></a>
        </li> --}}
        {{-- <li class="nav-item">
            <a class="nav-link" href="{{route('banks.index')}}">
                <i class="fas fa-house-user"></i>
                <span>Banks</span></a>
        </li> --}}
        <li class="nav-item">
            <a class="nav-link" href="{{route('loan-requests')}}">
                <i class="fas fa-money-bill-wave"></i>
                <span>Requests</span></a>
        </li>

        <li class="nav-item">
            <a class="nav-link" href="{{route('companies.index')}}">
                <i class="fas fa-mail-bulk"></i>
                <span>Designation</span></a>
        </li>
    

        @endif

        {{--Reports Menu--}}
        @if (auth()->user()->updated_psw==10)
        <li class="nav-item">
            <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapseTwo1"
                aria-expanded="true" aria-controls="collapseTwo">
                <i class="fas fa-fw fa-chart-area"></i>
                <span>Reports</span>
            </a>
            <div id="collapseTwo1" class="collapse" aria-labelledby="headingTwo" data-parent="#accordionSidebar">
                <div class="bg-white py-2 collapse-inner rounded">
                    <a class="collapse-item" href="{{route('repayments.index')}}">Repayments</a>
                   {{-- <a class="collapse-item" href="{{route('loans')}}">Loans</a>
                     <a class="collapse-item" href="{{route('all-invoices')}}">Invoices</a>
                    <a class="collapse-item" href="{{route('scheme-perfomance')}}">Scheme Perfomance</a> --}}
                    <a class="collapse-item" href="{{route('disbursement-report')}}">Disbursements</a>
                    <a class="collapse-item" href="{{route('profitability-report')}}">Profitability</a>
                </div>
            </div>
        </li>
        @endif


        @if(in_array(auth()->user()->role_type, ['user','1']))
            <li class="nav-item">
                <a class="nav-link" href="{{route('loan.index')}}">
                    <i class="fas fa-mail-bulk"></i>
                    <span>Apply Loan</span></a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="{{route('payment_schedule')}}">
                    <i class="fas fa-coins"></i>
                    <span>Repayment Schedules</span></a>
            </li>
        @endif

        {{-- <li class="nav-item">
            <a class="nav-link" href="profile.php">
                <i class="fas fa-user"></i>
                <span>Profile</span></a>
        </li> --}}
    
    
    
    </ul>