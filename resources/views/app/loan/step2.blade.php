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
            background: rgba(16, 185, 129, 0.1);
            color: #10b981;
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

        .btn-modern {
            padding: 12px 28px;
            border-radius: 12px;
            font-weight: 700;
            transition: all 0.3s;
        }

        .loan-details {
            animation: fadeIn 0.4s ease-out;
        }

        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: translateY(10px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
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
                    <div class="step-item active">2 <span class="step-label">Financials</span></div>
                    <div class="step-item">3 <span class="step-label">Loan Scope</span></div>
                    <div class="step-item">4 <span class="step-label">T & C</span></div>
                </div>

                <!-- Form Card -->
                <div class="card loan-form-card mt-5">
                    <div class="card-body p-4 p-lg-5">
                        <div class="form-section-title">
                            <i class="fas fa-wallet"></i>
                            Financial Declaration
                        </div>

                        <form action="{{ route('loan.store') }}" method="POST">
                            @csrf
                            <input type="hidden" name="type" value="2">

                            <div class="row">
                                <div class="col-md-4 mb-4">
                                    <label class="font-weight-bold text-gray-700 small">Gross Monthly Salary</label>
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text bg-light border-right-0">KES</span>
                                        </div>
                                        <input name="gross" value="{{ $gross }}" type="number"
                                            class="form-control form-control-modern border-left-0" placeholder="0.00"
                                            required />
                                    </div>
                                </div>
                                <div class="col-md-4 mb-4">
                                    <label class="font-weight-bold text-gray-700 small">Net Take-Home Pay</label>
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text bg-light border-right-0">KES</span>
                                        </div>
                                        <input name="net" value="{{ $net }}" type="number"
                                            class="form-control form-control-modern border-left-0" placeholder="0.00"
                                            required />
                                    </div>
                                </div>
                                <div class="col-md-4 mb-4">
                                    <label class="font-weight-bold text-gray-700 small">Other Allowances</label>
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text bg-light border-right-0">KES</span>
                                        </div>
                                        <input name="allowance" value="{{ $allowance }}" type="number"
                                            class="form-control form-control-modern border-left-0" placeholder="0.00"
                                            required />
                                    </div>
                                </div>
                            </div>

                            <hr class="my-4">

                            <div class="bg-light p-4 rounded-xl mb-4"
                                style="border-radius: 15px; border: 1px dashed #cbd5e1;">
                                <div class="row align-items-center">
                                    <div class="col-md-7">
                                        <h6 class="font-weight-bold mb-1">Existing Financial Obligations</h6>
                                        <p class="text-muted small mb-0">Do you have any outstanding loans with other
                                            financial institutions?</p>
                                    </div>
                                    <div class="col-md-5">
                                        <select name="outstanding_loan"
                                            class="form-control form-control-modern custom-select form-select"
                                            id="outstanding-toggle" required>
                                            <option value="0" {{ $outstanding_loan == 0 ? 'selected' : '' }}>No, I have no
                                                other loans</option>
                                            <option value="1" {{ $outstanding_loan == 1 ? 'selected' : '' }}>Yes, I have
                                                active loans</option>
                                        </select>
                                    </div>
                                </div>
                            </div>

                            <div id="loan-details-container" class="loan-details" style="display: none;">
                                <div class="row">
                                    <div class="col-md-6 mb-4">
                                        <label class="font-weight-bold text-gray-700 small">Name of Institution</label>
                                        <input name="financial_institution" value="{{ $outstanding_loan_org }}" type="text"
                                            class="form-control form-control-modern" placeholder="e.g. Absa, KCB, Equity" />
                                    </div>
                                    <div class="col-md-6 mb-4">
                                        <label class="font-weight-bold text-gray-700 small">Total Outstanding
                                            Balance</label>
                                        <input name="loan_bal" value="{{ $outstanding_loan_balance }}" type="number"
                                            class="form-control form-control-modern"
                                            placeholder="Current remaining balance" />
                                    </div>
                                </div>
                            </div>

                            <div class="d-flex justify-content-between align-items-center mt-5">
                                <a href="{{ route('loan.index', ['p' => 1]) }}" class="btn btn-light btn-modern text-muted">
                                    <i class="fas fa-chevron-left mr-2"></i> Previous Step
                                </a>
                                <button class="btn btn-primary btn-modern px-5 shadow" type="submit">
                                    Construct Loan Request <i class="fas fa-chevron-right ml-2"></i>
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
        function toggleLoanDetails() {
            const container = document.getElementById('loan-details-container');
            const selectValue = document.getElementById('outstanding-toggle').value;
            container.style.display = selectValue == '1' ? 'block' : 'none';
        }

        document.getElementById('outstanding-toggle').addEventListener('change', toggleLoanDetails);
        document.addEventListener('DOMContentLoaded', toggleLoanDetails);
    </script>
@endsection