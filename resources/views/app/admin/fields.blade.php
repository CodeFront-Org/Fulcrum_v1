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
            font-size: 0.7rem;
            font-weight: 800;
            letter-spacing: 0.05em;
            padding: 14px 12px !important;
            border-bottom: 2px solid #e2e8f0 !important;
            white-space: nowrap;
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
            transform: scale(1.1);
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.05);
            color: #4e73df;
            border-color: #4e73df;
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
            background-color: #f8fafc;
        }

        .form-control-modern:focus {
            background-color: white;
            border-color: #4e73df;
            box-shadow: 0 0 0 3px rgba(78, 115, 223, 0.1);
        }

        .btn-modern {
            padding: 10px 20px;
            border-radius: 10px;
            font-weight: 600;
        }

        .required-badge {
            font-size: 0.7rem;
            font-weight: 700;
            padding: 2px 6px;
            border-radius: 4px;
            text-transform: uppercase;
        }

        .badge-mandatory {
            background: #ffe2e2;
            color: #c53030;
        }

        .badge-optional {
            background: #e2e8f0;
            color: #4a5568;
        }

        .preview-card {
            border: 1px dashed #cbd5e1;
            border-radius: 16px;
            background: #f8fafc;
            padding: 24px;
        }

        .preview-title {
            font-size: 0.95rem;
            font-weight: 700;
            color: #475569;
            margin-bottom: 20px;
            border-bottom: 2px solid #e2e8f0;
            padding-bottom: 8px;
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
                <div class="mb-2">
                    <a href="{{ route('companies.index') }}" class="btn btn-link p-0 text-primary font-weight-bold small">
                        <i class="fas fa-arrow-left mr-1"></i> Back to Designation Rates
                    </a>
                </div>
                <h1 class="h3 mb-0 text-gray-800 font-weight-bold">Form Builder: {{ $company->name }}</h1>
                <p class="text-muted mb-0">Configure additional dynamic input fields for loan applicants of this scheme.</p>
            </div>
            <div class="col-auto">
                <button class="btn btn-primary btn-modern shadow-sm" data-toggle="modal" data-target="#newField">
                    <i class="fas fa-plus-circle mr-2"></i> Add Form Field
                </button>
            </div>
        </div>

        <div class="row">
            <!-- Form Fields Configuration -->
            <div class="col-xl-7 mb-4">
                <div class="card border-0 shadow-sm" style="border-radius: 16px;">
                    <div class="card-header bg-white py-3 border-0">
                        <h6 class="m-0 font-weight-bold text-gray-800 d-flex align-items-center">
                            <i class="fas fa-list mr-2 text-primary"></i> Configured Input Fields
                        </h6>
                    </div>
                    <div class="table-responsive">
                        <table class="table modern-table mb-0">
                            <thead>
                                <tr>
                                    <th style="width: 50px;">#</th>
                                    <th>Field Label</th>
                                    <th>Field Type</th>
                                    <th>Status</th>
                                    <th class="text-center" style="width: 100px;">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($fields as $field)
                                    <tr>
                                        <td class="text-muted small">{{ $loop->iteration }}</td>
                                        <td><span class="font-weight-bold text-gray-800">{{ $field->label }}</span></td>
                                        <td>
                                            <span class="badge badge-light text-capitalize font-weight-bold text-dark px-2 py-1">
                                                {{ $field->type === 'combobox' ? 'combobox (Select)' : $field->type }}
                                            </span>
                                        </td>
                                        <td>
                                            @if ($field->is_required)
                                                <span class="required-badge badge-mandatory">Mandatory</span>
                                            @else
                                                <span class="required-badge badge-optional">Optional</span>
                                            @endif
                                        </td>
                                        <td>
                                            <div class="d-flex justify-content-center">
                                                <button type="button" class="action-btn mr-2" data-toggle="modal"
                                                    data-target="#editField-{{ $field->id }}" title="Edit Field">
                                                    <i class="fas fa-pen fa-xs"></i>
                                                </button>
                                                <button type="button" onclick="delField(this)" value="{{ $field->id }}" class="action-btn"
                                                    title="Delete">
                                                    <i class="fas fa-trash fa-xs text-danger"></i>
                                                </button>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" class="text-center text-muted py-4">
                                            No additional fields configured for this scheme yet.
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <!-- Form Live Preview -->
            <div class="col-xl-5 mb-4">
                <div class="card border-0 shadow-sm" style="border-radius: 16px; height: 100%;">
                    <div class="card-header bg-white py-3 border-0">
                        <h6 class="m-0 font-weight-bold text-gray-800 d-flex align-items-center">
                            <i class="fas fa-eye mr-2 text-info"></i> Form Live Preview
                        </h6>
                    </div>
                    <div class="card-body">
                        <div class="preview-card">
                            <div class="preview-title">
                                <i class="fas fa-user-circle mr-2 text-secondary"></i>Applicant Form Preview
                            </div>
                            
                            <!-- Static Standard Field Example -->
                            <div class="form-group mb-3">
                                <label class="font-weight-bold text-gray-700 small">Next of Kin Name <span class="text-danger">*</span></label>
                                <input type="text" class="form-control form-control-modern" value="John Doe (Example)" readonly style="background-color: #f1f5f9; cursor: not-allowed;" />
                            </div>

                            <!-- Dynamic Configured Fields -->
                            @foreach ($fields as $field)
                                <div class="form-group mb-3">
                                    <label class="font-weight-bold text-gray-700 small">
                                        {{ $field->label }}
                                        @if ($field->is_required)
                                            <span class="text-danger">*</span>
                                        @endif
                                    </label>
                                    
                                    @if ($field->type === 'text')
                                        <input type="text" class="form-control form-control-modern" placeholder="Enter text..." readonly />
                                    @elseif ($field->type === 'number')
                                        <input type="number" class="form-control form-control-modern" placeholder="0" readonly />
                                    @elseif ($field->type === 'textarea')
                                        <textarea class="form-control form-control-modern" rows="2" placeholder="Enter long description..." readonly></textarea>
                                    @elseif ($field->type === 'combobox')
                                        <select class="form-control form-control-modern" readonly>
                                            <option value="">-- Select option --</option>
                                            @if ($field->options)
                                                @foreach (explode(',', $field->options) as $opt)
                                                    <option>{{ trim($opt) }}</option>
                                                @endforeach
                                            @endif
                                        </select>
                                    @endif
                                </div>
                            @endforeach

                            <div class="mt-4 pt-2">
                                <button class="btn btn-primary btn-block btn-modern" type="button" disabled>
                                    Continue to Financials <i class="fas fa-chevron-right ml-2"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Add Field Modal -->
    <div class="modal fade glass-modal" id="newField" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header bg-primary text-white" style="border-radius: 20px 20px 0 0;">
                    <h5 class="modal-title font-weight-bold"><i class="fas fa-plus-circle mr-2"></i>Add Form Field</h5>
                    <button class="close text-white" type="button" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
                <form action="{{ route('companies.fields.store', $company->id) }}" method="POST">
                    @csrf
                    <div class="modal-body p-4">
                        <div class="form-group mb-3">
                            <label class="font-weight-bold text-gray-700">Field Label</label>
                            <input name="label" type="text" class="form-control form-control-modern" placeholder="e.g. Department or Account Number" required />
                        </div>

                        <div class="form-group mb-3">
                            <label class="font-weight-bold text-gray-700">Field Type</label>
                            <select name="type" class="form-control form-control-modern type-selector" required>
                                <option value="text">Text Input</option>
                                <option value="number">Number Input</option>
                                <option value="combobox">Combobox (Select Dropdown)</option>
                                <option value="textarea">Text Area</option>
                            </select>
                        </div>

                        <div class="form-group mb-3 options-container" style="display: none;">
                            <label class="font-weight-bold text-gray-700">Combobox Options</label>
                            <input name="options" type="text" class="form-control form-control-modern" placeholder="Option A, Option B, Option C" />
                            <small class="form-text text-muted">Separate options with commas.</small>
                        </div>

                        <div class="form-group mb-0">
                            <label class="font-weight-bold text-gray-700 d-block">Validation Rule</label>
                            <div class="custom-control custom-checkbox">
                                <input type="checkbox" class="custom-control-input" id="isRequiredNew" name="is_required" value="1">
                                <label class="custom-control-label font-weight-bold text-gray-600" for="isRequiredNew">Field is mandatory</label>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer border-0 p-4 pt-0">
                        <button class="btn btn-light btn-modern px-4" type="button" data-dismiss="modal">Cancel</button>
                        <button class="btn btn-primary btn-modern px-5 shadow" type="submit">Save Field</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Edit Field Modals -->
    @foreach ($fields as $field)
        <div class="modal fade glass-modal" id="editField-{{ $field->id }}" tabindex="-1" role="dialog" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered" role="document">
                <div class="modal-content">
                    <div class="modal-header bg-primary text-white" style="border-radius: 20px 20px 0 0;">
                        <h5 class="modal-title font-weight-bold"><i class="fas fa-pen mr-2"></i>Edit Field: {{ $field->label }}</h5>
                        <button class="close text-white" type="button" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">×</span>
                        </button>
                    </div>
                    <form action="{{ route('companies.fields.update', $field->id) }}" method="POST">
                        @method('PUT')
                        @csrf
                        <div class="modal-body p-4">
                            <div class="form-group mb-3">
                                <label class="font-weight-bold text-gray-700">Field Label</label>
                                <input name="label" type="text" value="{{ $field->label }}" class="form-control form-control-modern" required />
                            </div>

                            <div class="form-group mb-3">
                                <label class="font-weight-bold text-gray-700">Field Type</label>
                                <select name="type" class="form-control form-control-modern type-selector" required>
                                    <option value="text" {{ $field->type === 'text' ? 'selected' : '' }}>Text Input</option>
                                    <option value="number" {{ $field->type === 'number' ? 'selected' : '' }}>Number Input</option>
                                    <option value="combobox" {{ $field->type === 'combobox' ? 'selected' : '' }}>Combobox (Select Dropdown)</option>
                                    <option value="textarea" {{ $field->type === 'textarea' ? 'selected' : '' }}>Text Area</option>
                                </select>
                            </div>

                            <div class="form-group mb-3 options-container" style="display: {{ $field->type === 'combobox' ? 'block' : 'none' }};">
                                <label class="font-weight-bold text-gray-700">Combobox Options</label>
                                <input name="options" type="text" value="{{ $field->options }}" class="form-control form-control-modern" placeholder="Option A, Option B, Option C" />
                                <small class="form-text text-muted">Separate options with commas.</small>
                            </div>

                            <div class="form-group mb-0">
                                <label class="font-weight-bold text-gray-700 d-block">Validation Rule</label>
                                <div class="custom-control custom-checkbox">
                                    <input type="checkbox" class="custom-control-input" id="isRequiredEdit-{{ $field->id }}" name="is_required" value="1" {{ $field->is_required ? 'checked' : '' }}>
                                    <label class="custom-control-label font-weight-bold text-gray-600" for="isRequiredEdit-{{ $field->id }}">Field is mandatory</label>
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer border-0 p-4 pt-0">
                            <button class="btn btn-light btn-modern px-4" type="button" data-dismiss="modal">Cancel</button>
                            <button class="btn btn-primary btn-modern px-5 shadow" type="submit">Update Field</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    @endforeach
@endsection

@section('scripts')
    <script>
        $(document).ready(function() {
            // Handle field type selection to toggle options container
            $(document).on('change', '.type-selector', function() {
                var selectedType = $(this).val();
                var optionsContainer = $(this).closest('form').find('.options-container');
                var optionsInput = optionsContainer.find('input');
                
                if (selectedType === 'combobox') {
                    optionsContainer.slideDown(200);
                    optionsInput.attr('required', 'required');
                } else {
                    optionsContainer.slideUp(200);
                    optionsInput.removeAttr('required');
                }
            });
        });

        function delField(e) {
            let id = e.value;
            Swal.fire({
                title: "Confirm Deletion",
                text: "This action will remove this dynamic form field from the loan application page.",
                icon: "warning",
                showCancelButton: true,
                confirmButtonColor: "#e74a3b",
                confirmButtonText: "Yes, Delete Field",
                borderRadius: "15px"
            }).then((t) => {
                if (t.isConfirmed) {
                    $.ajax({
                        type: "DELETE",
                        url: "/companies/fields/" + id,
                        data: { _token: "{{ csrf_token() }}", id },
                        success: function (response) {
                            Swal.fire("Deleted!", "Form field removed.", "success").then(() => { location.reload() })
                        }
                    });
                }
            })
        }
    </script>
@endsection
