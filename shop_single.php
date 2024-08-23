<?php
    session_start();
    require 'database/db.php';
    $db=new DB();
    $conn=$db->connect();
    date_default_timezone_set('Asia/Colombo');
    $currentDate=date('Y-m-d');

    $OutletID=base64_decode($_GET['s']);

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
    $sql = "SELECT * FROM tbl_outlet tou INNER JOIN tbl_shop_category tsc ON tou.category=tsc.category_id INNER JOIN tbl_route tr ON tou.route_id=tr.route_id WHERE outlet_id='$OutletID'";
    $rs=$conn->query($sql);
    if($row=$rs->fetch_array())
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
                                <h3>Shop Profile</h3>
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
                                        <!-- <a href="#!"> -->
                                            <?php if($OutletImage==''){ ?>
                                                <br>
                                                <img class="avatar border-gray" src="assets/img/icons/shop.png">
                                            <?php }else{ ?>
                                                <br>
                                                <a href="#!" data-toggle="modal" data-target="#OutletImage"><img class="avatar border-gray" src="<?php echo $OutletImage; ?>"></a>
                                            <?php } ?>

                                            <h5 class="title" style="color: #000;"><?php echo $OutletName; ?></h5>
                                        <!-- </a> -->
                                        </center>
                                        <p class="description text-center">
                                            <?php echo $OutletOwnerName; ?>
                                        </p>
                                    </div>
                                    <p class="description text-center">
                                        Contact Number : <a href="tel:<?php echo $OutletContact; ?>" style='color: #000;'><?php echo $OutletContact; ?></a><br>
                                        Route Name : <?php echo $OutletRouteName; ?><br>
                                        Address : <?php echo $OutletAddress; ?>
                                    </p>

                                    <form method="POST" id="Remove-Outlet">
                                    	<input type="hidden" name="outlet_id" value="<?php echo $OutletId; ?>" readonly>
                                    	<center><button type="submit" class="btn btn-danger" id="btn-remove-outlet">Remove This Outlet</button></center>
                                    </form>
                                    
                                    <div class="container" style="margin-top:10px">
                                        <center><button type="button" class="btn btn-primary" id="btn-update-outlet">Update This Outlet</button></center>
                                    </div>
                                   
                                    
                                    

                                    <hr>
                                    <div class="button-container">
                                      <div class="row">
                                        <div class="col-lg-4 col-md-6 col-6 text-center">
                                          <h5>Rs.
                                          <?php 
                                            $OutstandingSumSQL="SELECT SUM(`editable_total`) FROM `tbl_credit_orders` WHERE outlet_id='$OutletID'";
                                            $OutstandingSumResult = mysqli_query($conn, $OutstandingSumSQL);
                                            $OutstandingSumTotal = mysqli_fetch_assoc($OutstandingSumResult)['SUM(`editable_total`)'];
                                            echo number_format($OutstandingSumTotal,2);
                                          ?>

                                          <br><small>Outstanding</small></h5>
                                        </div>
                                        <div class="col-lg-4 col-md-6 col-6 text-center">
                                          <h5>
                                            <?php
                                              $PaidInvoiceCountSQL = "SELECT COUNT(*) FROM tbl_order WHERE outlet_id='$OutletID' AND payment_method='0' ";
                                              $PaidInvoiceCountResult = mysqli_query($conn, $PaidInvoiceCountSQL);
                                              $PaidInvoiceCount = mysqli_fetch_assoc($PaidInvoiceCountResult)['COUNT(*)'];
                                              echo $PaidInvoiceCount;
                                            ?>
                                            <br><small>Cash Invoice</small></h5>
                                        </div>
                                        <div class="col-lg-4 text-center">
                                          <h5>
                                            <?php
                                              $UnPaidInvoiceCountSQL = "SELECT COUNT(*) FROM tbl_order WHERE outlet_id='$OutletID' AND payment_method='2' ";
                                              $UnPaidInvoiceCountResult = mysqli_query($conn, $UnPaidInvoiceCountSQL);
                                              $UnPaidInvoiceCount = mysqli_fetch_assoc($UnPaidInvoiceCountResult)['COUNT(*)'];
                                              echo $UnPaidInvoiceCount;
                                            ?>
                                            <br><small>Credit Invoice</small></h5>
                                        </div>



                                      </div>


                                      <br>

                                        <div>
                                          <button class="btn btn-dark" id="btn-open-discount-dialog" style="width: 100%">Add Discount</button>
                                        </div>



                                    </div>
                                </div>

                                <div class="card">
                                    <div class="card-header">
                                        <h4 class="card-title">Outlet Location</h4>
                                    </div>
                                    <div class="card-body">
                                        <iframe src = "https://maps.google.com/maps?q=<?php echo $OutletLat;?>,<?php echo $OutletLon;?>&hl=es;z=14&amp;output=embed" width="100%" height="450"></iframe>
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
                                        <br><br><li><a href="#Documents" data-toggle="tab">Documents</a></li>
                                    </ul>
                                </div><br>
                                <div class="card">
                                    <div id="exTab2" class="container"> 

                                            <div class="card-body tab-content">
                                              <div class="tab-pane active" id="Records" style="padding: 5px;">
                                                

                                                <form id="Write-Outlet-Record" method="POST">
                                                  <input type="hidden" name="outlet_id" class="form-control" value="<?php echo $OutletID; ?>" readonly required>
                                                  <textarea name="record" id="record-textarea" style="padding: 8px; resize: auto; max-height: 100% !important;" cols="30" rows="5" class="form-control bg-transparent" placeholder="Please type what you want...."></textarea>
                                                  <br>
                                                  <button type="submit" class="btn btn-dark" id="btn-add-record">Add Records</button>
                                                </form>

                                                <hr>

                                                <div id="outlet-record-area">
                                                    <?php
                                                        $getOutletRecordQuery=$conn->query("SELECT * FROM outlet_records WHERE outlet_id='$OutletID' ORDER BY outlet_record_id DESC ");
                                                        while ($GORrs=$getOutletRecordQuery->fetch_array()) {
                                                    ?>
                                                    <div class="profile-uoloaded-post border-bottom-1 pb-5">
                                                        <h5>Remark Note <span class="pull-right" style="font-size: 12px; float: right;"><?php echo $GORrs[3]; ?></span></h5>
                                                        <p><?php echo nl2br($GORrs[2]); ?></p>
                                                                                    
                                                        <hr>
                                                    </div>
                                                    <?php } ?>
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
                                                            $CashInvoicesql = "SELECT * FROM tbl_order tor INNER JOIN tbl_route tro ON tor.route_id=tro.route_id WHERE tor.outlet_id='$OutletID' AND tor.payment_method='0' ORDER BY id DESC";
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
                                                            $getGrandTotalQuary = $conn->query("SELECT * FROM tbl_order_item_details WHERE order_id='$CashId'");
                                                            while($GGTrs = $getGrandTotalQuary->fetch_array()){
                                                                    $CashProductOrderId=$GGTrs[0];                       
                                                                    $CashProductItemId=$GGTrs[1];                       
                                                                    $CashProductQty=$GGTrs[3];                       
                                                                    $CashProductDiscountedPrice=$GGTrs[4];                        
                                                                    $CashProductDiscountedValue=$GGTrs[5];                       
                                                                    $CashProductPrice=$GGTrs[6];     
                                                                    $CashRPID=$GGTrs[7]; 

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
                                                            $CreditInvoicesql = "SELECT * FROM tbl_order tor INNER JOIN tbl_route tro ON tor.route_id=tro.route_id WHERE tor.outlet_id='$OutletID' AND tor.payment_method='2' ORDER BY id DESC";
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
                                                                $getGrandTotalQuary = $conn->query("SELECT * FROM tbl_order_item_details WHERE order_id='$CreditId'");
                                                                while($GGTrs = $getGrandTotalQuary->fetch_array()){
                                                                        $CreditProductOrderId=$GGTrs[0];                       
                                                                        $CreditProductItemId=$GGTrs[1];                       
                                                                        $CreditProductQty=$GGTrs[3];                       
                                                                        $CreditProductDiscountedPrice=$GGTrs[4];                        
                                                                        $CreditProductDiscountedValue=$GGTrs[5];                       
                                                                        $CreditProductPrice=$GGTrs[6];     
                                                                        $CreditRPID=$GGTrs[7]; 

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
                                                          $ReturnInvoicesql = "SELECT * FROM tbl_return_order tor INNER JOIN tbl_route tro ON tor.route_id=tro.route_id INNER JOIN tbl_user tus ON tor.user_id=tus.id WHERE tor.outlet_id='$OutletID' ORDER BY tor.id DESC";
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
                                                <input type="hidden" id="selling_summery_outlet_id" value="<?php echo $OutletID; ?>">

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
                                                <input type="hidden" id="payment_summery_outlet_id" value="<?php echo $OutletID; ?>">

                                                <div class="row">
                                                  <div class="col-xl-6">
                                                      <canvas id="PaymentMethodPieChart" width="100%" height="70"></canvas>
                                                  </div>
                                                  <div class="col-xl-6">
                                                      <canvas id="PaymentMethodBarChart" width="100%" height="70"></canvas>
                                                  </div>
                                                </div>

                                            </div>



                                            <div class="tab-pane" id="Documents">

                                                <h4 style="font-weight: 600;">Outlet Documents</h4>
                                               

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
    
    
    
    
    <!--Modal update shop details-->
    
    
        <!-- The Modal -->
                <div class="modal" id="modal-update-shop-details">
                  <div class="modal-dialog">
                    <div class="modal-content">
                
                      <!-- Modal Header -->
                      <div class="modal-header">
                        <h4 class="modal-title">Update Shop Details</h4>
                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                      </div>
                
                      <!-- Modal body -->
                      <div class="modal-body">
                          
                          
                       <form id="form-update-shop-details">
                           
                           <input type="hidden" name="update-outlet-id" value="<?php echo base64_encode($OutletId);?>">
                           
                            <div class="form-group">
                                   <label>Shop Name</label>
                                   <input type="text" class="form-control" name="update-shop-name" value="<?php echo $OutletName;?>">
                            </div>
                            
                            <div class="form-group">
                                   <button type="submit" class="btn btn-success">Update Now</button>
                            </div>
                           
                       </form>
                       
                       
                      </div>
                
                      <!-- Modal footer -->
                      <!--<div class="modal-footer">-->
                      <!--  <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>-->
                      <!--</div>-->
                
                    </div>
                  </div>
                </div>
    
    
    
    <!----------------------------->
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    

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

    <!-- Modal -->
	<div class="modal fade" id="OutletImage" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
	  <div class="modal-dialog modal-dialog-centered" role="document">
	    <div class="modal-content">
	      <div class="modal-header">
	        <h5 class="modal-title" id="exampleModalLongTitle"><?php echo $OutletName; ?></h5>
	        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
	          <span aria-hidden="true">&times;</span>
	        </button>
	      </div>
	      <div class="modal-body">
	        <center><img src="<?php echo $OutletImage; ?>" ></center>
	      </div>
	      <div class="modal-footer">
	        <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
	        <!-- <button type="button" class="btn btn-primary">Save changes</button> -->
	      </div>
	    </div>
	  </div>
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
        
        
        $("#form-update-shop-details").submit(function(e){
            e.preventDefault();
            var formData = new FormData(e.target);
           
           
        //   var formData = new FormData($(this)[0]);

        $.ajax({
                url:"outlet_post/update_outlet.php",
                type: 'POST',
                data: formData,
                //async: false,
                cache: false,
                contentType: false,
                processData: false,

                success: function (data) {
                    var json=JSON.parse(data);
                    
                    if(json.result){
                       location.reload();
                    }else{
                       alert(json.msg);
                    }
                    
                }

            });
           
           
           
           
           
        });
        
        $("#btn-update-outlet").click(function(){
            $("#modal-update-shop-details").modal();
        });



        $('#btn-open-discount-dialog').click(function(){
            $("#modal-add-discount").modal("show");


            $("#btn-save-discount").click(function(){
                var discount = $("#txt-discount").val();
                var outlet = $("#txt-outlet-id").val();

             
               
                if(discount !== "" && outlet !== ""){


                  $.ajax({

                    beforeSend : function() {

                    $("#progress_alert").addClass('show'); 

                    },

                    url:'scripts/save_outlet_discount.php',
                    type:'POST',
                    data:{
                      discount:discount,
                      outlet:outlet
                    },
                    success: function (data) {
                    
                        $("#progress_alert").removeClass('show');
                        
                        var json=JSON.parse(data);
                        
                        if(json.result){
                           $("#outlet-record-area").html(json.data);
                            
                           $("#success_msg").html(json.msg);
                           $("#success_alert").addClass('show'); 
                           
                           setTimeout(function(){$("#success_alert").removeClass('show');  }, 1000);
                           location.reload();
                            
                        }else{
                            $("#danger_alert").addClass('show');
                            setTimeout(function(){ $("#danger_alert").removeClass('show'); }, 1000);
                        }
                        
                    },error:function(err){
                        console.log(err);
                    }


                  });

                }


            }); 



        });
    } );
  </script>


  <script>
        
        $(document).on('submit', '#Write-Outlet-Record', function(e){
        e.preventDefault(); //stop default form submission

        $("#btn-add-record").attr("disabled",true);

        var formData = new FormData($(this)[0]);

        $.ajax({
            
            
                beforeSend : function() {

                $("#progress_alert").addClass('show'); 

                },

                url:"outlet_post/add_outlet_record.php",
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
                       $("#outlet-record-area").html(json.data);
                        
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
        
        $(document).on('submit', '#Remove-Outlet', function(e){
        e.preventDefault(); //stop default form submission

        $("#btn-remove-outlet").attr("disabled",true);

        var formData = new FormData($(this)[0]);

        $.ajax({
            
            
                beforeSend : function() {

                $("#progress_alert").addClass('show'); 

                },

                url:"outlet_post/remove_outlet.php",
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
                       $("#btn-remove-outlet").attr("disabled",false);
                       window.location.href = "shop";
                        
                    }else{
                    	$("#error_msg").html(json.msg);
                        $("#danger_alert_msg").addClass('show');
                        setTimeout(function(){ $("#danger_alert_msg").removeClass('show'); }, 1000);
                        $("#btn-remove-outlet").attr("disabled",false);
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
              url:'analytics/get_shop_buy_item.php',
              type:'POST',
              data: {
                  selling_summery_outlet_id:$("#selling_summery_outlet_id").val()
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
                            text: "Today Products Summary",
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
                                label: 'Today Products Summary',
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
              url:'analytics/get_shop_payment_method.php',
              type:'POST',
              data: {
                  payment_summery_outlet_id:$("#payment_summery_outlet_id").val()
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