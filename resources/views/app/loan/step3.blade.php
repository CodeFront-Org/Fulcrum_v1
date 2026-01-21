@extends('layouts.app')

@section('css')
    <style>
        .step-indicator {
            display: flex;
            justify-content: space-between;
            margin-bottom: 2rem;
            position: relative;
        }

        .step-indicator::before {
            content: '';
            position: absolute;
            top: 50%;
            left: 0;
            right: 0;
            height: 2px;
            background: #e2e8f0;
            transform: translateY(-50%);
            z-index: 1;
        }

        .step-item {
            position: relative;
            z-index: 2;
            background: #f8fafc;
            width: 40px;
            height: 40px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            border: 2px solid #e2e8f0;
            font-weight: 700;
            color: #64748b;
            transition: all 0.3s;
        }

        .step-item.active {
            background: #4e73df;
            border-color: #4e73df;
            color: white;
            box-shadow: 0 0 0 4px rgba(78, 115, 223, 0.2);
        }

        .step-item.completed {
            background: #10b981;
            border-color: #10b981;
            color: white;
        }

        .step-label {
            position: absolute;
            top: 100%;
            left: 50%;
            transform: translateX(-50%);
            margin-top: 8px;
            font-size: 0.7rem;
            font-weight: 700;
            text-transform: uppercase;
            white-space: nowrap;
            color: #64748b;
        }

        .loan-form-card {
            border: none;
            border-radius: 20px;
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.05);
            background: white;
        }

        .form-section-title {
            font-size: 1.1rem;
            font-weight: 700;
            color: #1e293b;
            margin-bottom: 1.5rem;
            display: flex;
            align-items: center;
        }

        .form-section-title i {
            width: 32px;
            height: 32px;
            background: rgba(78, 115, 223, 0.1);
            color: #4e73df;
            border-radius: 8px;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-right: 12px;
            font-size: 0.9rem;
        }

        .form-control-modern {
            border-radius: 10px;
            padding: 12px 16px;
            border: 1px solid #e2e8f0;
            font-size: 0.9rem;
            transition: all 0.2s;
            background-color: #f8fafc;
        }

        .form-control-modern:focus {
            background-color: white;
            border-color: #4e73df;
            box-shadow: 0 0 0 4px rgba(78, 115, 223, 0.1);
            outline: none;
        }

        .form-control-modern[readonly] {
            background-color: #f1f5f9;
            cursor: not-allowed;
        }

        .btn-modern {
            padding: 12px 28px;
            border-radius: 12px;
            font-weight: 700;
            transition: all 0.3s;
        }

        .emi-display {
            background: #4e73df;
            color: white;
            border-radius: 15px;
            padding: 20px;
            text-align: center;
            box-shadow: 0 4px 12px rgba(78, 115, 223, 0.3);
        }

        .emi-val {
            font-size: 1.75rem;
            font-weight: 800;
            display: block;
        }
    </style>
@endsection

