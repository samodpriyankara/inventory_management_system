<?php
    session_start();
    require 'database/db.php';
    $db=new DB();
    $conn=$db->connect();
    date_default_timezone_set('Asia/Colombo');
    $currentDate=date('Y-m-d');

    $is_distributor = false;
    $user_id = 0;

    if(empty($_SESSION['user'])){
        header('Location:index.php');
    }
    if(isset($_SESSION['DISTRIBUTOR'])){
      $is_distributor = $_SESSION['DISTRIBUTOR'];
    }
    if(isset($_SESSION['ID'])){
      $user_id = $_SESSION['ID'];

        $sql = "SELECT * FROM `tbl_web_console_user_account` WHERE user_id='$user_id' ";
        $rs=$conn->query($sql);
        if($row =$rs->fetch_array())
        {
            // $UserId = $row[0];
            // $UserName = $row[1];
            $Name = $row[6];
        }


    }
?>
<!DOCTYPE html>
<html lang="en" dir="ltr">

<?php include_once('controls/meta.php'); ?>

<link rel="stylesheet" href="https://cdn.datatables.net/buttons/2.1.0/css/buttons.dataTables.min.css">

<body class="layout-default" onload="startTime()">
    
    
       <!-- The Modal -->
                        <div class="modal" id="modal-auth">
                          <div class="modal-dialog">
                            <div class="modal-content">
                        
                              <!-- Modal Header -->
                              <div class="modal-header">
                                <h4 class="modal-title">Authenticate for editings</h4>
                                <button type="button" class="close" data-dismiss="modal">&times;</button>
                              </div>
                        
                              <!-- Modal body -->
                              <div class="modal-body">
                                
                                <form id="form-auth-for-grn-editing">
                                    <div class="form-group">
                                        <label>Password</label>
                                        <input type="password" class="form-control" placeholder = "Password" name="auth-pwd">
                                    </div>
                                    
                                    <div class="form-group">
                                       <button class="btn btn-success" id="btn-auth-now">Authenticate Now</button>
                                    </div>
                                    
                                </form>
                                
                              </div>
                        
                              
                            </div>
                          </div>
                        </div>
                
                
                
                <!-------------->
    
    
    
    
    
    
    
    
    
    
    

    <div class="preloader"></div>

    <div class="mdk-drawer-layout js-mdk-drawer-layout" data-push data-responsive-width="992px" data-fullbleed>
        <div class="mdk-drawer-layout__content">

            <!-- Header Layout -->
            <div class="mdk-header-layout js-mdk-header-layout" data-has-scrolling-region>

                <!-- Header -->

                <?php include_once('controls/header.php'); ?>

                <!-- // END Header -->

                <!-- Header Layout Content -->
                <div class="mdk-header-layout__content mdk-header-layout__content--fullbleed mdk-header-layout__content--scrollable page">


                    <div class="container-fluid page__container">
                        <div class="z-0">
                            <ul class="nav nav-tabs nav-tabs-custom" role="tablist">
                                <li class="nav-item">
                                    <a href="#tab-user-details-update" class="nav-link active" data-toggle="tab" role="tab" aria-controls="tab-user-details-update" aria-selected="true">
                                        <span class="nav-link__count"></span>
                                        Update User Details
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a href="#tab-create-web-users" class="nav-link" data-toggle="tab" role="tab" aria-selected="false">
                                        <span class="nav-link__count"></span>
                                        Create Web Users
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a href="#tab-web-users" class="nav-link" data-toggle="tab" role="tab" aria-selected="false">
                                        <span class="nav-link__count"></span>
                                        Web Users List
                                    </a>
                                </li>
                                <!-- <li class="nav-item">
                                    <a href="#tab-no-show" class="nav-link disabled" data-toggle="tab" role="tab" aria-selected="false">
                                        <span class="nav-link__count"></span>
                                        No Show
                                    </a>
                                </li> -->
                            </ul>
                            <div class="card">
                                <div class="tab-content">
                                    <div class="tab-pane active show fade" id="tab-user-details-update">

                                        <div class="border-bottom" style="padding: 50px;">

                                            <div class="row">
                                                <div class="col-md-4">
                                                    Name
                                                </div>
                                                <div class="col-md-4" id="user-name">
                                                    <?php echo $Name; ?>
                                                </div>
                                                <div class="col-md-4">
                                                    <label class="pull-right" style="cursor: pointer;"><input type="checkbox" id="edit-name" name="edit_name" style="display: none;"> <i class="fa fa-pencil-alt"></i> Edit</label>
                                                </div>
                                            </div>

                                            <div class="basic-form" id="Edit-Name-Form" style="display: none;">
                                            <br>
                                            <div class="row">
                                                <div class="col-md-1"></div>
                                                <div class="col-md-10">
                                                    <center>
                                                        <form method="POST" id="Update-User-Name">
                                                            <input type="hidden" name="user_id" class="form-control" value="<?php echo $user_id; ?>" readonly required>
                                                            <div class="form-group row">
                                                                <label class="col-form-label">Name *</label>
                                                                <input type="text" name="name" value="<?php echo $Name; ?>" class="form-control" placeholder="Name" required>
                                                                <br>

                                                                <div>
                                                                    <button type="submit" id="btn-save-name" class="btn btn-dark">Save Changes</button>

                                                                    <label class="btn btn-danger" style="margin-top: 8px;"><input type="checkbox" id="edit-name-close" name="edit_name" style="display: none;"> Cancel</label>
                                                                </div>
                                                            </div>
                                                        </form>
                                                    </center>
                                                </div>
                                                <div class="col-md-1"></div>
                                            </div>
                                        </div>

                                        <hr>

                                        <div class="row">
                                            <div class="col-md-4">
                                                Change password
                                            </div>
                                            <div class="col-md-4" id="user-name">
                                                It's a good idea to use a strong password that you don't use elsewhere
                                            </div>
                                            <div class="col-md-4">
                                                <label class="pull-right" style="cursor: pointer;"><input type="checkbox" id="psw-change" name="psw_change" style="display: none;"> <i class="fa fa-pencil-alt"></i> Edit</label>
                                            </div>
                                        </div>

                                        <div class="basic-form" id="Password-Change-Form" style="display: none;">
                                            <br>
                                            <div class="row">
                                                <div class="col-md-1"></div>
                                                <div class="col-md-10">
                                                    <form method="POST" id="Update-Password">
                                                        <input type="hidden" name="user_id" class="form-control" value="<?php echo $user_id; ?>" readonly required>
                                                        <div class="form-group">
                                                            <label class="col-form-label">New Password *</label>
                                                            <input type="password" name="password" id="password" class="form-control" placeholder="New Password" required>

                                                            <button type="submit" id="btn-save-password" class="btn btn-dark btn-xs">Save Changes</button>
                                                                <label class="btn btn-danger btn-xs" style="margin-top: 8px;"><input type="checkbox" id="psw-change-close" name="psw_change" style="display: none;"> Cancel</label>

                                                        </div>
                                                        <br>
                                                        
                                                    </form>
                                                </div>
                                                <div class="col-md-1"></div>
                                            </div>
                                        </div>



                                            
                                        </div>
                                        

                                    </div>

                                    <div class="tab-pane fade" id="tab-create-web-users">

                                        
                                        <div class="card-body">

                                            <p><strong class="headings-color">Create Web Users</strong></p>
                                            <!-- <p class="text-muted">Edit your account details and settings.</p> -->


                                            <form id="Register-Web-Console-User" class="col-lg-12 card-form__body card-body">
                                   
                                                <div class="row">
                                                    <div class="col">
                                                        <div class="form-group">
                                                            <label>First Name</label>
                                                            <input name="f_name" id="f_name" type="text" class="form-control" placeholder="First Name" required>
                                                        </div>
                                                    </div>
                                                    <div class="col">
                                                        <div class="form-group">
                                                            <label>Last Name</label>
                                                            <input name="l_name" id="l_name" type="text" class="form-control" placeholder="Last Name">
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <div class="col">
                                                        <div class="form-group">
                                                            <label>Login User Name <span id="user_name_check" style="color: black; text-align: right !important; position: absolute; right: 15px;"></span></label>
                                                            <input name="username" id="username" type="text" class="form-control" placeholder="Login User Name" oninput="return user_name();" required>
                                                        </div>
                                                    </div>
                                                    <div class="col">
                                                        <div class="form-group">
                                                            <label>Password</label>
                                                            <input name="password" id="password" type="password" class="form-control" placeholder="XXXXXX" required>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <div class="col">
                                                        <div class="form-group">
                                                            <label>User Role</label>
                                                            <select class="form-control" name="stat">
                                                                <option disabled>Select User Role</option>
                                                                <option value="1" selected>Super User</option>
                                                                <!-- <option value="2">Normal User</option> -->
                                                            </select>
                                                        </div>
                                                    </div>
                                                    <div class="col">
                                                        
                                                    </div>
                                                </div>

                                                <div class="text-right mb-5">
                                                    <button type="submit" id="btn-reg-web-console-user" class="btn btn-dark">Create Account</button>
                                                </div>

                                            </form>


                                        </div>

                                    </div>

                                    <div class="tab-pane fade" id="tab-web-users">

                                        
                                        <div class="card-body">
                                            
                                            <div class="table-responsive">
                                                <table id="WebUsersTable" class="display min-w850">
                                                    <thead>
                                                        <tr>
                                                            <th>#</th>
                                                            <th>Name</th>
                                                            <th>User Role</th>
                                                            <th>Register Date</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        <?php

                                                            $WebConsoleUsersSql = "SELECT * FROM `tbl_web_console_user_account` ORDER BY 'user_id' DESC";
                                                            $WebConsoleUsersrs=$conn->query($WebConsoleUsersSql);
                                                            while($WCUrow =$WebConsoleUsersrs->fetch_array())
                                                            {
                                                                $UserId = $WCUrow[0];
                                                                $UserName = $WCUrow[1];
                                                                $CreatedDate = $WCUrow[3];
                                                                $ActiveStatus = $WCUrow[4];
                                                                $Stat = $WCUrow[5];
                                                                $Name = $WCUrow[6];
                                                        ?>
                                                        <tr>
                                                            <td><?php echo $UserId; ?></td>
                                                            <td><?php echo $Name; ?></td>
                                                            <td>Super Admin</td>
                                                            <td><?php echo $CreatedDate; ?></td>
                                                        </tr>
                                                        <?php } ?>
                                                    </tbody>
                                                </table>
                                            </div>


                                        </div>

                                    </div>

                                </div>
                            </div>
                        </div>
                        
                        
                        <button type="button" onclick="open_auth_window()" class="btn btn-warning"><i class="fa fa-edit"></i> Authenticate For Editings</button>
                        
                    </div>


                    


                </div>
                <!-- // END header-layout__content -->

            </div>
            <!-- // END header-layout -->

        </div>
        <!-- // END drawer-layout__content -->

        
        <?php include_once('controls/sidebar.php'); ?>

    </div>
    <!-- // END drawer-layout -->

    <?php include_once('controls/notification.php'); ?>

    <!-- App Settings FAB -->
    <div id="app-settings">
        <app-settings></app-settings>
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



    <!-- List.js -->
    <script src="assets/vendor/list.min.js"></script>
    <script src="assets/js/list.js"></script>

    <!-- Dragula -->
    <script src="assets/vendor/dragula/dragula.min.js"></script>
    <script src="assets/js/dragula.js"></script>

    <!-- Datatable -->
    <script src="https://cdn.datatables.net/1.11.3/js/jquery.dataTables.min.js"></script>

    <script src="https://cdn.datatables.net/buttons/2.1.0/js/dataTables.buttons.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/pdfmake.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.1.0/js/buttons.html5.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.1.0/js/buttons.print.min.js"></script>

    <script>
        function startTime() {
          var today = new Date();
          var h = today.getHours();
          var m = today.getMinutes();
          var s = today.getSeconds();
          m = checkTime(m);
          s = checkTime(s);
          document.getElementById('txt').innerHTML =
          h + ":" + m + ":" + s;
          var t = setTimeout(startTime, 500);
        }
        function checkTime(i) {
          if (i < 10) {i = "0" + i};  // add zero in front of numbers < 10
          return i;
        }
    </script>

    <script>
        function user_name()
        {
            var username=document.getElementById('username').value;
            var dataString='username='+  username;
            $.ajax({

                type:"post",
                url: "controls/check_web_user_name.php",
                data:dataString,
                cache: false,

                success: function(html) {

                    $('#user_name_check').html(html);
                    return d = true;
                }

            });

            return false;
        }
    </script>

    <script>
        $(document).ready( function () {
            $('#WebUsersTable').DataTable({
                "order": [[ 0, "desc" ]],
                dom: 'Bfrtip',
                buttons: [
                    // 'copy', 'csv', 'excel', 'pdf', 'print'
                    'print', 'excel', 'pdf'
                ]
            });
            
            
            $("#form-auth-for-grn-editing").submit(function(e){
                e.preventDefault();
                auth_now(e);
            });
            
            
        } );
    </script>
    
    
    <script>
    
        function auth_now(form){

  
  var formData = new FormData(form.target);
    

  $.ajax({
                    url:'scripts/auth_editings.php',
                    type:'POST',
                    data:formData,
                    cache: false,
                    contentType: false,
                    processData: false,

                    beforeSend:function(){
                        //$("#btn-auth-now").prop("disabled",true);
                        $("button").prop("disabled", true);
                          

                    },
                    success:function(data){
                        
                        
                        
                       
                        $("button").prop("disabled", false);

                        var json=JSON.parse(data);
                        if(json.result){
                            alert("Editings has been approved.");
                            location.reload();
                        }else{
                            alert(json.msg);
                        }
                       

                                        
                    },
                    error:function(err,xhr,data){
                            console.log(err);
                    }

                });


}
    
        
        function open_auth_window(){
            $("#modal-auth").modal();
        }
        
        
    </script>
    

    <script>
        //edit-name
        $("#edit-name").change(function(){
            $("#Edit-Name-Form").show();
            $("#Password-Change-Form").hide();
        });
        $("#edit-name-close").change(function(){
            $("#Edit-Name-Form").hide();
            
        });

        //psw-change
        $("#psw-change").change(function(){
            $("#Password-Change-Form").show();
            $("#Edit-Name-Form").hide();
        });
        $("#psw-change-close").change(function(){
            $("#Password-Change-Form").hide();
           
        });
    </script>

    <script>
        
        $(document).on('submit', '#Update-User-Name', function(e){
        e.preventDefault(); //stop default form submission

        $("#btn-save-name").attr("disabled",true);

        var formData = new FormData($(this)[0]);

        $.ajax({
            
            
                beforeSend : function() {
                    $("#progress_alert").addClass('show'); 

                },

                url:"update/update_user_account_name.php",
                type: 'POST',
                data: formData,
                //async: false,
                cache: false,
                contentType: false,
                processData: false,

                success: function (data) {
                    
                    $("#progress_alert").removeClass('show');
                    
                    var json=JSON.parse(data);
                    
                    if(json.result){

                       $("#user-name").html(json.username);

                       $("#success_msg").html(json.msg);
                       $("#success_alert").addClass('show'); 
                       
                       setTimeout(function(){$("#success_alert").removeClass('show');  }, 1000);
                        
                       $("#success_msg").html(json.msg);
                       $("#success_alert").addClass('show');
                       $("#btn-save-name").attr("disabled",false);
                        
                    }else{
                        $("#danger_alert").addClass('show');
                        setTimeout(function(){ $("#danger_alert").removeClass('show'); }, 1000);
                        $("#btn-save-name").attr("disabled",false);
                    }
                    
                }

            });

        return false;
        });
    </script>

    <!--------------------------------Password Change------------------------------------------->

    <script>

        $(document).on('submit', '#Update-Password', function(e){
        e.preventDefault(); //stop default form submission

        $("#btn-save-password").attr("disabled",true);

        var formData = new FormData($(this)[0]);

        $.ajax({
            
            
                beforeSend : function() {
                    $("#progress_alert").addClass('show'); 

                },

                url:"update/update_user_account_password.php",
                type: 'POST',
                data: formData,
                //async: false,
                cache: false,
                contentType: false,
                processData: false,

                success: function (data) {
                    
                    $("#progress_alert").removeClass('show');
                    
                    var json=JSON.parse(data);
                    
                    if(json.result){

                       $("#success_msg").html(json.msg);
                       $("#success_alert").addClass('show'); 
                       
                       setTimeout(function(){$("#success_alert").removeClass('show');  }, 3000);
                       
                       $("#success_msg").html(json.msg);
                       $("#success_alert").addClass('show');
                       $("#btn-save-password").attr("disabled",false);
                        
                    }else{
                        $("#danger_alert").addClass('show');
                        setTimeout(function(){ $("#danger_alert").removeClass('show'); }, 3000);
                        $("#btn-save-password").attr("disabled",false);
                    }
                    
                }

            });

        return false;
        });
    </script>

    

    <script>

        $(document).on('submit', '#Register-Web-Console-User', function(e){
        e.preventDefault(); //stop default form submission

        $("#btn-reg-web-console-user").attr("disabled",true);

        var formData = new FormData($(this)[0]);

        $.ajax({
            
            
                beforeSend : function() {
                    $("#progress_alert").addClass('show'); 

                },

                url:"post/create_web_console_user.php",
                type: 'POST',
                data: formData,
                //async: false,
                cache: false,
                contentType: false,
                processData: false,

                success: function (data) {
                    
                    $("#progress_alert").removeClass('show');
                    
                    var json=JSON.parse(data);
                    
                    if(json.result){

                       $("#success_msg").html(json.msg);
                       $("#success_alert").addClass('show'); 
                       
                       setTimeout(function(){$("#success_alert").removeClass('show');  }, 3000);
                       
                       $("#success_msg").html(json.msg);
                       $("#success_alert").addClass('show');

                       document.getElementById('f_name').value = '';
                       document.getElementById('l_name').value = '';
                       document.getElementById('username').value = '';
                       document.getElementById('password').value = '';
                       $("#btn-reg-web-console-user").attr("disabled",false);
                       location.reload();
                        
                    }else{
                        $("#danger_alert").addClass('show');
                        setTimeout(function(){ $("#danger_alert").removeClass('show'); }, 3000);
                        $("#btn-reg-web-console-user").attr("disabled",false);
                    }
                    
                }

            });

        return false;
        });
    </script>

</body>

</html>