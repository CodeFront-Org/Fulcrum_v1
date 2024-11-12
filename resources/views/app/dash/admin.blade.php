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
                                Total Users</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{$tot_users}}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-users  fa-2x text-gray-300"></i>
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
                                Total Companies Registered</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{$tot_companies}}</div>
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
                            <div class="text-xs font-weight-bold text-info text-uppercase mb-1">Total Approvals
                            </div>
                            <div class="row no-gutters align-items-center">
                                <div class="col-auto">
                                    <div class="h5 mb-0 mr-3 font-weight-bold text-gray-800">{{$tot_approvals}}</div>
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
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{$tot_returned}}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-x fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Content Row -->
<div class="card shadow mb-4">
<div class="card-header py-3 text-center">
<h6 class="m-0 font-weight-bold text-primary">
View Loan Requests
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
        <th class="column-title">Loan ID</th>
        <th class="column-title">Name</th>
        <th class="column-title">Date</th>
        <th class="column-title">Amount</th>
        <th class="column-title">Installments</th>
        <th class="column-title">Period</th>
        <th class="column-title">View</th>
        <th class="column-title no-link last"><span class="nobr">Action</span></th>
        </tr>
    </thead>
    <tbody>
        @foreach ($loans as $item)        
        <tr>
        <td>{{$loop->index+1}}.</td>
        <td>{{$item->id}}</td>
        <td>{{$item->user->first_name}} {{$item->user->last_name}}</td>
        <td>{{$item->created_at->format('Y-m-d')}}</td>
        <td>{{$item->requested_loan_amount}}</td>
        <td>{{$item->monthly_installments}}</td>
        <td>{{$item->payment_period}}</td>
        <td>
            <button type="button" style="background-color: #08228a9f;color: white" class="btn btn-sm" data-toggle="modal" data-target="#view-m-{{$item->id}}">
                <i class='fas fa-eye' aria-hidden='true'></i>
            </button>
        </td>
        <td>
            <button type="button" class="btn btn-success btn-sm" data-toggle="modal" data-target="#approval-m-{{$item->id}}">
            Apporve <i class='fas fa-check-circle' aria-hidden='true'></i>
            </button>

            <button type="button" class="btn btn-danger btn-sm" data-toggle="modal" data-target="#return-m-{{$item->id}}">
                Return <i class='fas fa-exclamation' aria-hidden='true'></i>
            </button>
        </td>
        </tr>


        <!--  View Loan Modal-->
        <div class="modal fade" id="view-m-{{$item->id}}" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
            aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">View Loan Report</h5>
                        <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">×</span>
                        </button>
                    </div>
                    <div class="modal-body">
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
                            <span>{{number_format($item->gross_salary)}}</span>
                        </div>
                        <div class="mb-1">
                            <b style="font-weight: bolder; color:black">Net Pay: </b>
                            <span>{{number_format($item->net_salary)}}</span>
                        </div>
                        <div class="mb-1">
                            <b style="font-weight: bolder; color:black">Allowances: </b>
                            <span>{{number_format($item->other_allowances)}}</span>
                        </div>
                        @if ($item->outstanding_loan==1 || $item->outstanding_loan=='YES')
                            <div class="mb-1">
                                <b style="font-weight: bolder; color:black">Outstanding Loan Organization </b>
                                <span class="text-danger">{{$item->outstanding_loan_org}}</span>
                            </div>
                            <div class="mb-1">
                                <b style="font-weight: bolder; color:black">Outstanding Loan Amount: : </b>
                                <span class="text-danger">{{number_format($item->outstanding_loan_balance)}}</span>
                            </div>
                        @endif
                        <div class="mb-1">
                            <b style="font-weight: bolder; color:black">Requested Loan Amount: </b>
                            <span>{{number_format($item->requested_loan_amount)}}</span>
                        </div>
                        <div class="mb-1">
                            <b style="font-weight: bolder; color:black">Payement Period : </b>
                            <span>{{$item->payment_period}}</span>
                        </div>
                        <div class="mb-1">
                            <b style="font-weight: bolder; color:black">Monthly Installment : </b>
                            <span>{{number_format($item->monthly_installments)}}</span>
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
    <!-- End View Loan Modal-->

    @endforeach
    </tbody>
    </table>

