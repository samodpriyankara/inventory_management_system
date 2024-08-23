<?php
    session_start();
    require 'database/db.php';
    $db=new DB();
    $conn=$db->connect();
    date_default_timezone_set('Asia/Colombo');
    $currentDate=date('Y-m-d');

    $RouteID=base64_decode($_GET['s']);

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
                            <h3>All Outlet</h3>
                        </div>
                        <div class="col-md-6">
                            <div class="pull-right" style="float: right;">
                                <a href="register_outlet" class="btn btn-primary">Register Outlet</a>
                            </div>
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

                                        <table class="table mb-0 thead-border-top-0" id="ShopTable">
                                            <thead>
                                                <tr>
                                                    <th>ID</th>
                                                    <th>Outlet Name</th>
                                                    <th>Owner Number</th>
                                                    <th>Contact Number</th>
                                                    <th>Shop Category</th>
                                                    <th>Route Name</th>
                                                    <th></th>
                                                </tr>
                                            </thead>
                                            <tbody class="list">
                                                <?php
                                                    $sql = "SELECT * FROM tbl_outlet tou INNER JOIN tbl_shop_category tsc ON tou.category=tsc.category_id INNER JOIN tbl_route tr ON tou.route_id=tr.route_id WHERE tou.route_id='$RouteID'";
                                                    $rs=$conn->query($sql);
                                                    while($row=$rs->fetch_array())
                                                    {
                                                        $OutletId=$row[0];                       
                                                        $OutletName=$row[1];                       
                                                        $OutletOwnerName=$row[2];                       
                                                        $OutletContact=$row[3];                       
                                                        $OutletAddress=$row[4];                       
                                                        $OutletLat=$row[5];                       
                                                        $OutletLon=$row[6];                       
                                                        $OutletImage=$row[7];                       
                                                        $OutletType=$row[8];                       
                                                        $OutletDiscount=$row[9];                       
                                                        $OutletLastOrderValue=$row[10];                       
                                                        $OutletCurrentMonthPurches=$row[11];                       
                                                        $OutletAvaragePurchases=$row[12];                       
                                                        $OutletOutstanding=$row[13];                       
                                                        $OutletCategory=$row[14];                       
                                                        $OutletSequence=$row[15];                       
                                                        $OutletGrade=$row[16];                       
                                                        $OutletCreatedDate=$row[17];                       
                                                        $OutletRouteId=$row[18];  

                                                        /////////////////////////////////////

                                                        $OutletCategoryName=$row[20];                     
                                                        $OutletRouteName=$row[22];                       

                                                ?>
                                                <tr>
                                                  <td><?php echo $OutletId; ?></td>
                                                  <td><?php echo $OutletName; ?></td>
                                                  <td><?php echo $OutletOwnerName; ?></td>
                                                  <td><?php echo $OutletContact; ?></td>
                                                  <td><?php echo $OutletCategoryName; ?></td>
                                                  <td><?php echo $OutletRouteName; ?></td>
                                                  <td>
                                                    <a href="shop_single?s=<?php echo base64_encode($OutletId); ?>" class="btn btn-secondary btn-sm" style="color: #FFF;">View</a>
                                                  </td>
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
            $('#ShopTable').DataTable({
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