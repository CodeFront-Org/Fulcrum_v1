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
            font-size: 0.65rem;
            font-weight: 800;
            letter-spacing: 0.05em;
            padding: 12px 10px !important;
            border-bottom: 2px solid #e2e8f0 !important;
            white-space: nowrap;
        }

        .modern-table tbody td {
            padding: 12px 10px !important;
            vertical-align: middle !important;
            border-bottom: 1px solid #f1f5f9 !important;
            color: #334155;
            font-size: 0.8rem;
        }

        .rate-cell {
            font-family: 'Courier New', Courier, monospace;
            font-weight: 600;
            color: #4e73df;
        }

        .action-btn {
            width: 30px;
            height: 30px;
            border-radius: 8px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            transition: all 0.2s;
            border: 1px solid #e2e8f0;
            background: white;
        }

        .action-btn:hover {
            transform: scale(1.1);
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.05);
        }

        .glass-modal .modal-content {
            border: none;
            border-radius: 20px;
            box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.25);
        }

        .form-control-modern {
            border-radius: 8px;
            border: 1px solid #e2e8f0;
            font-size: 0.85rem;
            padding: 8px 12px;
        }

        .form-control-modern:focus {
            border-color: #4e73df;
            box-shadow: 0 0 0 3px rgba(78, 115, 223, 0.1);
        }

        .btn-modern {
            padding: 10px 20px;
            border-radius: 10px;
            font-weight: 600;
        }

        .rate-label {
            font-size: 0.75rem;
            text-transform: uppercase;
            color: #64748b;
            font-weight: 700;
            margin-bottom: 4px;
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

        <div class="row align-items-center mb-4">
            <div class="col">
                <h1 class="h3 mb-0 text-gray-800 font-weight-bold">Designation Rates</h1>
                <p class="text-muted mb-0">Manage interest rates and repayment factors for various company designations.</p>
            </div>
            <div class="col-auto">
                <button class="btn btn-primary btn-modern shadow-sm" data-toggle="modal" data-target="#newDesignation">
                    <i class="fas fa-plus-circle mr-2"></i> New Designation
                </button>
            </div>
        </div>

        <div class="card border-0 shadow-sm" style="border-radius: 16px;">
            <div class="card-header bg-white py-3 border-0">
                <h6 class="m-0 font-weight-bold text-gray-800 d-flex align-items-center">
                    <i class="fas fa-percentage mr-2 text-primary"></i> Monthly Factor Configuration
                </h6>
            </div>
            <div class="table-responsive">
                <table class="table modern-table mb-0" id="designationsTable">
                    <thead>
                        <tr>
                            <th style="width: 50px;">#</th>
                            <th style="min-width: 150px;">Designation Name</th>
                            @for ($i = 1; $i <= 12; $i++)
                                <th class="text-center">M{{$i}}</th>
                            @endfor
                            <th class="text-center">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($companies as $item)
                            <tr>
                                <td class="text-muted small">{{ $loop->iteration }}</td>
                                <td><span class="font-weight-bold text-gray-800">{{ $item['name'] }}</span></td>
                                @for ($i = 1; $i <= 12; $i++)
                                    @php $fieldName = 'month' . $i; @endphp
                                    <td class="text-center rate-cell">{{ number_format($item[$fieldName], 3) }}</td>
                                @endfor
                                <td>
                                    <div class="d-flex justify-content-center">
                                        <button type="button" class="action-btn mr-2" data-toggle="modal"
                                            data-target="#designation-edit-{{ $item['id'] }}" title="Edit Designation">
                                            <i class='fas fa-pen fa-xs text-primary'></i>
                                        </button>
                                        <button type="button" onclick="del(this)" value="{{ $item['id'] }}" class="action-btn"
                                            title="Delete">
                                            <i class='fa fa-trash fa-xs text-danger'></i>
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
    <div class="modal fade glass-modal" id="newDesignation" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header bg-primary text-white" style="border-radius: 20px 20px 0 0;">
                    <h5 class="modal-title font-weight-bold"><i class="fas fa-plus-circle mr-2"></i>Create New Designation
                    </h5>
                    <button class="close text-white" type="button" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
                <form action="{{ route('companies.store') }}" method="POST">
                    @csrf
                    <div class="modal-body p-4">
                        <div class="form-group mb-4">
                            <label class="font-weight-bold text-gray-700">Designation / Profile Name</label>
                            <input name="name" type="text" class="form-control form-control-modern"
                                placeholder="e.g. Standard Scheme - VIP" required />
                        </div>

                        <h6 class="text-primary font-weight-bold mb-3 border-bottom pb-2">Monthly Interest Factors</h6>
                        <div class="row">
                            @for ($i = 1; $i <= 12; $i++)
                                <div class="col-md-3 col-6 mb-3">
                                    <label class="rate-label">Month {{$i}} Factor</label>
                                    <input name="m{{$i}}" type="number" step="0.001" class="form-control form-control-modern"
                                        placeholder="0.000" required />
                                </div>
                            @endfor
                        </div>
                    </div>
                    <div class="modal-footer border-0 p-4 pt-0">
                        <button class="btn btn-light btn-modern px-4" type="button" data-dismiss="modal">Cancel</button>
                        <button class="btn btn-primary btn-modern px-5 shadow" type="submit">Save Designation</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    @foreach ($companies as $item)
        <div class="modal fade glass-modal" id="designation-edit-{{ $item['id'] }}" tabindex="-1" role="dialog"
            aria-hidden="true">
            <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
                <div class="modal-content">
                    <div class="modal-header bg-primary text-white" style="border-radius: 20px 20px 0 0;">
                        <h5 class="modal-title font-weight-bold"><i class="fas fa-pen mr-2"></i>Edit Designation:
                            {{ $item['name'] }}</h5>
                        <button class="close text-white" type="button" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">×</span>
                        </button>
                    </div>
                    <form action="{{ route('companies.update', $item['id']) }}" method="POST">
                        @method('PATCH')
                        @csrf
                        <div class="modal-body p-4">
                            <div class="form-group mb-4">
                                <label class="font-weight-bold text-gray-700">Designation Name</label>
                                <input name="name" type="text" value="{{ $item['name'] }}"
                                    class="form-control form-control-modern" required />
                            </div>

                            <h6 class="text-primary font-weight-bold mb-3 border-bottom pb-2">Update Factors</h6>
                            <div class="row">
                                @for ($i = 1; $i <= 12; $i++)
                                    @php $fieldName = 'month' . $i; @endphp
                                    <div class="col-md-3 col-6 mb-3">
                                        <label class="rate-label">Month {{$i}}</label>
                                        <input name="m{{$i}}" type="number" step="0.001" value="{{ $item[$fieldName] }}"
                                            class="form-control form-control-modern" required />
                                    </div>
                                @endfor
                            </div>
                        </div>
                        <div class="modal-footer border-0 p-4 pt-0">
                            <button class="btn btn-light btn-modern px-4" type="button" data-dismiss="modal">Cancel</button>
                            <button class="btn btn-primary btn-modern px-5 shadow" type="submit">Update Rates</button>
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
                text: "This action will remove the designation and its rate structure.",
                icon: "warning",
                showCancelButton: true,
                confirmButtonColor: "#e74a3b",
                confirmButtonText: "Yes, Delete Record",
                borderRadius: "15px"
            }).then((t) => {
                if (t.isConfirmed) {
                    $.ajax({
                        type: "DELETE",
                        url: "companies/" + id,
                        data: { _token: "{{ csrf_token() }}", id },
                        success: function (response) {
                            Swal.fire("Deleted!", "Designation removed.", "success").then(() => { location.reload() })
                        }
                    });
                }
            })
        }
    </script>
@endsection