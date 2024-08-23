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
                    
                    <!-- <h3>All Outlet</h3>
                    <div class="pull-right">
                      <a href="register_outlet" class="btn btn-primary" style="color: #FFF;">Register Outlet</a>
                    </div> -->
                    <div class="row">
                        <div class="col-md-6">
                            <h3>Unproductive Shop</h3>
                        </div>

                    </div>

                        <div class="card card-form">
                            <div class="row no-gutters">
                                <!-- <div class="col-lg-4 card-body">
                                    <p><strong class="headings-color">Search</strong></p>
                                    <p class="text-muted">Add search functionality to your tables with List.js. Please read the <a href="http://listjs.com/" target="_blank">official plugin documentation</a> for a full list of options.</p>
                                </div> -->
                                <div class="col-lg-12 card-form__body">

                                    <div class="table-responsive border-bottom" style="padding: 10px;">

                                        <!-- <div class="search-form search-form--light m-3">
                                            <input type="text" class="form-control search" placeholder="Search">
                                            <button class="btn" type="button" role="button"><i class="material-icons">search</i></button>
                                        </div> -->

                                        <table class="table mb-0 thead-border-top-0" id="UnproductiveShopTable">
                                            <thead>
                                                <tr>
                                                    <th>#</th>
                                                    <th>Outlet Name</th>
                                                    <th>Route</th>
                                                    <th>Distributor</th>
                                                    <th>Sales-Rep Name</th>
                                                    <th>Reason</th>
                                                    <th>Remark</th>
                                                    <th>Date Time</th>
                                                    <!-- <th></th> -->
                                                </tr>
                                            </thead>
                                            <tbody class="list">
                                                <?php
                                                    if($is_distributor){
                                                      $getUnproductiveShopQuery=$conn->query("SELECT * FROM tbl_outlet_unproductive_remarks tour INNER JOIN tbl_distributor_has_tbl_user tdhtu ON tour.user_id=tdhtu.user_id WHERE tdhtu.distributor_id='$user_id'");
                                                    }else{
                                                      $getUnproductiveShopQuery=$conn->query("SELECT * FROM tbl_outlet_unproductive_remarks tour INNER JOIN tbl_distributor_has_tbl_user tdhtu ON tour.user_id=tdhtu.user_id ");
                                                    }
                                                        while ($GUSrs=$getUnproductiveShopQuery->fetch_array()) {
                                                            $UnproductiveId = $GUSrs[0];
                                                            $ResonId = $GUSrs[1];
                                                            $Remark = $GUSrs[2];
                                                            $OutletId = $GUSrs[3];
                                                            $RouteId = $GUSrs[4];
                                                            $SalesRepId = $GUSrs[5];
                                                            $ServerDate = $GUSrs[6];
                                                            $DeviceDate = $GUSrs[7];

                                                            $DistributorId = $GUSrs[9];

                                                          $getOutletQuery=$conn->query("SELECT outlet_name FROM tbl_outlet WHERE outlet_id='$OutletId'");
                                                          if ($GOrs=$getOutletQuery->fetch_array()) {
                                                            $OutletName = $GOrs[0];
                                                          }

                                                          $getRouteQuery=$conn->query("SELECT route_name FROM tbl_route WHERE route_id='$RouteId'");
                                                          if ($GRrs=$getRouteQuery->fetch_array()) {
                                                            $RouteName = $GRrs[0];
                                                          }

                                                          $getSalesRepQuery=$conn->query("SELECT name FROM tbl_user WHERE id='$SalesRepId'");
                                                          if ($GSRrs=$getSalesRepQuery->fetch_array()) {
                                                            $SalesRepName = $GSRrs[0];
                                                          }

                                                          $getUnproductiveResonQuery=$conn->query("SELECT reason FROM tbl_unproductive_reasons WHERE id='$ResonId'");
                                                          if ($GRRrs=$getUnproductiveResonQuery->fetch_array()) {
                                                            $UnproductiveReason = $GRRrs[0];
                                                          }

                                                          $getDistributorQuery=$conn->query("SELECT name FROM tbl_distributor WHERE distributor_id='$DistributorId'");
                                                          if ($GDrs=$getDistributorQuery->fetch_array()) {
                                                            $DistributorName = $GDrs[0];
                                                          }

                                                                
                                                ?>
                                                <tr>
                                                    <td><?php echo $UnproductiveId; ?></td>
                                                    <td><?php echo $OutletName; ?></td>
                                                    <td><?php echo $RouteName; ?></td>
                                                    <td><?php echo $DistributorName; ?></td>
                                                    <td><?php echo $SalesRepName; ?></td>
                                                    <td><?php echo $UnproductiveReason; ?></td>
                                                    <td><?php echo $Remark; ?></td>
                                                    <td><?php echo $DeviceDate; ?></td>
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
    $(document).ready( function () {
            $('#UnproductiveShopTable').DataTable({
                "order": [[ 0, "desc" ]],
                    dom: 'Bfrtip',
                    buttons: [
                        // 'copy', 'csv', 'excel', 'pdf', 'print'
                        'print', 'excel', 'pdf'
                    ]
            });
        } );
  </script>

</body>
</html>