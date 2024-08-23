<?php
    session_start();
    require 'database/db.php';
    $db=new DB();
    $conn=$db->connect();
    date_default_timezone_set('Asia/Colombo');
    $currentDate=date('Y-m-d');

    $DistributorID=base64_decode($_GET['d']);

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
    $sql = "SELECT * FROM tbl_distributor WHERE distributor_id='$DistributorID'";
    $rs=$conn->query($sql);
    if($row=$rs->fetch_array())
    {
      // $DistributorID=$row[0];                       
      $DistributorName=$row[1];                       
      $DistributorAddress=$row[2];                       
      $DistributorContactNumber=$row[3];                     
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
                                <h3>Distributor Profile</h3>
                            </div>
                            <!-- <div class="col-md-6">
                                <div class="pull-right" style="float: right;">
                                    <a href="create_invoice" class="btn btn-primary">Create Invoice</a>
                                </div>
                            </div> -->
                        </div>
                        <div class="row">

                            <div class="col-md-4">
                                <div class="card">

                                    <div class="author">
                                        <center>
                                        <a href="#!">
                                            <img class="avatar border-gray" src="assets/img/icons/distribution-center.png">
                                            <h5 class="title" style="color: #000;"><?php echo $DistributorName; ?></h5>
                                        </a>
                                        </center>
                                    </div>
                                    <p class="description text-center">
                                        Contact Number : <a href="tel:<?php echo $OutletContact; ?>" style='color: #000;'><?php echo $DistributorContactNumber; ?></a><br>
                                        Address : <?php echo $DistributorAddress; ?>
                                    </p>

                                    <hr>
                                    <div class="button-container">
                                      <div class="row">
                                        <div class="col-lg-4 col-md-6 col-6 text-center">
                                          <h5>Rs.
                                          <?php 
                                            $OutstandingSumSQL="SELECT SUM(`grand_total`) FROM `tbl_distributor_product_invoice` WHERE distributor_id='$DistributorID' AND pay='0' AND stat='1'";
                                            $OutstandingSumResult = mysqli_query($conn, $OutstandingSumSQL);
                                            $OutstandingSumTotal = mysqli_fetch_assoc($OutstandingSumResult)['SUM(`grand_total`)'];
                                            echo number_format($OutstandingSumTotal,2);
                                          ?>

                                          <br><small>Outstanding</small></h5>
                                        </div>
                                        <div class="col-lg-4 col-md-6 col-6 text-center">
                                          <h5>
                                            <?php
                                              $PaidInvoiceCountSQL = "SELECT COUNT(*) FROM tbl_distributor_product_invoice WHERE distributor_id='$DistributorID' AND pay='1' ";
                                              $PaidInvoiceCountResult = mysqli_query($conn, $PaidInvoiceCountSQL);
                                              $PaidInvoiceCount = mysqli_fetch_assoc($PaidInvoiceCountResult)['COUNT(*)'];
                                              echo $PaidInvoiceCount;
                                            ?>
                                            <br><small>Paid Invoice</small></h5>
                                        </div>
                                        <div class="col-lg-4 text-center">
                                          <h5>
                                            <?php
                                              $UnPaidInvoiceCountSQL = "SELECT COUNT(*) FROM tbl_distributor_product_invoice WHERE distributor_id='$DistributorID' AND pay='0' AND stat='1' ";
                                              $UnPaidInvoiceCountResult = mysqli_query($conn, $UnPaidInvoiceCountSQL);
                                              $UnPaidInvoiceCount = mysqli_fetch_assoc($UnPaidInvoiceCountResult)['COUNT(*)'];
                                              echo $UnPaidInvoiceCount;
                                            ?>
                                            <br><small>Credit Invoice</small></h5>
                                        </div>



                                      </div>


                                      <br>

                                        <?php if($is_distributor){ }else{ ?>
                                        <div>
                                          <button class="btn btn-dark" data-toggle="modal" data-target="#RouteModalCenter" style="width: 100%">Add Routes</button>
                                        </div>
                                        <?php } ?>


                                    </div>
                                </div>

                                <div class="card">
                                    <div class="card-header">
                                        <h4 class="card-title">Routes</h4>
                                    </div>
                                    <div class="card-body">
                                        <table class="table mb-0 thead-border-top-0" id="DistributorRootesTable">
                                            <thead>
                                                <th>Route Name</th>
                                                <th><font style="float: right;">Shop Count</font></th>
                                                <?php if($is_distributor){ }else{ ?>
                                                <th></th>
                                                <?php } ?>
                                            </thead>
                                            <tbody class="list" id="staff">
                                                <?php
                                                    $DistributorRouteSql = "SELECT * FROM tbl_distributor_has_route tdhr INNER JOIN tbl_route tr ON tdhr.route_id=tr.route_id WHERE tdhr.distributor_id = '$DistributorID' ORDER BY tdhr.id ASC";
                                                    $DistributorRouteQuery=$conn->query($DistributorRouteSql);
                                                    while($DHRrow=$DistributorRouteQuery->fetch_array())
                                                    {
                                                        $DistributorRouteId=$DHRrow[1];                       
                                                        $DistributorRouteName=$DHRrow[4];

                                                        $DistributorRouteShopCountSQL = "SELECT COUNT(*) FROM tbl_outlet WHERE route_id='$DistributorRouteId'";
                                                        $DistributorRouteShopCountResult = mysqli_query($conn, $DistributorRouteShopCountSQL);
                                                        $DistributorRouteShopCountCount = mysqli_fetch_assoc($DistributorRouteShopCountResult)['COUNT(*)'];
                                                ?>
                                                <tr>
                                                    <td><small class="text-muted" style="color: #000 !important; font-weight: 600;"><?php echo $DistributorRouteName; ?></small></td>
                                                    <td><small class="text-muted" style="float: right; color: #000 !important; font-weight: 600;"><?php echo $DistributorRouteShopCountCount; ?></small></td>

                                                    <?php if($is_distributor){ }else{ ?>
                                                    <td>
                                                        <form id="Remove-Distributor-Route">
                                                            <input type="hidden" name="distributor_id" value="<?php echo $DistributorID; ?>" readonly required>
                                                            <input type="hidden" name="route_id" value="<?php echo $DistributorRouteId; ?>" readonly required>
                                                            <button type="submit" class="btn btn-danger btn-sm" style="float: right;"> X </button>
                                                        </form>
                                                    </td>
                                                    <?php } ?>

                                                </tr>
                                                <?php } ?>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>

                            </div>

                            <div class="col-md-8">
                                <div class="card-header card-header-tabs-basic nav" role="tablist">
                                    <ul class="nav nav-tabs">
                                        <br><br><li><a href="#Records" class="active" data-toggle="tab">Records</a></li>
                                        <?php if($is_distributor){ }else{ ?>
                                        <br><br><li><a href="#DistributorInvoice" data-toggle="tab">Dis Invoice</a></li>
                                        <?php } ?>
                                        <br><br><li><a href="#SalesRep" data-toggle="tab">Sales-Reps</a></li>
                                        <?php if($is_distributor){ }else{ ?>
                                        <br><br><li><a href="#CashInvoice" data-toggle="tab">Cash Invoice</a></li>
                                        <br><br><li><a href="#CreditInvoice" data-toggle="tab">Credit Invoice</a></li>
                                        <br><br><li><a href="#ReternOrders" data-toggle="tab">Return Orders</a></li>
                                        <?php } ?>
                                        <br><br><li><a href="#Summery" data-toggle="tab">Summary</a></li>
                                        <br><br><li><a href="#Targets" data-toggle="tab">Targets</a></li>
                                    </ul>
                                </div><br>
                                <div class="card">
                                    <div id="exTab2" class="container"> 

                                            <div class="card-body tab-content">
                                              <div class="tab-pane active" id="Records" style="padding: 5px;">
                                                
                                                <?php if($is_distributor){ }else{ ?>
                                                <form id="Write-Distributor-Record" method="POST">
                                                  <input type="hidden" name="distributor_id" class="form-control" value="<?php echo $DistributorID; ?>" readonly required>
                                                  <textarea name="record" id="record-textarea" style="padding: 8px; resize: auto; max-height: 100% !important;" cols="30" rows="5" class="form-control bg-transparent" placeholder="Please type what you want...."></textarea>
                                                  <br>
                                                  <button type="submit" class="btn btn-dark" id="btn-add-record">Add Records</button>
                                                </form>

                                                <hr>

                                            <?php } ?>

                                                <div id="distributor-record-area">
                                                    <?php
                                                        $getDistributorRecordQuery=$conn->query("SELECT * FROM distributor_records WHERE distributor_id='$DistributorID' ORDER BY distributor_record_id DESC ");
                                                        while ($GDRrs=$getDistributorRecordQuery->fetch_array()) {
                                                    ?>
                                                    <div class="profile-uoloaded-post border-bottom-1 pb-5">
                                                        <h5>Remark Note <span class="pull-right" style="font-size: 12px; float: right;"><?php echo $GDRrs[3]; ?></span></h5>
                                                        <p><?php echo nl2br($GDRrs[2]); ?></p>
                                                                                    
                                                        <hr>
                                                    </div>
                                                    <?php } ?>
                                                </div>




                                              </div>

                                              <div class="tab-pane" id="DistributorInvoice">
                                                
                                                <div class="table-responsive" style="overflow-y: hidden !important;">
                                                    <table id="DistributoInvoiceTable" class="display" style="width:100%;">
                                                        <thead>
                                                            <th>ID</th>
                                                            <th>Distributor Name</th>
                                                            <th>GrandTotal</th>
                                                            <th>Payment Status</th>
                                                            <th>Invoice Date/Time</th>
                                                            <th></th>
                                                        </thead>
                                                        <tbody>
                                                        <?php
                                                            $DistributorInvoiceSql = "SELECT * FROM tbl_distributor_product_invoice tdpi INNER JOIN tbl_distributor td ON tdpi.distributor_id=td.distributor_id WHERE tdpi.distributor_id='$DistributorID' ORDER BY tdpi.distributor_invoice_id DESC";
                                                            $DisInvoicers=$conn->query($DistributorInvoiceSql);
                                                            while($DIrow=$DisInvoicers->fetch_array())
                                                            {
                                                                $DistributorInvoiceId=$DIrow[0];                       
                                                                $DistributorId=$DIrow[1];                       
                                                                $AdminId=$DIrow[2];                       
                                                                $Note=$DIrow[3];                       
                                                                $Stat=$DIrow[4];                       
                                                                $Pay=$DIrow[5];                       
                                                                $GrandTotal=(double)$DIrow[6];                       
                                                                $DistributorInvoiceDateTime=$DIrow[7];       
                                                                ////                
                                                                $DistributorName=$DIrow[9];                           
                                                                $DistributorAddress=$DIrow[10];                           
                                                                $DistributorContactNumber=$DIrow[11];       
                                                        ?>
                                                        <tr>
                                                            <td>AMA/DIN/2022/<?php echo $DistributorInvoiceId+10000; ?></td>
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
                                                        </tr> 
                                                      <?php } ?>
                                                        
                                                    </tbody>
                                                  </table>
                                                </div>


                                            </div>

                                              
                                              <div class="tab-pane" id="SalesRep">
                                                
                                                <div class="table-responsive" style="overflow-y: hidden !important;">
                                                    <table id="SalesRepTable" class="display" style="width:100%;">
                                                        <thead>
                                                            <th>#</th>
                                                            <th>Rep Name</th>
                                                            <th>Rep User Name</th>
                                                            <th>Contact Number</th>
                                                            <th>Address</th>
                                                            <th></th>
                                                        </thead>
                                                        <tbody>
                                                        <?php
                                                            $ReturnInvoicesql = "SELECT * FROM tbl_user tu INNER JOIN tbl_distributor_has_tbl_user tdhu ON tu.id=tdhu.user_id WHERE tdhu.distributor_id = '$DistributorID' ORDER BY tu.id DESC";
                                                            $ReturnInvoicers=$conn->query($ReturnInvoicesql);
                                                            while($RIrow=$ReturnInvoicers->fetch_array())
                                                            {
                                                                $Id=$RIrow[0];                       
                                                                $RepName=$RIrow[1];   
                                                                $RepUserName=$RIrow[2];     
                                                                $CreatedDate=$RIrow[4];   
                                                                $RepAddress=$RIrow[5];   
                                                                $RepContactNumber=$RIrow[6];    
                                                                $RepStatus=$RIrow[7];    
                                                                $RepLoginStatus=$RIrow[8];  
                                                        ?>
                                                        <tr>
                                                            <td><?php echo $Id; ?></td>
                                                            <td><?php echo $RepName; ?></td>
                                                            <td><?php echo $RepUserName; ?></td>
                                                            <td><?php echo $RepContactNumber; ?></td>
                                                            <td><?php echo $RepAddress; ?></td>
                                                            <td><a href="sales_rep_single?s=<?php echo base64_encode($Id); ?>" class="btn btn-secondary btn-sm" style="color: #FFF;">View</a></td>
                                                        </tr> 
                                                      <?php } ?>
                                                        
                                                    </tbody>
                                                  </table>
                                                </div>


                                            </div>


                                            <div class="tab-pane" id="CashInvoice">
                                                
                                                <div class="table-responsive" style="overflow-y: hidden !important;">
                                                    <table id="PaidTable" class="display" style="width:100%;">
                                                        <thead>
                                                            <th>Invoice Id</th>
                                                            <th>Payment Method</th>
                                                            <th>App Version</th>
                                                            <th>Route</th>
                                                            <th>Date</th>
                                                            <th class="text-right">Invoice Amount</th>
                                                            <th></th>
                                                        </thead>
                                                        <tbody>
                                                        <?php
                                                            $CashInvoicesql = "SELECT * FROM tbl_order tor INNER JOIN tbl_route tro ON tor.route_id=tro.route_id WHERE tor.distributor_id='$DistributorID' AND tor.payment_method='0' ORDER BY id DESC";
                                                            $CashInvoicers=$conn->query($CashInvoicesql);
                                                            while($CIrow=$CashInvoicers->fetch_array())
                                                            {
                                                                $CashId=$CIrow[0];                       
                                                                $CashOrderId=$CIrow[1];   
                                                                $CashPaymentMethod=$CIrow[12];   
                                                                $CashInvoiceDate=$CIrow[3]; 
                                                                $CashAppVersion=$CIrow[13];  
                                                                $CashRouteName=$CIrow[20];   
                                                        ?>
                                                        <?php
                                                            $CashGrandTotal=0;
                                                            $getCashGrandTotalQuary = $conn->query("SELECT * FROM tbl_order_item_details WHERE order_id='$CashId'");
                                                            while($GGTCrs = $getCashGrandTotalQuary->fetch_array()){
                                                                    $CashProductOrderId=$GGTCrs[0];                       
                                                                    $CashProductItemId=$GGTCrs[1];                       
                                                                    $CashProductQty=$GGTCrs[3];                       
                                                                    $CashProductDiscountedPrice=$GGTCrs[4];                        
                                                                    $CashProductDiscountedValue=$GGTCrs[5];                       
                                                                    $CashProductPrice=$GGTCrs[6];     
                                                                    $CashRPID=$GGTCrs[7]; 

                                                                    ////////Calculation//////////////
                                                                    $CashDiscountedPrice = (double)$CashProductPrice-(((double)$CashProductPrice*(double)$CashProductDiscountedValue)/100);
                                                                    //With QTY
                                                                    $CashItemTotal = (double)$CashDiscountedPrice*(double)$CashProductQty;

                                                                    //Grand Total
                                                                    $CashGrandTotal += $CashItemTotal;
                                                                    ////////Calculation//////////////
                                                            }
                                                        ?>
                                                        <tr>
                                                            <td><?php echo $CashOrderId; ?></td>
                                                            <td>
                                                                <?php if($CashPaymentMethod=='0' || $CashPaymentMethod=='1' || $CashPaymentMethod=='3'){ echo 'Cash'; }else{ echo 'Credit'; } ?>
                                                            </td>
                                                            <td><?php echo $CashAppVersion; ?></td>
                                                            <td><?php echo $CashRouteName; ?></td>
                                                            <td><?php echo $CashInvoiceDate; ?></td>
                                                            <td class="text-right" style="font-weight: 600;">Rs. <?php echo number_format($CashGrandTotal,2); ?></td>
                                                            <td><a href="invoice?i=<?php echo base64_encode($CashId); ?>" class="btn btn-secondary btn-sm" style="color: #FFF;">View</a></td>
                                                        </tr>
                                                      <?php } ?>
                                                        
                                                    </tbody>
                                                  </table>
                                                </div>


                                            </div>
                                                <div class="tab-pane" id="CreditInvoice">
                                                  
                                                    <div class="table-responsive" style="overflow-y: hidden !important;">
                                                      <table id="CreditTable" class="display" style="width:100%;">
                                                        <thead>
                                                          <th>Invoice Id</th>
                                                          <th>Payment Method</th>
                                                          <th>App Version</th>
                                                          <th>Route</th>
                                                          <th>Date</th>
                                                          <th class="text-right">Invoice Amount</th>
                                                          <th></th>
                                                        </thead>
                                                        <tbody>
                                                          <?php
                                                            $CreditInvoicesql = "SELECT * FROM tbl_order tor INNER JOIN tbl_route tro ON tor.route_id=tro.route_id WHERE tor.distributor_id='$DistributorID' AND tor.payment_method='2' ORDER BY id DESC";
                                                            $CreditInvoicers=$conn->query($CreditInvoicesql);
                                                            while($CRIrow=$CreditInvoicers->fetch_array())
                                                            {
                                                              $CreditId=$CRIrow[0];                       
                                                              $CreditOrderId=$CRIrow[1];   
                                                              $CreditPaymentMethod=$CRIrow[12];   
                                                              $CreditInvoiceDate=$CRIrow[3];   
                                                              $CreditAppVersion=$CRIrow[13];   
                                                              $CreditRouteName=$CRIrow[20];   
                                                          ?>
                                                            <?php
                                                                $CreditGrandTotal=0;
                                                                $getCreditGrandTotalQuary = $conn->query("SELECT * FROM tbl_order_item_details WHERE order_id='$CreditId'");
                                                                while($GGTCRrs = $getCreditGrandTotalQuary->fetch_array()){
                                                                        $CreditProductOrderId=$GGTCRrs[0];                       
                                                                        $CreditProductItemId=$GGTCRrs[1];                       
                                                                        $CreditProductQty=$GGTCRrs[3];                       
                                                                        $CreditProductDiscountedPrice=$GGTCRrs[4];                        
                                                                        $CreditProductDiscountedValue=$GGTCRrs[5];                       
                                                                        $CreditProductPrice=$GGTCRrs[6];     
                                                                        $CreditRPID=$GGTCRrs[7]; 

                                                                        ////////Calculation//////////////
                                                                        $CreditDiscountedPrice = (double)$CreditProductPrice-(((double)$CreditProductPrice*(double)$CreditProductDiscountedValue)/100);
                                                                        //With QTY
                                                                        $CreditItemTotal = (double)$CreditDiscountedPrice*(double)$CreditProductQty;

                                                                        //Grand Total
                                                                        $CreditGrandTotal += $CreditItemTotal;
                                                                        ////////Calculation//////////////
                                                                }
                                                              ?>
                                                          <tr>
                                                            <td><?php echo $CreditOrderId; ?></td>
                                                            <td>
                                                                <?php if($CreditPaymentMethod=='0' || $CreditPaymentMethod=='1' || $CreditPaymentMethod=='3'){ echo 'Cash'; }else{ echo 'Credit'; } ?>
                                                            </td>
                                                            <td><?php echo $CreditAppVersion; ?></td>
                                                            <td><?php echo $CreditRouteName; ?></td>
                                                            <td><?php echo $CreditInvoiceDate; ?></td>
                                                            <td class="text-right" style="font-weight: 600;">Rs. <?php echo number_format($CreditGrandTotal,2); ?></td>
                                                            <td><a href="invoice?i=<?php echo base64_encode($CreditId); ?>" class="btn btn-secondary btn-sm" style="color: #FFF;">View</a></td>
                                                          </tr>
                                                          <?php } ?>
                                                            
                                                        </tbody>
                                                      </table>
                                                    </div>



                                                </div>

                                            <div class="tab-pane" id="ReternOrders">
                                                
                                                <div class="table-responsive" style="overflow-y: hidden !important;">
                                                    <table id="ReturnTable" class="display" style="width:100%;">
                                                      <thead>
                                                          <tr>   
                                                              <th>Invoice Id</th>
                                                              <th>Return Type</th>
                                                              <th>App Version</th>
                                                              <th>SalesRep Name</th>
                                                              <th>Route</th>
                                                              <th>Date</th>
                                                              <th class="text-right">Invoice Amount</th>
                                                              <th></th>
                                                          </tr>
                                                      </thead>
                                                      <tbody>
                                                        <?php
                                                          $ReturnInvoicesql = "SELECT * FROM tbl_return_order tor INNER JOIN tbl_route tro ON tor.route_id=tro.route_id INNER JOIN tbl_user tus ON tor.user_id=tus.id WHERE tor.distributor_id='$DistributorID' ORDER BY tor.id DESC";
                                                          $ReturnInvoicers=$conn->query($ReturnInvoicesql);
                                                          while($RIrow=$ReturnInvoicers->fetch_array())
                                                          {
                                                            $ReturnId=$RIrow[0];                       
                                                            $ReturnOrderId=$RIrow[1];   
                                                            $ReturnType=$RIrow[2];     
                                                            $ReturnInvoiceDate=$RIrow[3];   
                                                            $ReturnAppVersion=$RIrow[9];   
                                                            $ReturnRouteName=$RIrow[16];   
                                                            $ReturnSalesRepName=$RIrow[20];   
                                                        ?>
                                                        <?php
                                                          $ReturnGrandTotal=0;
                                                          $getReturnGrandTotalQuary = $conn->query("SELECT * FROM tbl_return_order_item_details WHERE order_id='$ReturnId'");
                                                          while($GGTRrs = $getReturnGrandTotalQuary->fetch_array()){
                                                            $ReturnProductOrderId=$GGTRrs[0];                       
                                                            $ReturnProductItemId=$GGTRrs[1];                       
                                                            $ReturnProductQty=$GGTRrs[3];                       
                                                            $ReturnProductDiscountedPrice=$GGTRrs[4];                        
                                                            $ReturnProductDiscountedValue=$GGTRrs[5];                       
                                                            $ReturnProductPrice=$GGTRrs[6];     
                                                            $ReturnRPID=$GGTRrs[7]; 
                                                            ////////Calculation//////////////
                                                            $ReturnDiscountedPrice = (double)$ReturnProductPrice-(((double)$ReturnProductPrice*(double)$ReturnProductDiscountedValue)/100);
                                                            //With QTY
                                                            $ReturnItemTotal = (double)$ReturnDiscountedPrice*(double)$ReturnProductQty;

                                                            //Grand Total
                                                            $ReturnGrandTotal += $ReturnItemTotal;
                                                            ////////Calculation//////////////
                                                          }
                                                        ?>

                                                        <tr>
                                                          <td><?php echo $ReturnOrderId; ?></td>
                                                          <td>
                                                              <?php if($ReturnType=='1'){ echo 'Damage Return'; }else{ echo 'Sales Return'; } ?>
                                                          </td>
                                                          <td><?php echo $ReturnAppVersion; ?></td>
                                                          <td><?php echo $ReturnSalesRepName; ?></td>
                                                          <td><?php echo $ReturnRouteName; ?></td>
                                                          <td><?php echo $ReturnInvoiceDate; ?></td>
                                                          <td class="text-right" style="font-weight: 600;">Rs. <?php echo number_format($ReturnGrandTotal,2); ?></td>
                                                          <td><a href="return_invoice?i=<?php echo base64_encode($ReturnId); ?>" class="btn btn-secondary btn-sm" style="color: #FFF;">View</a></td>
                                                        </tr>
                                                                                    
                                                        <?php } ?>
                                                      </tbody>
                                                                                
                                                    </table>
                                                </div>


                                            </div>

                                            <div class="tab-pane" id="Summery">

                                                <h4 style="font-weight: 600;">Selling Product Summery</h4>
                                                <input type="hidden" id="selling_summery_distributor_id" value="<?php echo $DistributorID; ?>">

                                                <div class="row">
                                                  <div class="col-xl-6">
                                                      <canvas id="SellingItemPieChart" width="100%" height="70"></canvas>
                                                  </div>
                                                  <div class="col-xl-6">
                                                      <canvas id="SellingItemBarChart" width="100%" height="70"></canvas>
                                                  </div>
                                                </div>
                                                
                                                <br>
                                                <hr>
                                               
                                                <h4 style="font-weight: 600;">Payment Method</h4>
                                                <input type="hidden" id="payment_summery_distributor_id" value="<?php echo $DistributorID; ?>">

                                                <div class="row">
                                                  <div class="col-xl-6">
                                                      <canvas id="PaymentMethodPieChart" width="100%" height="70"></canvas>
                                                  </div>
                                                  <div class="col-xl-6">
                                                      <canvas id="PaymentMethodBarChart" width="100%" height="70"></canvas>
                                                  </div>
                                                </div>

                                            </div>

                                            <div class="tab-pane" id="Targets">

                                                

                                            </div>

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

    <!-- Route Modal -->
    <div class="modal fade" id="RouteModalCenter" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLongTitle">Add Distributor Routes</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form id="Add-Route">
                    <div class="modal-body">
                        <input type="hidden" name="distributor_id" value="<?php echo $DistributorID; ?>" readonly required>
                        <div class="form-group">
                            <label>Select Route</label>
                            <select name="route_id" class="js-example-basic-single custom-select" style="width: 100%;">
                                <option selected disabled>Select Route</option>
                                <?php
                                    $RouteQuery=$conn->query("SELECT * FROM tbl_route WHERE status = 1");
                                    while ($Rrow=$RouteQuery->fetch_array()) {
                                ?>
                                    <option value="<?php echo $Rrow[0];?>"><?php echo $Rrow[1];?></option>
                                <?php } ?>
                            </select>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary" id="btn-add-route">Add route</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

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

    <!-- Chart JS -->
    <script src="assets/js/plugins/chartjs.min.js"></script>
    <script src="assets/js/plugins/chartjs-color.js"></script>

    <!-- App Settings (safe to remove) -->
    <script src="assets/js/app-settings.js"></script>

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
    $(document).ready( function () {
        $('#PaidTable').DataTable();
    } );
    $(document).ready( function () {
        $('#CreditTable').DataTable();
    } );
    $(document).ready( function () {
        $('#ReturnTable').DataTable();
    } );
    $(document).ready( function () {
        $('#SalesRepTable').DataTable();
    } );
    $(document).ready( function () {
        $('#DistributoInvoiceTable').DataTable();
    } );
    $(document).ready( function () {
        $('#DistributorRootesTable').DataTable();
    } );
  </script>


  <script>
        
        $(document).on('submit', '#Write-Distributor-Record', function(e){
        e.preventDefault(); //stop default form submission

        $("#btn-add-record").attr("disabled",true);

        var formData = new FormData($(this)[0]);

        $.ajax({
            
            
                beforeSend : function() {
                    $("#progress_alert").addClass('show');
                },

                url:"post/add_distributor_record.php",
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
                       $("#distributor-record-area").html(json.data);
                        
                       $("#success_msg").html(json.msg);
                       $("#success_alert").addClass('show'); 
                       
                       setTimeout(function(){$("#success_alert").removeClass('show');  }, 1000);
                       $("#btn-add-record").attr("disabled",false);
                       document.getElementById('record-textarea').value = '';
                        
                    }else{
                        $("#danger_alert").addClass('show');
                        setTimeout(function(){ $("#danger_alert").removeClass('show'); }, 1000);
                        $("#btn-add-record").attr("disabled",false);
                    }
                    
                }

            });

        return false;
        });
    </script>

    <script>
        
        $(document).on('submit', '#Add-Route', function(e){
        e.preventDefault(); //stop default form submission

        $("#btn-add-route").attr("disabled",true);

        var formData = new FormData($(this)[0]);

        $.ajax({
            
            
                beforeSend : function() {
                    $("#progress_alert").addClass('show');
                },

                url:"post/add_distributor_root.php",
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
                       $("#btn-add-route").attr("disabled",false);
                       location.reload();
                        
                    }else{
                        $("#danger_alert").addClass('show');
                        setTimeout(function(){ $("#danger_alert").removeClass('show'); }, 1000);
                        $("#btn-add-route").attr("disabled",false);
                    }
                    
                }

            });

        return false;
        });
    </script>

    
    <script>
        
        $(document).on('submit', '#Remove-Distributor-Route', function(e){
        e.preventDefault(); //stop default form submission

        var formData = new FormData($(this)[0]);

        $.ajax({
            
            
                beforeSend : function() {
                    $("#progress_alert").addClass('show');
                },

                url:"post/delete_distributor_root.php",
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
                       location.reload();
                        
                    }else{
                        $("#danger_alert").addClass('show');
                        setTimeout(function(){ $("#danger_alert").removeClass('show'); }, 1000);
                    }
                    
                }

            });

        return false;
        });
    </script>

    <!--------------------------Get Today Products Summary----------------------------------------------------->


        <script>

            SellingItemSummery();

            function SellingItemSummery(){

                $.ajax({
              url:'analytics/get_dis_buy_item.php',
              type:'POST',
              data: {
                  selling_summery_distributor_id:$("#selling_summery_distributor_id").val()
                },
              success:function(data){
                console.log(data);

                // alert(data);

                var json=JSON.parse(data);
                
                if(json.result){

                    var productName = json.productName;
                    var productQtySum = json.productQtySum;

                var ctx = document.getElementById('SellingItemPieChart');
                var SellingItemPieChart = new Chart(ctx, {
                    type: 'pie',
                    data: {
                        labels: productName,
                        datasets: [{
                        data: productQtySum,
                        // backgroundColor: [getRandomColorHex()]
                    }]
                    },
                    options: {
                        responsive: true,
                        title:{
                            display: true,
                            text: "Products Summary",
                        },

                        plugins: {
                          colorschemes: {
                            scheme: 'brewer.DarkTwo3'
                          }
                        }
                    }
                });


                var ctx = document.getElementById('SellingItemBarChart');
                    var SellingItemBarChart = new Chart(ctx, {
                        type: 'bar',
                        data: {
                            labels: productName,
                            datasets: [{
                                label: 'Products Summary',
                                data: productQtySum,
                                // backgroundColor: ["#6765D3", "#0D0C59"],
                                // borderColor: ["#6765D3", "#0D0C59"],
                                borderWidth: 1
                            }]
                        },
                        options: {
                            responsive: true,
                            scales: {
                                yAxes: [{
                                    ticks: {
                                        beginAtZero: true,
                                    }
                                }]
                            },
                            plugins: {
                                colorschemes: {
                                    scheme: 'brewer.DarkTwo3'
                                }
                            }
                        }
                    });

                 

                }
                
                Swal.close();


              },
              error:function(err){
                console.log(err);
              }


            });



            

            }

        </script>

        <!--------------------------End Get Today Products Summary----------------------------------------------------->


        <!--------------------------Get Payment Summary----------------------------------------------------->


        <script>

            PaymentSummery();

            function PaymentSummery(){

                $.ajax({
              url:'analytics/get_dis_payment_method.php',
              type:'POST',
              data: {
                  payment_summery_distributor_id:$("#payment_summery_distributor_id").val()
                },
              success:function(data){
                console.log(data);

                var json=JSON.parse(data);
                
                if(json.result){
                    var cashInvoiceCount = json.cashInvoiceCount;
                    var creditInvoiceCount = json.creditInvoiceCount;

                var ctx = document.getElementById('PaymentMethodPieChart');
                var PaymentMethodPieChart = new Chart(ctx, {
                    type: 'pie',
                    data: {
                        labels: ['Cash','Credit'],
                        datasets: [{
                        data: [cashInvoiceCount,creditInvoiceCount],
                        // backgroundColor: [getRandomColorHex()]
                    }]
                    },
                    options: {
                        responsive: true,
                        title:{
                            display: true,
                            text: "Payment Method Summary",
                        },

                        plugins: {
                          colorschemes: {
                            scheme: 'office.Violet6'
                          }
                        }
                    }
                });


                var ctx = document.getElementById('PaymentMethodBarChart');
                    var PaymentMethodBarChart = new Chart(ctx, {
                        type: 'bar',
                        data: {
                            labels: ['Cash','Credit'],
                            datasets: [{
                                label: 'Payment Method Summary',
                                data: [cashInvoiceCount,creditInvoiceCount],
                                // backgroundColor: ["#6765D3", "#0D0C59"],
                                // borderColor: ["#6765D3", "#0D0C59"],
                                borderWidth: 1
                            }]
                        },
                        options: {
                            responsive: true,
                            scales: {
                                yAxes: [{
                                    ticks: {
                                        beginAtZero: true,
                                    }
                                }]
                            },
                            plugins: {
                                colorschemes: {
                                    scheme: 'office.Violet6'
                                }
                            }
                        }
                    });

                 

                }
                
                Swal.close();


              },
              error:function(err){
                console.log(err);
              }


            });



            

            }

        </script>

        <!--------------------------End Get Payment Summary----------------------------------------------------->

</body>
</html>