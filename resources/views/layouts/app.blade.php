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

@yield('scripts')

</body>

</html>