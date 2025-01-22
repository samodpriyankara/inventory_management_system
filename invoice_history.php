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
                            <h3>Invoice History</h3>
                        </div>
                        <div class="col-md-6">
                            <div class="pull-right" style="float: right;">
                                <a href="create_invoice" class="btn btn-primary">Create Invoice</a>
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

                                        <table class="table mb-0 thead-border-top-0" id="InvoiceTable">
                                            <thead>
                                                <tr>
                                                    <th style="display: none;">#</th>
                                                    <th>Invoice Id</th>
                                                    <th>App Version</th>
                                                    <th>Payment Method</th>
                                                    <th>Payment Status</th>
                                                    <th>Shop Name</th>
                                                    <th>Route</th>
                                                    <th>Date</th>
                                                    <th class="text-right">Invoice Amount</th>
                                                    <th>Invoice Note</th>
                                                    <th></th>
                                                </tr>
                                            </thead>
                                            <tbody class="list">
                                                <?php

                                                    if($is_distributor){
                                                         $CashInvoicesql = "SELECT * FROM tbl_order tor INNER JOIN tbl_route tro ON tor.route_id=tro.route_id WHERE tor.distributor_id = '$user_id' ORDER BY tor.invoice_date DESC , tor.invoice_time DESC";
                                                    }else{
                                                        $CashInvoicesql = "SELECT * FROM tbl_order tor INNER JOIN tbl_route tro ON tor.route_id=tro.route_id ORDER BY tor.invoice_date DESC, tor.invoice_time DESC";
                                                    }

                                                    $CashInvoicers=$conn->query($CashInvoicesql);
                                                    while($CIrow=$CashInvoicers->fetch_array())
                                                    {
                                                        $Id=$CIrow[0];                       
                                                        $OrderId=$CIrow[1];   
                                                        $PaymentMethod=$CIrow[12];   
                                                        $InvoiceDate=$CIrow[3];   
                                                        $PaymentStatus=$CIrow[9];   
                                                        $AppVersion=$CIrow[13];   
                                                        $OutletId=$CIrow[15];   
                                                        $RouteName=$CIrow[20];   
                                                       
                                                        
                                                        $OutletDetailsSql = "SELECT * FROM tbl_outlet WHERE outlet_id='$OutletId' ";
                                                        $OutletDetailsrs=$conn->query($OutletDetailsSql);
                                                        if($ODrow=$OutletDetailsrs->fetch_array())
                                                        {
                                                            $OutletName=$ODrow[1];  
                                                        }
                                                        
                                                        
                                                        $InvoiceNoteSql = "SELECT invoice_note FROM tbl_invoice_note WHERE order_id='$OrderId' ";
                                                        $InvoiceNotesrs=$conn->query($InvoiceNoteSql);
                                                        if($INrow=$InvoiceNotesrs->fetch_array())
                                                        {
                                                            $Invoice_Note=$INrow[0];  
                                                        }else{
                                                            $Invoice_Note=NULL;  
                                                        }
                                                        
                                                        
                                                        
                                                ?>
                                                <?php
                                                    $GrandTotal=0; 
                                                    $getGrandTotalQuary = $conn->query("SELECT * FROM tbl_order_item_details toid INNER JOIN tbl_item tit ON toid.itemId=tit.itemId WHERE toid.order_id='$Id'");
                                                    while($GGTrs = $getGrandTotalQuary->fetch_array()){
                                                        $ProductOrderId=$GGTrs[0];                       
                                                        $ProductItemId=$GGTrs[1];                       
                                                        $ProductQty=$GGTrs[3];                       
                                                        $ProductDiscountedPrice=$GGTrs[4];                        
                                                        $ProductDiscountedValue=$GGTrs[5];                       
                                                        // $ProductPrice=$GGTrs[6];     
                                                        $RPID=$GGTrs[7]; 

                                                        //////////////////
                                                        $ItemCode=$GGTrs[9]; 
                                                        $ItemName=$GGTrs[10]; 
                                                        $ItemPrice=$GGTrs[11];

                                                        ////////Calculation//////////////
                                                        $DiscountedPrice = (double)$ItemPrice-(((double)$ItemPrice*(double)$ProductDiscountedValue)/100);
                                                        //With QTY
                                                        $ItemTotal = (double)$DiscountedPrice*(double)$ProductQty;
                                                        
                                                         //Grand Total
                                                        $GrandTotal += $ItemTotal;

                                                        ////////Calculation//////////////
                                                    } 
                                                    
                                                   
                                                        
                                                            $check_for_amendments = $conn->query("SELECT * FROM tbl_order_amendments WHERE order_id = '$Id'");
                                                            if($cars = $check_for_amendments->fetch_array()){
                                                                
                                                                $increase_value = $cars[1];
                                                                $decrease_value = $cars[2];
                                                                
                                                                if($increase_value > 0){
                                                                    $GrandTotal += $increase_value;
                                                                }
                                                                
                                                                if($decrease_value > 0){
                                                                    $GrandTotal -= $decrease_value;
                                                                }
                                                            }else{
                                                                $GrandTotal = $GrandTotal;
                                                            }
                                                ?>
                                                
                                                <tr>
                                                    <td style="display: none;"><?php echo $Id; ?></td>
                                                    <td><?php echo $OrderId; ?></td>
                                                    <td><?php echo $AppVersion; ?></td>
                                                    <td>
                                                        <?php if($PaymentMethod=='0' || $PaymentMethod=='1' || $PaymentMethod=='3'){ echo 'Cash'; }else{ echo 'Credit'; } ?>
                                                    </td>
                                                    <td>
                                                        <?php if($PaymentStatus=='0'){ ?>
                                                          <font style="font-weight: 700; color: #FF0000;">Not Paid</font>
                                                        <?php }else{ ?>
                                                          <font style="font-weight: 700; color: #26580F;">Paid</font>
                                                        <?php } ?>
                                                    </td>
                                                    <td><?php echo $OutletName; ?></td>
                                                    <td><?php echo $RouteName; ?></td>
                                                    <td><?php echo $InvoiceDate; ?></td>
                                                    <td class="text-right" style="font-weight: 600;">Rs. <?php echo number_format($GrandTotal,2); ?></td>
                                                     <td>
                                                            <?php if ($Invoice_Note != null) {
                                                            ?>
                                                                <p>
                                                                    <a class="btn btn-primary btn-sm" data-toggle="collapse" href="#collapseExample" role="button" aria-expanded="false" aria-controls="collapseExample">
                                                                        Note
                                                                    </a>

                                                                </p>
                                                            <?php } ?>
                                                            <div class="collapse" id="collapseExample">

                                                                <?php echo $Invoice_Note; ?>

                                                            </div>
                                                        </td>
                                                    <td><a href="invoice?i=<?php echo base64_encode($Id); ?>" class="btn btn-secondary btn-sm" style="color: #FFF;">View</a></td>
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
            $('#InvoiceTable').DataTable({
                "order": [[ 7, "desc" ]],
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