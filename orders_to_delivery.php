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
                            <h3>Orders to Delivery</h3>
                        </div>
                    </div>

                        <div class="card card-form">
                            <div class="row no-gutters">
                                <div class="col-lg-12 card-form__body">

                                    <div class="border-bottom" style="padding: 10px;">

                                        <div class="row">

                                            <?php
                                                $Routesql = "SELECT * FROM tbl_route ORDER BY route_id ASC";
                                                $Routers=$conn->query($Routesql);
                                                while($Rrow=$Routers->fetch_array())
                                                {
                                                    $RouteId=$Rrow[0];                       
                                                    $RouteName=$Rrow[1];    
                                            ?>
                                            <div class="col-lg-3 col-md-6 col-sm-6">
                                              <a href="orders_delivery_single?r=<?php echo base64_encode($RouteId); ?>" style="text-decoration: none;">
                                              <div class="card card-stats" style="background-color: #F0F8FF; border-radius: 10px;">
                                                <div class="card-body" style="padding: 0rem !important;">
                                                  <div class="row">
                                                    <div class="col-5 col-md-4">
                                                      <div class="icon-big text-center icon-warning">
                                                        <img src="assets/img/icons/box.png" style="width: 100%;">
                                                      </div>
                                                    </div>
                                                    <div class="col-7 col-md-8">
                                                      <div class="numbers">
                                                        <p class="card-category" style="font-weight: 700; color: #000; float: right;">Route Name: <?php echo $RouteName; ?></p>
                                                        <p class="card-title" style="font-weight: 600; color: #000; font-size: 20px; float: right;">
                                                          <?php
                                                            if($is_distributor){
                                                                $RouteInvoiceCountSQL = "SELECT COUNT(*) FROM tbl_order tor INNER JOIN tbl_order_delivery tod ON tor.id=tod.order_id WHERE tor.distributor_id = '$user_id' AND tor.route_id='$RouteId' AND (tod.delivery_status='0' OR tod.delivery_status='1')";
                                                            }else{
                                                                $RouteInvoiceCountSQL = "SELECT COUNT(*) FROM tbl_order tor INNER JOIN tbl_order_delivery tod ON tor.id=tod.order_id WHERE tor.route_id='$RouteId' AND (tod.delivery_status='0' OR tod.delivery_status='1')";
                                                            }
                                                                $RouteInvoiceCountResult = mysqli_query($conn, $RouteInvoiceCountSQL);
                                                                $RouteInvoiceCount = mysqli_fetch_assoc($RouteInvoiceCountResult)['COUNT(*)'];
                                                                echo $RouteInvoiceCount;
                                                            ?>
                                                        <p>
                                                      </div>
                                                    </div>
                                                  </div>
                                                </div>
                                              </div>
                                              </a>
                                            </div>
                                          <?php } ?>

                                        </div>
                                        

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

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <!-- Chart JS -->
    <script src="assets/js/plugins/chartjs.min.js"></script>

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


</body>
</html>