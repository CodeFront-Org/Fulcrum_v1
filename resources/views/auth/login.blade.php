<!DOCTYPE html>
<html lang="zxx">

<!-- Head -->

<head>

    <title>Fulcrum Link LTD</title>

    <!-- Meta-Tags -->
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <!-- App css -->
    <link href="{{asset('css/bootstrap.min.css')}}" rel="stylesheet" type="text/css" id="bs-default-stylesheet" />
    <link href="{{asset('css/app.min.css')}}" rel="stylesheet" type="text/css" id="app-default-stylesheet" />
    <!-- style CSS -->
    <link rel="stylesheet" href="{{asset('css/style.css')}}" type="text/css" media="all">
    <link rel="icon" href="{{asset('images/logo.jpeg')}}" type="image/x-icon">

    <!-- fontawesome -->
    <link rel="stylesheet" href="{{asset('css/font-awesome.min.css')}}" type="text/css" media="all">

    <!-- google fonts -->
    <link href="//fonts.googleapis.com/css?family=Mukta:300,400,500" rel="stylesheet">

        <!-- Notification css (Toastr) -->
        <link href="{{asset('libs/toastr/build/toastr.min.css')}}" rel="stylesheet" type="text/css" />

        <!-- Sweet Alert-->
        <link href="{{asset('libs/sweetalert2/sweetalert2.min.css')}}" rel="stylesheet" type="text/css" />
        <script>
            (function(window, location) {
                history.replaceState(null, document.title, location.pathname+"#!/stealth");
                history.pushState(null, document.title, location.pathname);
        
                window.addEventListener("popstate", function() {
                    if(location.hash === "#!/stealth") {
                        history.replaceState(null, document.title, location.pathname);
                        setTimeout(function(){
                            location.replace("/");  // Redirect to the login or home page if they try to go back
                        }, 0);
                    }
                }, false);
            }(window, location));
        </script>

        <style>
            @media only screen and (min-width: 1024px) {
    /* Styles for desktop only */
    .logForm {
        /* Desktop-specific styles */
        left: -14%;
        position: relative
    }
}
        </style>
        
        

</head>
<!-- //Head -->

<!-- Body -->

<body>

    <section class="main">
        <div class="bottom-grid">
            <div class="logo">
                <h1> <a href="index.html"> <img src="./images/logo.jpeg" width="120" alt="Company Logo"></a></h1>
            </div>
        </div>
        <!-- register -->
    <div style="position: relative; width:100%" class="logForm">
        <div class="w3lhny-register w-160" style="justify-content: center;align-items: center;align-content: center;" >
            <div class="iconhny">
               <span class="fa fa-lock"></span>
             </div>
             <form  id="loginForm" method="post" class="register-form mt-4" autocomplete="off">
                <fieldset>
                    <div class="form">
                        <div class="form-row">
                            <span class="fa fa-phone"></span>
                            <input type="text" id="mobile" class="form-text" name="mobile" autocomplete="off" placeholder="Mobile / Email address" required>
                        </div>
                        <div class="form-row">
                            <span class="fa fa-lock"></span>
                            <input type="password" class="form-text" id="password1" name="psw-1" autocomplete="new-password" placeholder="Passcode" required>
                        </div>
                        <div class="form-row button-login">
                            <button class="btn btn-login" id="btn_save">Sign In</button>
                            <button class="btn rounded-pill p-2 btn-login" id="loader" style="display: none" type="button">
                                <span class="spinner-border spinner-border-sm me-1" role="status" aria-hidden="true"></span>
                                Authenticating...
                            </button>
                        </div>
                    </div>
                </fieldset>
            </form>
            <div style="margin-top: 40px;">
                <p class="already">Forgot Password <a href="/forgot-password">Click here</a></p>
            </div>
        </div>
    </div>
        <!-- //register -->

        <div class="footer1">
            <p>© Fulcrum Link Limited
            </p>
        </div>
    </section>
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
                        var contacts=$("#mobile").val();
                        var psw=$("#password1").val();
                        var password=psw;
                        var loader = $("#loader")
        
                        var btn = $("#btn_save");
                        btn.hide();
                        loader.show();
        
                $.ajax({
                    type: 'POST',
                    url: '{{route('/app/login')}}',
                    data:{
                        _token:"{{csrf_token()}}", contacts,password
                    },
                    success:function(response)
                    { //console.log(response.msg) 

                        if(response.msg==='otp_sent'){
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
                            toastr["success"]("", "An OTP has been sent to your device. You will be redirected shortly.")

                            localStorage.setItem('otp_email_fltd',contacts)

                            setTimeout(() => {
                                window.location.href='/otp'
                                
                            }, 2000);


                        }

                        btn.show();
                        loader.hide();
                    },
                    error: function(response) { console.log(response)
                    if(response.status==419){
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
        
                        toastr["error"]("", "Session has expired. Refreshing Page.")
                        window.location.href='/login'
                    }else{
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
                        toastr["error"]("", response.responseJSON.msg)
                    }
                        btn.show();
                        loader.hide();
                    }
                });
        
                    })
        
                $("#resetForm").on('submit',(event)=>{
                event.preventDefault();
                var resetbtn = $("#resetbtn")
                var loaderMail = $("#loaderMail")
                resetbtn.hide()
                loaderMail.show()
                var email1=$("#resetemail").val();
        $.ajax({
            type: "POST",
            url: "#",
            data: {_token:"{{csrf_token()}}",email1},
            success: function (res) {
                if(res==1){
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
                           toastr["success"]("", "An Email has been sent successfully")
                        resetbtn.show()
                        loaderMail.hide()
                        $('#emailtext').text(email1);
                        $('#myModal').modal('show')
                        $('#con-close-modal-changepsw').modal('hide')
        
                }else if(res==2){
                    resetbtn.show()
                    loaderMail.hide()
                    Swal.fire({
                        icon: "error",
                        title: "Error",
                        text: "Email provided does not match our records."
                    })
                }
            },
            error: function(res){
                resetbtn.show()
                loaderMail.hide()
                if(res.status==419){
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
        
                        toastr["error"]("", "Session has expired. Refreshing Page.")
                        window.location.href='/login'
                }else{
        
                    Swal.fire({
                        icon: "error",
                        title: "Error",
                        text: "Mail not sent. Please Check your connection and try again"
                    })}
            }
        });
                })
        
                })
            </script>


<script>
    if (window.history && window.history.pushState) {
        window.history.pushState(null, null, window.location.href);
        window.onpopstate = function () {
            window.history.pushState(null, null, window.location.href);
        };
    }
</script>

<script>
    (function(window, location) {
        history.replaceState(null, document.title, location.pathname+"#!/stealth");
        history.pushState(null, document.title, location.pathname);

        window.addEventListener("popstate", function() {
            if(location.hash === "#!/stealth") {
                history.replaceState(null, document.title, location.pathname);
                setTimeout(function(){
                    location.replace("/");  // Redirect to the login or home page if they try to go back
                }, 0);
            }
        }, false);
    }(window, location));
</script>




</body>
<!-- //Body -->

</html>