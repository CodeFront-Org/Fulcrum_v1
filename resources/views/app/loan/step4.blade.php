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

        .legal-box {
            background: #f8fafc;
            border-radius: 12px;
            padding: 20px;
            border-left: 4px solid #4e73df;
            margin-bottom: 20px;
            line-height: 1.6;
        }

        .legal-list {
            padding-left: 20px;
            margin-bottom: 0;
        }

        .legal-list li {
            margin-bottom: 10px;
            color: #475569;
            font-size: 0.9rem;
        }

        .form-control-modern {
            border-radius: 10px;
            padding: 12px 16px;
            border: 1px solid #e2e8f0;
            font-size: 0.9rem;
            transition: all 0.2s;
            background-color: white;
        }

        .form-control-modern:focus {
            border-color: #4e73df;
            box-shadow: 0 0 0 4px rgba(78, 115, 223, 0.1);
            outline: none;
        }

        .accent-box {
            background: #eff6ff;
            border-radius: 15px;
            padding: 24px;
            border: 1px solid #bfdbfe;
        }

        .btn-modern {
            padding: 12px 28px;
            border-radius: 12px;
            font-weight: 700;
            transition: all 0.3s;
        }

        .btn-submit {
            background: #10b981;
            border: none;
            box-shadow: 0 4px 12px rgba(16, 185, 129, 0.3);
        }

        .btn-submit:hover {
            background: #059669;
            transform: translateY(-2px);
            box-shadow: 0 6px 15px rgba(16, 185, 129, 0.4);
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
                    <div class="step-item completed"><i class="fas fa-check"></i> <span class="step-label">Loan Scope</span>
                    </div>
                    <div class="step-item active">4 <span class="step-label">T & C</span></div>
                </div>

                <div class="card loan-form-card mt-5">
                    <div class="card-body p-4 p-lg-5">
                        <div class="form-section-title">
                            <i class="fas fa-file-contract"></i>
                            Declaration & Irrevocable Authority
                        </div>

                        <form action="{{ route('loan.store') }}" method="POST">
                            @csrf
                            <input type="hidden" name="type" value="4">
                            <input type="hidden" name="loan_id" value="{{ $loan_id }}">

                            <!-- Section 1: General Terms -->
                            <div class="legal-box shadow-sm mb-4">
                                <h6 class="font-weight-bold text-gray-800 mb-3"><i
                                        class="fas fa-info-circle mr-2 text-primary"></i>1. General Facility Terms</h6>
                                <ol class="legal-list">
                                    <li>I certify this information is true and correct and authorize Fulcrum Link Limited to
                                        contact any source for confirmation.</li>
                                    <li>I agree to be bound by the terms and conditions of this facility. I understand
                                        Fulcrum Link Limited reserves the right to decline this application without giving
                                        reasons.</li>
                                    <li>I understand that this application will go through a vetting process and should my
                                        loan be approved, a loan account of the amount disbursed will be created in my name.
                                    </li>
                                    <li>I understand that the interest of this loan will be applied at the prevailing fixed
                                        interest rate as per my designation policy.</li>
                                    <li>I instruct Fulcrum Link Limited to credit the loan amount approved to my mobile
                                        number.</li>
                                </ol>
                            </div>

                            <div class="row mb-5">
                                <div class="col-md-6 mb-3">
                                    <label class="font-weight-bold text-gray-700 small">Disbursement Mobile Number</label>
                                    <input type="text" readonly value="{{ $contacts }}" name="mobileNumber"
                                        class="form-control form-control-modern bg-light font-weight-bold text-primary">
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="font-weight-bold text-gray-700 small">Agreement to Terms (1-5)</label>
                                    <select name="agreement" class="form-control form-control-modern custom-select"
                                        required>
                                        <option value="" selected disabled>Please select...</option>
                                        <option value="1">I Agree with all terms</option>
                                        <option value="2">I Disagree</option>
                                    </select>
                                </div>
                            </div>

                            <!-- Section 2: Salary Deduction Authority -->
                            <div class="accent-box mb-4">
                                <h6 class="font-weight-bold text-blue-800 mb-3 d-flex align-items-center">
                                    <i class="fas fa-signature mr-2"></i> Irrevocable Authority to Deduct Salary
                                </h6>
                                <p class="text-gray-700 mb-3" style="font-size: 0.95rem; line-height: 1.7;">
                                    I, <span class="font-weight-bold text-primary">{{ $user_name }}</span>,
                                    give my employer express and irrevocable instructions to deduct the monthly loan
                                    repayments of
                                    <span class="font-weight-bold text-primary">KSH {{ number_format($installment) }}</span>
                                    (<span class="font-weight-bold">{{ $installment_in_words }}</span>)
                                    from my salary and remit the same to <span class="font-weight-bold">Fulcrum Link
                                        Limited</span>
                                    until the loan is fully repaid.
                                </p>
                                <div class="alert alert-warning border-0 bg-white-50 small text-gray-600 mb-4"
                                    style="background: rgba(255,255,255,0.5); border-radius: 10px;">
                                    <i class="fas fa-exclamation-triangle mr-2"></i>
                                    In the event of employment termination, I authorize the transfer of final settlements to
                                    offset any outstanding balance.
                                </div>

                                <div class="row">
                                    <div class="col-md-7">
                                        <label class="font-weight-bold text-gray-700 small">Authorize Irrevocable
                                            Deduction?</label>
                                        <select name="irrevocableAgreement"
                                            class="form-control form-control-modern custom-select" required>
                                            <option value="" selected disabled>Please confirm authority...</option>
                                            <option value="1">Yes, I grant Irrevocable Authority</option>
                                            <option value="2">No, I do not agree</option>
                                        </select>
                                    </div>
                                </div>
                            </div>

                            <div class="d-flex justify-content-between align-items-center mt-5">
                                <a href="{{ route('loan.index', ['p' => 3]) }}" class="btn btn-light btn-modern text-muted">
                                    <i class="fas fa-chevron-left mr-2"></i> Review Configuration
                                </a>
                                <button type="submit" class="btn btn-primary btn-modern btn-submit px-5 text-white">
                                    <i class="fas fa-paper-plane mr-2"></i> Submit Application
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection