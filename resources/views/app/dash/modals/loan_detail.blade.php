<div class="modal fade glass-modal" id="view-m-{{$loan->id}}" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header border-0 pb-0">
                <h5 class="modal-title font-weight-bold text-primary">Loan Report: #{{$loan->id}}</h5>
                <button class="close" type="button" data-dismiss="modal"><span>&times;</span></button>
            </div>
            <div class="modal-body p-4">
                <div class="row">
                    <div class="col-6 mb-3">
                        <div class="text-xs font-weight-bold text-muted text-uppercase">Applicant</div>
                        <div class="font-weight-bold">{{$loan->user->first_name}} {{$loan->user->last_name}}</div>
                    </div>
                    <div class="col-6 mb-3">
                        <div class="text-xs font-weight-bold text-muted text-uppercase">Phone Number</div>
                        <div class="font-weight-bold">{{$loan->user->contacts}}</div>
                    </div>
                    <div class="col-6 mb-3">
                        <div class="text-xs font-weight-bold text-muted text-uppercase">Designation</div>
                        <div class="font-weight-bold">{{$loan->company}}</div>
                    </div>
                    <div class="col-6 mb-3">
                        <div class="text-xs font-weight-bold text-muted text-uppercase">Requested Amount</div>
                        <div class="font-weight-bold text-primary">KES {{number_format($loan->requested_loan_amount)}}
                        </div>
                    </div>
                    <div class="col-6 mb-3">
                        <div class="text-xs font-weight-bold text-muted text-uppercase">Monthly Installment</div>
                        <div class="font-weight-bold">KES {{number_format($loan->monthly_installments)}}</div>
                    </div>
                </div>
                <hr class="my-3">
                <hr class="my-3">
                <div class="mb-3">
                    <div class="text-xs font-weight-bold text-muted text-uppercase mb-2">Internal Vetting Documents</div>
                    @if($loan->supporting_doc_file)
                        <a href="{{asset('uploads/supporting_docs/' . $loan->supporting_doc_file)}}" target="_blank"
                            class="btn btn-sm btn-outline-primary btn-block">
                            <i class="fas fa-file-download mr-1"></i> View Supporting Payslip
                        </a>
                    @else
                        <button type="button" class="btn btn-sm btn-outline-secondary btn-block" onclick="alert('No attachment was found for this application.')">
                            <i class="fas fa-file-download mr-1"></i> View Supporting Payslip
                        </button>
                    @endif
                </div>
                <div class="bg-light p-3 rounded small">
                    <div class="font-weight-bold mb-1">Purpose of Loan:</div>
                    {{$loan->loan_reason}}
                </div>

                <div class="mt-4">
                    <h6 class="font-weight-bold text-gray-800 mb-3">Approval Progress</h6>

                    <!-- HRO Step -->
                    <div class="d-flex align-items-center mb-3">
                        <div class="mr-3">
                            @if($loan->approval_level > 1 || $loan->final_decision == 1)
                                <span class="btn btn-success btn-circle btn-sm"><i class="fas fa-check"></i></span>
                            @elseif($loan->approval_level == 1 && $loan->final_decision == 0)
                                <span class="btn btn-warning btn-circle btn-sm"><i
                                        class="fas fa-spinner fa-spin"></i></span>
                            @elseif($loan->final_decision == 2 && $loan->approval_level == 1)
                                <span class="btn btn-danger btn-circle btn-sm"><i class="fas fa-times"></i></span>
                            @else
                                <span class="btn btn-light btn-circle btn-sm border"><i
                                        class="fas fa-circle text-muted"></i></span>
                            @endif
                        </div>
                        <div>
                            <div class="font-weight-bold small">Human Resources</div>
                            <div class="text-xs text-muted">
                                @if($loan->approval_level > 1 || $loan->final_decision == 1)
                                    Approved
                                @elseif($loan->approval_level == 1 && $loan->final_decision == 0)
                                    Pending Review
                                @elseif($loan->final_decision == 2 && $loan->approval_level == 1)
                                    returned
                                @else
                                    Waiting
                                @endif
                            </div>
                        </div>
                    </div>

                    <!-- Finance Step -->
                    <div class="d-flex align-items-center mb-3">
                        <div class="mr-3">
                            @if($loan->approval_level > 2 || $loan->final_decision == 1)
                                <span class="btn btn-success btn-circle btn-sm"><i class="fas fa-check"></i></span>
                            @elseif($loan->approval_level == 2 && $loan->final_decision == 0)
                                <span class="btn btn-warning btn-circle btn-sm"><i
                                        class="fas fa-spinner fa-spin"></i></span>
                            @elseif($loan->final_decision == 2 && $loan->approval_level == 2)
                                <span class="btn btn-danger btn-circle btn-sm"><i class="fas fa-times"></i></span>
                            @else
                                <span class="btn btn-light btn-circle btn-sm border"><i
                                        class="fas fa-circle text-muted"></i></span>
                            @endif
                        </div>
                        <div>
                            <div class="font-weight-bold small">Finance Department</div>
                            <div class="text-xs text-muted">
                                @if($loan->approval_level > 2 || $loan->final_decision == 1)
                                    Approved
                                @elseif($loan->approval_level == 2 && $loan->final_decision == 0)
                                    Pending Review
                                @elseif($loan->final_decision == 2 && $loan->approval_level == 2)
                                    returned
                                @else
                                    Waiting
                                @endif
                            </div>
                        </div>
                    </div>

                    <!-- Final Step -->
                    <div class="d-flex align-items-center">
                        <div class="mr-3">
                            @if($loan->final_decision == 1)
                                <span class="btn btn-success btn-circle btn-sm"><i class="fas fa-check"></i></span>
                            @elseif($loan->approval_level == 3 && $loan->final_decision == 0)
                                <span class="btn btn-warning btn-circle btn-sm"><i
                                        class="fas fa-spinner fa-spin"></i></span>
                            @elseif($loan->final_decision == 2 && $loan->approval_level == 3)
                                <span class="btn btn-danger btn-circle btn-sm"><i class="fas fa-times"></i></span>
                            @else
                                <span class="btn btn-light btn-circle btn-sm border"><i
                                        class="fas fa-circle text-muted"></i></span>
                            @endif
                        </div>
                        <div>
                            <div class="font-weight-bold small">Final Appoval</div>
                            <div class="text-xs text-muted">
                                @if($loan->final_decision == 1)
                                    Approved
                                @elseif($loan->approval_level == 3 && $loan->final_decision == 0)
                                    Pending Review
                                @elseif($loan->final_decision == 2 && $loan->approval_level == 3)
                                    returned
                                @else
                                    Waiting
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>