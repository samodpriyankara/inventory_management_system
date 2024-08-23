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
<?php
    $getDistributorInvoiceDetailsQuery=$conn->query("SELECT COUNT(*) FROM tbl_distributor_product_invoice WHERE stat='0' AND admin_id='$user_id'");
    if ($GgdirnQ=$getDistributorInvoiceDetailsQuery->fetch_array()) {
        $DistributorInvoiceOngoingCount=$GgdirnQ[0];
    }
?>
<?php if($DistributorInvoiceOngoingCount=='0'){ ?>
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
                                    <p><strong class="headings-color">Create Distributor Invoice</strong></p>
                                    <p class="text-muted">Edit your account details and settings.</p>
                                </div>
                                <form id="Create-Distributo-Invoice-Details" class="col-lg-8 card-form__body card-body">
                                    <input type="hidden" name="admin_id" value="<?php echo $user_id; ?>" required readonly>
                                    <div class="row">
                                        <div class="col">
                                            <div class="form-group">
                                                <label>Distributor Name</label>
                                                <select class="js-example-basic-single custom-select" name="distributor_id" required>
                                                    <option selected disabled>Select Distributor Name</option>
                                                    <?php
                                                        $getDataDistributor=$conn->query("SELECT * FROM tbl_distributor");
                                                        while ($row=$getDataDistributor->fetch_array()) {
                                                    ?>
                                                        <option value="<?php echo $row[0];?>"><?php echo $row[1];?></option>
                                                    <?php } ?>
                                                </select> 
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label>Note</label>
                                        <textarea class="form-control" name="note" rows="5"></textarea>
                                    </div>
                                        
                                    <div class="text-right mb-5">
                                        <button type="submit" class="btn btn-primary">Create Distributor Invoice</button>
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

    <script src="https://cdn.datatables.net/1.11.3/js/jquery.dataTables.min.js"></script>

    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>


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

    <script type="text/javascript">
        $(document).ready(function() {
            $('.js-example-basic-single').select2();
        });
    </script>

    <script>
        
        $(document).on('submit', '#Create-Distributo-Invoice-Details', function(e){
        e.preventDefault(); //stop default form submission
        var formData = new FormData($(this)[0]);

        $.ajax({

                beforeSend : function() {
                    $("#progress_alert").addClass('show');
                },

                url:"distributor_invoice_post/submit_distributor_invoice_details.php",
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
                       setTimeout(function(){$("#success_alert").removeClass('show');  }, 1000);
                       var j_id = json.j_id;
                       window.location.href = "distributor_invoice?d="+btoa(j_id);
                        
                    }else{
                        $("#danger_alert").addClass('show');
                        setTimeout(function(){ $("#danger_alert").removeClass('show'); }, 1000);
                    }
                    
                }



            });

        return false;
        });
    </script>


  
</body>

</html>

<?php }else{ ?>

<?php
    $getDistributorInvoiceDetailsOngoingIdQuery=$conn->query("SELECT distributor_invoice_id FROM tbl_distributor_product_invoice WHERE stat='0' AND admin_id='$user_id' ORDER BY distributor_invoice_id DESC LIMIT 1");
    if ($GgdiDOrs=$getDistributorInvoiceDetailsOngoingIdQuery->fetch_array()) {
        $DistributorinvoiceDetailId=$GgdiDOrs[0];
    }
?>
    <script>
        window.location.href = "distributor_invoice?d=<?php echo(base64_encode($DistributorinvoiceDetailId)) ?>";
    </script>

<?php }?>
