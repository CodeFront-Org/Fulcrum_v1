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

        .step-item.active+.step-label {
            color: #4e73df;
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

        .btn-next {
            padding: 12px 32px;
            border-radius: 12px;
            font-weight: 700;
            box-shadow: 0 4px 6px rgba(78, 115, 223, 0.2);
            transition: all 0.3s;
        }

        .btn-next:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 12px rgba(78, 115, 223, 0.3);
        }
    </style>
@endsection

@section('content')
    <div class="container py-4">
        <div class="row justify-content-center">
            <div class="col-lg-10">
                <!-- Step Progress -->
                <div class="step-indicator px-lg-5">
                    <div class="step-item active">1 <span class="step-label">Personal Info</span></div>
                    <div class="step-item">2 <span class="step-label">Financials</span></div>
                    <div class="step-item">3 <span class="step-label">Loan Scope</span></div>
                    <div class="step-item">4 <span class="step-label">T & C</span></div>
                </div>

                <!-- Form Card -->
                <div class="card loan-form-card mt-5">
                    <div class="card-body p-4 p-lg-5">
                        <div class="form-section-title">
                            <i class="fas fa-user"></i>
                            Review Your Personal Details
                        </div>

                        <form action="{{ route('loan.store') }}" method="POST">
                            @csrf
                            <input type="hidden" name="type" value="1">

                            <div class="row">
                                <div class="col-md-6 mb-4">
                                    <label class="font-weight-bold text-gray-700 small">Applicant Reference ID</label>
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text bg-light border-right-0"><i
                                                    class="fas fa-id-badge text-muted"></i></span>
                                        </div>
                                        <input name="applicant_id" type="text" value="{{ $applicant_id }}"
                                            class="form-control form-control-modern border-left-0" readonly required />
                                    </div>
                                </div>
                                <div class="col-md-6 mb-4">
                                    <label class="font-weight-bold text-gray-700 small">Full Names</label>
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text bg-light border-right-0"><i
                                                    class="fas fa-user-tag text-muted"></i></span>
                                        </div>
                                        <input type="text" value="{{ $first_name }} {{ $last_name }}"
                                            class="form-control form-control-modern border-left-0" readonly />
                                    </div>
                                    <input type="hidden" name="first_name" value="{{ $first_name }}">
                                    <input type="hidden" name="second_name" value="{{ $last_name }}">
                                </div>

                                <div class="col-md-6 mb-4">
                                    <label class="font-weight-bold text-gray-700 small">Primary Contact</label>
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text bg-light border-right-0"><i
                                                    class="fas fa-phone text-muted"></i></span>
                                        </div>
                                        <input name="contacts" type="text" value="{{ $contacts }}"
                                            class="form-control form-control-modern border-left-0" readonly required />
                                    </div>
                                </div>
                                <div class="col-md-6 mb-4">
                                    <label class="font-weight-bold text-gray-700 small">Service Designation</label>
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text bg-light border-right-0"><i
                                                    class="fas fa-briefcase text-muted"></i></span>
                                        </div>
                                        <input name="company" type="text" value="{{ $company }}"
                                            class="form-control form-control-modern border-left-0" readonly required />
                                    </div>
                                </div>

                                <div class="col-md-12 mb-4">
                                    <div class="alert alert-light border d-flex align-items-center"
                                        style="border-radius: 12px;">
                                        <i class="fas fa-info-circle text-primary mr-3 fa-lg"></i>
                                        <div class="small text-muted">Essential contract information. Note: Loans are
                                            subject to remaining contract duration.</div>
                                    </div>
                                </div>

                                <div class="col-md-6 mb-4">
                                    <label class="font-weight-bold text-gray-700 small">Contract End Date</label>
                                    <input name="contract" type="text" value="{{ $contract_end }}"
                                        class="form-control form-control-modern" readonly required />
                                </div>
                            </div>

                            <hr class="my-4">

                            <div class="form-section-title">
                                <i class="fas fa-users-cog"></i>
                                Emergency & Next of Kin
                            </div>

                            <div class="row">
                                <div class="col-md-6 mb-4">
                                    <label class="font-weight-bold text-gray-700 small">Next of Kin Name</label>
                                    <input name="kin" type="text" value="{{ $kin }}"
                                        class="form-control form-control-modern" placeholder="Enter full names"
                                        pattern="[A-Za-z\s]+" required />
                                </div>
                                <div class="col-md-6 mb-4">
                                    <label class="font-weight-bold text-gray-700 small">Kin Contact Number</label>
                                    <input name="kin_mobile" type="tel" value="{{ $kin_contacts }}"
                                        class="form-control form-control-modern" placeholder="07XXXXXXXX"
                                        pattern="[0-9]{9,12}" required />
                                </div>
                            </div>

                            <div class="d-flex justify-content-between align-items-center mt-4">
                                <div class="text-muted small">Step 1 of 4: Personal Verification</div>
                                <button class="btn btn-primary btn-next" type="submit">
                                    Continue to Financials <i class="fas fa-chevron-right ml-2"></i>
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection