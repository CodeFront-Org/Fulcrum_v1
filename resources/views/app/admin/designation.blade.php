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
                  Companies Data
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
                        <th>M1</th>
                        <th>M2</th>
                        <th>M3</th>
                        <th>M4</th>
                        <th>M5</th>
                        <th>M6</th>
                        <th>M7</th>
                        <th>M8</th>
                        <th>M9</th>
                        <th>M10</th>
                        <th>M11</th>
                        <th>M12</th>
                        <th>Actions</th>
                      </tr>
                    </thead>
                    <tbody>
                        @foreach ($companies as $item)                            
                            <tr>
                                <td>{{$loop->index+1}}. </td>
                                <td>{{$item['name']}}</td>
                                <td>{{$item['month1']}}</td>
                                <td>{{$item['month2']}}</td>
                                <td>{{$item['month3']}}</td>
                                <td>{{$item['month4']}}</td>
                                <td>{{$item['month5']}}</td>
                                <td>{{$item['month6']}}</td>
                                <td>{{$item['month7']}}</td>
                                <td>{{$item['month8']}}</td>
                                <td>{{$item['month9']}}</td>
                                <td>{{$item['month10']}}</td>
                                <td>{{$item['month11']}}</td>
                                <td>{{$item['month12']}}</td>
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

     <!-- NewStaff Modal-->
    <div class="modal fade" id="newStaff" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">New Designation</h5>
                    <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
                <div class="modal-body">
                <form action="{{route('companies.store')}}" method="POST">
                    @method('POST')
                    @csrf
                <div class="row">
                    <div class="col-md-12">
                    <div class="mb-3">
                        <label class="form-label">Company Name</label>
                        <input
                        name="name"
                        type="text"
                        class="form-control"
                        placeholder="Full company name"
                        required
                        />
                    </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6">
                    <div class="mb-3">
                        <label class="form-label">Month 1 Rate</label>
                        <input
                        name="m1"
                        type="number"
                        class="form-control"
                        placeholder=""
                        required
                        step="0.001" 
                        />
                    </div>
                    </div>
                    <div class="col-md-6">
                    <div class="mb-3">
                        <label class="form-label">Month 2 Rate</label>
                        <input
                        name="m2"
                        type="number"
                        class="form-control"
                        placeholder=""
                        required
                        step="0.001" 
                        />
                    </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6">
                    <div class="mb-3">
                        <label class="form-label">Month 3 Rate</label>
                        <input
                        name="m3"
                        type="number"
                        class="form-control"
                        placeholder=""
                        required
                        step="0.001" 
                        />
                    </div>
                    </div>
                    <div class="col-md-6">
                    <div class="mb-3">
                        <label class="form-label">Month 4 Rate</label>
                        <input
                        name="m4"
                        type="number"
                        class="form-control"
                        placeholder=""
                        step="0.001" 
                        
                        />
                    </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6">
                    <div class="mb-3">
                        <label class="form-label">Month 5 Rate</label>
                        <input
                        name="m5"
                        type="number"
                        class="form-control"
                        placeholder=""
                        step="0.001" 
                        
                        />
                    </div>
                    </div>
                    <div class="col-md-6">
                    <div class="mb-3">
                        <label class="form-label">Month 6 Rate</label>
                        <input
                        name="m6"
                        type="number"
                        class="form-control"
                        placeholder=""
                        step="0.001" 
                        
                        />
                    </div>
                    </div>
                </div>




                <div class="row">
                    <div class="col-md-6">
                    <div class="mb-3">
                        <label class="form-label">Month 7 Rate</label>
                        <input
                        name="m7"
                        type="number"
                        class="form-control"
                        placeholder=""
                        step="0.001" 
                        
                        />
                    </div>
                    </div>
                    <div class="col-md-6">
                    <div class="mb-3">
                        <label class="form-label">Month 8 Rate</label>
                        <input
                        name="m8"
                        type="number"
                        class="form-control"
                        placeholder=""
                        step="0.001" 
                        
                        />
                    </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6">
                    <div class="mb-3">
                        <label class="form-label">Month 9 Rate</label>
                        <input
                        name="m9"
                        type="number"
                        class="form-control"
                        placeholder=""
                        step="0.001" 
                        
                        />
                    </div>
                    </div>
                    <div class="col-md-6">
                    <div class="mb-3">
                        <label class="form-label">Month 10 Rate</label>
                        <input
                        name="m10"
                        type="number"
                        class="form-control"
                        placeholder=""
                        step="0.001" 
                        
                        />
                    </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6">
                    <div class="mb-3">
                        <label class="form-label">Month 11 Rate</label>
                        <input
                        name="m11"
                        type="number"
                        class="form-control"
                        placeholder=""
                        step="0.001" 
                        
                        />
                    </div>
                    </div>
                    <div class="col-md-6">
                    <div class="mb-3">
                        <label class="form-label">Month 12 Rate</label>
                        <input
                        name="m12"
                        type="number"
                        class="form-control"
                        placeholder=""
                        step="0.001" 
                        
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



