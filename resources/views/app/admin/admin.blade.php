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
                  Admins Data
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
                          <th>Role</th>
                          <th>Actions</th>
                        </tr>
                      </thead>
                      <tbody>
                        <tr>
                          <td>1.</td>
                          <td>Tiger Nixon</td>
                          <td>0797965678</td>
                          <td>tiger@gmail.com</td>
                          <td>123554</td>
                          <td>Male</td>
                          <td>453432</td>
                          <td>7637TP</td>
                          <td>Contract</td>
                          <td>Fulcrum</td>
                          <td>2025-03-06</td>
                          <td>User</td>
                          <td>
                            <button type="button" style="background-color: #08228a9f;color: white" class="btn btn-sm" data-toggle="modal" data-target="#modal-edit-1">
                              <i class='fas fa-pen' aria-hidden='true'></i>
                            </button>
                            <button type="button" onclick="del(this)" value="" class="btn btn-danger btn-sm">
                              <i class='fa fa-trash' aria-hidden='true'></i>
                            </button>
                          </td>
                        </tr>
                      </tbody>
                    </table>
                  </div>
                  
              </div>
            </div>


     <!-- NewStaff Modal-->
    <div class="modal fade" id="newStaff" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">New Admin</h5>
                    <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form action="{{route('add-admin')}}" method="POST">
                        @method('POST')
                        @csrf
                    <div class="row">


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
                                <option value="1">Permanent</option>
                                <option value="2">Contract</option>
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
                            <div class="col-md-12">
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


    <!-- Edit staff Modal-->
    <div class="modal fade" id="modal-edit-1" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Edit Staff</h5>
                    <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
                <div class="modal-body">
                <form onsubmit="submitNewStaff(event)">
                <div class="row">
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
                </div>
                <div class="row">
                    <div class="col-md-6">
                    <div class="mb-3">
                        <label class="form-label">Contacts</label>
                        <input
                        name="contacts"
                        type="text"
                        class="form-control"
                        placeholder="contacts"
                        required
                        />
                    </div>
                    </div>
                    <div class="col-md-6">
                    <div class="mb-3">
                        <label class="form-label">Email</label>
                        <input
                        name="email"
                        type="email"
                        class="form-control"
                        placeholder="email"
                        required
                        />
                    </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12 mb-3">
                    <label class="form-label">Role</label>
                    <select
                        name="role_type"
                        class="form-control form-select"
                        required
                    >
                        <option value="1">Admin</option>
                        <option value="2">Staff</option>
                    </select>
                    </div>
                </div>
                </form>

                </div>
                <div class="modal-footer">
                    <button class="btn btn-secondary" type="button" data-dismiss="modal">Cancel</button>
                    <a class="btn btn-primary" href="login.html">Submit</a>
                </div>
            </div>
        </div>
    </div>
     <!-- End Edit Staff Modal-->
     
@endsection