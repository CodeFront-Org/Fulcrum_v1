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
                  Bank Data
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
                        <th>Actions</th>
                      </tr>
                    </thead>
                    <tbody>
                        @foreach ($banks as $item)                            
                            <tr>
                                <td>{{$loop->index+1}}. </td>
                                <td>{{$item['name']}}</td>
                                <td>
                                <button type="button" style="background-color: #08228a9f;color: white" class="btn btn-sm" data-toggle="modal" data-target="#company-edit-{{$item['id']}}">
                                    <i class='fas fa-pen' aria-hidden='true'></i>
                                    </button>
                                <button type="button" onclick="del(this)" value="" class="btn btn-danger btn-sm">
                                    <i class='fa fa-trash' aria-hidden='true'></i>
                                </button>
                                </td>
                            </tr>
                        @endforeach

                    </tbody>
                  </table>
                </div>
              </div>
            </div>

     <!-- New Bank Modal-->
    <div class="modal fade" id="newStaff" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Add Bank</h5>
                    <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
                <div class="modal-body">
                <form action="{{route('banks.store')}}" method="POST">
                    @method('POST')
                    @csrf
                <div class="row">
                    <div class="col-md-12">
                    <div class="mb-3">
                        <label class="form-label">Bank Name</label>
                        <input
                        name="name"
                        type="text"
                        class="form-control"
                        placeholder="Enter bank name"
                        required
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
     <!-- End Comapny Modal-->



@foreach ($banks as $item)
     <!-- Edit Company Modal-->
     <div class="modal fade" id="company-edit-{{$item['id']}}" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
     aria-hidden="true">
     <div class="modal-dialog modal-dialog-centered" role="document">
         <div class="modal-content">
             <div class="modal-header">
                 <h5 class="modal-title" id="exampleModalLabel">Edit Bank</h5>
                 <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                     <span aria-hidden="true">×</span>
                 </button>
             </div>
             <div class="modal-body">
             <form action="{{route('banks.update',$item['id'])}}" method="POST">
                 @method('PATCH')
                 @csrf
             <div class="row">
                 <div class="col-md-12">
                 <div class="mb-3">
                     <label class="form-label">Bank Name</label>
                     <input
                     name="name"
                     type="text"
                     value="{{$item['name']}}"
                     class="form-control"
                     placeholder="Full company name"
                     required
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
  <!-- End NewStaff Modal-->
@endforeach

@endsection