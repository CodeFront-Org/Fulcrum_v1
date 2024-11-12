@extends('layouts.app')

@section('content')

            <!-- DataTales Example -->
            <button class="btn btn-primary" data-toggle="modal" data-target="#newStaff"
        aria-expanded="true" aria-controls="collapseTwo">New Role</button>
            <div class="card shadow mb-4">
              <div class="card-header py-3 text-center">
                <h6 class="m-0 font-weight-bold text-primary">
                  Manage Users Roles
                </h6>
              </div>
              <div class="card-body">
                <div class="table-responsive">
                  <table
                    class="table table-bordered"
                    id="dataTable"
                    width="100%"
                    cellspacing="0"
                    style="font-size:15px;text-align:center;white-space:nowrap;"
                  >
                    <thead>
                      <tr>
                        <th>#</th>
                        <th>Name</th>
                        <th>Contacts</th>
                        <th>ID Number</th>
                        <th>Role</th>
                        <th>Companies Access</th>
                        <th>Actions</th>
                      </tr>
                    </thead>
                    <tbody>
                      <tr>
                        <td>1. </td>
                        <td>Tiger Nixon</td>
                        <td>0797965678</td>
                        <td>123554</td>
                        <td>Admin</td>
                        <td>2</td>
                        <td>

                        <button type="button" style="background-color: #08228a9f;color: white" class="btn btn-sm" data-toggle="modal" data-target="#modal-edit-1">
                            <i class='fas fa-eye' aria-hidden='true'></i>
                            </button>
                        </td>
                      </tr>

                    </tbody>
                  </table>
                </div>
              </div>
            </div>


     <!-- New Role Modal-->
    <div class="modal fade" id="newStaff" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Assign Role</h5>
                    <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
                <div class="modal-body">
                <form action="{{route('roles.store')}}" method="POST">
                    @method('POST')
                    @csrf
                <div class="row">
                    <div class="col-md-12">
                    <div class="mb-3">
                        <label class="form-label">Select User</label>
                        @php
                        use App\Models\User;
                        use App\Models\Company;
                        $users = User::select('id','first_name','last_name')->get();
                        $companies=Company::select('id','name')->get();

                        @endphp
                        <!-- Input field to display names -->
                        <input list="regnoo" id="user-input" class="form-control" placeholder="Select user" autocomplete="off">

                        <!-- Hidden input to store the user ID -->
                        <input type="hidden" name="user_id" id="user-id">

                        <datalist id="regnoo">
                            @foreach ($users as $user)
                                <option data-id="{{ $user->id }}" value="{{ $user->first_name.' '.$user->last_name }}"></option>
                            @endforeach
                        </datalist>
                    </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-12 mb-3">
                    <label class="form-label">Designation</label>
                    <input list="regnoo1" class="form-control" name="company" placeholder="Select Company...">

                    <datalist id="regnoo1">
                        @foreach ($companies as $c)
                            <option value="{{ $c->name }}">{{$c->name}}</option>
                        @endforeach
                    </datalist>
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
                    <option value="1">User</option>
                    <option value="2">Human Resource</option>
                    <option value="3">Finance</option>
                    <option value="4">Admin</option>
                    </select>
                    </div>
                </div>

                <div class="modal-footer">
                    <button class="btn btn-secondary" type="button" data-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary"> Submit</button>
                </div>
            </form>
        </div>
            </div>
        </div>
    </div>
     <!-- End New Role Modal-->


    <!-- View Role Modal-->
    <div class="modal fade" id="modal-edit-1" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Martin Njoroge Roles</h5>
                    <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="table-responsive">
                        <table
                          class="table table-bordered"
                          id="dataTable"
                          width="100%"
                          cellspacing="0"
                          style="font-size:15px;text-align:center"
                        >
                          <thead style="color: white" class="bg-primary">
                            <tr>
                              <th>#</th>
                              <th>Designation</th>
                              <th>Role</th>
                              <th>Actions</th>
                            </tr>
                          </thead>
                          <tbody>
                            <tr>
                              <td>1. </td>
                              <td>FulcrumFulcrumFulcrum Fulcrum</td>
                              <td>Admin</td>
                              <td>
                                  <button type="button" class="btn btn-danger btn-sm" data-toggle="modal" data-target="#modal-rm-1">
                                      <i class='fas fa-trash' aria-hidden='true'></i>
                                      </button>
                              </td>
                            </tr>

                            <tr>
                                <td>1. </td>
                                <td>Fulcrum</td>
                                <td>Admin</td>
                                <td>
                                    <button type="button" class="btn btn-danger btn-sm" data-toggle="modal" data-target="#modal-rm-1">
                                        <i class='fas fa-trash' aria-hidden='true'></i>
                                        </button>
                                </td>
                              </tr>
      
                          </tbody>
                        </table>
                      </div>
            </div>
        </div>
    </div>
</div>
     <!-- End View Role Modal-->
     


    <!-- Remove Role Modal-->
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
 <!-- End Remove Role Modal-->
 

 <script>
    // JavaScript to handle selection
    document.getElementById('user-input').addEventListener('input', function(e) {
        const input = e.target;
        const list = document.getElementById('regnoo');
        const hiddenInput = document.getElementById('user-id');
        const options = list.options;

        // Reset hidden input value
        hiddenInput.value = '';

        // Iterate over options to find a match
        for (let i = 0; i < options.length; i++) {
            if (options[i].value === input.value) {
                hiddenInput.value = options[i].getAttribute('data-id');
                break;
            }
        }
    });
</script>

@endsection

