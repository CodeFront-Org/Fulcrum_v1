@extends('layouts.app')

@section('content')
                <div class="card shadow mb-4">
                <div class="card-header py-3 text-center">
                    <h6 class="m-0 font-weight-bold text-danger">
                          Make Partial Payment for Codefront Invoice for the period {{$date}}
                        </h6>
                      </div>
                      <div class="card-body">
                        <div class="table-responsive">
                            <table
                              class="table table-bordered"
                              id="dataTable"
                              width="100%"
                              cellspacing="0"
                              style="font-size:13px;text-align:center;white-space:nowrap;"
                            >
                            <thead>
                              <tr>
                                <th>#</th>
                                <th>User</th>
                                <th>Contacts</th>
                                <th>Installments</th>
                                <th>Status</th>
                                <th>Action</th>
                              </tr>
                            </thead>
                            <tbody>
                              @foreach ($users as $user)
                              <tr>
                                <td>{{$loop->index+1}}. </td>
                                <td>{{$user['name']}}</td>
                                <td>{{$user['contacts']}}</td>
                                <td>{{number_format($user['installments'])}}</td>
                                @if ($user['status']==1)
                                  <td class="text-success">Paid</td>
                                @endif
                                @if ($user['status']==0)
                                  <td class="text-danger">Not Paid</td>
                                @endif
                                @if ($user['status']==2)
                                  <td class="text-warning">Partial Payement</td>
                                @endif
                                <td>
                                    <button type="button" class="btn btn-success btn-sm" data-toggle="modal" data-target="#approval-m-{{$user['loan_id']}}">
                                     <i class='fas fa-money-bill' aria-hidden='true'></i>
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
  <div class="modal fade" id="approval-m-{{$user['loan_id']}}" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
  aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered" role="document">
      <div class="modal-content">
          <div class="modal-header">
              <h5 class="modal-title text-primary" id="exampleModalLabel"><span style="color:black">User:</span> {{$user['name']}} <span style="color:black">Amt:</span> {{number_format($user['installments'])}}</h5>
              <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                  <span aria-hidden="true">×</span>
              </button>
          </div>
          <div class="modal-body">
              <form action="{{route('partial_payment')}}" method="POST">
                 @method('POST')
                 @csrf
                 <input type="hidden" name="invoice_number" value="{{$invoice_number}}">
                 <input type="hidden" name="company" value="{{$company}}">
                 <input type="hidden" name="loan_id" value="{{$user['loan_id']}}">
                 <input type="hidden" name="installments" value="{{$user['installments']}}">
                 <div class="row">
                  <div class="col-md-12">
                      <div class="mb-3">
                          <label class="form-label">Payment Status</label>
                          <select name="payment_status" class="form-control form-select" id="paymentStatus">
                              <option value="0">UnPaid</option>
                              <option value="1">Paid</option>
                          </select>
                      </div>
                  </div>
              </div>
              
              
              <div class="row" id="commentsRow">
                  <div class="col-md-12 mt-1">
                      <div class="mb-3">
                          <label for="field-2" class="form-label">Comments</label>
                          <textarea id="textarea" name="desc" class="form-control" required maxlength="3000" rows="3" placeholder="Comments"></textarea>
                      </div>
                  </div>
              </div>

          </div>
          <div class="modal-footer">
              <button class="btn btn-secondary" type="button" data-dismiss="modal">Cancel</button>
              <button class="btn btn-success" type="submit">Proceed</button>
          </div>
      </div>
  </form>
  </div>
</div>
  @endforeach

  <!-- End Modal-->


@endsection