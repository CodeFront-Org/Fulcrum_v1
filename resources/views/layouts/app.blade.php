<!--Header Sections-->

<x-header :label='$label' />

<!--End Header Section -->

@yield('css')
@yield('modals')
    <!-- Begin page -->

<body id="page-top" class="sidebar-toggled"">

    <!-- Page Wrapper -->
    <div id="wrapper">

        <!-- ========== Left Sidebar Start ========== -->
                <x-leftnav />
        <!-- Left Sidebar End -->

        <!-- Content Wrapper -->
        <div id="content-wrapper" class="d-flex flex-column">

            <!-- Main Content -->
            <div id="content">
                <!-- Topbar Start -->
        
                    <x-topnav :label='$label'  />
                <!-- end Topbar -->
            <div style="margin-top:-20px">
            </div>

                <!-- Begin Page Content -->
                <div class="container-fluid">

                    <!-- Page Heading -->
                    <div class="d-sm-flex align-items-center justify-content-between mb-2">
                        <h1 class="h3 mb-0 text-gray-800">{{$label}}</h1>
                        <!-- <a href="#" class="d-none d-sm-inline-block btn btn-sm btn-primary shadow-sm"><i
                                class="fas fa-download fa-sm text-white-50"></i> Generate Report</a> -->
                    </div>

                    <!-- Content Row -->
                    <!-- Start Content-->
                    @yield('content')


                </div>
                <!-- /.container-fluid -->
            </div>
            <!-- End of Main Content -->

        <!-- Footer Start -->
        <x-footer />
        <!-- end Footer -->


    </div>
    <!-- End of Content Wrapper -->

</div>
<!-- End of Page Wrapper -->

<!-- Scroll to Top Button-->
<a class="scroll-to-top rounded" href="#page-top">
    <i class="fas fa-angle-up"></i>
</a>


        <!-- Vendor -->
        <script src="{{asset('libs/jquery/jquery.min.js')}}"></script>
        <script src="{{asset('libs/bootstrap/js/bootstrap.bundle.min.js')}}"></script>
        <script src="{{asset('libs/simplebar/simplebar.min.js')}}"></script>
        <script src="{{asset('libs/node-waves/waves.min.js')}}"></script>
        <script src="{{asset('libs/waypoints/lib/jquery.waypoints.min.js')}}"></script>
        <script src="{{asset('libs/jquery.counterup/jquery.counterup.min.js')}}"></script>
        <script src="{{asset('libs/feather-icons/feather.min.js')}}"></script>


        <!-- Plugin js-->
        <script src="{{asset('libs/parsleyjs/parsley.min.js')}}"></script>

        <!-- Validation init js-->
        <script src="{{asset('js/pages/form-validation.init.js')}}"></script>

        <!-- App js -->
        <script src="{{asset('js/app.min.js')}}"></script>

            <!-- App js -->
            <script src="{{asset('js/app.min.js')}}"></script>

<!-- Bootstrap core JavaScript-->
<script src="{{asset('vendor/jquery/jquery.min.js')}}"></script>
<script src="{{asset('vendor/bootstrap/js/bootstrap.bundle.min.js')}}"></script>

<!-- Page level plugins -->
<script src="{{asset('vendor/datatables/jquery.dataTables.min.js')}}"></script>
<script src="{{asset('vendor/datatables/dataTables.bootstrap4.min.js')}}"></script>

<!-- Page level custom scripts -->
<script src="{{asset('js/demo/datatables-demo.js')}}"></script>

<!-- Core plugin JavaScript-->
<script src="{{asset('vendor/jquery-easing/jquery.easing.min.js')}}"></script>

<!-- Custom scripts for all pages-->
<script src="{{asset('js/sb-admin-2.min.js')}}"></script>

<!-- Page level plugins -->
<script src="{{asset('vendor/chart.js/Chart.min.js')}}"></script>

