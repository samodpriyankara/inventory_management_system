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
                                <h3>Create and update routes</h3>
                            </div>
                            <!-- <div class="col-md-6">
                                <div class="pull-right" style="float: right;">
                                    <a href="create_invoice" class="btn btn-primary">Create Invoice</a>
                                </div>
                            </div> -->
                        </div>
                        <div class="card card-form">
                            <div class="row no-gutters">
                                
                                <div id="Register-Outlet" class="col-lg-4 card-form__body card-body">
                                    <div class="form-group">
                                        <label>Route Name</label>
                                        <input id="txt-route-name" type="text" class="form-control" placeholder="Ex : XXXXX-XXXXX" required>
                                    </div>
                                        
                                    <div class="form-group">
                                        <label>City</label>
                                        <input id="txt-route-city" type="text" class="form-control" placeholder="Ex : XXXXX" list="lst-city" required>
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

                                        <table class="table mb-0 thead-border-top-0" id="RouteTable">
                                            <thead>
                                                <tr>
                                                    <th>Route Name</th>
                                                    <th>Created Date</th>
                                                    <th>Status</th>
                                                    <th></th>
                                                </tr>
                                            </thead>
                                            <tbody class="list">
                                                <?php
                                                    $getRoutesQuery=$conn->query("SELECT * FROM tbl_route WHERE status = 1");
                                                    $statusText="";
                                                    while ($route=$getRoutesQuery->fetch_array()) {

                                                      $status=$route[3];
                                                      if($status==0){
                                                        $statusText="Deactive";
                                                      }else if($status==1){
                                                        $statusText="Active";
                                                      }else{
                                                        $statusText="Unknown";
                                                      }
                                                      
                                                ?>

                                            <tr>
                                                <td><?php echo $route[1];?></td>
                                                <td><?php echo $route[2];?></td>
                                                <td><?php echo $statusText;?></td>
                                                <td></td>
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
            $('#RouteTable').DataTable({
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
    function editRoute(routeId,routeName){
      alert(routeId+" ---- "+routeName);
    }





    $(document).ready(function() {

      $("#btn-submit").click(function (){

        var routeName=$("#txt-route-name").val();
        var cityName=$("#txt-route-city").val();

           $.ajax({

                    beforeSend : function() {
                        $("#progress_alert").addClass('show'); 
                    },

                        url: "scripts/upload_route_details.php",
                        type: 'POST',
                        data: {
                            rname:routeName,
                            cname:cityName
                        },
                        
                        success: function (data) {
                    
                            $("#progress_alert").removeClass('show');
                            
                            var json=JSON.parse(data);
                            
                            if(json.result){
                               // $("#outlet-record-area").html(json.data);
                                
                               $("#success_msg").html(json.msg);
                               $("#success_alert").addClass('show'); 
                               
                                setTimeout(function(){$("#success_alert").removeClass('show');  }, 1000);
                                location.reload();
                            }else{
                                $("#danger_alert").addClass('show');
                                setTimeout(function(){ $("#danger_alert").removeClass('show'); }, 1000);
                            }
                            
                        },error: function (jqXHR, textStatus, errorThrown) {
                            alert(errorThrown+"");
                        }

                    });


      });



     
    });
  </script>

  
</body>
</html>