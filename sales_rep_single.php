<?php
    session_start();
    require 'database/db.php';
    $db=new DB();
    $conn=$db->connect();
    date_default_timezone_set('Asia/Colombo');
    $currentDate=date('Y-m-d');

    $RepId=base64_decode($_GET['s']);

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

  $lat = 0;
  $lng = 0;

  $getRepLastLocation = $conn->query("SELECT lat,lon FROM tbl_gps_track WHERE user_id = '$RepId'");
  if($locRs = $getRepLastLocation->fetch_array()){
    $lat = $locRs[0];
    $lng = $locRs[1];
  }

    $sql = "SELECT * FROM tbl_user WHERE id='$RepId'";
    $rs=$conn->query($sql);
    if($row=$rs->fetch_array())
    {
        $Id=$row[0];                       
        $RepName=$row[1];   
        $RepUserName=$row[2];     
        $CreatedDate=$row[4];   
        $RepAddress=$row[5];   
        $RepContactNumber=$row[6];    
        $RepStatus=$row[7];    
        $RepLoginStatus=$row[8];  
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
                                <h3>Sales-Rep Profile</h3>
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
                                            <img class="avatar border-gray" src="assets/img/default-avatar.png">
                                            <h5 class="title" style="color: #000;"><?php echo $RepName; ?></h5>
                                        </a>
                                    <center>
                                        <p class="description">
                                            <?php //echo $OutletOwnerName; ?>
                                        </p>
                                    </div>
                                    <p class="description text-center">
                                        Contact Number : <a href="tel:<?php echo $RepContactNumber; ?>" style='color: #000;'><?php echo $RepContactNumber; ?></a><br>
                                        Address : <?php echo $RepAddress; ?><br>
                                        User Name : <?php echo $RepUserName; ?><br>
                                        Rep Registered Date : <?php echo $CreatedDate; ?>
                                    </p>

                                    <hr>
                                    <div class="button-container">
                                      <div class="row">
                                        <div class="col-lg-4 col-md-6 col-6 text-center">
                                          <h5>Rs.
                                          <?php 
                                            $OutstandingCorrectPrice=0;
                                            $OutstandingFullPrice=0;
                                            $OutstandingInvoiceIdSQL="SELECT id FROM `tbl_order` WHERE user_id='$RepId' AND payment_status='0' ";
                                            $OutstandingInvoiceIdQuery=$conn->query($OutstandingInvoiceIdSQL);
                                            while($OIIrow=$OutstandingInvoiceIdQuery->fetch_array()){
                                              $OutstandingOrderId=$OIIrow[0];

                                              $OutstandingProductsSQL="SELECT qty,price,discounted_value FROM `tbl_order_item_details` WHERE order_id='$OutstandingOrderId' ";
                                              $OutstandingProductsQuery=$conn->query($OutstandingProductsSQL);
                                              $OutstandingGrandTotal=0;
                                              while($OIProw=$OutstandingProductsQuery->fetch_array()){
                                                $OutstandingProductQty=$OIProw[0];
                                                $OutstandingProductPrice=$OIProw[1];
                                                $OutstandingProductDiscountedValue=$OIProw[2];

                                                ////////Calculation//////////////
                                                $OutstandingDiscountedPrice = (double)$OutstandingProductPrice-(((double)$OutstandingProductPrice*(double)$OutstandingProductDiscountedValue)/100);
                                                //With QTY
                                                $OutstandingItemTotal = (double)$OutstandingDiscountedPrice*(double)$OutstandingProductQty;

                                                //Grand Total
                                                $OutstandingGrandTotal += $OutstandingItemTotal;
                                                ////////Calculation//////////////

                                              }

                                              $OutstandingFullPrice += $OutstandingGrandTotal;

                                              $OutstandingPaiedFullPrice=0;
                                              $OutstandingPaiedSQL="SELECT SUM(amount) FROM `tbl_outstanding_payments` WHERE order_id='$OutstandingOrderId' ";
                                              $OutstandingPaiedQuery=$conn->query($OutstandingPaiedSQL);
                                              if($OProw=$OutstandingPaiedQuery->fetch_array()){
                                                $OutstandingPaiedTotal=$OProw[0];

                                                $OutstandingPaiedFullPrice += $OutstandingPaiedTotal;
                                              }


                                            }
                                              
                                              //OutstandingCorrectPriceCal
                                              $OutstandingCorrectPrice = $OutstandingFullPrice - $OutstandingPaiedFullPrice;

                                            echo number_format($OutstandingCorrectPrice,2);
                                          ?>
                                          <br><small>Debtor Amount</small></h5>
                                        </div>
                                        <div class="col-lg-4 col-md-6 col-6 text-center">
                                          <h5>
                                            <?php
                                              $PaidInvoiceCountSQL = "SELECT COUNT(*) FROM tbl_order WHERE user_id='$RepId' AND payment_method='0' ";
                                              $PaidInvoiceCountResult = mysqli_query($conn, $PaidInvoiceCountSQL);
                                              $PaidInvoiceCount = mysqli_fetch_assoc($PaidInvoiceCountResult)['COUNT(*)'];
                                              echo $PaidInvoiceCount;
                                            ?>
                                            <br><small>Cash Invoice</small></h5>
                                        </div>
                                        <div class="col-lg-4 text-center">
                                          <h5>
                                            <?php
                                              $UnPaidInvoiceCountSQL = "SELECT COUNT(*) FROM tbl_order WHERE user_id='$RepId' AND payment_method='2' ";
                                              $UnPaidInvoiceCountResult = mysqli_query($conn, $UnPaidInvoiceCountSQL);
                                              $UnPaidInvoiceCount = mysqli_fetch_assoc($UnPaidInvoiceCountResult)['COUNT(*)'];
                                              echo $UnPaidInvoiceCount;
                                            ?>
                                            <br><small>Credit Invoice</small></h5>
                                        </div>
                                      </div>
                                    </div>
                                </div>


                                <div class="card" id="map-view-print">
                                    <div class="card-header">
                                        <h4 class="card-title">Sales Rep Last Location</h4>
                                    </div>
                                    <div class="card-body">
                                        <iframe src = "https://maps.google.com/maps?q=<?php echo $lat.','.$lng;?>&hl=es;z=14&amp;output=embed" width="100%" height="450"></iframe>
                                    </div>
                                </div>

                            </div>

                            <div class="col-md-8">
                                <div class="card-header card-header-tabs-basic nav" role="tablist">
                                    <ul class="nav nav-tabs">
                                        <br><br><li><a href="#Records" class="active" data-toggle="tab">Records</a></li>
                                        <br><br><li><a href="#CashInvoice" data-toggle="tab">Cash Invoice</a></li>
                                        <br><br><li><a href="#CreditInvoice" data-toggle="tab">Credit Invoice</a></li>
                                        <br><br><li><a href="#ReternOrders" data-toggle="tab">Return Orders</a></li>
                                        <br><br><li><a href="#Summery" data-toggle="tab">Summary</a></li>
                                        <br><br><li><a href="#Expenses" data-toggle="tab">Expenses</a></li>
                                    </ul>
                                </div><br>
                                <div class="card">
                                    <div id="exTab2" class="container"> 

                                        <div class="tab-content">
                                              <div class="tab-pane active" id="Records" style="padding: 5px;">
                                                

                                                <form id="Write-Rep-Record" method="POST">
                                                  <input type="hidden" name="rep_id" class="form-control" value="<?php echo $RepId; ?>" readonly required>
                                                  <textarea name="record" id="record-textarea" style="padding: 8px; resize: auto; max-height: 100% !important;" cols="30" rows="5" class="form-control bg-transparent" placeholder="Please type what you want...."></textarea>
                                                  <br>
                                                  <button type="submit" class="btn btn-dark" id="btn-add-record">Add Records</button>
                                                </form>

                                                <hr>

                                                <div id="rep-record-area">
                                                <?php
                                                    $getSalesRepRecordQuery=$conn->query("SELECT * FROM sales_rep_records WHERE rep_id='$RepId' ORDER BY sales_rep_record_id DESC ");
                                                    while ($GSRRrs=$getSalesRepRecordQuery->fetch_array()) {
                                                ?>
                                                    <div class="profile-uoloaded-post border-bottom-1 pb-5">
                                                      <h5>Remark Note <span class="pull-right" style="font-size: 12px; float: right;"><?php echo $GSRRrs[3]; ?></span></h5>
                                                      <p><?php echo nl2br($GSRRrs[2]); ?></p>
                                                                                    
                                                      <hr>
                                                    </div>
                                                <?php } ?>
                                                </div>

                                              </div>
                                              <div class="tab-pane" id="CashInvoice">
                                                
                                                <div class="table-responsive" style="overflow-y: hidden !important; padding: 10px;">
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
                                                        $CashInvoicesql = "SELECT * FROM tbl_order tor INNER JOIN tbl_route tro ON tor.route_id=tro.route_id WHERE tor.user_id='$RepId' AND tor.payment_method='0' ORDER BY id DESC";
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
                                                            $getGrandTotalCashQuary = $conn->query("SELECT * FROM tbl_order_item_details WHERE order_id='$CashId'");
                                                            while($GGTCrs = $getGrandTotalCashQuary->fetch_array()){
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
                                                  
                                                <div class="table-responsive" style="overflow-y: hidden !important; padding: 10px;">
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
                                                        $CreditInvoicesql = "SELECT * FROM tbl_order tor INNER JOIN tbl_route tro ON tor.route_id=tro.route_id WHERE tor.user_id='$RepId' AND tor.payment_method='2' ORDER BY id DESC";
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
                                                            $getGrandTotalQuaryCredit = $conn->query("SELECT * FROM tbl_order_item_details WHERE order_id='$CreditId'");
                                                            while($GGTCrs = $getGrandTotalQuaryCredit->fetch_array()){
                                                                $CreditProductOrderId=$GGTCrs[0];                       
                                                                $CreditProductItemId=$GGTCrs[1];                       
                                                                $CreditProductQty=$GGTCrs[3];                       
                                                                $CreditProductDiscountedPrice=$GGTCrs[4];                        
                                                                $CreditProductDiscountedValue=$GGTCrs[5];                       
                                                                $CreditProductPrice=$GGTCrs[6];     
                                                                $CreditRPID=$GGTCrs[7]; 

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
                                                
                                                <div class="table-responsive" style="overflow-y: hidden !important; padding: 10px;">
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
                                                          $ReturnInvoicesql = "SELECT * FROM tbl_return_order tor INNER JOIN tbl_route tro ON tor.route_id=tro.route_id INNER JOIN tbl_user tus ON tor.user_id=tus.id WHERE tor.user_id='$RepId' ORDER BY tor.id DESC";
                                                          $ReturnInvoicers=$conn->query($ReturnInvoicesql);
                                                          while($RIrow=$ReturnInvoicers->fetch_array())
                                                          {
                                                            $ReturnId=$RIrow[0];                       
                                                            $ReturnOrderId=$RIrow[1];   
                                                            $ReturnReturnType=$RIrow[2];     
                                                            $ReturnInvoiceDate=$RIrow[3];   
                                                            $ReturnAppVersion=$RIrow[9];   
                                                            $ReturnRouteName=$RIrow[16];   
                                                            $ReturnSalesRepName=$RIrow[20];   
                                                        ?>
                                                        <?php
                                                            $ReturnGrandTotal=0;
                                                            $getGrandTotalQuary = $conn->query("SELECT * FROM tbl_return_order_item_details WHERE order_id='$ReturnId'");
                                                            while($GGTrs = $getGrandTotalQuary->fetch_array()){
                                                                $ReturnProductOrderId=$GGTrs[0];                       
                                                                $ReturnProductItemId=$GGTrs[1];                       
                                                                $ReturnProductQty=$GGTrs[3];                       
                                                                $ReturnProductDiscountedPrice=$GGTrs[4];                        
                                                                $ReturnProductDiscountedValue=$GGTrs[5];                       
                                                                $ReturnProductPrice=$GGTrs[6];     
                                                                $ReturnRPID=$GGTrs[7]; 

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
                                                              <?php if($ReturnReturnType=='1'){ echo 'Damage Return'; }else{ echo 'Sales Return'; } ?>
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
                                                <br>
                                                  <div class="row" style="padding: 15px" id="search-summary-view">
                                                    <input type="hidden" id="txt-rep-id" value="<?php echo $RepId;?>">
                                                    <div class="col-md-4">
                                                      <div class="form-group">
                                                        <label>Start Date</label>
                                                        <input type="date" id="txt-search-date-start" class="form-control">
                                                      </div>
                                                    </div>
                                                    <div class="col-md-4">
                                                      <div class="form-group">
                                                        <label>End Date</label>
                                                        <input type="date" id="txt-search-date-end" class="form-control">
                                                      </div>
                                                    </div>
                                                    <div class="col-md-2">
                                                      <div class="form-group">
                                                        <label style="color: #fff;">Search</label><br>
                                                        <button class="btn btn-dark pull-right" id="btn-search-summary"><i class="fa fa-search"></i> SEARCH</button>
                                                      </div>
                                                    </div>
                                                    <div class="col-md-2">
                                                      <div class="form-group">
                                                        <label style="color: #fff;">Print</label><br>
                                                        <button class="btn btn-danger pull-right" id="btn-search-print" onclick="printDiv('print-this-area')" style="display: none;"><i class="fa fa-print"></i> PRINT</button>
                                                      </div>
                                                    </div>
                                                  </div>

                                                <div id="print-this-area" style="padding: 15px;">

                                                  <div class="row">
                                                    <div class="col-md-12" style="color: #000; text-align: center; font-weight: 600;">
                                                      <h5><?php echo $RepName; ?> Summary</h5>
                                                      <div id="area-date-view" style="color: #000;">
                                                        <span id="lbl-date-from">2021-12-12</span> - <span id="lbl-date-to">2021-12-12</span>
                                                      </div>
                                                    </div>
                                                  </div>
                                                  
                                                  
                                                  


                                                <h4 style="font-weight: 600;">Cash Invoice Summary</h4>
                                                <input type="hidden" id="selling_summery_outlet_id" value="<?php echo $OutletID; ?>">

                                                <div class="row">
                                                  <div class="table-responsive" style="overflow: hidden !important; padding: 10px;">
                                                    <table class="table">
                                                      <thead>
                                                        <th>#</th>
                                                        <th>Invoice Id</th>
                                                        <th>Payment Method</th>
                                                        <th>App Version</th>
                                                        <th>Route</th>
                                                        <th>Date</th>
                                                        <th class="text-right">Invoice Amount (.Rs)</th>
                                                        <th></th>
                                                      </thead>
                                                      <tbody id="cash-invoice-area">

                                                      </tbody>
                                                    </table>
                                                  </div>
                                                </div>

                                                <div class="row">
                                                  <div class="col-md-12">
                                                    <div class="pull-right">
                                                      <h4 style="color: #000; font-weight: 600;">Cash Income Total - Rs.<span id="lbl-cash-income-total">0.00</span></h4>
                                                    </div>
                                                  </div>
                                                </div>
                                                
                                                <br>
                                                <hr>
                                               
                                                <h4 style="font-weight: 600;">Credit Invoice Summary</h4>

                                                <div class="row">
                                                  <div class="table-responsive" style="overflow: hidden !important; padding: 10px;">
                                                    <table class="table">
                                                      <thead>
                                                        <th>#</th>
                                                        <th>Invoice Id</th>
                                                        <th>Payment Method</th>
                                                        <th>App Version</th>
                                                        <th>Route</th>
                                                        <th>Date</th>
                                                        <th class="text-right">Invoice Amount (.Rs)</th>
                                                        <th></th>
                                                      </thead>
                                                      <tbody id="credit-invoice-area">

                                                      </tbody>
                                                    </table>
                                                  </div>
                                                </div>

                                                <div class="row">
                                                  <div class="col-md-12">
                                                    <div class="pull-right">
                                                      <h4 style="color: #000; font-weight: 600;">Credit Income Total - Rs.<span id="lbl-credit-income-total">0.00</span></h4>
                                                    </div>
                                                  </div>
                                                </div>
                                                
                                                <br>
                                                <hr>

                                                <h4 style="font-weight: 600;">Return Orders Summary</h4>

                                                <div class="row">
                                                  <div class="table-responsive" style="overflow: hidden !important; padding: 10px;">
                                                    <table class="table">
                                                      <thead>
                                                        <th>#</th>
                                                        <th>Invoice Id</th>
                                                        <th>Return Type</th>
                                                        <th>App Version</th>
                                                        <th>Route</th>
                                                        <th>Date</th>
                                                        <th class="text-right">Invoice Amount (.Rs)</th>
                                                        <th></th>
                                                      </thead>
                                                      <tbody id="return-orders-area">

                                                      </tbody>
                                                    </table>
                                                  </div>
                                                </div>
                                                
                                                <br>
                                                <hr>

                                                <h4 style="font-weight: 600;">Free Issue Products</h4>

                                                <div class="row">
                                                  <div class="table-responsive" style="overflow: hidden !important; padding: 10px;">
                                                    <table class="table">
                                                      <thead>
                                                        <th>#</th>
                                                        <th>Item Name</th>
                                                        <th>QTY</th>
                                                      </thead>
                                                      <tbody id="area-free-issue">

                                                      </tbody>
                                                    </table>
                                                  </div>
                                                </div>

                                                <br>
                                                <hr>


                                                <h4 style="font-weight: 600;">Van Selling Stock</h4>

                                                <div class="row">
                                                  <div class="table-responsive" style="overflow: hidden !important; padding: 10px;">
                                                    <table class="table">
                                                      <thead>
                                                        <th>#</th>
                                                        <th>Item Name</th>
                                                        <th>Loading</th>
                                                        <th>Unloading</th>
                                                        <th>Sold</th>
                                                      </thead>
                                                      <tbody id="area-van-selling">

                                                      </tbody>
                                                    </table>
                                                  </div>
                                                </div>











                                                
                                                <br>
                                                <hr>

                                              </div>

                                              </div>


                                              <div class="tab-pane" id="Expenses">
                                                
                                                <div class="table-responsive" style="overflow-y: hidden !important; padding: 10px;">
                                                    <table id="ExpensesTable" class="display" style="width:100%;">
                                                      <thead>
                                                          <tr>   
                                                              <th>Expenses Type</th>
                                                              <th>Expenses Remark</th>
                                                              <th class="text-right">Expenses Amount</th>
                                                              <th>Date Time</th>
                                                          </tr>
                                                      </thead>
                                                      <tbody>
                                                        <?php
                                                          $SalesRepExpensesSql = "SELECT * FROM tbl_sales_rep_expenses tsre INNER JOIN tbl_expenses_types tet ON tsre.type_id =tet.id WHERE tsre.user_id='$RepId' ORDER BY tsre.id DESC";
                                                          $SalesRepExpensesQuery=$conn->query($SalesRepExpensesSql);
                                                          while($SRErow=$SalesRepExpensesQuery->fetch_array())
                                                          {   
                                                            $ExpensesAmount=$SRErow[2];     
                                                            $ExpensesRemark=$SRErow[3];   
                                                            $ExpensesDateTime=$SRErow[4];   
                                                            $ExpensesType=$SRErow[7];   
                                                        ?>
                                                        <tr>
                                                          <td><?php echo $ExpensesType; ?></td>
                                                          <td><?php echo $ExpensesRemark; ?></td>
                                                          <td class="text-right"><b><?php echo number_format($ExpensesAmount,2); ?></b></td>
                                                          <td><?php echo $ExpensesDateTime; ?></td>
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

    <!-- Discount Modal -->
    <div class="modal" id="modal-add-discount">
      <div class="modal-dialog">
        <div class="modal-content">

          <!-- Modal Header -->
          <div class="modal-header">
            <h4 class="modal-title">Add discount</h4>
            <button type="button" class="close" data-dismiss="modal">&times;</button>
          </div>

          <!-- Modal body -->
          <div class="modal-body">
            

            <input type="hidden" id="txt-outlet-id" value="<?php echo $OutletID;?>">


            <div class="form-group">
              <label>Discount Percentage (%)</label>
              <input type="number" id="txt-discount" value="<?php echo $OutletDiscount;?>" class="form-control" placeholder="%">
            </div>

          </div>

          <!-- Modal footer -->
          <div class="modal-footer">
            <button type="button" id="btn-save-discount" class="btn btn-danger">SAVE</button>
          </div>

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
            $('#PaidTable').DataTable();
        } );
        $(document).ready( function () {
            $('#CreditTable').DataTable();
        } );
        $(document).ready( function () {
            $('#ReturnTable').DataTable();
        } );
        $(document).ready( function () {
            $('#ExpensesTable').DataTable();
        } );


         $(document).ready( function () {
          $("#area-date-view").hide();
            $('#btn-search-summary').click(function(){


              var searchDateStart = $("#txt-search-date-start").val();
              var searchDateEnd = $("#txt-search-date-end").val();
              var repId = $("#txt-rep-id").val();
              if(searchDateStart !== "" && searchDateEnd !== "" && repId !== ""){

                $("#lbl-date-from").html(searchDateStart);
                $("#lbl-date-to").html(searchDateEnd);
                $("#area-date-view").show();

                $.ajax({

                  url:'post/download_rep_summary.php',
                  type:'POST',
                  data:{
                    rep_id:repId,
                    search_date_start:searchDateStart,
                    search_date_end:searchDateEnd
                  },success:function(data){
                    
                    var json = JSON.parse(data);
                    if(json.result){

                      

                      $("#area-van-selling").html(json.van_loading_data);
                      $("#area-free-issue").html(json.free_issue_data);
                      $("#return-orders-area").html(json.return_data);
                      $("#credit-invoice-area").html(json.credit_data);
                      $("#lbl-credit-income-total").html(json.credit_income_total);


                      $("#cash-invoice-area").html(json.other_order_data);
                      $("#lbl-cash-income-total").html(json.cash_income_total);

                      $("#btn-search-print").show();


                    }



                  },
                  error:function(err){
                    console.log(err);
                  }


                });



              }

            });
        } );
    </script>

    <script>
        function printDiv(divName){
          var printContents = document.getElementById(divName).innerHTML;
          var originalContents = document.body.innerHTML;
          document.body.innerHTML = printContents;
          window.print();
          document.body.innerHTML = originalContents;

        }
    </script>

    <script>
        
        $(document).on('submit', '#Write-Rep-Record', function(e){
        e.preventDefault(); //stop default form submission

        $("#btn-add-record").attr("disabled",true);

        var formData = new FormData($(this)[0]);

        $.ajax({
            
            
                beforeSend : function() {

                $("#progress_alert").addClass('show'); 

                },

                url:"rep_post/add_rep_record.php",
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
                       $("#rep-record-area").html(json.data);
                        
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


</body>
</html>