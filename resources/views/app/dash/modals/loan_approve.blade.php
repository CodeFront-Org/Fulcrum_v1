<div class="modal fade glass-modal" id="approval-m-{{$loan->id}}" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content border-0">
            <div class="modal-header bg-success text-white border-0" style="border-radius: 20px 20px 0 0;">
                <h5 class="modal-title font-weight-bold"><i class="fas fa-check-circle mr-2"></i>Approve Application
                    #{{$loan->id}}</h5>
                <button class="close text-white" type="button" data-dismiss="modal"><span>&times;</span></button>
            </div>
            <form action="{{route('approve')}}" method="POST">
                @csrf
                <input type="hidden" name="loan_id" value="{{$loan->id}}">
                <input type="hidden" name="type" value="1">
                <div class="modal-body p-4">
                    @if(auth()->user()->role_type == 'admin' || auth()->user()->role_type == 'approver')
                        <div class="mb-4">
                            <label class="text-xs font-weight-bold text-muted text-uppercase mb-1">Transaction Reference
                                Code</label>
                            <input name="trx" type="text" class="form-control form-control-modern"
                                placeholder="e.g. MPESA-ABC123XYZ" required>
                            <small class="text-muted">Enter the disbursement reference from the financial provider.</small>
                        </div>
                    @endif

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
                        <label class="text-xs font-weight-bold text-muted text-uppercase mb-1">Internal Comments</label>
                        <textarea name="desc" class="form-control form-control-modern" rows="3"
                            placeholder="Brief note on why this was approved..." required></textarea>
                    </div>
                </div>
                <div class="modal-footer border-0 pt-0 px-4 pb-4">
                    <button class="btn btn-light btn-modern flex-grow-1" type="button"
                        data-dismiss="modal">Cancel</button>
                    <button class="btn btn-success btn-modern px-5 shadow flex-grow-1" type="submit">Confirm
                        Approval</button>
                </div>
            </form>
        </div>
    </div>
</div>