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
            <!-- DataTales Example -->
            <button class="btn btn-primary" data-toggle="modal" data-target="#newStaff"
        aria-expanded="true" aria-controls="collapseTwo">New</button>
            <div class="card shadow mb-4">
              <div class="card-header py-3 text-center">
                <h6 class="m-0 font-weight-bold text-primary">
                  Users Data
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
                          <th>Name</th>
                          <th>Contacts</th>
                          <th>Email</th>
                          <th>ID Number</th>
                          <th>Gender</th>
                          <th>Pin</th>
                          <th>Staff No</th>
                          <th>Type</th>
                          <th>Designation</th>
                          <th>Contract End</th>
                          <th>Actions</th>
                        </tr>
                      </thead>
                      <tbody>
                        @foreach ($users as $user)                            
                            <tr>
                            <td>{{$page}}. </td>
                            <td>{{$user->first_name}} {{$user->last_name}}</td>
                            <td>{{$user->contacts}}</td>
                            <td>{{$user->email}}</td>
                            <td>{{$user->id_number}}</td>
                            <td>{{$user->gender}}</td>
                            <td>{{$user->pin_certificate}}</td>
                            <td>{{$user->staff_number}}</td>
                            <td>{{$user->employment_type}}</td>
                            <td>{{$user->designation}}</td>
                            <td>{{$user->contract_end}}</td>
                            <td>
                                <button type="button" style="background-color: #08228a9f;color: white" class="btn btn-sm" data-toggle="modal" data-target="#modal-edit-{{$user->id}}">
                                <i class='fas fa-pen' aria-hidden='true'></i>
                                </button>
                                <button type="button" onclick="del(this)" value="{{$user->id}}" class="btn btn-danger btn-sm">
                                <i class='fa fa-trash' aria-hidden='true'></i>
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
                                {{ $users->appends(request()->except('page'))->links('vendor.pagination.simple-bootstrap-4')}}
                            </div>
                        </div>

              </div>
            </div>


     <!-- NewStaff Modal-->
    <div class="modal fade" id="newStaff" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">New User</h5>
                    <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form action="{{route('users.store')}}" method="POST">
                        @method('POST')
                        @csrf
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">Company</label>

                                @php
                                    use App\Models\Company;
                                   // $companies = Company::select('id','name')->get();
        
                                @endphp
        
                                <input type="text" list="regnoo" parsley-trigger="change" required class="form-control"
                                id="reg_no" name='company' placeholder="Select company ..." aria-label="Recipient's username"
                                id="top-search" autocomplete="off" />
                        
                        <datalist id="regnoo">
                            @foreach ($companies as $company)
                                <option value="{{ $company->name }}">{{ $company->name }}</option>
                            @endforeach
                        </datalist>
 
                            </div>
                        </div>

                        <div class="col-md-6">
                        <div class="mb-3">
                            <label class="form-label">First Name</label>
                            <input
                            name="first_name"
                            type="text"
                            class="form-control"
                            placeholder="first name"
                            required
                            />
                        </div>
                        </div>
                        <div class="col-md-6">
                        <div class="mb-3">
                            <label class="form-label">Last Name</label>
                            <input
                            name="last_name"
                            type="text"
                            class="form-control"
                            placeholder="last name"
                            required
                            />
                        </div>
                        </div>
                        
                        <div class="col-md-6">
                        <div class="mb-3">
                            <label class="form-label">Email</label>
                            <input
                            name="email"
                            type="text"
                            class="form-control"
                            placeholder="email"
                            required
                            />
                        </div>
                        </div>
                        <div class="col-md-6">
                        <div class="mb-3">
                            <label class="form-label">Contacts</label>
                            <input
                            name="contacts"
                            type="text"
                            class="form-control"
                            placeholder="07123..."
                            required
                            />
                        </div>
                        </div>

                        <div class="col-md-6">
                        <div class="mb-3">
                            <label class="form-label">ID Number:</label>
                            <input
                            name="id_number"
                            type="number"
                            class="form-control"
                            placeholder="id number"
                            required
                            />
                        </div>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Gender</label>
                            <select
                                name="gender"
                                class="form-control form-select"
                                required
                            >
                                <option value="MALE">Male</option>
                                <option value="FEMALE">Female</option>
                            </select>
                            </div>
                        <div class="col-md-6">
                        <div class="mb-3">
                            <label class="form-label">Pin Certificate</label>
                            <input
                            name="pin"
                            type="text"
                            class="form-control"
                            placeholder="Pin certificate"
                            required
                            />
                        </div>
                        </div>

