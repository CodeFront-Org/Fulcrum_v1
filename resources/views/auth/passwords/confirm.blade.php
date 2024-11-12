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

    <script>
    window.onload = function() {
        var email = localStorage.getItem('fulcrum-confirm-mail');
        // Check if email exists in local storage
        if (email) {
            document.getElementById('emailDisplay').textContent = email;
        } else {
            document.getElementById('emailDisplay').textContent = '';
        }
    };
    </script>

</head>

<body class="loading authentication-bg1 authentication-bg-pattern">

    <div class="account-pages mt-3 mb-1">
        <div class="container">

            <div class="row justify-content-center">
                <div class="col-md-8 col-lg-6 col-xl-4">
                    <div class="text-center">
                        <a href="index.html">
                            <img src="assets/images/logo-dark.png" alt="" height="22" class="mx-auto">
                        </a>
                        <p class="text-muted mt-2 mb-4">Fulcrum LTD</p>
                    </div>
                    <div class="card text-center">

                        <div class="card-body p-4">
                            
                            <div class="mb-4">
                                <h4 class="text-uppercase mt-0">Confirm Email</h4>
                            </div>
                            <img src="{{asset('images/mail/mail_confirm.png')}}" alt="img" width="86" class="mx-auto d-block" />

                            <p class="text-muted font-14 mt-2"> An email has been sent to <b id="emailDisplay"></b>.
                                Please check for an email from the company and click on the included link to
                                reset your password. </p>

                            <a href="/" style="background-color: #0009b0e2;color:white" class="btn rounded-3 d-block waves-effect waves-light mt-3">Back to Home</a>

                        </div> <!-- end card-body -->
                    </div>
                    <!-- end card -->

                </div> <!-- end col -->
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
                var psw=$("#newpsw").val();
                var conpsw=$("#conpsw").val();
                var token1=$("#token").val();
                var id=$("#userId").val();
                var loader = $("#loader")
                var btn = $("#btn_save");
                btn.hide();
                loader.show();
    

if(psw==conpsw){

        $.ajax({
            type: 'POST',
            url: '{{route('reset-psw')}}',
                data: {
                    _token:"{{csrf_token()}}",
                    psw: psw,
                    psw_confirmation: conpsw,  // Include the confirmation field
                    id: id
                },

            success:function(response)
            {console.log(response)
if(response==3){
    Swal.fire({
        icon: "error",
        title: "Error",
        text: "Your token has expired. Please Resend token."
    })
}else if(response==1){
    Command: toastr["success"]("", "Password Updated Successfully. Redirecting...")
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
               window.location.href='/dashboard'
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
}else{
    Swal.fire({
        icon: "error",
        title: "Error",
        text: "Password should match"
    })
                btn.show();
                loader.hide();
}



            })

        $("#resetForm").on('submit',(event)=>{
        event.preventDefault();
        var email1=$("#resetemail").val();
$.ajax({
    type: "POST",
    url: "{{route('reset')}}",
    data: {_token:"{{csrf_token()}}",email1},
    success: function (response) {
        console.log(response)
    },
    error: function(res){
        console.log(res)
    }
});
        })

        })
    </script>
</body>

</html>