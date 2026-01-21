@extends('layouts.app')

@section('css')
    <style>
        .users-stats-card {
            transition: all 0.3s cubic-bezier(.25, .8, .25, 1);
            border: none;
            border-radius: 16px;
            background: white;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
        }

        .users-stats-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04);
        }

        .stat-icon {
            width: 48px;
            height: 48px;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.25rem;
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
            font-size: 0.75rem;
            font-weight: 700;
            letter-spacing: 0.05em;
            padding: 16px 24px !important;
            border-bottom: 1px solid #e2e8f0 !important;
        }

        .modern-table tbody td {
            padding: 16px 24px !important;
            vertical-align: middle !important;
            border-bottom: 1px solid #f1f5f9 !important;
            color: #334155;
            font-size: 0.875rem;
        }

        .modern-table tbody tr {
            transition: all 0.2s ease;
        }

        .modern-table tbody tr:hover {
            background-color: #f8fafc !important;
        }

        .avatar-circle {
            width: 40px;
            height: 40px;
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 700;
            color: white;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.05);
        }

        .status-badge {
            padding: 4px 12px;
            border-radius: 9999px;
            font-size: 0.75rem;
            font-weight: 600;
            text-transform: capitalize;
        }

        .badge-permanent {
            background: #dcfce7;
            color: #15803d;
        }

        .badge-contract {
            background: #fef9c3;
            color: #854d0e;
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
            transform: scale(1.1);
            background: #f1f5f9;
            color: #1e293b;
        }

        .action-btn.btn-edit:hover {
            background: #eff6ff;
            color: #2563eb;
            border-color: #bfdbfe;
        }

        .action-btn.btn-reset:hover {
            background: #fffbeb;
            color: #d97706;
            border-color: #fef3c7;
        }

        .action-btn.btn-delete:hover {
            background: #fef2f2;
            color: #dc2626;
            border-color: #fecaca;
        }

        .glass-modal .modal-content {
            border: none;
            border-radius: 20px;
            box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.25);
        }

        .glass-modal .modal-header {
            border-bottom: 1px solid #f1f5f9;
            padding: 24px;
        }

        .glass-modal .modal-body {
            padding: 24px;
        }

        .form-control-modern {
            border-radius: 10px;
            padding: 12px 16px;
            border: 1px solid #e2e8f0;
            transition: all 0.2s;
            font-size: 0.9rem;
        }

        .form-control-modern:focus {
            border-color: #4e73df;
            box-shadow: 0 0 0 4px rgba(78, 115, 223, 0.1);
        }

        .btn-modern {
            padding: 12px 24px;
            border-radius: 10px;
            font-weight: 600;
            transition: all 0.2s;
        }

        .pagination-container .page-link {
            border: none;
            padding: 8px 16px;
            margin: 0 4px;
            border-radius: 8px;
            color: #64748b;
            font-weight: 500;
        }

        .pagination-container .page-item.active .page-link {
            background: #4e73df;
            color: white;
            box-shadow: 0 4px 6px rgba(78, 115, 223, 0.25);
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

        <!-- Stats Row -->
        <div class="row mb-4">
            <div class="col-xl-3 col-md-6 mb-4">
                <div class="users-stats-card p-4">
                    <div class="d-flex align-items-center justify-content-between">
                        <div>
                            <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">Total Employees</div>
                            <div class="h3 mb-0 font-weight-bold text-gray-800">{{ $users->total() }}</div>
                        </div>
                        <div class="stat-icon bg-primary-soft text-primary" style="background: rgba(78, 115, 223, 0.1);">
                            <i class="fas fa-users"></i>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-xl-3 col-md-6 mb-4">
                <div class="users-stats-card p-4">
                    <div class="d-flex align-items-center justify-content-between">
                        <div>
                            <div class="text-xs font-weight-bold text-success text-uppercase mb-1">Permanent</div>
                            @php
                                use App\Models\User;
                                $permanentCount = User::where('employment_type', 'PERMANENT')->where('role_type', 'user')->count();
                            @endphp
                            <div class="h3 mb-0 font-weight-bold text-gray-800">{{ $permanentCount }}</div>
                        </div>
                        <div class="stat-icon bg-success-soft text-success" style="background: rgba(28, 200, 138, 0.1);">
                            <i class="fas fa-user-tie"></i>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-xl-3 col-md-6 mb-4">
                <div class="users-stats-card p-4">
                    <div class="d-flex align-items-center justify-content-between">
                        <div>
                            <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">Contractor</div>
                            @php
                                $contractCount = User::where('employment_type', 'CONTRACT')->where('role_type', 'user')->count();
                            @endphp
                            <div class="h3 mb-0 font-weight-bold text-gray-800">{{ $contractCount }}</div>
                        </div>
                        <div class="stat-icon bg-warning-soft text-warning" style="background: rgba(246, 194, 62, 0.1);">
                            <i class="fas fa-user-clock"></i>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-xl-3 col-md-6 mb-4 d-flex align-items-center">
                <button class="btn btn-primary btn-block btn-modern shadow-sm py-3" data-toggle="modal"
                    data-target="#newStaff">
                    <i class="fas fa-plus-circle mr-2"></i> Register New User
                </button>
            </div>
        </div>

        <!-- Main Content -->
        <div class="card border-0 shadow-sm overflow-hidden" style="border-radius: 16px;">
            <div class="card-header bg-white py-3 border-0 d-flex align-items-center justify-content-between">
                <h6 class="m-0 font-weight-bold text-gray-800">
                    <i class="fas fa-list-ul mr-2 text-primary"></i>Employee Directory
                </h6>
                <div class="text-muted small">Showing {{ $users->firstItem() }} to {{ $users->lastItem() }} of
                    {{ $users->total() }} entries
                </div>
            </div>
            <div class="table-responsive">
                <table class="table modern-table mb-0" id="usersTable">
                    <thead>
                        <tr>
                            <th>Employee</th>
                            <th>Contact Details</th>
                            <th>Identification</th>
                            <th>Emp. Details</th>
                            <th>Status</th>
                            <th class="text-center">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @php $page = $page_number; @endphp
                        @foreach ($users as $user)
                            <tr>
                                <td>
                                    <div class="d-flex align-items-center">
                                        @php
                                            $colors = ['#4e73df', '#1cc88a', '#36b9cc', '#f6c23e', '#e74a3b', '#858796'];
                                            $userColor = $colors[ord(substr($user->first_name, 0, 1)) % count($colors)];
                                        @endphp
                                        <div class="avatar-circle mr-3" style="background: {{ $userColor }};">
                                            {{ strtoupper(substr($user->first_name, 0, 1)) }}{{ strtoupper(substr($user->last_name, 0, 1)) }}
                                        </div>
                                        <div>
                                            <div class="font-weight-bold text-gray-800 mb-0" style="font-size: 0.95rem;">
                                                {{ $user->first_name }} {{ $user->last_name }}
                                            </div>
                                            <div class="text-muted small">Staff No: {{ $user->staff_number ?: 'N/A' }}</div>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <div class="d-flex flex-column">
                                        <span class="text-gray-700" style="font-size: 0.85rem;"><i
                                                class="fas fa-envelope mr-1 text-muted"></i> {{ $user->email }}</span>
                                        <span class="text-muted small mt-1"><i class="fas fa-phone mr-1"></i>
                                            {{ $user->contacts }}</span>
                                    </div>
                                </td>
                                <td>
                                    <div class="d-flex flex-column">
                                        <span class="font-weight-bold text-gray-800" style="font-size: 0.85rem;">ID:
                                            {{ $user->id_number }}</span>
                                        <span class="text-muted small mt-1">PIN: {{ $user->pin_certificate }}</span>
                                    </div>
                                </td>
                                <td>
                                    <div class="d-flex flex-column text-muted small">
                                        <span
                                            class="text-primary font-weight-bold">{{ $user->designation ?: 'Unassigned' }}</span>
                                        <span class="mt-1">Gender: {{ ucfirst(strtolower($user->gender)) }}</span>
                                    </div>
                                </td>
                                <td>
                                    <span class="status-badge badge-{{ strtolower($user->employment_type) }}">
                                        {{ $user->employment_type }}
                                    </span>
                                </td>
                                <td>
                                    <div class="d-flex justify-content-center">
                                        <button type="button" class="action-btn btn-edit mr-2" data-toggle="modal"
                                            data-target="#modal-edit-{{ $user->id }}" title="Edit User">
                                            <i class='fas fa-pen fa-sm'></i>
                                        </button>
                                        <button type="button" class="action-btn btn-reset mr-2" data-toggle="modal"
                                            data-target="#modal-reset-{{ $user->id }}" title="Reset Password">
                                            <i class='fas fa-key fa-sm'></i>
                                        </button>
                                        <button type="button" onclick="del(this)" value="{{ $user->id }}"
                                            class="action-btn btn-delete" title="Delete User">
                                            <i class='fa fa-trash fa-sm'></i>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                            @php $page += 1; @endphp
                        @endforeach
                    </tbody>
                </table>
            </div>

            <div class="card-footer bg-white border-top-0 py-4 pagination-container">
                <div class="d-flex justify-content-end">
                    {{ $users->appends(request()->except('page'))->links('vendor.pagination.simple-bootstrap-4') }}
                    </div>
                </div>
            </div>
        </div>

        <!-- Modals (Outside table) -->

        <!-- NewStaff Modal-->
        <div class="modal fade glass-modal" id="newStaff" tabindex="-1" role="dialog" aria-hidden="true">
            <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
                <div class="modal-content">
                    <div class="modal-header bg-primary text-white" style="border-radius: 20px 20px 0 0;">
                        <h5 class="modal-title font-weight-bold"><i class="fas fa-user-plus mr-2"></i>Register New Employee</h5>
                        <button class="close text-white" type="button" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">×</span>
                        </button>
                    </div>
                    <form action="{{ route('users.store') }}" method="POST">
                        @csrf
                        <div class="modal-body">
                            <div class="row">
                                <div class="col-md-12 mb-4">
                                    <h6 class="text-primary font-weight-bold mb-3 border-bottom pb-2">Company & Role Information
                                    </h6>
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label class="font-weight-bold text-gray-700">Workplace/Company</label>
                                                <input type="text" list="regnoo" required
                                                    class="form-control form-control-modern" name='company'
                                                    placeholder="Search & Select company..." autocomplete="off" />
                                                <datalist id="regnoo">
                                                    @foreach ($companies as $company)
                                                        <option value="{{ $company->name }}">{{ $company->name }}</option>
                                                    @endforeach
                                                </datalist>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label class="font-weight-bold text-gray-700">Employment Type</label>
                                                <select name="emp_type" class="form-control form-control-modern custom-select"
                                                    required>
                                                    <option value="1">Permanent</option>
                                                    <option value="2">Contract</option>
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-md-12 mb-4">
                                    <h6 class="text-primary font-weight-bold mb-3 border-bottom pb-2">Personal Details</h6>
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label class="text-gray-700">First Name</label>
                                                <input name="first_name" type="text" class="form-control form-control-modern"
                                                    placeholder="e.g John" required />
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label class="text-gray-700">Last Name</label>
                                                <input name="last_name" type="text" class="form-control form-control-modern"
                                                    placeholder="e.g Doe" required />
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label class="text-gray-700">Email Address</label>
                                                <input name="email" type="email" class="form-control form-control-modern"
                                                    placeholder="john.doe@example.com" required />
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label class="text-gray-700">Contacts (Phone)</label>
                                                <input name="contacts" type="text" class="form-control form-control-modern"
                                                    placeholder="0712345678" required />
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label class="text-gray-700">Gender</label>
                                                <select name="gender" class="form-control form-control-modern custom-select"
                                                    required>
                                                    <option value="MALE">Male</option>
                                                    <option value="FEMALE">Female</option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label class="text-gray-700">Identification (ID Number)</label>
                                                <input name="id_number" type="number" class="form-control form-control-modern"
                                                    placeholder="National ID" required />
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-md-12 mb-2">
                                    <h6 class="text-primary font-weight-bold mb-3 border-bottom pb-2">Official Documents & Dates
                                    </h6>
                                    <div class="row">
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label class="text-gray-700">KRA Pin</label>
                                                <input name="pin" type="text" class="form-control form-control-modern"
                                                    placeholder="PIN Certificate" required />
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label class="text-gray-700">Staff Number</label>
                                                <input name="staff_no" type="text" class="form-control form-control-modern"
                                                    placeholder="Optional" />
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label class="text-gray-700">Employment Date</label>
                                                <input name="employment_date" type="date"
                                                    class="form-control form-control-modern" value="{{ date('Y-m-d') }}" />
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label class="text-gray-700">Contract End Date</label>
                                                <input name="contract_end" type="date"
                                                    class="form-control form-control-modern" />
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer border-0 p-4">
                            <button class="btn btn-light btn-modern px-4" type="button" data-dismiss="modal">Cancel</button>
                            <button class="btn btn-primary btn-modern px-5 shadow" type="submit">Submit Registration</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        @foreach ($users as $user)
            <!-- Edit staff Modal-->
            <div class="modal fade glass-modal" id="modal-edit-{{ $user->id }}" tabindex="-1" role="dialog" aria-hidden="true">
                <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
                    <div class="modal-content">
                        <div class="modal-header bg-primary text-white" style="border-radius: 20px 20px 0 0;">
                            <h5 class="modal-title font-weight-bold"><i class="fas fa-user-edit mr-2"></i>Edit Employee:
                                {{ $user->first_name }}</h5>
                            <button class="close text-white" type="button" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">×</span>
                            </button>
                        </div>
                        <form action="{{ route('users.update', $user->id) }}" method="POST">
                            @method('PATCH')
                            @csrf
                            <div class="modal-body">
                                <!-- Similar row structure as the create modal -->
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="font-weight-bold text-gray-700">Company</label>
                                            <input type="text" list="regnooEdit-{{ $user->id }}" required
                                                class="form-control form-control-modern" name='company'
                                                value="{{ $user->designation }}" autocomplete="off" />
                                            <datalist id="regnooEdit-{{ $user->id }}">
                                                @foreach ($companies as $company)
                                                    <option value="{{ $company->name }}">{{ $company->name }}</option>
                                                @endforeach
                                            </datalist>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="text-gray-700">First Name</label>
                                            <input name="first_name" type="text" class="form-control form-control-modern"
                                                value="{{ $user->first_name }}" required />
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="text-gray-700">Last Name</label>
                                            <input name="last_name" type="text" class="form-control form-control-modern"
                                                value="{{ $user->last_name }}" required />
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="text-gray-700">Email</label>
                                            <input name="email" type="email" class="form-control form-control-modern"
                                                value="{{ $user->email }}" required />
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="text-gray-700">Contacts</label>
                                            <input name="contacts" type="text" class="form-control form-control-modern"
                                                value="{{ $user->contacts }}" required />
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="text-gray-700">ID Number</label>
                                            <input name="id_number" type="number" class="form-control form-control-modern"
                                                value="{{ $user->id_number }}" required />
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="text-gray-700">Employment Type</label>
                                            <select name="emp_type" class="form-control form-control-modern custom-select">
                                                <option {{ $user->employment_type == 'PERMANENT' ? 'selected' : '' }} value="1">
                                                    Permanent</option>
                                                <option {{ $user->employment_type == 'CONTRACT' ? 'selected' : '' }} value="2">
                                                    Contract</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="text-gray-700">Staff Number</label>
                                            <input name="staff_no" type="text" class="form-control form-control-modern"
                                                value="{{ $user->staff_number }}" />
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="modal-footer border-0">
                                <button class="btn btn-light btn-modern px-4" type="button" data-dismiss="modal">Cancel</button>
                                <button class="btn btn-primary btn-modern px-5 shadow" type="submit">Update Details</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            <!-- Reset Password Modal-->
            <div class="modal fade glass-modal" id="modal-reset-{{ $user->id }}" tabindex="-1" role="dialog" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered" role="document">
                    <div class="modal-content">
                        <div class="modal-header bg-warning">
                            <h5 class="modal-title font-weight-bold text-dark"><i class="fas fa-key mr-2"></i>Reset Password</h5>
                            <button class="close text-dark" type="button" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">×</span>
                            </button>
                        </div>
                        <form action="{{ route('users.resetPassword', $user->id) }}" method="POST">
                            @csrf
                            <div class="modal-body p-4">
                                <div class="text-center mb-4">
                                    <div class="bg-warning-soft rounded-circle d-inline-flex align-items-center justify-content-center mb-3"
                                        style="width: 60px; height: 60px; background: rgba(246, 194, 62, 0.1);">
                                        <i class="fas fa-lock text-warning fa-2x"></i>
                                    </div>
                                    <h6 class="font-weight-bold text-gray-800">Security Access for {{ $user->first_name }}</h6>
                                    <p class="text-muted small">The user will receive an email notification after reset.</p>
                                </div>

                                <div class="form-group">
                                    <label class="text-gray-700 font-weight-bold">New Password</label>
                                    <input name="password" type="password" class="form-control form-control-modern"
                                        placeholder="Minimum 8 characters" required minlength="8" />
                                </div>
                                <div class="form-group">
                                    <label class="text-gray-700 font-weight-bold">Confirm New Password</label>
                                    <input name="password_confirmation" type="password" class="form-control form-control-modern"
                                        placeholder="Repeat password" required minlength="8" />
                                </div>
                            </div>
                            <div class="modal-footer border-0 p-4 pt-0">
                                <button class="btn btn-light btn-modern" type="button" data-dismiss="modal">Cancel</button>
                                <button class="btn btn-warning btn-modern px-4 font-weight-bold shadow-sm" type="submit">Verify &
                                    Reset</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        @endforeach

@endsection

@section('scripts')
    <script>
        function del(e) {
            let id = e.value;
            var type = 0;

            Swal.fire({
                title: "Are you sure?",
                text: "All associated records for this employee will be permanently removed.",
                icon: "warning",
                showCancelButton: true,
                confirmButtonColor: "#e74a3b",
                cancelButtonColor: "#858796",
                confirmButtonText: "Yes, Delete Record",
                cancelButtonText: "Cancel",
                borderRadius: "15px"
            }).then((t) => {
                if (t.isConfirmed) {
                    $.ajax({
                        type: "DELETE",
                        url: "users/" + id,
                        data: {
                            _token: "{{ csrf_token() }}",
                            id,
                            type
                        },
                        success: function (response) {
                            Swal.fire({
                                title: "Deleted!",
                                text: "Employee record removed successfully.",
                                icon: "success",
                                borderRadius: "15px"
                            }).then(() => {
                                location.href = '/users'
                            })
                        },
                        error: function (res) {
                            Swal.fire("System Error", "Unable to complete request. Please contact support.", "error");
                        }
                    });
                }
            })
        }
    </script>
@endsection
