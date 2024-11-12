<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <title>Reset Password | Fulcrum LTD</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta content="" name="description" />
    <meta content="Martin Njoroge" name="author" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <!-- App favicon -->
    <link rel="shortcut icon" href="{{asset('images/Logo/s-dark.png')}}">
    <!-- Notification css (Toastr) -->
    <link href="{{asset('libs/toastr/build/toastr.min.css')}}" rel="stylesheet" type="text/css" />

    <!-- Sweet Alert-->
    <link href="{{asset('libs/sweetalert2/sweetalert2.min.css')}}" rel="stylesheet" type="text/css" />

    <!-- App css -->
    <link href="{{asset('css/bootstrap.min.css')}}" rel="stylesheet" type="text/css" id="bs-default-stylesheet" />
    <link href="{{asset('css/app.min.css')}}" rel="stylesheet" type="text/css" id="app-default-stylesheet" />

    <!-- App-dark css -->
    <link href="{{asset('css/bootstrap-dark.min.css')}}" rel="stylesheet" type="text/css" id="bs-dark-stylesheet" disabled="disabled" />
    <link href="{{asset('css/app-dark.min.css')}}" rel="stylesheet" type="text/css" id="app-dark-stylesheet" disabled="disabled" />

    <!-- icons -->
    <link href="{{asset('css/icons.min.css')}}" rel="stylesheet" type="text/css" />
    <link href="assets/css/icons.min.css" rel="stylesheet" type="text/css" />

</head>

<body class="loading authentication-bg1 authentication-bg-pattern">

    <div class="account-pages mt-3 mb-1">
        <div class="container">

            <div class="row justify-content-center">
                <div class="col-md-8 col-lg-6 col-xl-4">
                    <div class="text-center">
                        <div class="bottom-grid">
                            <div class="logo">
                                <h1> <a href="index.html"> <img src="{{asset('images/logo.jpeg')}}" width="120" alt="Company Logo"></a></h1>
                            </div>
                            <p class="text-muted mt-0 mb-1">Enter your registered email to proceed</p>
                        </div>

                    </div>
                    <div class="card" style="box-shadow: 0 2px 4px rgba(0, 0, 0, 0.4);border-radius: 10px;">
                        <div class="card-body p-4">

                            <form id="loginForm" class="parsley-examples">
                                <div class="mb-3">
                                    <label for="password" class="form-label">Email</label>

                                    <div class="1">
                                        <input type="email"  name="email" id="newpsw123" required class="form-control" autocomplete="off" placeholder="Enter a valid email address">
                                     
                                    </div>
                                </div>
                                <div class="mb-2 d-grid text-center">
                                    <button class="btn rounded-3 p-1" id="btn_save" style="background-color: #0009b0e2;color: white;" type="submit">

                                                        Submit
                                                    </button>
                                    <button class="btn rounded-3 p-1" id="loader" style="background-color: #0009b0e2;color: white;display:none;" type="button">
                                                        <span class="spinner-border spinner-border-sm me-1" role="status" aria-hidden="true"></span>
                                                        Please wait...
                                                    </button>
                                </div>
                            </form>

                        </div>
                        <!-- end card-body -->
                    </div>
                    <!-- end card -->

                    <!-- end row -->

                </div>
                <!-- end col -->
            </div>
            <!-- end row -->
        </div>
        <!-- end container -->

    </div>
    <!-- end page -->

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

    <!-- Sweet Alerts js -->
    <script src="{{asset('libs/sweetalert2/sweetalert2.all.min.js')}}"></script>

    <!-- Sweet alert init js-->
    <script src="{{asset('js/pages/sweet-alerts.init.js')}}"></script>


    <!-- Toastr js -->
    <script src="{{asset('libs/toastr/build/toastr.min.js')}}"></script>

    <script src="{{asset('js/pages/toastr.init.js')}}"></script>

    <script>
        // Get the form element
        $(document).ready(function() {
            $('#loginForm').on('submit', (e) => {
                e.preventDefault();
                var email=$("#newpsw123").val();
                var loader = $("#loader")
                var btn = $("#btn_save");
                btn.hide();
                loader.show();


        $.ajax({
            type: 'POST',
            url: '{{route('forgot_psw')}}',
                data: {
                    _token:"{{csrf_token()}}",
                    email1: email,
                },

            success:function(response)
            {
                console.log(response); 

                localStorage.setItem('fulcrum-confirm-mail',email);

                if(response==3){
                    Swal.fire({
                        icon: "error",
                        title: "Error",
                        text: "Your token has expired. Please Resend token."
                    })
                }else{
                    Command: toastr["success"]("", "Reset Link Sent Successfully. Redirecting...")
                    toastr.options = {
                        "closeButton": false,
                        "debug": false,
                        "newestOnTop": false,
                        "progressBar": true,
                        "positionClass": "toast-top-center",
                        "preventDuplicates": false,
                        "onclick": null,
                        "showDuration": "300",
                        "hideDuration": "1000",
                        "timeOut": "5000",
                        "extendedTimeOut": "1000",
                        "showEasing": "swing",
                        "hideEasing": "linear",
                        "showMethod": "fadeIn",
                        "hideMethod": "fadeOut"
                    }
                }

                btn.show();
                loader.hide();
               window.location.href='/confirmation-email'
            },
            error: function(response) { console.log(response)
            if(response.status==419){
                Command: toastr["error"]("", "Session has expired. Please Refresh and try again.")
                toastr.options = {
                    "closeButton": false,
                    "debug": false,
                    "newestOnTop": false,
                    "progressBar": true,
                    "positionClass": "toast-top-center",
                    "preventDuplicates": false,
                    "onclick": null,
                    "showDuration": "300",
                    "hideDuration": "1000",
                    "timeOut": "5000",
                    "extendedTimeOut": "1000",
                    "showEasing": "swing",
                    "hideEasing": "linear",
                    "showMethod": "fadeIn",
                    "hideMethod": "fadeOut"
                }
                window.location.href='/forgot-password'
            }else{
                Command: toastr["error"]("", "Incorrect Details.")
                toastr.options = {
                    "closeButton": false,
                    "debug": false,
                    "newestOnTop": false,
                    "progressBar": true,
                    "positionClass": "toast-top-center",
                    "preventDuplicates": false,
                    "onclick": null,
                    "showDuration": "300",
                    "hideDuration": "1000",
                    "timeOut": "5000",
                    "extendedTimeOut": "1000",
                    "showEasing": "swing",
                    "hideEasing": "linear",
                    "showMethod": "fadeIn",
                    "hideMethod": "fadeOut"
                }
            }
                btn.show();
                loader.hide();
            }
        });




            })

        })
    </script>
</body>

</html>