@section('content')
    <div class="container py-4">
        <div class="row justify-content-center">
            <div class="col-lg-10">
                <!-- Step Progress -->
                <div class="step-indicator px-lg-5">
                    <div class="step-item completed"><i class="fas fa-check"></i> <span class="step-label">Personal
                            Info</span></div>
                    <div class="step-item completed"><i class="fas fa-check"></i> <span class="step-label">Financials</span>
                    </div>
                    <div class="step-item active">3 <span class="step-label">Loan Scope</span></div>
                    <div class="step-item">4 <span class="step-label">T & C</span></div>
                </div>

                <!-- Form Card -->
                <div class="card loan-form-card mt-5">
                    <div class="card-body p-4 p-lg-5">
                        <div class="form-section-title">
                            <i class="fas fa-hand-holding-usd"></i>
                            Loan Configuration
                        </div>

                        <form action="{{ route('loan.store') }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            <input type="hidden" name="type" value="3">

                            <div class="row">
                                <div class="col-md-7">
                                    <div class="row">
                                        <div class="col-md-12 mb-4">
                                            <label class="font-weight-bold text-gray-700 small">Requested Amount</label>
                                            <div class="input-group">
                                                <div class="input-group-prepend">
                                                    <span class="input-group-text bg-light border-right-0">KES</span>
                                                </div>
                                                <input name="loan" id="loanAmt" value="{{ $loan_amt }}" type="number"
                                                    class="form-control form-control-modern border-left-0"
                                                    placeholder="Enter amount" required />
                                            </div>
                                        </div>
                                        <div class="col-md-12 mb-4">
                                            <label class="font-weight-bold text-gray-700 small">Repayment Duration</label>
                                            <select name="period" class="form-control form-control-modern custom-select"
                                                id="field-period" required>
                                                <option value="" disabled selected>Select period (Months)</option>
                                                @foreach ($periods as $item)
                                                    <option {{ $period == $loop->index + 1 ? 'selected' : '' }}
                                                        value="{{ $loop->index + 1 }}" data-val="{{ $item }}"
                                                        data-description="{{ $loop->index + 1 }}">{{ $loop->index + 1 }} Months
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-5 mb-4">
                                    <div class="emi-display">
                                        <span class="small font-weight-bold text-uppercase" style="opacity: 0.8">Estimated
                                            Monthly Installment</span>
                                        <span class="emi-val" id="display-emi">0.00</span>
                                        <input type="hidden" name="installments" id="M_I" value="{{ $installment }}">
                                        <span class="small" style="opacity: 0.6">*Calculated based on designation
                                            rate</span>
                                    </div>
                                </div>
                            </div>

                            <hr class="my-3">

                            <div class="row">
                                <div class="col-md-12 mb-4">
                                    <label class="font-weight-bold text-gray-700 small">Verification Documents (Current
                                        Payslip)</label>
                                    <div class="custom-file-modern p-3 border rounded d-flex align-items-center bg-light">
                                        <i class="fas fa-file-upload fa-2x text-primary mr-3"></i>
                                        <div class="flex-grow-1">
                                            <input type="file" name="payslip" class="d-none" id="payslip-input" {{ !$is_upload ? 'required' : '' }} accept=".pdf, .jpg, .jpeg, .png">
                                            <div class="font-weight-bold small text-gray-800"
                                                onclick="document.getElementById('payslip-input').click()"
                                                style="cursor: pointer;">
                                                {{ $is_upload ? 'Change: ' . $file_name : 'Click to upload your recent payslip' }}
                                            </div>
                                            <div class="text-muted extra-small">Accepted formats: PDF, JPG, PNG</div>
                                        </div>
                                        @if($is_upload)
                                            <span class="badge badge-success px-2 py-1"><i class="fas fa-check"></i>
                                                Attached</span>
                                        @endif
                                    </div>
                                </div>

                                <div class="col-md-12 mb-4">
                                    <label class="font-weight-bold text-gray-700 small">Statement of Purpose / Reason for
                                        Loan</label>
                                    <textarea name="desc" class="form-control form-control-modern" rows="4"
                                        placeholder="Briefly describe the purpose of this loan request..."
                                        required>{{ $reason }}</textarea>
                                </div>
                            </div>

                            <div class="d-flex justify-content-between align-items-center mt-4">
                                <a href="{{ route('loan.index', ['p' => 2]) }}" class="btn btn-light btn-modern text-muted">
                                    <i class="fas fa-chevron-left mr-2"></i> Previous Step
                                </a>
                                <button class="btn btn-primary btn-modern px-5 shadow" type="submit">
                                    Finalize Request <i class="fas fa-chevron-right ml-2"></i>
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <script>
        $(document).ready(function () {
            function calculateEMI() {
                const selectedOption = $('#field-period').find('option:selected');
                const n_months = parseInt(selectedOption.data('description'), 10);
                const rate = parseFloat(selectedOption.data('val'));
                const loan = parseFloat($('#loanAmt').val());

                if (loan > 0 && n_months > 0 && rate > 0) {
                    const EMI = loan * ((rate * Math.pow((1 + rate), n_months)) / (Math.pow((1 + rate), n_months) - 1));
                    if (isFinite(EMI)) {
                        const formattedEMI = EMI.toFixed(2);
                        $("#M_I").val(formattedEMI);
                        $("#display-emi").text(new Intl.NumberFormat('en-KE', { minimumFractionDigits: 2 }).format(EMI));
                    }
                } else {
                    $("#display-emi").text("0.00");
                }
            }

            $('#field-period, #loanAmt').on('change input', calculateEMI);

            // File input label update
            $('#payslip-input').on('change', function () {
                const fileName = $(this).val().split('\\').pop();
                if (fileName) {
                    $(this).siblings('div').find('.font-weight-bold').text('Selected: ' + fileName);
                }
            });

            // Initial calculation
            calculateEMI();
        });
    </script>
@endsection