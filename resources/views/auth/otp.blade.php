<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <title>OTP Verification | Fulcrum LTD</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta content="" name="description" />
    <meta content="Martin Njoroge" name="author" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <!-- csrf-->
    <meta name="csrf-token" content="{{csrf_token()}}" />
    <!-- App favicon -->
    <link rel="shortcut icon" href="{{asset('images/Logo/s-dark.png')}}">

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
</head>

<body class="authentication-bg1">
    <div class="account-pages mt-2 mb-2">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-md-8 col-lg-6 col-xl-4">
                    <div class="text-center">
                        <img src="assets/images/Logo/s-no bg.png" alt="" width="50" class="mx-auto">

                        <p class="mt-2 font-16 mb-1">Fulcrum LTD</p>
                    </div>
                    <div class="card" style="box-shadow: 0 2px 4px rgba(0, 0, 0, 0.4);border-radius: 10px;">
                        <div class="card-body p-lg-3">
                            <div class="text-center mb-4">
                                <h4 class="text-uppercase mt-0">
                                    <svg style="margin-right: 5px" xmlns="http://www.w3.org/2000/svg" width="19" height="19" fill="currentColor" class="bi bi-lock-fill" viewBox="0 0 16 16">
                      <path
                        d="M8 1a2 2 0 0 1 2 2v4H6V3a2 2 0 0 1 2-2zm3 6V3a3 3 0 0 0-6 0v4a2 2 0 0 0-2 2v5a2 2 0 0 0 2 2h6a2 2 0 0 0 2-2V9a2 2 0 0 0-2-2z"
                      />
                    </svg>
                                </h4>
                                <h2>Verification</h2>
                                <p>We Have Sent A Verification Code To Your Phone. <b>Expires in 5 Minutes</b></p>
                            </div>

                            <form id="verifyForm">
                                <div class="form-group row">
                                    <div class="col-md-12">
                                        <div class="input-group">
                                            <h4 class="main_question" style="color: #777; font-weight: 200">
                                                <strong>Enter the code below</strong>
                                            </h4>
                                            <div style="
                            display: flex;
                            flex-direction: row;
                            flex-wrap: wrap;
                          ">
                          <div style="display: flex;">
                            <input id="code-input-1" class="code-input form-control" name="code" required style="width: 50px; margin-right: 5px;" />
                            <input id="code-input-2" class="code-input form-control" name="code" required style="width: 50px; margin-right: 5px;" />
                            <input id="code-input-3" class="code-input form-control" name="code" required style="width: 50px; margin-right: 5px;" />
                            <input id="code-input-4" class="code-input form-control" name="code" required style="width: 50px; margin-right: 5px;" />
                            <input id="code-input-5" class="code-input form-control" name="code" required style="width: 50px; margin-right: 5px;" />
                        </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="form-group mb-0 text-center">

                                    <button class="btn rounded-pill p-1 mt-3" id="btn_save" style="width: 100%; background-color: #0009b0e2;color: white;" type="submit">

                                                        Verify OTP
                                                    </button>
                                    <button class="btn rounded-pill p-1 mt-3" id="loader" style="width: 100%; background-color: #0009b0e2;color: white;display:none;" type="button">
                                                        <span class="spinner-border spinner-border-sm me-1" role="status" aria-hidden="true"></span>
                                                        Verifying otp...
                                                    </button>
                                </div>
                            </form>

                            <button class="btn rounded-pill p-1 mt-3" id="loader1" style="width: 100%; background-color: #0009b0e2;color: white;display:none;" type="button">
                                                        <span class="spinner-border spinner-border-sm me-1" role="status" aria-hidden="true"></span>
                                                        Sending OTP...
                                                    </button>
                            {{-- <div class="mt-2" style="text-align: center">
                                <a id="resendCodebtn" href="#" style="color: #0009b0e2"><b> RESEND CODE</b></a
                  >
                </div> --}}
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

        <!-- App js -->
        <script src="{{asset('js/app.min.js')}}"></script>

    <!-- Sweet Alerts js -->
    <script src="{{asset('libs/sweetalert2/sweetalert2.all.min.js')}}"></script>

    <!-- Sweet alert init js-->
    <script src="{{asset('js/pages/sweet-alerts.init.js')}}"></script>


    <script>
        // Function to detect if the device is mobile
        function isMobileDevice() {
            return /Mobi|Android/i.test(navigator.userAgent);
        }
    
        // Get all input fields
        const inputs = document.querySelectorAll('.code-input');
    
        // Change input type based on device
        inputs.forEach(input => {
            if (isMobileDevice()) {
                input.setAttribute('type', 'number');
            } else {
                input.setAttribute('type', 'text');
                input.setAttribute('pattern', '^[0-9]\\d*(\\.\\d+)?$');
                input.setAttribute('title', 'Please enter a valid positive number');
            }
        });
    </script>


    <script>

