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
                            <h3>Distributor Invoice History</h3>
                        </div>
                        <div class="col-md-6">
                            <div class="pull-right" style="float: right;">
                                <a href="create_distributor_invoice" class="btn btn-primary">Create Distributor Invoice</a>
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
                                                    <th>Distributor Name</th>
                                                    <th>GrandTotal</th>
                                                    <th>Payment Status</th>
                                                    <th>Invoice Date/Time</th>
                                                    <th></th>
                                                    <th></th>
                                                </tr>
                                            </thead>
                                            <tbody class="list">
                                                <?php

                                                    if($is_distributor){
                                                        $sql = "SELECT * FROM tbl_distributor_product_invoice tdpi INNER JOIN tbl_distributor td ON tdpi.distributor_id=td.distributor_id WHERE tdpi.distributor_id='$user_id' ORDER BY tdpi.distributor_invoice_id DESC";
                                                    }else{
                                                        $sql = "SELECT * FROM tbl_distributor_product_invoice tdpi INNER JOIN tbl_distributor td ON tdpi.distributor_id=td.distributor_id ORDER BY tdpi.distributor_invoice_id DESC";
                                                    }

                                                    $rs=$conn->query($sql);
                                                    while($row=$rs->fetch_array())
                                                    {
                                                        $DistributorInvoiceId=$row[0];                       
                                                        $DistributorId=$row[1];                       
                                                        $AdminId=$row[2];                       
                                                        $Note=$row[3];                       
                                                        $Stat=$row[4];                       
                                                        $Pay=$row[5];                       
                                                        $GrandTotal=(double)$row[6];                       
                                                        $DistributorInvoiceDateTime=$row[7];       
                                                        ////                
                                                        $DistributorName=$row[9];                           
                                                        $DistributorAddress=$row[10];                           
                                                        $DistributorContactNumber=$row[11];                           

                                                ?>
                                                <tr>
                                                  <td><?php echo $DistributorInvoiceId+10000; ?></td>
                                                  <td><?php echo $DistributorName; ?></td>
                                                  <td><font style="float: right; font-weight: 600;"><?php echo number_format($GrandTotal,2); ?></font></td>
                                                  <td>
                                                        <?php if($Pay=='0'){ ?>
                                                            <font style="color: #FF0000; font-weight: 600;">Pending Payment</font>
                                                        <?php }else{ ?>
                                                            <font style="color: #26580F; font-weight: 600;">Paid</font>
                                                        <?php } ?>
                                                  </td>
                                                  <td><?php echo $DistributorInvoiceDateTime; ?></td>
                                                  <td>
                                                    <a href="dis_invoice?d=<?php echo base64_encode($DistributorInvoiceId); ?>" class="btn btn-secondary btn-sm" style="color: #FFF;">View</a>
                                                  </td>
                                                  
                                                  <?php
                                                    
                                                    if(isset($_SESSION['EDT_ENABLED']) && $_SESSION['EDT_ENABLED'] == true){
                                                        
                                                        ?>
                                                            <td> <button type="button" onclick="remove_item('<?php echo $DistributorInvoiceId;?>')" class="btn btn-danger pull-right"><i class="fa fa-remove"></i></button> </td>
                                                        <?php
                                                        
                                                        
                                                    }
                                                
                                                ?>
                                                  
                                                  
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
        
      
        
        
        
         function remove_item(distributor_invoice_id){
             
             
            
            
            $.ajax({
                    url:'scripts/remove_dist_invoice.php',
                    type:'POST',
                    data:{
                        invoice_id:distributor_invoice_id
                    },
                    cache: false,
                    // contentType: false,
                    // processData: false,

                    beforeSend:function(){
                        // $("#btn-auth-now").prop("disabled",true);
                          $("button").prop("disabled", true);

                    },
                    success:function(data){
                        
                        
                        
                        var json=JSON.parse(data);
                        if(json.result){
                            location.reload();
                        }else{
                            alert(json.msg);
                        }
                       
                        $("button").prop("disabled", false);
                                        
                    },
                    error:function(err,xhr,data){
                            console.log(err);
                    }

                });
            
            
            
            
        }
        
        
        
        
        
        
        
        
        
        
        
    </script>

</body>
</html>