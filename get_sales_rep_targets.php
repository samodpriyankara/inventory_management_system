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

    <style>
        .select2-container .select2-selection--single{
            height: 35px !important;
        }
        .select2-container--default .select2-selection--single .select2-selection__rendered {
            line-height: 35px !important;
        }
    </style>

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
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <p><strong class="headings-color">Sales-Rep Targets (Product Wise)</strong></p>
                                            <p class="text-muted">Sales-Rep Targets Result.</p>
                                        </div>
                                        
                                    </div>
                                </div>
                                <div class="col-lg-12 card-form__body">
                                    <div class="table-responsive border-bottom" style="padding: 10px;">

                                        <table class="table mb-0 thead-border-top-0" id="ProductViceTargetsTable">
                                            <thead>
                                                <tr>
                                                    <th>#</th>
                                                    <th>Product Name</th>
                                                    <th>Quantity</th>
                                                    <th>Start Date</th>
                                                    <th>End Date</th>
                                                    <th>Status</th>
                                                    <th></th>
                                                </tr>
                                            </thead>
                                            <tbody class="list">
                                                <?php
                                                    $getProductViceTargets=$conn->query("SELECT * FROM tbl_rep_target_qty_wise ORDER BY id ASC");
                                                    while ($PVTrs=$getProductViceTargets->fetch_array()) {
                                                        $PId = $PVTrs[0];
                                                        $PItemId = $PVTrs[1];
                                                        $PQty = $PVTrs[2];
                                                        $PValidFrom = $PVTrs[3];
                                                        $PValidTo = $PVTrs[4];
                                                        $PStatus = $PVTrs[5];

                                                        $ItemDetails=$conn->query("SELECT * FROM tbl_item WHERE itemId='$PItemId'");
                                                        if ($IDrs=$ItemDetails->fetch_array()) {
                                                            $ItemCode = $IDrs[1];
                                                            $ItemName = $IDrs[2];
                                                        }

                                                ?>
                                                    <tr>
                                                        <td><?php echo $PId; ?></td>
                                                        <td><?php echo $ItemName.' ('.$ItemCode.')'; ?></td>
                                                        <td><?php echo $PQty; ?></td>
                                                        <td><?php echo $PValidFrom; ?></td>
                                                        <td><?php echo $PValidTo; ?></td>
                                                        <td><?php echo $PStatus; ?></td>
                                                        <td>View</td>
                                                    </tr>
                                                <?php } ?>
                                            </tbody>
                                        </table>

                                    </div>
                                </div>
                            </div>
                        </div>


                        
                    </div>

                    <!---------------AMOUNT VICE-------------->

                    <div class="container-fluid page__container">

                        

                        
                        <div class="card card-form">
                            <div class="row no-gutters">
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <p><strong class="headings-color">Sales-Rep Targets (Amount Wise)</strong></p>
                                            <p class="text-muted">Sales-Rep Targets Result.</p>
                                        </div>
                                        
                                    </div>
                                </div>
                                <div class="col-lg-12 card-form__body">
                                    <div class="table-responsive border-bottom" style="padding: 10px;">

                                        <table class="table mb-0 thead-border-top-0" id="ProductViceTargetsTable">
                                            <thead>
                                                <tr>
                                                    <th>#</th>
                                                    <th>Amount (.Rs)</th>
                                                    <th>Start Date</th>
                                                    <th>End Date</th>
                                                    <th></th>
                                                </tr>
                                            </thead>
                                            <tbody class="list">
                                                <?php
                                                    $getAmountViceTargets=$conn->query("SELECT * FROM tbl_rep_target_amount_wise ORDER BY id ASC");
                                                    while ($AVTrs=$getAmountViceTargets->fetch_array()) {
                                                        $AId = $AVTrs[0];
                                                        $Amount = $AVTrs[1];
                                                        $AValidFrom = $AVTrs[2];
                                                        $AValidTo = $AVTrs[3];
                                                ?>
                                                    <tr>
                                                        <td><?php echo $AId; ?></td>
                                                        <td><?php echo number_format($Amount,2); ?></td>
                                                        <td><?php echo $AValidFrom; ?></td>
                                                        <td><?php echo $AValidTo; ?></td>
                                                        <td>View</td>
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

   


</body>
</html>