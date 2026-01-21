@extends('layouts.app')

@section('css')
    <style>
        .roles-stats-card {
            border: none;
            border-radius: 20px;
            transition: all 0.3s cubic-bezier(.25, .8, .25, 1);
            overflow: hidden;
            background: white;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
        }
        .roles-stats-card:hover { transform: translateY(-5px); box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1); }
        .card-icon { width: 48px; height: 48px; border-radius: 12px; display: flex; align-items: center; justify-content: center; font-size: 1.25rem; margin-bottom: 0.75rem; }
        .icon-blue { background: rgba(78, 115, 223, 0.1); color: #4e73df; }
        .icon-red { background: rgba(231, 74, 59, 0.1); color: #e74a3b; }
        .icon-purple { background: rgba(126, 34, 206, 0.1); color: #7e22ce; }

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
        .modern-table tbody td { padding: 14px 12px !important; vertical-align: middle !important; border-bottom: 1px solid #f1f5f9 !important; color: #334155; font-size: 0.85rem; }
        
        .role-pill { padding: 4px 10px; border-radius: 6px; font-size: 0.7rem; font-weight: 700; text-transform: uppercase; letter-spacing: 0.02em; }
        .pill-admin { background: #fee2e2; color: #dc2626; }
        .pill-approver { background: #f3e8ff; color: #7e22ce; }
        .pill-finance { background: #dcfce7; color: #15803d; }
        .pill-hro { background: #e0f2fe; color: #0369a1; }
        .pill-user { background: #f1f5f9; color: #475569; }

        .action-btn { width: 32px; height: 32px; border-radius: 8px; display: inline-flex; align-items: center; justify-content: center; transition: all 0.2s; border: 1px solid #e2e8f0; background: white; color: #64748b; }
        .action-btn:hover { background: #f8fafc; color: #4e73df; transform: translateY(-2px); }
        .glass-modal .modal-content { border: none; border-radius: 20px; box-shadow: 0 25px 50px -12px rgba(0,0,0,0.25); }
        .form-control-modern { border-radius: 10px; padding: 12px 16px; border: 1px solid #e2e8f0; font-size: 0.9rem; transition: all 0.2s; }
    </style>
@endsection

@section('content')
    <div class="container-fluid py-4">
        <div class="row align-items-center mb-4">
            <div class="col">
                <h1 class="h3 mb-0 text-gray-800 font-weight-bold">Role & Access Management</h1>
                <p class="text-muted mb-0">Control organization-wide permissions and departmental access.</p>
            </div>
            <div class="col-auto">
                <button class="btn btn-primary px-4 py-2 font-weight-bold shadow-sm" style="border-radius: 10px;" data-toggle="modal" data-target="#newStaff">
                    <i class="fas fa-plus-circle mr-2"></i> Grant New Permission
                </button>
            </div>
        </div>

        <div class="row mb-4">
            <div class="col-xl-4 col-md-6 mb-4">
                <div class="card roles-stats-card p-4">
                    <div class="card-icon icon-blue"><i class="fas fa-user-shield"></i></div>
                    <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">Total System Personnel</div>
                    <div class="h3 mb-0 font-weight-bold text-gray-800">{{ $users->count() }}</div>
                </div>
            </div>
            <div class="col-xl-4 col-md-6 mb-4">
                <div class="card roles-stats-card p-4">
                    <div class="card-icon icon-red"><i class="fas fa-admin"></i></div>
                    <div class="text-xs font-weight-bold text-danger text-uppercase mb-1">Active Administrators</div>
                    <div class="h3 mb-0 font-weight-bold text-gray-800">{{ $users->where('role_type', 'admin')->count() }}</div>
                </div>
            </div>
            <div class="col-xl-4 col-md-6 mb-4">
                <div class="card roles-stats-card p-4">
                    <div class="card-icon icon-purple"><i class="fas fa-check-double"></i></div>
                    <div class="text-xs font-weight-bold text-purple text-uppercase mb-1" style="color: #7e22ce">Designated Approvers</div>
                    <div class="h3 mb-0 font-weight-bold text-gray-800">{{ $users->where('role_type', 'approver')->count() }}</div>
                </div>
            </div>
        </div>

        <div class="card border-0 shadow-sm" style="border-radius: 16px;">
            <div class="table-responsive">
                <table class="table modern-table mb-0" id="rolesTable">
                    <thead>
                        <tr>
                            <th>User Profile</th>
                            <th>Contact</th>
                            <th>ID Number</th>
                            <th>Primary Role</th>
                            <th>Authorized Entites</th>
                            <th class="text-center">Configure</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($users as $user)
                            @php
                                $role = strtolower($user->role_type) ?: 'user';
                                $pillClass = "pill-$role";
                            @endphp
                            <tr>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <div class="bg-gray-100 text-primary font-weight-bold rounded-lg d-flex align-items-center justify-content-center mr-3 shadow-sm" style="width: 40px; height: 40px; border-radius: 10px;">
                                            {{ strtoupper(substr($user->first_name, 0, 1)) }}
                                        </div>
                                        <div>
                                            <div class="font-weight-bold text-gray-800">{{ $user->first_name }} {{ $user->last_name }}</div>
                                            <div class="text-muted extra-small">{{ $user->email }}</div>
                                        </div>
                                    </div>
                                </td>
                                <td><span class="text-gray-600 small">{{ $user->contacts }}</span></td>
                                <td><span class="text-gray-600 small">{{ $user->id_number }}</span></td>
                                <td><span class="role-pill {{ $pillClass }}">{{ strtoupper($user->role_type) }}</span></td>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <span class="badge badge-primary badge-pill mr-2">{{ $user->companies_count }}</span>
                                        <span class="text-muted small">Companies</span>
                                    </div>
                                </td>
                                <td class="text-center">
                                    <button type="button" class="action-btn" data-toggle="modal" data-target="#modal-view-{{ $user->id }}">
                                        <i class="fas fa-sliders-h"></i>
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
    @foreach($users as $user)
        <div class="modal fade glass-modal" id="modal-view-{{ $user->id }}" tabindex="-1" role="dialog" aria-hidden="true">
            <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
                <div class="modal-content">
                    <div class="modal-header bg-gray-50 border-0">
                        <h5 class="modal-title font-weight-bold text-gray-800">Entity Access: {{ $user->first_name }}</h5>
                        <button class="close" type="button" data-dismiss="modal"><span>&times;</span></button>
                    </div>
                    <div class="modal-body p-4">
                        <div class="row mb-4">
                            <div class="col-6"><span class="text-xs font-weight-bold text-muted text-uppercase d-block mb-1">Full Name</span><div class="font-weight-bold">{{ $user->first_name }} {{ $user->last_name }}</div></div>
                            <div class="col-6"><span class="text-xs font-weight-bold text-muted text-uppercase d-block mb-1">System Role</span><span class="role-pill {{ 'pill-'.(strtolower($user->role_type)?:'user') }}">{{ strtoupper($user->role_type) }}</span></div>
                        </div>

                        <h6 class="font-weight-bold text-gray-800 border-bottom pb-2 mb-3">Authorized Designations</h6>
                        <div class="table-responsive">
                            <table class="table table-hover table-sm">
                                <thead class="bg-light">
                                    <tr>
                                        <th class="px-3">#</th>
                                        <th>Entity Name</th>
                                        <th class="text-right px-3">Revoke</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($user->companies_list as $company_access)
                                        <tr>
                                            <td class="px-3">{{ $loop->iteration }}</td>
                                            <td class="font-weight-bold">{{ $company_access->name }}</td>
                                            <td class="text-right px-3">
                                                <form action="{{ route('roles.destroy', $company_access->access_id) }}" method="POST" onsubmit="return confirm('Revoke access immediately?')">
                                                    @csrf @method('DELETE')
                                                    <button class="btn btn-sm btn-outline-danger border-0"><i class="fas fa-trash-alt"></i></button>
                                                </form>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr><td colspan="3" class="text-center py-4 text-muted">No entities assigned.</td></tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endforeach

    <div class="modal fade glass-modal" id="newStaff" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content shadow-lg">
                <div class="modal-header bg-primary text-white border-0" style="border-radius: 20px 20px 0 0;">
                    <h5 class="modal-title font-weight-bold"><i class="fas fa-plus-circle mr-2"></i>New Permission Grant</h5>
                    <button class="close text-white" type="button" data-dismiss="modal"><span>&times;</span></button>
                </div>
                <form action="{{route('roles.store')}}" method="POST">
                    @csrf
                    <div class="modal-body p-4">
                        <div class="form-group mb-4">
                            <label class="font-weight-bold text-gray-700 small">1. Select Target User</label>
                            @php
                                $all_users = \App\Models\User::select('id', 'first_name', 'last_name', 'email')->get();
                                $companies = \App\Models\Company::select('id', 'name')->get();
                            @endphp
                            <input list="userList" id="user-input" class="form-control form-control-modern" placeholder="Search by name or email..." required>
                            <input type="hidden" name="user_id" id="user-id">
                            <datalist id="userList">
                                @foreach ($all_users as $u)
                                    <option data-id="{{ $u->id }}" value="{{ $u->first_name . ' ' . $u->last_name }}">({{ $u->email }})</option>
                                @endforeach
                            </datalist>
                        </div>

                        <div class="form-group mb-4">
                            <label class="font-weight-bold text-gray-700 small">2. Assign to Entity</label>
                            <select name="company" class="form-control form-control-modern custom-select" required>
                                <option value="" selected disabled>Select Entity/Company...</option>
                                @foreach ($companies as $c)
                                    <option value="{{ $c->name }}">{{ $c->name }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="form-group mb-4">
                            <label class="font-weight-bold text-gray-700 small">3. Functional Role Authority</label>
                            <select name="role_type" class="form-control form-control-modern custom-select" required>
                                <option value="">Choose Role Capability...</option>
                                <option value="1">Standard User (Applicant)</option>
                                <option value="2">Human Resource (Reviewer)</option>
                                <option value="3">Finance Officer (Reviewer)</option>
                                <option value="4">System Admin (Full Access)</option>
                                <option value="5">Executive Approver (Final)</option>
                            </select>
                        </div>
                    </div>
                    <div class="modal-footer border-0 p-4 pt-0">
                        <button class="btn btn-light btn-modern flex-grow-1" type="button" data-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-primary btn-modern flex-grow-1 shadow">Grant Access</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <script>
        $(document).ready(function() {
            $('#user-input').on('input', function() {
                const val = this.value;
                const option = $('#userList option').filter(function() {
                    return this.value === val;
                });
                if(option.length) {
                    $('#user-id').val(option.data('id'));
                }
            });

            if ($.fn.dataTable.isDataTable('#rolesTable')) {
                $('#rolesTable').DataTable().destroy();
            }
            $('#rolesTable').DataTable({
                "pageLength": 10,
                "language": { "search": "", "searchPlaceholder": "Filter permissions..." },
                "dom": '<"d-flex justify-content-between align-items-center mb-0"f>t<"d-flex justify-content-between align-items-center mt-3"ip>'
            });
        });
    </script>
@endsection