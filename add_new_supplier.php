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
                        <div class="card card-form">
                            <div class="row no-gutters">
                                <div class="col-lg-4 card-body">
                                    <p><strong class="headings-color">Register Supplier</strong></p>
                                    <p class="text-muted">Edit your account details and settings.</p>
                                </div>
                                <form id="Register-Supplier" class="col-lg-8 card-form__body card-body">
                                   
                                    <div class="row">
                                        <div class="col">
                                            <div class="form-group">
                                                <label>Supplier Name</label>
                                                <input name="supplier_name" type="text" class="form-control" placeholder="Supplier Name" required>
                                            </div>
                                        </div>
                                        <div class="col">
                                            <div class="form-group">
                                                <label>Supplier Nic Number</label>
                                                <input name="nic_number" type="text" class="form-control" placeholder="Supplier Nic Number">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col">
                                            <div class="form-group">
                                                <label>BR Number</label>
                                                <input name="br_number" type="text" class="form-control" placeholder="BR Number" required>
                                            </div>
                                        </div>
                                        <div class="col">
                                            
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col">
                                            <div class="form-group">
                                                <label>Contact Person Name</label>
                                                <input name="contact_person_name" type="text" class="form-control" placeholder="Contact Person Name" required>
                                            </div>
                                        </div>
                                        <div class="col">
                                            <div class="form-group">
                                                <label>Contact Number</label>
                                                <input name="contact_number" type="text" class="form-control" placeholder="07X XXXXXXX" required>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label>Address</label>
                                        <textarea name="address" rows="4" class="form-control" placeholder="Address ..." required></textarea>
                                    </div>

                                    <div class="text-right mb-5">
                                        <button type="submit" id="btn-reg-outlet" class="btn btn-dark">Save</button>
                                    </div>

                                </form>

                                
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
        
        $(document).on('submit', '#Register-Supplier', function(e){
        e.preventDefault(); //stop default form submission

        $("#btn-reg-outlet").attr("disabled",true);

        var formData = new FormData($(this)[0]);

        $.ajax({
            
            
                beforeSend : function() {

                $("#progress_alert").addClass('show'); 

                },

                url:"post/add_supplier.php",
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
                       // $("#outlet-record-area").html(json.data);
                        
                       $("#success_msg").html(json.msg);
                       $("#success_alert").addClass('show'); 
                       
                       setTimeout(function(){$("#success_alert").removeClass('show');  }, 1000);
                       $("#btn-reg-outlet").attr("disabled",false);
                       // document.getElementById('record-textarea').value = '';
                       var id = json.lastId;
                       // alert(id);
                       window.location.href = "supplier_single?s="+id;
                        
                    }else{
                        $("#danger_alert").addClass('show');
                        setTimeout(function(){ $("#danger_alert").removeClass('show'); }, 1000);
                        $("#btn-reg-outlet").attr("disabled",false);
                    }
                    
                }

            });

        return false;
        });
    </script>


</body>
</html>