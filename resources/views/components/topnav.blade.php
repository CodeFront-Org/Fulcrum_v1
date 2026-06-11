<nav class="navbar navbar-expand navbar-light bg-white topbar mb-4 static-top shadow">

    <!-- Sidebar Toggle (Topbar) -->
    <button id="sidebarToggleTop" class="btn btn-link d-md-none rounded-circle mr-3">
        <i class="fa fa-bars"></i>
    </button>

    <!-- Topbar Search -->
    <form action="{{route('search-user')}}" class="d-none d-sm-inline-block form-inline mr-auto ml-md-3 my-2 my-md-0 mw-100 navbar-search">
        <div class="input-group">
            <input type="text" name="email" id="reg_no"list="regnoo1" class="form-control bg-light border-0 small" placeholder="Search for user..."
                aria-label="Search" aria-describedby="basic-addon2">
                @php
                    use App\Models\User;
                    $role_type=Auth::id();
                    $role_type=User::find($role_type)->role_type;
                    if($role_type=='admin'){
                        $users = User::select('id','email','first_name','last_name')->get();
                    }else{
                        $users=[];
                    }
                @endphp
                <datalist id="regnoo1">
                    @foreach ($users as $user)
                        <option value="{{ $user->email }}">{{ $user->first_name }} {{ $user->last_name }}</option>
                    @endforeach
                </datalist>
            <div class="input-group-append"> 
                <button class="btn btn-primary" type="submit">
                    <i class="fas fa-search fa-sm"></i>
                </button>
            </div>
        </div>
    </form>  

    {{-- <form action="{{route('search-user')}}" class="d-none d-sm-inline-block form-inline mr-auto ml-md-3 my-2 my-md-0 mw-100 navbar-search">
        <div class="input-group">
            <input type="text" name="email" id="reg_no"list="regnoo1" class="form-control bg-light border-0 small" placeholder="Search for user..."
                aria-label="Search" aria-describedby="basic-addon2">

            <div class="input-group-append"> 
                <button class="btn btn-primary" type="submit">
                    <i class="fas fa-search fa-sm"></i>
                </button>
            </div>
        </div>
    </form>  --}}
   
    
    <!-- Topbar Navbar   $2y$10$YhfQrjd0/Q.HqOySLbZCAeGIxhzepz2XPVyPOz1yZT9z9WKfcK/IO   
    $2y$10$H1Urn6dDotkv0oaFVH7d6O/CoVkrIpJzDGymXU65YmxaJymeVN42O

    $2y$10$0OLRcEqNxG.yqXMWucizeOq1vcszJ6USrZqJ5ZJvWJbvvSxTONZAe

    2024-07-17 14:38:58
    -->
    <ul class="navbar-nav ml-auto">
    
        <!-- Nav Item - Search Dropdown (Visible Only XS) -->
        <li class="nav-item dropdown no-arrow d-sm-none">
            <a class="nav-link dropdown-toggle" id="searchDropdown" role="button"
                data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                <i class="fas fa-search fa-fw"></i>
            </a>
            <!-- Dropdown - Messages -->
            <div class="dropdown-menu dropdown-menu-right p-3 shadow animated--grow-in"
                aria-labelledby="searchDropdown">
                <form class="form-inline mr-auto w-100 navbar-search">
                    <div class="input-group">
                        <input type="text" class="form-control bg-light border-0 small"
                            placeholder="Search for..." aria-label="Search"
                            aria-describedby="basic-addon2">
                        <div class="input-group-append">
                            <button class="btn btn-primary" type="button">
                                <i class="fas fa-search fa-sm"></i>
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </li>
    
        <!-- Nav Item - Alerts -->
        <li class="nav-item dropdown no-arrow mx-1">
            <a class="nav-link dropdown-toggle" href="#" id="alertsDropdown" role="button"
                data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                <i class="fas fa-bell fa-fw"></i>
                <!-- Counter - Alerts -->
                <span class="badge badge-danger badge-counter">0</span>
            </a>
            <!-- Dropdown - Alerts -->
            {{-- <div class="dropdown-list dropdown-menu dropdown-menu-right shadow animated--grow-in"
                aria-labelledby="alertsDropdown">
                <h6 class="dropdown-header">
                    Alerts Center
                </h6>
                <a class="dropdown-item d-flex align-items-center" href="#">
                    <div class="mr-3">
                        <div class="icon-circle bg-primary">
                            <i class="fas fa-file-alt text-white"></i>
                        </div>
                    </div>
                    <div>
                        <div class="small text-gray-500">December 12, 2019</div>
                        <span class="font-weight-bold">A new monthly report is ready to download!</span>
                    </div>
                </a>
                <a class="dropdown-item d-flex align-items-center" href="#">
                    <div class="mr-3">
                        <div class="icon-circle bg-success">
                            <i class="fas fa-donate text-white"></i>
                        </div>
                    </div>
                    <div>
                        <div class="small text-gray-500">December 7, 2019</div>
                        $290.29 has been deposited into your account!
                    </div>
                </a>
                <a class="dropdown-item d-flex align-items-center" href="#">
                    <div class="mr-3">
                        <div class="icon-circle bg-warning">
                            <i class="fas fa-exclamation-triangle text-white"></i>
                        </div>
                    </div>
                    <div>
                        <div class="small text-gray-500">December 2, 2019</div>
                        Spending Alert: We've noticed unusually high spending for your account.
                    </div>
                </a>
                <a class="dropdown-item text-center small text-gray-500" href="#">Show All Alerts</a>
            </div> --}}
        </li>
    
        <div class="topbar-divider d-none d-sm-block"></div>
    
        <!-- Nav Item - User Information -->
        <li class="nav-item dropdown no-arrow">
            <a class="nav-link dropdown-toggle" href="#" id="userDropdown" role="button"
                data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                <span class="mr-2 d-none d-lg-inline text-gray-600 small">{{auth()->user()->first_name}} {{auth()->user()->last_name}}</span>
                <img class="img-profile rounded-circle"
                    src="{{asset('images/default.jpg')}}">
            </a>
            <!-- Dropdown - User Information -->
            <div class="dropdown-menu dropdown-menu-right shadow animated--grow-in"
                aria-labelledby="userDropdown">
                {{-- <a class="dropdown-item" href="{{route('view_profile')}}">
                    <i class="fas fa-user fa-sm fa-fw mr-2 text-gray-400"></i>
                    Profile
                </a> --}}
                <a class="dropdown-item" href="#" data-toggle="modal" data-target="#changePasswordModal">
                    <i class="fas fa-key fa-sm fa-fw mr-2 text-gray-400"></i>
                    Change Password
                </a>
                <div class="dropdown-divider"></div>
                <!-- item-->
                <a href="{{ route('logout') }}" onclick="event.preventDefault(); document.getElementById('logout-form').submit();" class="dropdown-item notify-item">
                    <i class="fas fa-sign-out-alt fa-sm fa-fw mr-2 text-gray-400"></i>
                    <span>Logout</span>
                </a>
                <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                    @csrf
                </form>
            </div>
        </li>
    
    </ul>
    
    </nav>

    <!-- Change Password Modal -->
    <div class="modal fade" id="changePasswordModal" tabindex="-1" role="dialog" aria-labelledby="changePasswordModalLabel" aria-hidden="true" style="z-index: 9999;">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content border-0 shadow-lg" style="border-radius: 16px; overflow: hidden;">
                <div class="modal-header bg-primary text-white p-4">
                    <h5 class="modal-title font-weight-bold" id="changePasswordModalLabel">
                        <i class="fas fa-key mr-2"></i>Change Password
                    </h5>
                    <button class="close text-white" type="button" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form action="{{ route('change_password') }}" method="POST">
                    @csrf
                    <div class="modal-body p-4">
                        <div class="text-center mb-4">
                            <div class="bg-primary-soft rounded-circle d-inline-flex align-items-center justify-content-center mb-3"
                                style="width: 60px; height: 60px; background: rgba(78, 115, 223, 0.1);">
                                <i class="fas fa-shield-alt text-primary fa-2x"></i>
                            </div>
                            <h6 class="font-weight-bold text-gray-800">Update Your Security Password</h6>
                            <p class="text-muted small">Choose a strong password containing at least 8 characters.</p>
                        </div>

                        <div class="form-group mb-3">
                            <label class="text-gray-700 font-weight-bold small">Current Password</label>
                            <input name="current_password" type="password" class="form-control"
                                style="border-radius: 10px; padding: 12px 16px;" placeholder="Enter current password" required />
                        </div>

                        <div class="form-group mb-3">
                            <label class="text-gray-700 font-weight-bold small">New Password</label>
                            <input name="password" type="password" class="form-control"
                                style="border-radius: 10px; padding: 12px 16px;" placeholder="Minimum 8 characters" required minlength="8" />
                        </div>

                        <div class="form-group mb-3">
                            <label class="text-gray-700 font-weight-bold small">Confirm New Password</label>
                            <input name="password_confirmation" type="password" class="form-control"
                                style="border-radius: 10px; padding: 12px 16px;" placeholder="Repeat new password" required minlength="8" />
                        </div>
                    </div>
                    <div class="modal-footer border-0 p-4 pt-0">
                        <button class="btn btn-light px-4" style="border-radius: 10px; font-weight: 600;" type="button" data-dismiss="modal">Cancel</button>
                        <button class="btn btn-primary px-4 font-weight-bold shadow-sm" style="border-radius: 10px;" type="submit">Update Password</button>
                    </div>
                </form>
            </div>
        </div>
    </div>