<!-- Page level custom scripts -->
<script src="{{asset('js/demo/chart-area-demo.js')}}"></script>
<script src="{{asset('js/demo/chart-pie-demo.js')}}"></script>


<script>
    document.addEventListener('DOMContentLoaded', function () {
        // Toggle sidebar visibility on button click for mobile screens only
        document.getElementById('sidebarToggleTop').addEventListener('click', function () {
            if (window.innerWidth < 768) { // Only run if screen is mobile
                var sidebar = document.getElementById('accordionSidebar');
                sidebar.classList.toggle('show-sidebar');
            }
        });

        // Hide sidebar when clicking outside of it on mobile only
        document.addEventListener('click', function (event) {
            var sidebar = document.getElementById('accordionSidebar');
            var toggleButton = document.getElementById('sidebarToggleTop');


            if (window.innerWidth < 768) { // Only run if screen is mobile
                // If click is outside the sidebar and the toggle button, hide the sidebar
                if (!sidebar.contains(event.target) && !toggleButton.contains(event.target)) {
                    sidebar.classList.remove('show-sidebar');
                }
            }
        });
    });
</script>

        <!-- Sweet Alerts js -->
        <script src="{{asset('libs/sweetalert2/sweetalert2.all.min.js')}}"></script>

        <!-- Sweet alert init js-->
        <script src="{{asset('js/pages/sweet-alerts.init.js')}}"></script>
        
        <!-- Select2 JS -->
        <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

    <!-- Toastr js -->
    <script src="{{asset('libs/toastr/build/toastr.min.js')}}"></script>

    <script src="{{asset('js/pages/toastr.init.js')}}"></script>

    @if (session()->has('message'))
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                toastr.success("{{ session('message') }}");
            });
        </script>
    @endif
    @if (session()->has('error'))
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                toastr.error("{{ session('error') }}");
            });
        </script>
    @endif

    @if (Auth::check() && Auth::user()->updated_psw == 1)
        <!-- Force Change Password Modal -->
        <div class="modal fade" id="forceChangePasswordModal" tabindex="-1" role="dialog" aria-labelledby="forceChangePasswordModalLabel" aria-hidden="true" style="z-index: 99999;">
            <div class="modal-dialog modal-dialog-centered" role="document">
                <div class="modal-content border-0 shadow-lg" style="border-radius: 16px; overflow: hidden;">
                    <div class="modal-header bg-danger text-white p-4">
                        <h5 class="modal-title font-weight-bold" id="forceChangePasswordModalLabel">
                            <i class="fas fa-exclamation-triangle mr-2"></i>Change Temporary Password
                        </h5>
                    </div>
                    <form action="{{ route('change_password') }}" method="POST">
                        @csrf
                        <div class="modal-body p-4">
                            <div class="text-center mb-4">
                                <div class="bg-danger-soft rounded-circle d-inline-flex align-items-center justify-content-center mb-3"
                                    style="width: 60px; height: 60px; background: rgba(220, 53, 69, 0.1);">
                                    <i class="fas fa-lock text-danger fa-2x"></i>
                                </div>
                                <h6 class="font-weight-bold text-gray-800">Password Change Required</h6>
                                <p class="text-muted small">You are logged in with a temporary password. You must set a new security passcode to continue.</p>
                            </div>

                            <div class="form-group mb-3">
                                <label class="text-gray-700 font-weight-bold small">Temporary Password (Current)</label>
                                <input name="current_password" type="password" class="form-control"
                                    style="border-radius: 10px; padding: 12px 16px;" placeholder="Enter temporary password" required />
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
                        <div class="modal-footer border-0 p-4 pt-0 text-center justify-content-center">
                            <button class="btn btn-danger btn-block py-3 font-weight-bold shadow-sm" style="border-radius: 10px;" type="submit">Update & Activate Account</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                $('#forceChangePasswordModal').modal({
                    backdrop: 'static',
                    keyboard: false
                });
                $('#forceChangePasswordModal').modal('show');
            });
        </script>
    @endif

@yield('scripts')

</body>

</html>