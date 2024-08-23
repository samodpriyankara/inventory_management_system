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
    }
?>
<!DOCTYPE html>
<html lang="en" dir="ltr">

<?php include_once('controls/meta.php'); ?>

<body class="layout-default" onload="startTime()">

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
                        <div class="row">
                            <div class="col-md-6">
                                <h3>Create & Update Sales-Rep Accounts</h3>
                            </div>
                            <!-- <div class="col-md-6">
                                <div class="pull-right" style="float: right;">
                                    <a href="create_invoice" class="btn btn-primary">Create Invoice</a>
                                </div>
                            </div> -->
                        </div>
                        <div class="card card-form">
                            <div class="row no-gutters">
                                
                                <div class="col-lg-4 card-form__body card-body">
                                    <div class="form-group">
                                        <label>Full Name</label>
                                        <input type="text" class="form-control" id="txt-name" placeholder="Ex : John Doe">
                                    </div>
                                        
                                    <div class="form-group">
                                      <label>Address</label>
                                      <input type="text" class="form-control" id="txt-address" placeholder="Ex : 123 Baker Street London">
                                    </div>


                                    <div class="form-group">
                                      <label>Contact No</label>
                                      <input type="telephone" class="form-control" id="txt-contact" placeholder="Ex : 0710000000">
                                    </div>

                                    <div class="form-group">
                                        <label>Distributor</label>
                                        <select id="txt-dis-id" class="custom-select" style="width: 100%;">
                                            <option value="0" selected disabled>Select Distributor</option>
                                            <?php
                                                if($is_distributor){
                                                    $getDisQuery=$conn->query("SELECT td.distributor_id,td.name FROM tbl_distributor td WHERE distributor_id = '$user_id' ORDER BY td.name ASC");
                                                }else{
                                                    $getDisQuery=$conn->query("SELECT td.distributor_id,td.name FROM tbl_distributor td ORDER BY td.name ASC");
                                                }
                                                while ($dis=$getDisQuery->fetch_array()) {
                                            ?>
                                                <option value=<?php echo $dis[0];?>><?php echo $dis[1];?></option>
                                            <?php } ?>
                                        </select>
                                    </div>

                                    <hr/>

                                    <div class="form-group">
                                        <label>Username</label>
                                        <input type="text" class="form-control" id="txt-uname" placeholder="Username">
                                    </div>

                                    <div class="form-group">
                                        <label>Password</label>
                                        <input type="Password" class="form-control" id="txt-pass" placeholder="Password">
                                    </div>

                                    <div class="form-group">
                                        <label>Re-type Password</label>
                                        <input type="Password" class="form-control" id="txt-re-pass" placeholder="Re-type Password">
                                    </div>

                                    <div class="text-right mb-5">
                                        <button class="btn btn-danger" id="btn-cancel">Cancel</button>
                                        <button type="submit" id="btn-submit" class="btn btn-primary">Save</button>
                                    </div>
                                </div>

                                <div class="col-lg-8 card-body">
                                    
                                    <div class="table-responsive border-bottom" style="padding: 10px;">

                                        <!-- <div class="search-form search-form--light m-3">
                                            <input type="text" class="form-control search" placeholder="Search">
                                            <button class="btn" type="button" role="button"><i class="material-icons">search</i></button>
                                        </div> -->

                                        <table class="table mb-0 thead-border-top-0" id="usersTable">
                                            <thead>
                                                <tr>
                                                    <th>Representative's Name</th>
                                                    <th>Username</th>
                                                    <th>Login Status</th>
                                                    <th></th>
                                                </tr>
                                            </thead>
                                            <tbody class="list">
                                                <?php

                                                    if($is_distributor){
                                                        $rs=$conn->query("SELECT tu.id,tu.name,tu.login_status,tu.username FROM tbl_user tu INNER JOIN tbl_distributor_has_tbl_user tdhu ON tu.id = tdhu.user_id WHERE tdhu.distributor_id = '$user_id'");
                                                    }else{
                                                        $rs=$conn->query("SELECT id,name,login_status,username FROM tbl_user");
                                                    }

                                                    while ($row=$rs->fetch_array()) {
                                                        $userId = $row[0];
                                                        $loginStatusText="";
                                                        $loginStatus = $row[2];
                                                          
                                                            if($loginStatus==1){
                                                                $loginStatusText="Logged in";
                                                            }else{
                                                                $loginStatusText="Logged out";
                                                            }

                                                ?>
                                                <tr>
                                                    <td><?php echo $row[1];?></td>
                                                    <td><?php echo $row[3];?></td>
                                                    <td><?php echo $loginStatusText;?></td>
                                                    <?php if($loginStatus == 1){ ?>
                                                    <td>
                                                        <button class="btn btn-danger" style="font-size: 17px" onclick="changeLoginStatus('logout','<?php echo $userId;?>')"><i class="fa fa-power-off" aria-hidden="true"></i></button>
                                                    </td>
                                                    <?php }else{ ?>
                                                    <td></td>
                                                    <?php } ?>
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
                <!-- // END header-layout__content -->

            </div>
            <!-- // END header-layout -->

        </div>
        <!-- // END drawer-layout__content -->

        <?php include_once('controls/sidebar.php'); ?>
    </div>
    <!-- // END drawer-layout -->

    <?php include_once('controls/notification.php'); ?>

    <!-- Modal -->
<div id="modal-update" class="modal fade" role="dialog">
  <div class="modal-dialog">

    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        <!-- <button type="button" class="close" data-dismiss="modal">&times;</button> -->
        <h4 class="modal-title">Update item details</h4>

        <button class="btn btn-success" id="btn-enable-product" style="display: none;">Enable Product</button>
        <button class="btn btn-danger" id="btn-disable-product" style="display: none;">Disable Product</button>



      </div>
      <div class="modal-body">

            <input type="hidden" id="lbl-h-id" disabled class="form-control">

            <div class="form-group">
                <label>Product Code</label>
                <input type="text" id="lbl-code" disabled class="form-control">
            </div>

            <div class="form-group">
                <label>Product Name</label>
                <input type="text" id="lbl-name" disabled class="form-control">
            </div>


        <!--    <div class="form-group">
                <label>Buying Price</label>
                <input type="text" id="lbl-b-price" class="form-control" onkeypress='validate(event)'>
            </div>


            <div class="form-group">
                <label>Selling Price</label>
                <input type="text" id="lbl-s-price" class="form-control" onkeypress='validate(event)'>
            </div>
 -->

             <h4 class="modal-title">Free issue scheme</h4>
             <hr>


             <div class="form-group">
                <label>Free issue margin</label><span style="color:red"> ( product quantity to enable free issues. )</span>
                <input type="number" id="lbl-free-margin" class="form-control" placeholder="25">
            </div>

             <div class="form-group">
                <label>Free issue quantity</label><span style="color:red"> ( items quantity which will be issued free when above quntity is bought. )</span>
                <input type="number" id="lbl-free-qty" class="form-control" placeholder="1">
            </div>







      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" id="btn-update-product-data">Update</button>
      </div>
    </div>

  </div>
</div>


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

    <!-- Dropzone -->
    <script src="assets/vendor/dropzone.min.js"></script>
    <script src="assets/js/dropzone.js"></script>

    <script src="https://cdn.datatables.net/1.11.3/js/jquery.dataTables.min.js"></script>

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

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
    $(document).ready( function () {
        $('#usersTable').DataTable();
    } );
  </script>

  


  <script type="text/javascript">

    function changeLoginStatus(status,user_id){


        Swal.fire({
          title: 'Are you sure?',
          text: "Do you want signout the user?",
          icon: 'warning',
          showCancelButton: true,
          confirmButtonColor: '#3085d6',
          cancelButtonColor: '#d33',
          confirmButtonText: 'Yes'
        }).then((result) => {
          if (result.isConfirmed) {


             $.ajax({
                        url: "scripts/change_user_login_status.php",
                        type: 'POST',
                        data: {
                            status:status,
                            user_id:user_id
                          
                        },
                        success: function (data) {


                            var response=JSON.parse(data);
                            if(response.result){
                               
                                Swal.fire({
                                    title: 'Success',
                                    text: 'Successfully Updated.',
                                    icon: 'success',
                                    allowOutsideClick:false,
                                    showCancelButton: false,
                                    showConfirmButton:true,
                                    cancelButtonColor: '#d33',
                                    confirmButtonText: 'OK'
                                  }).then((result) => {
                                    if (result.value) {

                                      location.reload();
                                  
                                    }
                                  });
                                


                            
                            }else{

                                 Swal.fire({
                                    title: 'Warning !',
                                    text: response.msg,
                                    icon: 'warning',
                                    allowOutsideClick:false,
                                    showCancelButton: true,
                                    showConfirmButton:false,
                                    cancelButtonColor: '#d33',
                                    cancelButtonText: 'OK'
                                  });
                                

                              
                            }





                        },
                        error: function (jqXHR, textStatus, errorThrown) {
                            alert(errorThrown+"");
                        }

                    });
           
          }
        })


    }



    
    $(document).ready(function (){

      $("#btn-submit").click(function (){

        var name=$("#txt-name").val();
        var address=$("#txt-address").val();
        var contact=$("#txt-contact").val();
        var username=$("#txt-uname").val();
        var password=$("#txt-pass").val();
        var repassword=$("#txt-re-pass").val();
        var distributor=$("#txt-dis-id").val();



        if(name==""){
         

        }else if(address==""){
        

        }else if(contact==""){


        }else if(username==""){


        }else if(password==""){


        }else if(repassword==""){


        }else if(!(password == repassword)){
          
        }else{
          
          $.ajax({
                        url: "scripts/upload_user_account_details.php",
                        type: 'POST',
                        data: {
                            name:name,
                            address:address,
                            contact:contact,
                            username:username,
                            password:password,
                            dis:distributor
                        },
                        success: function (data) {


                            var response=JSON.parse(data);
                            if(response.result){
                               
                                Swal.fire({
                                    title: 'Success',
                                    text: 'User registration successful.',
                                    icon: 'success',
                                    allowOutsideClick:false,
                                    showCancelButton: false,
                                    showConfirmButton:true,
                                    cancelButtonColor: '#d33',
                                    confirmButtonText: 'OK'
                                  }).then((result) => {
                                    if (result.value) {

                                      location.reload();
                                  
                                    }
                                  });
                                


                            
                            }else{

                                 Swal.fire({
                                    title: 'Warning !',
                                    text: response.msg,
                                    icon: 'warning',
                                    allowOutsideClick:false,
                                    showCancelButton: true,
                                    showConfirmButton:false,
                                    cancelButtonColor: '#d33',
                                    cancelButtonText: 'OK'
                                  });
                                

                              
                            }





                        },
                        error: function (jqXHR, textStatus, errorThrown) {
                            alert(errorThrown+"");
                        }

                    });


        }



      });

    });



    function fetchUserDataFromServer(userId){
      alert(userId);
    }


  </script>

  
</body>
</html>