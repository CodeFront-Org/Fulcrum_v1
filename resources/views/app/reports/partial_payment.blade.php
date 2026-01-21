@extends('layouts.app')

@section('css')
  <style>
    .report-card {
      border: none;
      border-radius: 20px;
      box-shadow: 0 10px 25px rgba(0, 0, 0, 0.05);
      background: white;
    }

    .modern-table thead th {
      background-color: #f8fafc !important;
      color: #64748b;
      text-transform: uppercase;
      font-size: 0.7rem;
      font-weight: 700;
      padding: 16px 12px !important;
      border-bottom: 2px solid #e2e8f0 !important;
    }

    .modern-table tbody td {
      padding: 14px 12px !important;
      border-bottom: 1px solid #f1f5f9 !important;
      font-size: 0.85rem;
    }

    .status-pill {
      padding: 4px 10px;
      border-radius: 6px;
      font-weight: 700;
      font-size: 0.7rem;
      text-transform: uppercase;
    }

    .pill-paid {
      background: #dcfce7;
      color: #166534;
    }

    .pill-unpaid {
      background: #fee2e2;
      color: #991b1b;
    }

    .pill-partial {
      background: #fffecb;
      color: #92400e;
    }

    .btn-modern {
      border-radius: 10px;
      font-weight: 600;
      padding: 10px 20px;
      transition: all 0.2s;
    }

    .glass-modal .modal-content {
      border: none;
      border-radius: 20px;
      box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.25);
    }
  </style>
@endsection

@section('content')
  <div class="container-fluid py-4">
    <div class="row align-items-center mb-4">
      <div class="col">
        <h1 class="h3 mb-0 text-gray-800 font-weight-bold">Batch Disbursement reconciliation</h1>
        <p class="text-muted mb-0">Reconcile individual payments for <span
            class="font-weight-bold text-primary">{{$company}}</span> for cycle <span
            class="badge badge-light border">{{$date}}</span></p>
      </div>
    </div>

    <div class="card report-card overflow-hidden shadow-sm">
      <div class="card-header bg-white py-3 border-0">
        <h6 class="m-0 font-weight-bold text-gray-800">Individual Participant Ledger</h6>
      </div>
      <div class="table-responsive">
        <table class="table modern-table mb-0" id="partialTable">
          <thead>
            <tr>
              <th>#</th>
              <th>Participant Name</th>
              <th>Contact Info</th>
              <th class="text-right">Expected Instalment</th>
              <th class="text-center">Current Status</th>
              <th class="text-center">Action</th>
            </tr>
          </thead>
          <tbody>
            @foreach ($users as $user)
              <tr>
                <td><span class="text-muted small">{{$loop->index + 1}}</span></td>
                <td>
                  <div class="font-weight-bold text-gray-800">{{$user['name']}}</div>
                </td>
                <td><span class="text-muted small">{{$user['contacts']}}</span></td>
                <td class="text-right font-weight-bold text-gray-900">KES {{number_format($user['installments'])}}</td>
                <td class="text-center">
                  @if ($user['status'] == 1) <span class="status-pill pill-paid">Paid</span> @endif
                  @if ($user['status'] == 0) <span class="status-pill pill-unpaid">Pending</span> @endif
                  @if ($user['status'] == 2) <span class="status-pill pill-partial">Partial</span> @endif
                </td>
                <td class="text-center">
                  <button type="button" class="btn btn-primary btn-sm btn-modern px-3" data-toggle="modal"
                    data-target="#approval-m-{{$user['loan_id']}}">
                    <i class='fas fa-receipt mr-1'></i> Settle
                  </button>
                </td>
              </tr>
            @endforeach
          </tbody>
        </table>
      </div>
    </div>
  </div>

  @foreach ($users as $user)
    <div class="modal fade glass-modal" id="approval-m-{{$user['loan_id']}}" tabindex="-1" role="dialog" aria-hidden="true">
      <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
          <div class="modal-header bg-primary text-white border-0" style="border-radius: 20px 20px 0 0;">
            <h5 class="modal-title font-weight-bold">Settle Instalment: {{ $user['name'] }}</h5>
            <button class="close text-white" type="button" data-dismiss="modal"><span>&times;</span></button>
          </div>
          <form action="{{route('partial_payment')}}" method="POST">
            @csrf
            <input type="hidden" name="invoice_number" value="{{$invoice_number}}">
            <input type="hidden" name="company" value="{{$company}}">
            <input type="hidden" name="loan_id" value="{{$user['loan_id']}}">
            <input type="hidden" name="installments" value="{{$user['installments']}}">
            <input type="hidden" name="repayment_id" value="{{$user['repayment_id']}}">

            <div class="modal-body p-4">
              <div class="alert alert-soft-primary small mb-4">
                <i class="fas fa-info-circle mr-2"></i> Confirming this payment will mark the instalment as fully satisfied
                in the system.
              </div>

              <div class="mb-4">
                <label class="small font-weight-bold text-muted text-uppercase d-block mb-1">Update Status</label>
                <select name="payment_status" class="form-control form-control-modern custom-select shadow-none">
                  <option value="0" {{ $user['status'] == 0 ? 'selected' : '' }}>Outstanding (Not Paid)</option>
                  <option value="1" {{ $user['status'] == 1 ? 'selected' : '' }}>Fully Settled (Paid)</option>
                </select>
              </div>

              <div class="mb-0">
                <label class="small font-weight-bold text-muted text-uppercase d-block mb-1">Auditor Reconciliation
                  Note</label>
                <textarea name="desc" class="form-control form-control-modern" required maxlength="3000" rows="3"
                  placeholder="Enter bank reference or payment notes..."></textarea>
              </div>
            </div>
            <div class="modal-footer border-0 p-4 pt-0">
              <button class="btn btn-light btn-modern flex-grow-1" type="button" data-dismiss="modal">Cancel</button>
              <button class="btn btn-primary btn-modern shadow flex-grow-1" type="submit">Verify Payment</button>
            </div>
          </form>
        </div>
      </div>
    </div>
  @endforeach
@endsection