//Resend OTP
$("#resendCodebtn").on('click',(e)=>{
e.preventDefault();
var btn=$("#resendCodebtn");
var btn2 = $("#btn_save");
var btn1=$("#loader1");
btn.hide();
btn2.hide();
btn1.show();
var email=localStorage.getItem('otp_email_fltd');
$.ajax({
  type: 'POST',
  url: '{{route('send-otp')}}',
  data: {_token:"{{csrf_token()}}",email },
  success: function(response) {
    // Handle success response
                            Swal.fire({
                                position: "top-end",
                                icon: "success",
                                title: "OTP Sent",
                                showConfirmButton: !1,
                                timer: 2000
                            });
btn.show();
btn2.show();
btn1.hide();
  },
  error: function(response) {
    // Handle error response
    console.error('Error sending SMS:', response.statusText);
  }
});

})

      const inputElements = [...document.querySelectorAll("input.code-input")];

      inputElements.forEach((ele, index) => {
        ele.addEventListener("keydown", (e) => {
          if (e.keyCode === 8 && e.target.value === "")
            inputElements[Math.max(0, index - 1)].focus();
        });
        ele.addEventListener("input", (e) => {
          const [first, ...rest] = e.target.value;
          e.target.value = first ?? "";
          if (index !== inputElements.length - 1 && first !== undefined) {
            inputElements[index + 1].focus();
            inputElements[index + 1].value = rest.join("");
            inputElements[index + 1].dispatchEvent(new Event("input"));
          }
        });
      });


            $('form').submit(function(event) {
                event.preventDefault();
                        var loader = $("#loader")
                        var btn = $("#btn_save");
                        btn.hide();
                        loader.show();

      // Get the values from the input boxes
      var values = [];
      $('.code-input').each(function() {
        values.push($(this).val());
      });

    var otp=values.join('');
var otp1=localStorage.getItem('mobile');
var email=localStorage.getItem('otp_email_fltd');
var mobile=localStorage.getItem('mobile');
$.ajax({
    type: "POST",
    url: "{{route('otp/verify')}}",
    data: {_token:"{{csrf_token()}}",otp,email,mobile},
    success: function (res) { 
        if(res==1){// otp match
            Swal.fire({
                position: "top-end",
                icon: "success",
                title: "Verified",
                showConfirmButton: !1,
                timer: 3000
            }); // 3 seconds
            setTimeout(() => {
                window.location.href = "{{route('dashboard')}}";
            }, 2000);
            btn.show();
            loader.hide();
        }else if(res==2){//otp mismatch
            Swal.fire({
                icon: "error",
                title: "Incorrect OTP",
                text: ""
            })
            btn.show();
            loader.hide();
        }else if(res==3){//otp has expired
            Swal.fire({
                icon: "error",
                title: "OTP has Expired",
                text: " Please click Resend Button."
            })
            btn.show();
            loader.hide();
        }
    },
    error: function(res){ console.log(res)
            Swal.fire({
                icon: "error",
                title: "Error",
                text: res
            })

            btn.show();
            loader.hide();
    }
});

      // Output the values to the console
     // console.log(values.join(''));
})
    </script>
  </body>
</html>