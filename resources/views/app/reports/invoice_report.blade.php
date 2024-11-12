@extends('layouts.app')

@section('content')
<div class="row">
  <div class="col-md-12">
      <div class="card">
          <div class="card-body">
           
              <div class="panel-body">
                  <div class="clearfix">
                      <div class="float-start">
                          <h3>Fulcrum Link</h3>
                      </div>
                      <div class="float-end">
                          <h4>Invoice # <br>
                              <strong>{{$invoice_number}}</strong>
                          </h4>
                      </div>
                  </div>
                  <hr>
                  <div class="row">
                      <div class="col-md-12">

                          <div class="float-start mt-3">
                              <address>
                                  <strong>{{$company}}.</strong><br>
                                  Kenya<br>
                              </address>
                          </div>
                          <div class="float-end mt-3">
                              <p><strong>Order Date: </strong> {{$date}}</p>
                              <p class="m-t-10"><strong>Order Status: </strong> 
                                @if ($status==1)
                                  <span class="text-success">Paid</span>
                                @endif
                                @if ($status==0)
                                  <span class="text-danger">Not Paid</span>
                                @endif
                                @if ($status==2)
                                  <span class="text-warning">Partially Paid</span>
                                @endif
                              </p>
                          </div>
                      </div><!-- end col -->
                  </div>
                  <!-- end row -->

                  <div class="row">
                      <div class="col-md-12">
                          <div class="table-responsive">
                            <table
                                class="table table-bordered"
                                id="dataTable"
                                width="100%"
                                cellspacing="0"
                                style="font-size:15px;text-align:center; font-size:small"
                                >
                                  <thead>
                                  <tr><th>#</th>
                                      <th>User</th>
                                      <th>Contacts</th>
                                      <th>Loan Amount</th>
                                      <th>Installment</th>
                                  </tr></thead>
                                  <tbody>
                                    @foreach ($users as $item)

                                    @if ($item['user_partial_status'])
                                    <tr class="text-warning">
                                        <td>{{$loop->index+1}}</td>
                                        <td>{{$item['name']}}</td>
                                        <td>{{$item['contacts']}}</td>
                                        <td>{{number_format($item['loan_amt'])}}</td>
                                        <td>{{number_format($item['installments'])}}</td>
                                    </tr>
                                    @else
                                    <tr>
                                        <td>{{$loop->index+1}}</td>
                                        <td>{{$item['name']}}</td>
                                        <td>{{$item['contacts']}}</td>
                                        <td>{{number_format($item['loan_amt'])}}</td>
                                        <td>{{number_format($item['installments'])}}</td>
                                    </tr>
                                        
                                    @endif
                                    @endforeach

                                  </tbody>
                              </table>
                          </div>
                      </div>
                  </div>
                  <div class="row">
                      <div class="col-xl-6 col-6">
                          <div class="clearfix mt-4">
                              <h5 class="small text-dark fw-normal">PAYMENT TERMS AND POLICIES</h5>

                              <small>
                                  All accounts are to be paid within 7 days from receipt of
                                  invoice. To be paid by cheque or credit card or direct payment
                                  online. If account is not paid within 7 days the credits details
                                  supplied as confirmation of work undertaken will be charged the
                                  agreed quoted fee noted above.
                              </small>
                          </div>
                      </div>
                      <div class="col-xl-3 col-6 offset-xl-3">
                          <p class="text-end"><b>Sub-total:</b> {{number_format($sum)}}</p>
                          <p class="text-end">Discout: 0%</p>
                          <p class="text-end">VAT: 0%</p>
                          <hr>
                          <h3 class="text-end">KES {{number_format($sum)}}</h3>
                      </div>
                  </div>
                  <hr>
                  <div class="d-print-none">
                      <div class="float-end">
                          <a href="javascript:window.print()" class="btn btn-dark waves-effect waves-light"><i class="fa fa-print"></i></a>
                          {{-- <a href="#" class="btn btn-primary waves-effect waves-light">Submit</a> --}}
                      </div>
                      <div class="clearfix"></div>
                  </div>
              </div>
          </div>
      </div>
    
  </div>

</div>
<!-- end row -->  
@endsection