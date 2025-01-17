<?php
  session_start();
    if(isset($_SESSION['user'])){
        header('Location:dashboard.php');
    }
?>



<!DOCTYPE html>
<html lang="en" dir="ltr">

<?php include_once('controls/meta.php'); ?>

<body class="layout-login-centered-boxed">

    <div class="layout-login-centered-boxed__form">
        <div class="d-flex flex-column justify-content-center align-items-center mt-2 mb-2 navbar-light">
            <a href="index.html" class="navbar-brand text-center mb-2 mr-0" style="min-width: 0">
                <img class="navbar-brand-icon" src="assets/img/Smart-Salesman.png" style="width: 100%;" alt="Smart Salesman Logo">
                <span></span>
            </a>
        </div>

        <div class="card card-body">


            <!-- <div class="alert alert-soft-success d-flex" role="alert">
                <i class="material-icons mr-3">check_circle</i>
                <div class="text-body">An email with password reset instructions has been sent to your email address, if it exists on our system.</div>
            </div>

            <a href="" class="btn btn-light btn-block">
                <span class="mr-2">
                    <svg version="1.1" xmlns="http://www.w3.org/2000/svg" width="18px" height="18px" viewBox="0 0 48 48" class="abcRioButtonSvg">
                        <g>
                            <path fill="#EA4335" d="M24 9.5c3.54 0 6.71 1.22 9.21 3.6l6.85-6.85C35.9 2.38 30.47 0 24 0 14.62 0 6.51 5.38 2.56 13.22l7.98 6.19C12.43 13.72 17.74 9.5 24 9.5z"></path>
                            <path fill="#4285F4" d="M46.98 24.55c0-1.57-.15-3.09-.38-4.55H24v9.02h12.94c-.58 2.96-2.26 5.48-4.78 7.18l7.73 6c4.51-4.18 7.09-10.36 7.09-17.65z"></path>
                            <path fill="#FBBC05" d="M10.53 28.59c-.48-1.45-.76-2.99-.76-4.59s.27-3.14.76-4.59l-7.98-6.19C.92 16.46 0 20.12 0 24c0 3.88.92 7.54 2.56 10.78l7.97-6.19z"></path>
                            <path fill="#34A853" d="M24 48c6.48 0 11.93-2.13 15.89-5.81l-7.73-6c-2.15 1.45-4.92 2.3-8.16 2.3-6.26 0-11.57-4.22-13.47-9.91l-7.98 6.19C6.51 42.62 14.62 48 24 48z"></path>
                            <path fill="none" d="M0 0h48v48H0z"></path>
                        </g>
                    </svg>
                </span>
                Continue with Google
            </a>

            <div class="page-separator">
                <div class="page-separator__text">or</div>
            </div> -->

            <div  novalidate>
                <div class="form-group">
                    <?php //echo getIp() ?>
                    <label class="text-label" for="email_2">Login Type:</label>
                    <div class="input-group input-group-merge">
                        <select class="form-control form-control-prepended" id="login_type">
                            <option value="0">Admin</option>
                            <option value="1">Distributor</option>
                          </select>
                        <div class="input-group-prepend">
                            <div class="input-group-text">
                                <span class="fa fa-user-shield"></span>
                            </div>
                        </div>
                    </div>
                    <label id="un-err" class="text-danger pull-right"></label>
                </div>
                <div class="form-group">
                    <label class="text-label" for="email_2">Email Address:</label>
                    <div class="input-group input-group-merge">
                        <input id="txt-u-name" type="text" required="" class="form-control form-control-prepended" placeholder="john@doe.com">
                        <div class="input-group-prepend">
                            <div class="input-group-text">
                                <span class="far fa-envelope"></span>
                            </div>
                        </div>
                    </div>
                    <label id="un-err" class="text-danger pull-right"></label>
                </div>
                <div class="form-group">
                    <label class="text-label" for="password_2">Password:</label>
                    <div class="input-group input-group-merge">
                        <input id="txt-pass" type="password" required="" class="form-control form-control-prepended" placeholder="Enter your password">
                        <div class="input-group-prepend">
                            <div class="input-group-text">
                                <span class="fa fa-key"></span>
                            </div>
                        </div>
                    </div>
                    <label id="pw-err" class="text-danger pull-right"></label>
                </div>
                <div class="form-group text-center">
                    <div class="custom-control custom-checkbox">
                        <input type="checkbox" class="custom-control-input" checked="" id="remember">
                        <label class="custom-control-label" for="remember">Remember me</label>
                    </div>
                </div>
                <div class="form-group">
                    <button class="btn btn-block btn-primary" id="btn-signin" type="submit">Login</button>
                </div>
                
                <div class="form-group text-center">
                    <a href="">Forgot password?</a> <hr>
                    Version 2.9.3<br>
                    Powered By <a class="text-body text-underline" href="https://amazoft.com/" target="_blank"><img src="assets/img/alogo.png" style="width: 35%;"></a>
                    <!-- Don't have an account? <a class="text-body text-underline" href="signup.html">Sign up!</a> -->
                </div>
            </div>
        </div>
    </div>


    <!-- jQuery -->
    <script src="assets/vendor/jquery.min.js"></script>

    <!-- Bootstrap -->
    <script src="assets/vendor/popper.min.js"></script>
    <script src="assets/vendor/bootstrap.min.js"></script>

    <!-- Simplebar -->
    <script src="assets/vendor/simplebar.min.js"></script>

    <!-- DOM Factory -->
    <script src="assets/vendor/dom-factory.js"></script>

    <!-- MDK -->
    <script src="assets/vendor/material-design-kit.js"></script>

    <!-- Range Slider -->
    <script src="assets/vendor/ion.rangeSlider.min.js"></script>
    <script src="assets/js/ion-rangeslider.js"></script>

    <!-- App -->
    <script src="assets/js/toggle-check-all.js"></script>
    <script src="assets/js/check-selected-row.js"></script>
    <script src="assets/js/dropdown.js"></script>
    <script src="assets/js/sidebar-mini.js"></script>
    <script src="assets/js/app.js"></script>

    <!-- App Settings (safe to remove) -->
    <script src="assets/js/app-settings.js"></script>

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>


    <script>

    $(document).ready(function() {

      $("#txt-u-name").focus(function(){
          $("#un-err").html("");
       });

      $("#txt-pass").focus(function(){
          $("#pw-err").html("");
       });

       $("#btn-signin").click(function (){

        var username=$("#txt-u-name").val();
        var password=$("#txt-pass").val();
        var login_type = $("#login_type").val();

        if(username==""){
            $("#un-err").html("Please enter a valid username");
        }else if(password==""){
            $("#pw-err").html("Please enter a valid password");
        }else{


            $.ajax({
                        url: "scripts/signin_to_web_system.php",
                        type: 'POST',
                        data: {
                            un:username,
                            pw:password,
                            login_type:login_type //0 = admin,1 = distributor
                        },
                        success: function (data) {


                            var json=JSON.parse(data);
                            
                            if(!json.result){
                                Swal.fire({
                                    title: 'Warning !',
                                    text: json.msg,
                                    icon: 'error',
                                    allowOutsideClick:false,
                                    showCancelButton: true,
                                    showConfirmButton:false,
                                    cancelButtonColor: '#d33',
                                    cancelButtonText: 'OK'
                                  });
                                

                            }else{

                              location.href='dashboard.php';

                            }





                        },
                        error: function (jqXHR, textStatus, errorThrown) {
                            alert(jqXHR+" | "+textStatus+" | "+errorThrown);
                        }

                    });


        }


      });

    
     
    });
  </script>


</body>

</html>