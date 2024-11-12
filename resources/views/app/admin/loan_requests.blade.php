@extends('layouts.app')

@section('content')
<div class="card shadow mb-4">
    <div class="card-header py-3 text-center">
      <h6 class="m-0 font-weight-bold text-primary">
        Loan Requests
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

                          
          @php
          $page=$page_number;
      @endphp

            <thead>
              <tr>
                <th>#</th>
                <th class="column-title">Loan ID</th>
                <th class="column-title">Name</th>
                <th class="column-title">Email</th>
                <th class="column-title">Date</th>
                <th class="column-title">Amount</th>
                <th class="column-title">Installments</th>
                <th class="column-title">Period</th>
                <th class="column-title">Scheme</th>
                <th class="column-title">Level</th>
                <th class="column-title">Status</th>
                <th class="column-title">View</th>
              </tr>
            </thead>
            <tbody>
                                    
                @php
                $page=$page_number;
            @endphp
              @foreach ($data as $loan)                            
                  <tr>
                  <td>{{$page}}. </td>
                  <td>{{$loan['loan_id']}}</td>
                  <td>{{$loan['user_name']}}</td>
                  <td>{{$loan['email']}}</td>
                  <td>{{$loan['date']}}</td>
                  <td>{{number_format($loan['amount'])}}</td>
                  <td>{{number_format($loan['installments'])}}</td>
                  <td>{{$loan['period']}}</td>
                  <td>{{$loan['scheme']}}</td>
                  @if ($loan['level']==1)
                      <td>HR</td>
                  @endif
                  @if ($loan['level']==2)
                      <td>Finance</td>
                  @endif
                  @if ($loan['level']==3)
                      <td>Admin</td>
                  @endif
                  @if ($loan['level']==5)
                      <td>Cleared</td>
                  @endif

                  @if ($loan['status']==1)
                      <td class="text-success">Approved</td>
                  @endif
                  @if ($loan['status']==0)
                      <td class="text-warning">Pending</td>
                  @endif
                  @if ($loan['status']==2)
                      <td class="text-danger">Returned</td>
                  @endif

                  <td>
                    <button type="button" style="background-color: #08228a9f;color: white" class="btn btn-sm" data-toggle="modal" data-target="#view-m-{{$loan['loan_id']}}">
                        <i class='fas fa-eye' aria-hidden='true'></i>
                    </button>
                </td>
                  </tr>

                  @php
                  $page+=1;
              @endphp
              @endforeach
            </tbody>
          </table>
        </div>
        

              <!-- Pagination links -->
              <div class="d-flex justify-content-end" style="margin-top: 20px;height:30%;height:1032%"> <!-- Adjust margin-top as needed -->
                  <div style="margin-right: 0; text-align: right; font-size: 14px; color: #555;">
                      {{ $data->appends(request()->except('page'))->links('vendor.pagination.simple-bootstrap-4')}}
                  </div>
              </div>

    </div>
  </div>




  @foreach ($loans as $item)
      
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
                                                                <span>({{ \App\Models\User::find($item->approver1_id)?->first_name ?? '' }})</span>  <span class="text-success">Approved</span>
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
                                                          <span>({{ \App\Models\User::find($item->approver1_id)?->first_name ?? '' }})</span> 
                                                          <span class="text-success">Approved</span>
                                                      </div>
                                                      <div class="mb-1">
                                                          <b style="font-weight: bolder; color:black">Finance Decision: </b>
                                                          <span>({{ \App\Models\User::find($item->approver2_id)?->first_name ?? '' }})</span> 
                                                              <span class="text-success">Approved</span>
                                                      </div>
                                                      <div class="mb-1">
                                                          <b style="font-weight: bolder; color:black">Admin Decision: </b>
                                                          <span>({{ \App\Models\User::find($item->approver3_id)?->first_name ?? '' }})</span> 
                                                              <span class="text-success">Approved</span>
                                                      </div>
                                                      @endif
                                                      @if ($item->final_decision==2)
                                                          @if ($item->approval_level==1)
                                                              <div class="mb-1">
                                                                  <b style="font-weight: bolder; color:black">HR Decision: </b>
                                                                  <span>({{ \App\Models\User::find($item->approver1_id)?->first_name ?? '' }})</span> 
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
                                                                   <span>({{ \App\Models\User::find($item->approver2_id)?->first_name ?? '' }})</span> 
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
                                                                    <span>({{ \App\Models\User::find($item->approver3_id)?->first_name ?? '' }})</span> 
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

@endsection