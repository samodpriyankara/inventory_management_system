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
                                <h3>Create & Update Distributor Accounts</h3>
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
                                        <input type="text" id="txt-dis-name" class="form-control" placeholder="Ex : John Doe">
                                    </div>
                                        
                                    <div class="form-group">
                                      <label>Address</label>
                                      <input type="text" id="txt-dis-add" class="form-control" placeholder="Ex : 123 Bakert Street London">
                                    </div>


                                    <div class="form-group">
                                      <label>Contact No</label>
                                      <input type="text" id="txt-dis-contact" class="form-control" placeholder="Ex : 071 XXXXXXX">
                                    </div>
                                    
                                    <hr/>

                                    <div class="form-group">
                                        <label>Username</label>
                                        <input type="text" id="txt-dis-username" class="form-control" placeholder="Username">
                                    </div>

                                    <div class="form-group">
                                        <label>Password</label>
                                        <input type="Password" id="txt-dis-password" class="form-control" placeholder="Password">
                                    </div>

                                    <div class="form-group">
                                        <label>Re-type Password</label>
                                        <input type="Password" id="txt-dis-re-password" class="form-control" placeholder="Password">
                                    </div>

                                    <div class="text-right mb-5">
                                        <button type="submit" id="btn-submit" class="btn btn-primary">Save</button>
                                    </div>
                                </div>

                                <div class="col-lg-8 card-body">
                                    
                                    <div class="table-responsive border-bottom" style="padding: 10px;">

                                        <!-- <div class="search-form search-form--light m-3">
                                            <input type="text" class="form-control search" placeholder="Search">
                                            <button class="btn" type="button" role="button"><i class="material-icons">search</i></button>
                                        </div> -->

                                        <table class="table mb-0 thead-border-top-0" id="DISTRIBUTORTable">
                                            <thead>
                                                <tr>
                                                    <th>Distributor's Name</th>
                                                    <th>Address</th>
                                                    <th>Contact No</th>
                                                    <th></th>
                                                    <!-- <th></th> -->
                                                </tr>
                                            </thead>
                                            <tbody class="list">
                                                <?php
                                                    $getDisQuery=$conn->query("SELECT * FROM tbl_distributor");
                                                    while ($dis=$getDisQuery->fetch_array()) {
                                                ?>
                                                <tr>
                                                    <td><?php echo $dis[1];?></td>
                                                    <td><?php echo $dis[2];?></td>
                                                    <td><?php echo $dis[3];?></td>
                                                    <td><a class="btn btn-secondary" href="view_distributor_stock.php?distributor=<?php echo base64_encode($dis[0]);?>">View Current Stock</a></td>
                                                    <!-- <td><a class="btn btn-dark" href="view_distributor_stock_history.php?distributor=<?php //echo base64_encode($dis[0]);?>">Stock History</a></td> -->
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
        $(document).ready( function () {
            $('#DISTRIBUTORTable').DataTable({
                "order": [[ 0, "desc" ]],
                    dom: 'Bfrtip',
                    buttons: [
                        // 'copy', 'csv', 'excel', 'pdf', 'print'
                        'print', 'excel', 'pdf'
                    ]
            });
        } );
    </script>

  
  <script>

    function editDistributor(id,name,address,contact){

      alert(id+" "+name+" "+address+" "+contact);

    }


    $(document).ready(function() {

       

            $("#btn-submit").click(function(){

                 var disName=$("#txt-dis-name").val();
                 var disAddress=$("#txt-dis-add").val();
                 var disContact=$("#txt-dis-contact").val();


                 var dis_password = $("#txt-dis-password").val();
                 var dis_re_password = $("#txt-dis-re-password").val();
                 var dis_username = $("#txt-dis-username").val();

                 if(disName==""){

                 }else if(disAddress==""){

                 }else if(disContact==""){

                 }else if(dis_username==""){

                 }else if(dis_password==""){

                 }else if(dis_re_password==""){

                 }else{

                  if(dis_password != dis_re_password){
                    alert("Password confirmation failed.");
                  }else{

                    $.ajax({
                        url: "scripts/upload_distributor_details.php",
                        type: 'POST',
                        data: {
                            dis_name:disName,
                            dis_address:disAddress,
                            dis_contact:disContact,
                            dis_username:dis_username,
                            dis_password:dis_password
                        },
                        success: function (data) {


                            var json=JSON.parse(data);
                            if(json.result){

                                Swal.fire({
                                    title: 'Success',
                                    text: 'Successfully Registered.',
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
                                    text: json.msg,
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



                     




                 }





              




            });


      






      
    });
  </script>
  
</body>
</html>