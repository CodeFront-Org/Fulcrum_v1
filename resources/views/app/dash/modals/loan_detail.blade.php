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
                <div class="mb-3">
                    <div class="text-xs font-weight-bold text-muted text-uppercase mb-2">Internal Vetting Documents
                    </div>
                    <a href="{{asset('uploads/supporting_docs/' . $loan->supporting_doc_file)}}" target="_blank"
                        class="btn btn-sm btn-outline-primary btn-block">
                        <i class="fas fa-file-download mr-1"></i> View Supporting Payslip
                    </a>
                </div>
                <div class="bg-light p-3 rounded small">
                    <div class="font-weight-bold mb-1">Purpose of Loan:</div>
                    {{$loan->loan_reason}}
                </div>
            </div>
        </div>
    </div>
</div>