</div>
</div>
</div>
 


     <!--  View Loan Modal-->
     <div class="modal fade" id="view-m" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">View Loan Report</h5>
                    <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
                <div class="modal-body">
                <form onsubmit="submitNewStaff(event)">
                <div class="row text-black">
                    <div class="col-md-12">
                    <div class="mb-1">
                        <b style="font-weight: bolder; color:black">LOAN ID: </b>
                        <span>12343</span>
                    </div>
                    <div class="mb-1">
                        <b style="font-weight: bolder; color:black">User Name(s):: </b>
                        <span>Martin Njoroge Muchene</span>
                    </div>
                    {{-- <div class="mb-1">
                        <b style="font-weight: bolder; color:black">Designation: </b>
                        <span>Fulcrum Link</span>
                    </div> --}}
                    <div class="mb-1">
                        <b style="font-weight: bolder; color:black">Contacts: </b>
                        <span>1232342343</span>
                    </div>
                    <div class="mb-1">
                        <b style="font-weight: bolder; color:black">Outstanding Loan: : </b>
                        <span class="text-danger">Fulcrum Link LTD</span>
                    </div>
                    <div class="mb-1">
                        <b style="font-weight: bolder; color:black">Outstanding Loan Organization: : </b>
                        <span class="text-danger">12,008</span>
                    </div>
                    <div class="mb-1">
                        <b style="font-weight: bolder; color:black">Requested Loan Amount: </b>
                        <span>40,000</span>
                    </div>
                    <div class="mb-1">
                        <b style="font-weight: bolder; color:black">Payement Period : </b>
                        <span>4</span>
                    </div>
                    <div class="mb-1">
                        <b style="font-weight: bolder; color:black">Monthly Installment : </b>
                        <span>10,500</span>
                    </div>
                    <div class="mb-1">
                        <b style="font-weight: bolder; color:black">Loan Reason: </b>
                        <span>To cater for my school fees To cater for my school fees To cater for my school fees</span>
                    </div>
                    <div class="mb-1">
                        <b style="font-weight: bolder; color:black">Agreed Terms and Conditions : </b>
                        <span>Yes</span>
                    </div>
                    <div class="mb-1">
                        <b style="font-weight: bolder; color:black">Consent to Irrevocable authority: </b>
                        <span class="text-danger">NO</span>
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
    <!-- End View Loan Modal-->


     <!-- Approve Modal-->
     @foreach ($loans as $item)         
     <div class="modal fade" id="approval-m-{{$item->id}}" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title text-success" id="exampleModalLabel">Approve Loan: <i class="fa fa-check-circle"></i> ID:  {{$item->id}}</h5>
                    <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form action="{{route('approve')}}" method="POST">
                       @method('POST')
                       @csrf
                       <input type="hidden" name="loan_id" value="{{$item->id}}">
                       <input type="hidden" name="type" value="1">
                <div class="row">
                    <div class="col-md-12">
                    <div class="mb-3">
                        <label class="form-label">Transaction Code</label>
                        <input
                        name="trx"
                        type="text"
                        class="form-control"
                        placeholder="Enter the code for transaction"
                        required
                        />
                    </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-12 mt-1">
                        <div class="mb-3">
                            <label for="field-2" class="form-label">Comments</label>
                            <textarea id="textarea" name="desc" class="form-control" required maxlength="3000" rows="3" placeholder="Comments on approval"></textarea>
                        </div>
                    </div>
                </div>

                </div>
                <div class="modal-footer">
                    <button class="btn btn-secondary" type="button" data-dismiss="modal">Cancel</button>
                    <button class="btn btn-success" type="submit">Approve</button>
                </div>
            </div>
        </form>
        </div>
     </div>
     <!-- End Approval Modal-->

          <!-- Return Modal-->
          <div class="modal fade" id="return-m-{{$item->id}}" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
          aria-hidden="true">
          <div class="modal-dialog modal-dialog-centered" role="document">
              <div class="modal-content">
                  <div class="modal-header">
                      <h5 class="modal-title text-danger" id="exampleModalLabel">Return <i class="fa fa-times-circle"></i> ID: {{$item->id}}</h5>
                      <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                          <span aria-hidden="true">×</span>
                      </button>
                  </div>
                  <div class="modal-body">
                    <form action="{{route('approve')}}" method="POST">
                       @method('POST')
                       @csrf
                       <input type="hidden" name="loan_id" value="{{$item->id}}">
                       <input type="hidden" name="type" value="2">
                  <div class="row">
                      <div class="col-md-12 mt-1">
                          <div class="mb-3">
                              <label for="field-2" class="form-label">Comments</label>
                              <textarea id="textarea" name="desc" class="form-control" required maxlength="3000" rows="3" placeholder="Comments on denial"></textarea>
                          </div>
                      </div>
                  </div>
  
                  </div>
                  <div class="modal-footer">
                      <button class="btn btn-secondary" type="button" data-dismiss="modal">Cancel</button>
                      <button class="btn btn-danger" type="submit">Return</button>
                  </div>
              </div>
            </form>
          </div>
      </div>
      <!-- End Return Modal-->
     @endforeach

 @endsection