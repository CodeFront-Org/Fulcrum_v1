@extends('layouts.app')

@section('css')
    <style>
        .modern-table { border-collapse: collapse; width: 100%; background: white; border-radius: 12px; overflow: hidden; }
        .modern-table thead th { background-color: #f8fafc !important; color: #64748b; text-transform: uppercase; font-size: 0.7rem; font-weight: 700; letter-spacing: 0.025em; padding: 16px 12px !important; border-bottom: 2px solid #e2e8f0 !important; }
        .modern-table tbody td { padding: 14px 12px !important; vertical-align: middle !important; border-bottom: 1px solid #f1f5f9 !important; color: #334155; font-size: 0.85rem; }
        .avatar-circle { width: 35px; height: 35px; border-radius: 8px; display: flex; align-items: center; justify-content: center; font-weight: 700; color: white; margin-right: 10px; font-size: 0.8rem; }
        .status-badge { padding: 4px 10px; border-radius: 6px; font-size: 0.7rem; font-weight: 700; text-transform: uppercase; }
        .badge-admin { background: #fee2e2; color: #dc2626; }
        .action-btn { width: 32px; height: 32px; border-radius: 8px; display: inline-flex; align-items: center; justify-content: center; transition: all 0.2s; border: 1px solid #e2e8f0; background: white; color: #64748b; }
        .action-btn:hover { background: #f8fafc; color: #4e73df; transform: translateY(-2px); }
        .glass-modal .modal-content { border: none; border-radius: 20px; box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.25); }
        .form-control-modern { border-radius: 10px; padding: 12px 16px; border: 1px solid #e2e8f0; transition: all 0.2s; font-size: 0.9rem; }
        .form-control-modern:focus { border-color: #4e73df; box-shadow: 0 0 0 4px rgba(78, 115, 223, 0.1); }
        .btn-modern { padding: 12px 24px; border-radius: 10px; font-weight: 600; }
    </style>
@endsection

@section('content')
    <div class="container-fluid py-4">
        @if (session()->has('message'))
            <div id="toast" class="alert alert-success border-0 shadow-sm d-flex align-items-center mb-4" role="alert" style="border-radius: 12px;">
                <i class="fas fa-check-circle mr-3 fa-lg"></i>
                <div class="font-weight-bold">{{ session('message') }}</div>
                <button type="button" class="close ml-auto" data-dismiss="alert">&times;</button>
            </div>
        @endif

        <div class="row align-items-center mb-4">
            <div class="col">
                <h1 class="h3 mb-0 text-gray-800 font-weight-bold">System Administrators</h1>
                <p class="text-muted mb-0">Manage privileged access and administrative profiles.</p>
            </div>
            <div class="col-auto">
                <button class="btn btn-primary btn-modern shadow-sm" data-toggle="modal" data-target="#newAdmin">
                    <i class="fas fa-user-shield mr-2"></i> Register New Admin
                </button>
            </div>
        </div>

        <div class="card border-0 shadow-sm" style="border-radius: 16px;">
            <div class="card-header bg-white py-3 border-0">
                <h6 class="m-0 font-weight-bold text-gray-800">Administrative Directory</h6>
            </div>
            <div class="table-responsive">
                <table class="table modern-table mb-0" id="adminsTable">
                    <thead>
                        <tr>
                            <th>Admin</th>
                            <th>Contact Info</th>
                            <th>Staff Detail</th>
                            <th>Status</th>
                            <th class="text-center">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($users as $admin)
                        <tr>
                            <td>
                                <div class="d-flex align-items-center">
                                    <div class="avatar-circle" style="background: #4e73df;">
                                        {{ strtoupper(substr($admin->first_name, 0, 1)) }}{{ strtoupper(substr($admin->last_name, 0, 1)) }}
                                    </div>
                                    <div>
                                        <div class="font-weight-bold text-gray-800">{{ $admin->first_name }} {{ $admin->last_name }}</div>
                                        <div class="text-muted extra-small">ID: {{ $admin->id_number }}</div>
                                    </div>
                                </div>
                            </td>
                            <td>
                                <div class="small text-gray-700"><i class="fas fa-envelope mr-1 text-muted"></i> {{ $admin->email }}</div>
                                <div class="small text-muted mt-1"><i class="fas fa-phone mr-1"></i> {{ $admin->contacts }}</div>
                            </td>
                            <td>
                                <div class="small"><span class="font-weight-bold text-primary">{{ $admin->staff_number }}</span></div>
                                <div class="small text-muted">{{ $admin->gender }} | {{ $admin->employment_type }}</div>
                            </td>
                            <td><span class="status-badge badge-admin">Administrator</span></td>
                            <td>
                                <div class="d-flex justify-content-center">
                                    <button type="button" class="action-btn mr-2" data-toggle="modal"
                                        data-target="#modal-edit-{{ $admin->id }}">
                                        <i class='fas fa-pen fa-sm'></i>
                                    </button>
                                    <button type="button" onclick="del(this)" value="{{ $admin->id }}" class="action-btn">
                                        <i class='fa fa-trash fa-sm text-danger'></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Modals -->
    <div class="modal fade glass-modal" id="newAdmin" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header bg-primary text-white" style="border-radius: 20px 20px 0 0;">
                    <h5 class="modal-title font-weight-bold"><i class="fas fa-user-plus mr-2"></i>Add System Admin</h5>
                    <button class="close text-white" type="button" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
                <form action="{{ route('add-admin') }}" method="POST">
                    @csrf
                    <div class="modal-body p-4">
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="text-gray-700 font-weight-bold small">First Name</label>
                                <input name="first_name" type="text" class="form-control form-control-modern" placeholder="e.g. Finance" required />
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="text-gray-700 font-weight-bold small">Last Name</label>
                                <input name="last_name" type="text" class="form-control form-control-modern" placeholder="e.g. Officer" required />
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="text-gray-700 font-weight-bold small">Work Email</label>
                                <input name="email" type="email" class="form-control form-control-modern" placeholder="admin@fulcrum.com" required />
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="text-gray-700 font-weight-bold small">Phone Contact</label>
                                <input name="contacts" type="text" class="form-control form-control-modern" placeholder="07XXXXXXXX" required />
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="text-gray-700 font-weight-bold small">ID Number</label>
                                <input name="id_number" type="number" class="form-control form-control-modern" required />
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="text-gray-700 font-weight-bold small">Gender</label>
                                <select name="gender" class="form-control form-control-modern custom-select" required>
                                    <option value="MALE">Male</option>
                                    <option value="FEMALE">Female</option>
                                </select>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="text-gray-700 font-weight-bold small">Employment Type</label>
                                <select name="emp_type" class="form-control form-control-modern custom-select" required>
                                    <option value="1">Permanent</option>
                                    <option value="2">Contract</option>
                                </select>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="text-gray-700 font-weight-bold small">Staff Number</label>
                                <input name="staff_no" type="text" class="form-control form-control-modern" required />
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer border-0 p-4 pt-0">
                        <button class="btn btn-light btn-modern" type="button" data-dismiss="modal">Cancel</button>
                        <button class="btn btn-primary btn-modern px-5 shadow" type="submit">Create Admin Account</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    @foreach ($users as $admin)
        <div class="modal fade glass-modal" id="modal-edit-{{ $admin->id }}" tabindex="-1" role="dialog" aria-hidden="true">
            <!-- Reuse structure from create modal with prefilled values -->
        </div>
    @endforeach
@endsection