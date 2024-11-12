@extends('layouts.app')

@section('content')
    
<div class="row">
    <div class="card-body col-md-12">

                        <ul class="nav nav-pills navtab-bg nav-justified">
                            <li class="nav-item">
                                <a href="#home1" data-bs-toggle="tab" aria-expanded="false" class="nav-link active">
                                    Info
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="#home2" data-bs-toggle="tab" aria-expanded="false" class="nav-link bg-warning">
                                    Salary
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="#messages1" data-bs-toggle="tab" aria-expanded="false" class="nav-link bg-warning">
                                    Loan 
                                </a>
                            </li>

                            <li class="nav-item">
                                <a href="#messages1" data-bs-toggle="tab" aria-expanded="false" class="nav-link bg-warning">
                                     Terms
                                </a>
                            </li>
                        </ul>
                        <div class="tab-content">
                            <div class="tab-pane active" id="home1">

                                    <div>
                                        <div  class="modal-dialog1 modal-dialog-centered1 col-8 responsive1">
                                                    <h5 class="modal-title" id="exampleModalLabel">My Personal Details</h5>
                                            <div class="modal-content" style="font-size: larger;">
                                                <div class="modal-body">
                                                <form onsubmit="submitNewStaff(event)">
                                                <div class="row mb-2">
                                                    <div class="col-md-6">
                                                    <div class="mb-3">
                                                        <label class="form-label">Applicant ID</label>
                                                        <input
                                                        name="first_name"
                                                        type="text"
                                                        class="form-control"
                                                        placeholder="Applicant ID"
                                                        required
                                                        />
                                                    </div>
                                                    </div>
                                                    <div class="col-md-6">
                                                    <div class="mb-3">
                                                        <label class="form-label">First Name</label>
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

                                                <div class="col-md-6 mb-2">
                                                    <div class="mb-3">
                                                        <label class="form-label">Second Name</label>
                                                        <input
                                                        name="last_name"
                                                        type="text"
                                                        class="form-control"
                                                        placeholder="second name"
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
                                                        placeholder="contacts"
                                                        required
                                                        />
                                                    </div>
                                                    </div>
                                                    <div class="col-md-6 mb-2">
                                                    <div class="mb-3">
                                                        <label class="form-label">Contract Expires</label>
                                                        <input
                                                        name="email"
                                                        type="text"
                                                        class="form-control"
                                                        placeholder="expiry"
                                                        required
                                                        />
                                                    </div>
                                                    </div>


                                                    <div class="col-md-6 mb-2">
                                                    <div class="mb-3">
                                                        <label class="form-label">Designation</label>
                                                        <input
                                                        name="email"
                                                        type="text"
                                                        class="form-control"
                                                        placeholder="designation"
                                                        required
                                                        />
                                                    </div>
                                                    </div>


                                                    <div class="col-md-6">
                                                    <div class="mb-3">
                                                        <label class="form-label">Next Of KIN</label>
                                                        <input
                                                        name="email"
                                                        type="text"
                                                        class="form-control"
                                                        placeholder="next of kin names"
                                                        required
                                                        />
                                                    </div>
                                                    </div>


                                                    <div class="col-md-6">
                                                    <div class="mb-3">
                                                        <label class="form-label">KIN Contacts</label>
                                                        <input
                                                        name="email"
                                                        type="text"
                                                        class="form-control"
                                                        placeholder="kin contacts"
                                                        required
                                                        />
                                                    </div>
                                                    </div>

                                                </div>

                                                </form>

                                                </div>
                                                <div class="modal-footer">
                                                    <!-- <button class="btn btn-secondary" type="button" data-dismiss="modal">Cancel</button> -->
                                                    <a class="btn btn-primary" href="login.html">Next  <i class="fa fa-arrow-right"></i></a>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <!-- End NewStaff Modal-->

                            </div>
                            <div class="tab-pane show" id="home2">
                                <p>Donec pede justo, fringilla vel, aliquet nec, vulputate eget, arcu. In enim justo, rhoncus ut, imperdiet a, venenatis vitae, justo. Nullam dictum felis eu pede mollis pretium. Integer tincidunt.Cras dapibus. Vivamus elementum semper nisi. Aenean vulputate eleifend tellus. Aenean leo ligula, porttitor eu, consequat vitae, eleifend ac, enim.</p>
                                <p class="mb-0">Vakal text here dolor sit amet, consectetuer adipiscing elit. Aenean commodo ligula eget dolor. Aenean massa. Cum sociis natoque penatibus et magnis dis parturient montes, nascetur ridiculus mus. Donec quam felis, ultricies nec, pellentesque eu, pretium quis, sem. Nulla consequat massa quis enim.</p>
                            </div>
                            <div class="tab-pane" id="messages1">
                                <p>Vakal text here dolor sit amet, consectetuer adipiscing elit. Aenean commodo ligula eget dolor. Aenean massa. Cum sociis natoque penatibus et magnis dis parturient montes, nascetur ridiculus mus. Donec quam felis, ultricies nec, pellentesque eu, pretium quis, sem. Nulla consequat massa quis enim.</p>
                                <p class="mb-0">Donec pede justo, fringilla vel, aliquet nec, vulputate eget, arcu. In enim justo, rhoncus ut, imperdiet a, venenatis vitae, justo. Nullam dictum felis eu pede mollis pretium. Integer tincidunt.Cras dapibus. Vivamus elementum semper nisi. Aenean vulputate eleifend tellus. Aenean leo ligula, porttitor eu, consequat vitae, eleifend ac, enim.</p>
                            </div>
                        </div>
                    </div>
    </div>
@endsection