@foreach ($companies as $item)
     <!-- Edit Company Modal-->
     <div class="modal fade" id="company-edit-{{$item['id']}}" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
     aria-hidden="true">
     <div class="modal-dialog modal-dialog-centered" role="document">
         <div class="modal-content">
             <div class="modal-header">
                 <h5 class="modal-title" id="exampleModalLabel">Edit Designation</h5>
                 <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                     <span aria-hidden="true">×</span>
                 </button>
             </div>
             <div class="modal-body">
             <form action="{{route('companies.update',$item['id'])}}" method="POST">
                 @method('PATCH')
                 @csrf
             <div class="row">
                 <div class="col-md-12">
                 <div class="mb-3">
                     <label class="form-label">Company Name</label>
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

             <div class="row">
                 <div class="col-md-6">
                 <div class="mb-3">
                     <label class="form-label">Month 1 Rate</label>
                     <input
                     name="m1"
                     type="number"
                     class="form-control"
                     placeholder=""
                     required
                     step="0.001" 
                     value="{{$item['month1']}}"
                     />
                 </div>
                 </div>
                 <div class="col-md-6">
                 <div class="mb-3">
                     <label class="form-label">Month 2 Rate</label>
                     <input
                     name="m2"
                     type="number"
                     class="form-control"
                     placeholder=""
                     required
                     step="0.001" 
                     value="{{$item['month2']}}"
                     />
                 </div>
                 </div>
             </div>
             <div class="row">
                 <div class="col-md-6">
                 <div class="mb-3">
                     <label class="form-label">Month 3 Rate</label>
                     <input
                     name="m3"
                     type="number"
                     class="form-control"
                     placeholder=""
                     required
                     step="0.001" 
                     value="{{$item['month3']}}"
                     />
                 </div>
                 </div>
                 <div class="col-md-6">
                 <div class="mb-3">
                     <label class="form-label">Month 4 Rate</label>
                     <input
                     name="m4"
                     type="number"
                     class="form-control"
                     placeholder=""
                     step="0.001" 
                     value="{{$item['month4']}}"
                     
                     />
                 </div>
                 </div>
             </div>
             <div class="row">
                 <div class="col-md-6">
                 <div class="mb-3">
                     <label class="form-label">Month 5 Rate</label>
                     <input
                     name="m5"
                     type="number"
                     class="form-control"
                     placeholder=""
                     step="0.001" 
                     value="{{$item['month5']}}"
                     
                     />
                 </div>
                 </div>
                 <div class="col-md-6">
                 <div class="mb-3">
                     <label class="form-label">Month 6 Rate</label>
                     <input
                     name="m6"
                     type="number"
                     class="form-control"
                     placeholder=""
                     step="0.001" 
                     value="{{$item['month6']}}"
                     
                     />
                 </div>
                 </div>
             </div>
             <div class="row">
                 <div class="col-md-6">
                 <div class="mb-3">
                     <label class="form-label">Month 7 Rate</label>
                     <input
                     name="m7"
                     type="number"
                     class="form-control"
                     placeholder=""
                     step="0.001" 
                     value="{{$item['month7']}}"
                     
                     />
                 </div>
                 </div>
                 <div class="col-md-6">
                 <div class="mb-3">
                     <label class="form-label">Month 8 Rate</label>
                     <input
                     name="m8"
                     type="number"
                     class="form-control"
                     placeholder=""
                     step="0.001" 
                     value="{{$item['month8']}}"
                     
                     />
                 </div>
                 </div>
             </div>
             <div class="row">
                 <div class="col-md-6">
                 <div class="mb-3">
                     <label class="form-label">Month 9 Rate</label>
                     <input
                     name="m9"
                     type="number"
                     class="form-control"
                     placeholder=""
                     step="0.001" 
                     value="{{$item['month9']}}"
                     
                     />
                 </div>
                 </div>
                 <div class="col-md-6">
                 <div class="mb-3">
                     <label class="form-label">Month 10 Rate</label>
                     <input
                     name="m10"
                     type="number"
                     class="form-control"
                     placeholder=""
                     step="0.001" 
                     value="{{$item['month10']}}"
                     
                     />
                 </div>
                 </div>
             </div>
             <div class="row">
                 <div class="col-md-6">
                 <div class="mb-3">
                     <label class="form-label">Month 11 Rate</label>
                     <input
                     name="m11"
                     type="number"
                     class="form-control"
                     placeholder=""
                     step="0.001" 
                     value="{{$item['month11']}}"
                     
                     />
                 </div>
                 </div>
                 <div class="col-md-6">
                 <div class="mb-3">
                     <label class="form-label">Month 12 Rate</label>
                     <input
                     name="m12"
                     type="number"
                     class="form-control"
                     placeholder=""
                     step="0.001" 
                     value="{{$item['month12']}}"
                     
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