{{-- 
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Select Bank</label>
                            <select
                                name="bank_id"
                                class="form-control form-select"
                                required
                                style="font-size:13px"
                            >
                            <option value="" disabled selected>Select Bank</option> 
                            @foreach ($banks as $bank)
                                <option value="{{$bank->id}}">{{$bank->name}}</option>
                            @endforeach
                            </select>
                            </div> --}}


                        {{-- <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">Bank Branch</label>
                                <input
                                name="branch"
                                type="text"
                                class="form-control"
                                placeholder="Branch"
                                required
                                />
                            </div>
                            </div>

                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">Account Number</label>
                                <input
                                name="acc_no"
                                type="text"
                                class="form-control"
                                placeholder="Enter bank account number"
                                required
                                />
                            </div>
                            </div> --}}

                        <div class="col-md-6">
                        <div class="mb-3">
                            <label class="form-label">Staff Number</label>
                            <input
                            name="staff_no"
                            type="text"
                            class="form-control"
                            placeholder="staff number"
                            required
                            />
                        </div>
                        </div>


                        <div class="col-md-6 mb-3">
                            <label class="form-label">Employment Type</label>
                            <select
                                name="emp_type"
                                class="form-control form-select"
                                required
                            >
                            <option value="2">Contract</option>
                                <option value="1">Permanent</option>
                            </select>
                            </div>


                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">Employment Date</label>
                                <input
                                name="employment_date"
                                type="date"
                                class="form-control"
                                placeholder="employment date"
                                
                                />
                            </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label">Contract End Date</label>
                                    <input
                                    name="contract_end"
                                    type="date"
                                    class="form-control"
                                    placeholder="employment date"
                                    />
                                </div>
                                </div>
                    </div>

                </div>
                <div class="modal-footer">
                    <button class="btn btn-secondary" type="button" data-dismiss="modal">Cancel</button>
                    <button class="btn btn-primary" type="submit">Submit</button>
                </div>
            </div>
        </form>
        </div>
    </div>
     <!-- End NewStaff Modal-->


     @foreach ($users as $user)
             <!-- Edit staff Modal-->
            <div class="modal fade" id="modal-edit-{{$user->id}}" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
            aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">Edit User</h5>
                        <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">×</span>
                        </button>
                    </div>
                    <div class="modal-body">
                    <form action="{{route('users.update',$user->id)}}" method="POST">
                        @method('PATCH')
                        @csrf
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label">Company</label>
                                    @php
                                        $companies = Company::select('id','name')->get();
            
                                    @endphp
            
                                    <input type="text" list="regnoo" parsley-trigger="change" required class="form-control"
                                    id="reg_no" name='company' value="{{$user->designation}}" placeholder="Select company ..." aria-label="Recipient's username"
                                    id="top-search" autocomplete="off" />
                            
                            <datalist id="regnoo">
                                @foreach ($companies as $company)
                                    <option value="{{ $company->name }}">{{ $company->name }}</option>
                                @endforeach
                            </datalist>

                                </div>
                            </div>

                            <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">First Name</label>
                                <input
                                name="first_name"
                                type="text"
                                class="form-control"
                                placeholder="first name"
                                value="{{$user->first_name}}"
                                required
                                />
                            </div>
                            </div>
                            <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">Last Name</label>
                                <input
                                name="last_name"
                                type="text"
                                class="form-control"
                                placeholder="last name"
                                value="{{$user->last_name}}"
                                required
                                />
                            </div>
                            </div>
                            
                            <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">Email</label>
                                <input
                                name="email"
                                type="text"
                                class="form-control"
                                placeholder="email"
                                value="{{$user->email}}"
                                required
                                />
                            </div>
                            </div>
                            <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">Contacts</label>
                                <input
                                name="contacts"
                                type="text"
                                class="form-control"
                                placeholder="07123..."
                                value="{{$user->contacts}}"
                                required
                                />
                            </div>
                            </div>

                            <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">ID Number:</label>
                                <input
                                name="id_number"
                                type="number"
                                class="form-control"
                                placeholder="id number"
                                min="1"  pattern="^[1-9]\d*(\.\d+)?$" title="Please enter a valid positive number"
                                value="{{$user->id_number}}"
                                required
                                />
                            </div>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Gender</label>
                                <select
                                    name="gender"
                                    class="form-control form-select"
                                    required
                                >
                                    <option {{$user->gender=='MALE'?'selected':''}} value="MALE">Male</option>
                                    <option {{$user->gender=='FEMALE'?'selected':''}} value="FEMALE">Female</option>
                                </select>
                                </div>
                            <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">Pin Certificate</label>
                                <input
                                name="pin"
                                type="text"
                                class="form-control"
                                placeholder="Pin certificate"
                                value="{{$user->pin_certificate}}"
                                required
                                />
                            </div>
                            </div>


                            {{-- <div class="col-md-6 mb-3">
                                <label class="form-label">Select Bank</label>
                                <select
                                    name="bank_id"
                                    class="form-control form-select"
                                    required
                                    style="font-size:13px"
                                >
                                @foreach ($banks as $bank)
                                    <option {{$user->my_bank==$bank->name?'selected':''}} value="{{$bank->id}}">{{$bank->name}}</option>
                                @endforeach
                                </select>
                                </div> --}}


                            {{-- <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label">Bank Branch</label>
                                    <input
                                    name="branch"
                                    type="text"
                                    class="form-control"
                                    placeholder="Branch"
                                    value="{{$user->branch}}"
                                    required
                                    />
                                </div>
                                </div>

                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label">Account Number</label>
                                    <input
                                    name="acc_no"
                                    type="text"
                                    class="form-control"
                                    placeholder="Enter bank account number"
                                    value="{{$user->acc_no}}"
                                    required
                                    />
                                </div>
                                </div> --}}

                            <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">Staff Number</label>
                                <input
                                name="staff_no"
                                type="text"
                                class="form-control"
                                placeholder="staff number"
                                value="{{$user->staff_number}}"
                                required
                                />
                            </div>
                            </div>


                            <div class="col-md-6 mb-3">
                                <label class="form-label">Employment Type</label>
                                <select
                                    name="emp_type"
                                    class="form-control form-select"
                                    required
                                >
                                    <option {{$user->employment_type=='PERMANENT'?'selected':''}} value="1">Permanent</option>
                                    <option {{$user->employment_type=='CONTRACT'?'selected':''}} value="2">Contract</option>
                                </select>
                                </div>


                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label">Employment Date</label>
                                    <input
                                    name="employment_date"
                                    type="date"
                                    class="form-control"
                                    placeholder="employment date"
                                    value="{{$user->employment_date}}"
                                    />
                                </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label class="form-label">Contract End Date</label>
                                        <input
                                        name="contract_end"
                                        type="date"
                                        class="form-control"
                                        placeholder="employment date"
                                        value="{{$user->contract_end}}"
                                        />
                                    </div>
                                    </div>
                        </div>

                    </div>
                    <div class="modal-footer">
                        <button class="btn btn-secondary" type="button" data-dismiss="modal">Cancel</button>
                        <button class="btn btn-primary" type="submit">Submit</button>
                    </div>
                </form>
                </div>
            </div>
        </div>
        <!-- End Edit Staff Modal-->
     @endforeach

     
@endsection

@section('scripts')
    <script>
        //Deleting User
        function del(e){
        let id=e.value;
        var type=0;//For knowing deletion operation is coming from settings

        Swal.fire({
            title: "Confirm deletion",
            text: "You won't be able to revert this!",
            type: "error",
            showCancelButton: !0,
            confirmButtonColor: "#3085d6",
            cancelButtonColor: "#d33",
            confirmButtonText: "Yes, delete it!"
        }).then((t)=>{
        if(t.value){
                $.ajax({
                    type: "DELETE",
                    url: "users/"+id,
                    data:{
                        _token:"{{csrf_token()}}", id,type
                    },
                    success: function (response) { console.log(response)

                        Swal.fire("Deleted", "Successfully.", "success").then(()=>{
                        location.href='/users'})
                    },
                    error: function(res){console.log(res)
                        Swal.fire("Error!", "Try again later...", "error");
                    }
                });
            }
            })
        }
    </script>
@endsection