@extends('layouts.app')

@section('content')
@if (session()->has('message'))
    <div id="toast" class="alert text-center alert-success alert-dismissible w-100 fade show" role="alert">
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        {{ session('message') }}
    </div>
@endif

@if (session()->has('error'))
    <div id="toast" class="alert text-center alert-danger alert-dismissible w-100 fade show" role="alert">
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        {{ session('error') }}
    </div>
@endif
                        <!-- Content Row -->
                        <div class="row">

                            <!-- Earnings (Monthly) Card Example -->
                            <div class="col-xl-3 col-md-6 mb-4">
                                <div class="card border-left-primary shadow h-100 py-2">
                                    <div class="card-body">
                                        <div class="row no-gutters align-items-center">
                                            <div class="col mr-2">
                                                <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                                                    Total Requests</div>
                                                <div class="h5 mb-0 font-weight-bold text-gray-800">{{$tot_request}}</div>
                                            </div>
                                            <div class="col-auto">
                                                <i class="fas fa-  fa-2x text-gray-300"></i>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
    
                            <!-- Earnings (Monthly) Card Example -->
                            <div class="col-xl-3 col-md-6 mb-4">
                                <div class="card border-left-success shadow h-100 py-2">
                                    <div class="card-body">
                                        <div class="row no-gutters align-items-center">
                                            <div class="col mr-2">
                                                <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                                                    Total Loan Issued</div>
                                                <div class="h5 mb-0 font-weight-bold text-gray-800">{{number_format((float) $tot_amt)}}</div>
                                            </div>
                                            <div class="col-auto">
                                                <i class="fas fa-dollar-sign fa-2x text-gray-300"></i>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
    
                            <!-- Earnings (Monthly) Card Example -->
                            <div class="col-xl-3 col-md-6 mb-4">
                                <div class="card border-left-info shadow h-100 py-2">
                                    <div class="card-body">
                                        <div class="row no-gutters align-items-center">
                                            <div class="col mr-2">
                                                <div class="text-xs font-weight-bold text-info text-uppercase mb-1">Approved
                                                </div>
                                                <div class="row no-gutters align-items-center">
                                                    <div class="col-auto">
                                                        <div class="h5 mb-0 mr-3 font-weight-bold text-gray-800">{{$approved}}</div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-auto">
                                                <i class="fas fa-check fa-2x text-gray-300"></i>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
    
                            <!-- Pending Requests Card Example -->
                            <div class="col-xl-3 col-md-6 mb-4">
                                <div class="card border-left-warning shadow h-100 py-2">
                                    <div class="card-body">
                                        <div class="row no-gutters align-items-center">
                                            <div class="col mr-2">
                                                <div class="text-xs font-weight-bold text-danger text-uppercase mb-1">
                                                    Returned</div>
                                                <div class="h5 mb-0 font-weight-bold text-gray-800">{{$returned}}</div>
                                            </div>
                                            <div class="col-auto">
                                                <i class="fas fa-cancel fa-2x text-gray-300"></i>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

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
                                        <th>#</th>
                                        <th class="column-title">Loan ID</th>
                                        <th class="column-title">Date</th>
                                        <th class="column-title">Amount</th>
                                        <th class="column-title">Instalments</th>
                                        <th class="column-title">Period</th>
                                        <th class="column-title">Approval Decision</th>
                                        <th class="column-title">View progress</th>
                                      </tr>
                                  </thead>
                                  <tbody>
                                    @foreach ($loans as $item)
                                        <tr>
                                        <td>{{$loop->index+1}}.</td>
                                        <td>{{$item->id}}</td>
                                        <td>{{$item->created_at->format('j F Y')}}</td>
                                        <td>{{$item->requested_loan_amount}}</td>
                                        <td>{{$item->monthly_installments}}</td>
                                        <td>{{$item->payment_period}}</td>
                                        @if ($item->final_decision==0)
                                            <td class="text-warning bold">Pending</td>
                                        @endif
                                        @if($item->final_decision==1)
                                            <td class="text-success bold">Approved</td>
                                        @endif
                                        @if ($item->final_decision==2)
                                            <td class="text-danger bold">Returned</td>
                                        @endif
                                        <td>
                                            <button type="button" style="background-color: #08228a9f;color: white" class="btn btn-sm" data-toggle="modal" data-target="#view-m-{{$item->id}}">
                                                <i class='fas fa-eye' aria-hidden='true'></i>
                                            </button>
                                        </td>
                                        </tr>


                                        <!--  View Loan Modal-->
                                        <div class="modal fade" id="view-m-{{$item->id}}" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
                                        aria-hidden="true">
                                        <div class="modal-dialog modal-dialog-centered" role="document">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h5 class="modal-title" id="exampleModalLabel">Approval Progress</h5>
                                                    <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                                                        <span aria-hidden="true">×</span>
                                                    </button>
                                                </div>

                                                <div class="modal-body">
                                                    @if ($item->final_decision==0)
                                                        <div class="mb-1">
                                                            <b style="font-weight: bolder; color:black">HR Decision: </b>
                                                            @if ($item->approver1_action=='RECOMMENDED' || $item->approver1_action==1)
                                                                <span class="text-success">Approved</span>
                                                            @else 
                                                            <span class="text-danger">Pending</span>
                                                            @endif
                                                        </div>
                                                        <div class="mb-1">
                                                            <b style="font-weight: bolder; color:black">Finance Decision: </b>
                                                            @if ($item->approver2_action=='RECOMMENDED' || $item->approver1_action==1)
                                                                <span class="text-success">Approved</span>
                                                            @else 
                                                            <span class="text-danger">Pending</span>
                                                            @endif
                                                        </div>
                                                        <div class="mb-1">
                                                            <b style="font-weight: bolder; color:black">Admin Decision: </b>
                                                            @if ($item->approver3_action=='RECOMMENDED' || $item->approver1_action==1)
                                                                <span class="text-success">Approved</span>
                                                            @else 
                                                            <span class="text-danger">Pending</span>
                                                            @endif
                                                        </div>
                                                    @endif
                                                    @if ($item->final_decision==1)
                                                    <div class="mb-1">
                                                        <b style="font-weight: bolder; color:black">HR Decision: </b>
                                                        <span class="text-success">Approved</span>
                                                    </div>
                                                    <div class="mb-1">
                                                        <b style="font-weight: bolder; color:black">Finance Decision: </b>
                                                            <span class="text-success">Approved</span>
                                                    </div>
                                                    <div class="mb-1">
                                                        <b style="font-weight: bolder; color:black">Admin Decision: </b>
                                                            <span class="text-success">Approved</span>
                                                    </div>
                                                    @endif
                                                    @if ($item->final_decision==2)
                                                        @if ($item->approval_level==1)
                                                            <div class="mb-1">
                                                                <b style="font-weight: bolder; color:black">HR Decision: </b>
                                                                <span class="text-danger">Returned</span>
                                                            </div>
                                                            <div class="mb-1">
                                                                <b style="font-weight: bolder; color:black">Reason : </b>
                                                                <span class="text-danger">{{$item->approver1_comments}}</span>
                                                            </div>
                                                         @endif
                                                         @if ($item->approval_level==2)
                                                             <div class="mb-1">
                                                                 <b style="font-weight: bolder; color:black">Finance Decision: </b>
                                                                 <span class="text-danger">Returned</span>
                                                             </div>
                                                             <div class="mb-1">
                                                                 <b style="font-weight: bolder; color:black">Reason : </b>
                                                                 <span class="text-danger">{{$item->approver2_comments}}</span>
                                                             </div>
                                                          @endif
                                                          @if ($item->approval_level==3)
                                                              <div class="mb-1">
                                                                  <b style="font-weight: bolder; color:black">Admin Decision: </b>
                                                                  <span class="text-danger">Returned</span>
                                                              </div>
                                                              <div class="mb-1">
                                                                  <b style="font-weight: bolder; color:black">Reason : </b>
                                                                  <span class="text-danger">{{$item->approver3_comments}}</span>
                                                              </div>
                                                           @endif
                                                        
                                                    @endif

                                                </div>

                                                <hr>

                                                <div class="modal-body">
                                                    <h5 class="modal-title text-primary mb-3" id="exampleModalLabel">View Loan Report</h5>
                                                <form onsubmit="submitNewStaff(event)">
                                                <div class="row text-black">
                                                    <div class="col-md-12">
                                                    <div class="mb-1">
                                                        <b style="font-weight: bolder; color:black">LOAN ID: </b>
                                                        <span>{{$item->id}}</span>
                                                    </div>
                                                    <div class="mb-1">
                                                        <b style="font-weight: bolder; color:black">Designation: </b>
                                                        <span>{{$item->company}}</span>
                                                    </div>
                                                    <div class="mb-1">
                                                        <b style="font-weight: bolder; color:black">Contacts: </b>
                                                        <span>{{$item->contacts}}</span>
                                                    </div>
                                                    <div class="mb-1">
                                                        <b style="font-weight: bolder; color:black">Next of KIN: </b>
                                                        <span>{{$item->kin}}</span>
                                                    </div>
                                                    <div class="mb-1">
                                                        <b style="font-weight: bolder; color:black">KIN Contacts: </b>
                                                        <span>{{$item->kin_contacts}}</span>
                                                    </div>
                                                    <div class="mb-1">
                                                        <b style="font-weight: bolder; color:black">Gross Pay: </b>
                                                        <span>{{number_format( (float) $item->gross_salary)}}</span>
                                                    </div>
                                                    <div class="mb-1">
                                                        <b style="font-weight: bolder; color:black">Net Pay: </b>
                                                        <span>{{number_format( (float) $item->net_salary)}}</span>
                                                    </div>
                                                    <div class="mb-1">
                                                        <b style="font-weight: bolder; color:black">Allowances: </b>
                                                        <span>{{number_format( (float) $item->other_allowances)}}</span>
                                                    </div>
                                                    @if ($item->outstanding_loan==1 || $item->outstanding_loan=='YES')
                                                        <div class="mb-1">
                                                            <b style="font-weight: bolder; color:black">Outstanding Loan Organization </b>
                                                            <span class="text-danger">{{$item->outstanding_loan_org}}</span>
                                                        </div>
                                                        <div class="mb-1">
                                                            <b style="font-weight: bolder; color:black">Outstanding Loan Amount: : </b>
                                                            <span class="text-danger">{{number_format( (float) $item->outstanding_loan_balance)}}</span>
                                                        </div>
                                                    @endif
                                                    <div class="mb-1">
                                                        <b style="font-weight: bolder; color:black">Requested Loan Amount: </b>
                                                        <span>{{number_format( (float) $item->requested_loan_amount)}}</span>
                                                    </div>
                                                    <div class="mb-1">
                                                        <b style="font-weight: bolder; color:black">Payement Period : </b>
                                                        <span>{{$item->payment_period}}</span>
                                                    </div>
                                                    <div class="mb-1">
                                                        <b style="font-weight: bolder; color:black">Monthly Installment : </b>
                                                        <span>{{number_format( (float) $item->monthly_installments)}}</span>
                                                    </div>
                                                    <div class="mb-1">
                                                        <b style="font-weight: bolder; color:black">Loan Reason: </b>
                                                        <span>{{$item->loan_reason}}</span>
                                                    </div>
                                                    @if ($item->final_decision==1 || $item->final_decision==2)
                                                    @else
                                                    <div class="mb-1">
                                                        <b style="font-weight: bolder; color:black">Supporting Doc: </b>
                                                        <a href="{{asset('uploads/supporting_docs/'.$item->supporting_doc_file)}}" class="btn btn-primary btn-sm ml-2" target="_blank">
                                                            <i class="fas fa-download"></i> View Document
                                                        </a>
                                                    </div>
                                                    @endif
                                                    <div class="mb-1">
                                                        <b style="font-weight: bolder; color:black">Agreed Terms and Conditions : </b>
                                                        @if ($item->agreed_terms==1)
                                                            <span>Yes</span>
                                                        @else
                                                            <span class="text-danger">NO</span>
                                                        @endif
                                                    </div>
                                                    <div class="mb-1">
                                                        <b style="font-weight: bolder; color:black">Consent to Irrevocable authority: </b>
                                                        @if ($item->consent_to_irrevocable_authority==1)
                                                            <span>Yes</span>
                                                        @else
                                                            <span class="text-danger">NO</span>
                                                        @endif
                                                    </div>

                                                    </div>
                                                </div>
                                                </form>

                                                </div>
                                                <div class="modal-footer">
                                                    <button class="btn btn-secondary" type="button" data-dismiss="modal">Cancel</button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <!-- End View Loan Modal LAST CHANGE 1234-->
                                    @endforeach
                                  </tbody>
                                  </table>
              
                              </div>
                            </div>
                          </div>



@endsection