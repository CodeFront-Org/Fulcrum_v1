@extends('layouts.app')

@section('content')
                        <div class="card shadow mb-4">
                            <div class="card-header py-3 text-center">
                              <h6 class="m-0 font-weight-bold text-primary">
                                My Loan Requests
                              </h6>
                            </div>
                            <div class="card-body">
                              <div class="table-responsive">
                              <table
                                  class="table table-bordered"
                                  id="dataTable"
                                  width="100%"
                                  cellspacing="0"
                                  style="font-size:15px;text-align:center; font-size:small"
                                  >
                                  <thead>
                                      <tr>
                                        <th class="column-title">Loan ID</th>
                                        <th class="column-title">Date</th>
                                        <th class="column-title">Amount</th>
                                        <th class="column-title">Instalments</th>
                                        <th class="column-title">Period</th>
                                        <th class="column-title">Payment status</th>
                                        <th class="column-title">View Payments</th>
                                      </tr>
                                  </thead>
                                  <tbody>
                                    @foreach ($data as $item)
                                        <tr>
                                        <td>{{$item['loan_id']}}</td>
                                        <td>{{$item['date']}}</td>
                                        <td>{{number_format($item['amount'])}}</td>
                                        <td>{{number_format($item['installments'])}}</td>
                                        <td>{{$item['period']}}</td>
                                        @if ($item['status']==1)
                                            <td><span class="text-success">Completed</span></td>
                                        @else
                                            <td><span class="text-danger">Incomplete</span></td>
                                        @endif
                                        <td>
                                            <button type="button" style="background-color: #08228a9f;color: white" class="btn btn-sm" data-toggle="modal" data-target="#view-m-{{$item['loan_id']}}">
                                                <i class='fas fa-eye' aria-hidden='true'></i>
                                            </button>
                                        </td>
                                        </tr>
                                    @endforeach
                                  </tbody>
                                  </table>
              
                              </div>
                            </div>
                          </div>



                <!--  View Loan Modal-->
                @foreach ($payments_data as $item)
                    <div class="modal fade" id="view-m-{{$item['loan_id']}}" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
                    aria-hidden="true">
                    <div class="modal-dialog modal-dialog-centered" role="document">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="exampleModalLabel">Payment Progress LoanID: {{$item['loan_id']}}</h5>
                                <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">×</span>
                                </button>
                            </div>

                            <div class="modal-body">
                                <table
                                    class="table table-bordered"
                                    id="dataTable"
                                    width="100%"
                                    cellspacing="0"
                                    style="font-size:13px;text-align:center;white-space:nowrap;"
                                >
                                <thead>
                                    <tr>
                                        <th>Month</th>
                                        <th>Installment</th>
                                        <th>Status</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($item['data'] as $item1)
                                        <tr>
                                            <td>{{$item1['date']}}</td>
                                            <td>{{number_format($item1['installments'])}}</td>
                                            @if ($item1['status']==1)
                                                <td><span class="text-success">Paid</span></td>
                                            @endif
                                            @if ($item1['status']==0)
                                                <td><span class="text-danger">Not Paid</span></td>
                                            @endif
                                            @if ($item1['status']==2)
                                                <td><span class="text-danger">Not Paid</span></td>
                                            @endif
                                        </tr>
                                    @endforeach
                                </tbody>

                                </table>
                            </div>
                            <div class="modal-footer">
                                <button class="btn btn-secondary" type="button" data-dismiss="modal">Cancel</button>
                            </div>
                        </div>
                    </div>
                </div>    
                @endforeach

            <!-- End View Loan Modal-->

@endsection