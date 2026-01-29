<div class="modal fade glass-modal" id="return-m-{{$loan->id}}" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content border-0">
            <div class="modal-header bg-danger text-white border-0" style="border-radius: 20px 20px 0 0;">
                <h5 class="modal-title font-weight-bold"><i class="fas fa-undo-alt mr-2"></i>Return Request
                    #{{$loan->id}}</h5>
                <button class="close text-white" type="button" data-dismiss="modal"><span>&times;</span></button>
            </div>
            <form action="{{route('approve')}}" method="POST">
                @csrf
                <input type="hidden" name="loan_id" value="{{$loan->id}}">
                <input type="hidden" name="type" value="2">
                <div class="modal-body p-4">
                    <div class="alert alert-soft-danger small mb-4">
                        <i class="fas fa-info-circle mr-2"></i>
                        Returning a request allows the applicant to correct details and resubmit.
                    </div>

                    <div class="mb-3">
                        <label class="text-xs font-weight-bold text-muted text-uppercase mb-1">Supporting
                            Document</label>
                        @if($loan->supporting_doc_file)
                            <a href="{{asset('uploads/supporting_docs/' . $loan->supporting_doc_file)}}" target="_blank"
                                class="btn btn-sm btn-outline-primary btn-block">
                                <i class="fas fa-file-alt mr-2"></i> View Attachment
                            </a>
                        @else
                            <button type="button" class="btn btn-sm btn-outline-secondary btn-block"
                                onclick="alert('No attachment was found for this application.')">
                                <i class="fas fa-file-alt mr-2"></i> View Attachment
                            </button>
                        @endif
                    </div>

                    <div>
                        <label class="text-xs font-weight-bold text-muted text-uppercase mb-1">Reason for Return</label>
                        <textarea name="desc" class="form-control form-control-modern" rows="4"
                            placeholder="Explain what needs to be fixed (e.g. 'Payslip is blurry')..."
                            required></textarea>
                    </div>
                </div>
                <div class="modal-footer border-0 pt-0 px-4 pb-4">
                    <button class="btn btn-light btn-modern flex-grow-1" type="button"
                        data-dismiss="modal">Cancel</button>
                    <button class="btn btn-danger btn-modern px-5 shadow flex-grow-1" type="submit">Return to
                        Applicant</button>
                </div>
            </form>
        </div>
    </div>
</div>