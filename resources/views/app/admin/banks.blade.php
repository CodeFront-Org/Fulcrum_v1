@extends('layouts.app')

@section('css')
    <style>
        .bank-stats-card {
            transition: all 0.3s cubic-bezier(.25, .8, .25, 1);
            border: none;
            border-radius: 16px;
            background: white;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
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

        .glass-modal .modal-content {
            border: none;
            border-radius: 20px;
            box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.25);
        }

        .form-control-modern {
            border-radius: 10px;
            padding: 12px 16px;
            border: 1px solid #e2e8f0;
            transition: all 0.2s;
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

        <!-- Header Row -->
        <div class="row align-items-center mb-4">
            <div class="col">
                <h1 class="h3 mb-0 text-gray-800 font-weight-bold">Bank Management</h1>
                <p class="text-muted mb-0">Configure and manage supported financial institutions.</p>
            </div>
            <div class="col-auto">
                <button class="btn btn-primary btn-modern shadow-sm px-4" data-toggle="modal" data-target="#newBank">
                    <i class="fas fa-plus-circle mr-2"></i> Add Supporting Bank
                </button>
            </div>
        </div>

        <!-- Main Card -->
        <div class="card border-0 shadow-sm overflow-hidden" style="border-radius: 16px;">
            <div class="card-header bg-white py-3 border-0">
                <h6 class="m-0 font-weight-bold text-gray-800">Available Banks</h6>
            </div>
            <div class="table-responsive">
                <table class="table modern-table mb-0" id="banksTable">
                    <thead>
                        <tr>
                            <th style="width: 80px;">#</th>
                            <th>Bank Name</th>
                            <th>System Code</th>
                            <th class="text-center">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($banks as $item)
                            <tr>
                                <td><span class="text-muted font-weight-bold">{{ $loop->iteration }}</span></td>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <div class="stat-icon mr-3"
                                            style="background: rgba(78, 115, 223, 0.1); width: 35px; height: 35px; font-size: 0.9rem; color: #4e73df;">
                                            <i class="fas fa-university"></i>
                                        </div>
                                        <span class="font-weight-bold text-gray-800">{{ $item['name'] }}</span>
                                    </div>
                                </td>
                                <td><code
                                        class="bg-light px-2 py-1 rounded text-primary">{{ str_pad($item['id'], 3, '0', STR_PAD_LEFT) }}</code>
                                </td>
                                <td>
                                    <div class="d-flex justify-content-center">
                                        <button type="button" class="action-btn mr-2" data-toggle="modal"
                                            data-target="#bank-edit-{{ $item['id'] }}" title="Edit Bank">
                                            <i class='fas fa-pen fa-sm text-primary'></i>
                                        </button>
                                        <button type="button" onclick="del(this)" value="{{ $item['id'] }}" class="action-btn"
                                            title="Delete Bank">
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

    <!-- New Bank Modal-->
    <div class="modal fade glass-modal" id="newBank" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header bg-primary text-white" style="border-radius: 20px 20px 0 0;">
                    <h5 class="modal-title font-weight-bold"><i class="fas fa-plus-circle mr-2"></i>Add New Bank</h5>
                    <button class="close text-white" type="button" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
                <form action="{{ route('banks.store') }}" method="POST">
                    @csrf
                    <div class="modal-body p-4">
                        <div class="form-group mb-0">
                            <label class="font-weight-bold text-gray-700">Bank Name</label>
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <span class="input-group-text bg-white border-right-0"><i
                                            class="fas fa-university text-muted"></i></span>
                                </div>
                                <input name="name" type="text" class="form-control form-control-modern border-left-0"
                                    placeholder="Enter full bank name" required />
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer border-0 p-4 pt-0">
                        <button class="btn btn-light btn-modern px-4" type="button" data-dismiss="modal">Cancel</button>
                        <button class="btn btn-primary btn-modern px-5 shadow" type="submit">Save Bank</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    @foreach ($banks as $item)
        <!-- Edit Bank Modal-->
        <div class="modal fade glass-modal" id="bank-edit-{{ $item['id'] }}" tabindex="-1" role="dialog" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered" role="document">
                <div class="modal-content">
                    <div class="modal-header bg-primary text-white" style="border-radius: 20px 20px 0 0;">
                        <h5 class="modal-title font-weight-bold"><i class="fas fa-pen mr-2"></i>Edit Bank Details</h5>
                        <button class="close text-white" type="button" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">×</span>
                        </button>
                    </div>
                    <form action="{{ route('banks.update', $item['id']) }}" method="POST">
                        @method('PATCH')
                        @csrf
                        <div class="modal-body p-4">
                            <div class="form-group mb-0">
                                <label class="font-weight-bold text-gray-700">Bank Name</label>
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text bg-white border-right-0"><i
                                                class="fas fa-university text-muted"></i></span>
                                    </div>
                                    <input name="name" type="text" value="{{ $item['name'] }}"
                                        class="form-control form-control-modern border-left-0" required />
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer border-0 p-4 pt-0">
                            <button class="btn btn-light btn-modern px-4" type="button" data-dismiss="modal">Cancel</button>
                            <button class="btn btn-primary btn-modern px-5 shadow" type="submit">Update Bank</button>
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
            Swal.fire({
                title: "Confirm Deletion",
                text: "Removing this bank may affect users assigned to it.",
                icon: "warning",
                showCancelButton: true,
                confirmButtonColor: "#e74a3b",
                confirmButtonText: "Yes, Delete",
                borderRadius: "15px"
            }).then((t) => {
                if (t.isConfirmed) {
                    $.ajax({
                        type: "DELETE",
                        url: "banks/" + id,
                        data: {
                            _token: "{{ csrf_token() }}",
                            id
                        },
                        success: function (response) {
                            Swal.fire("Deleted!", "Bank removed successfully.", "success").then(() => {
                                location.reload()
                            })
                        }
                    });
                }
            })
        }
    </script>
@endsection