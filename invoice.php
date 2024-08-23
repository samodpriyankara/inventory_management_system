<?php
    session_start();
    require 'database/db.php';
    $db=new DB();
    $conn=$db->connect();
    date_default_timezone_set('Asia/Colombo');
    $currentDate=date('Y-m-d');

    $InvoiceID=base64_decode($_GET['i']);

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

    $sql = "SELECT * FROM tbl_order tor INNER JOIN tbl_outlet tou ON tor.outlet_id=tou.outlet_id INNER JOIN tbl_route tr ON tor.route_id=tr.route_id INNER JOIN tbl_distributor tdi ON tor.distributor_id=tdi.distributor_id INNER JOIN tbl_order_delivery tod ON tor.id=tod.order_id WHERE tor.id='$InvoiceID'";
    $rs=$conn->query($sql);
  
    
    
    if($row=$rs->fetch_array())
    {    
      //Order Details          
      $OrderId=$row[1];                       
      $OrderType=$row[2];                       
      $InvoiceDate=$row[3];                       
      $InvoiceTime=$row[4];                       
      $Lon=$row[5];                       
      $Lat=$row[6];                       
      $BattryLevel=$row[7];                       
      $TimeStamp=$row[8];                       
      $PaymentStatus=$row[9];                       
      $OptinalDiscountAmount=$row[10];                       
      $OptinalDiscountPercentage=$row[11];                       
      $PaymentMethod=$row[12];                       
      $AppVersion=$row[13];                       
      $SessionId=$row[14];                       
      $OutletId=$row[15];                       
      $RouteId=$row[16];                       
      $DistributorId=$row[17];                       
      $UserId=$row[18]; 

      //Outlet Details
      /////////////////////////
      $OutletName=$row[20];
      $OwnerName=$row[21];
      $OutletContactNumber=$row[22];
      $OutletAddress=$row[23];

      //Route Details
      /////////////////////////
      $RouteName=$row[39]; 

      //Distributor Details
      ////////////////////////
      $DistributorName=$row[43]; 

      //Sales Rep Details
      ///////////////////////
      // $SalesRepName=$row[47]; 

      //Delivery Details
      //////////////////////
      $DeliveryStatus=$row[48]; 

      $repName = "N/A";

      if($UserId == 0){
        $split = explode("/",$OrderId);
        $orderedUser = $split[3];

        $getRepName = $conn->query("SELECT name FROM tbl_web_console_user_account WHERE user_id = '$orderedUser'");
        if($repRs = $getRepName->fetch_array()){
            $repName = $repRs[0];
        }


      }else{

        $getRepName = $conn->query("SELECT name FROM tbl_user WHERE id = '$UserId'");
        if($repRs = $getRepName->fetch_array()){
            $repName = $repRs[0];
        }

      }
    }

?>
<!DOCTYPE html>
<html lang="en" dir="ltr">

<?php include_once('controls/meta.php'); ?>

<body class="layout-default" onload="startTime()">

                        <style>
                          @media print {
                            @page {
                              size: auto;   /* auto is the initial value */
                              size: A4 portrait;
                              margin: 0;  /* this affects the margin in the printer settings */
                              border: 1px solid red;  /* set a border for all printed pages */
                            }
                            body {
                              /*zoom: 80%;*/
                              /*transform: scale(.9);*/
                              /*margin-top: -600px;*/
                              /*width: auto !important;*/
                            }
                            #btn-print {
                              display: none;
                            }
                            #btn-stock-sent {
                              display: none;
                            }
                            #btn-stock-delivered {
                              display: none;
                            }
                            #sidebar{
                              visibility: hidden;
                            }
                            #topbar{
                              display: none;
                            }
                            #footer{
                              display: none;
                            }
                            #invoice-print{
                                /*margin-left: -300px !important;*/
                                /*float:left !important;*/
                                /*width: 100%;*/
                                /*margin-top: -100px;
                                margin-left: -250px;*/
                            }
                            .main-panel{
                                width: 105% !important;
                            }
                            #outlet-details{
                                /*margin-left: 650px !important;*/
                                margin-top: -110px;
                            }
                            #print-footer{
                                text-align: center;
                            }
                            #btn-minus{
                                display: none;
                            }
                            #btn-plus{
                                display: none;  
                            }
                            #btn-discount-model{
                                display: none;  
                            }
                            #default-drawer{
                                display: none;  
                            }
                            #header{
                                display: none;  
                            }
                            #app-settings{
                                display: none;  
                            }
                            #print-image{
                                display: none;
                            }
                            
                            #shop-details-area{
                                margin-top:20px;
                            }
                            
                            #invoice-details-area{
                                margin-top:-110px;
                            }
                            
                            #area-letter-head{
                                margin-top:-90px;
                            }
                            
                            #area-card{
                                border:none;
                            }
                            
                            #letter-logo{
                                width:100%;
                            }
                            
                            #header-text{
                                font-size:17px !important;
                            }
                            
                            #area-rep-name{
                                font-size:20px !important;
                            }
                            
                            
                            #area-invo-date{
                                font-size:20px !important;
                            }
                            
                            
                            #area-invo-no{
                                font-size:20px !important;
                            }
                            
                            #area-img{
                               width: 50% !important;
                            }
                            
                            #sign-area{
                                visibility:visible !important;
                            }
                            
                            #amendments-row{
                                display:none !important;
                            }
                            
                            
                            
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
                        <div class="card card-form" id="area-card">
                            <div class="row no-gutters">
                                

                                <div class="col-md-12" style="padding: 10px;">
                               
                                    <?php if($PaymentStatus=='0'){ ?>   
                                        <!-- <div class="container" id="invoice-print"> -->
                                    <?php }else{ ?>
                                        <!-- <div class="container" id="invoice-print" style="background-image: url(assets/img/paid_stic.png); background-repeat: no-repeat; background-position: center;"> -->
                                    <?php }?>
                                    
                                    <div class="row" id="area-letter-head">
                                        
                                        <div class="col-md-12 text-center" id="company-details">
                                            <img src="assets/img/shop_logo.png" style="width: 50%;" class="img-rounded logo" id="area-img">
                                        </div>
                                        
                                    </div> 
                                    </br>
                                    
                                    <div class="row">
                                        <div class="col-md-12 text-center">
                                           <b> <span style="font-size:17px" id="header-text">No:09,Moragahapitiya Estate,Balagolla,Kandy.</span></b></br>
                                           <b> <span style="font-size:17px" id="header-text">Tel: 0812375383 , E-mail: goallanka@gmail.com</span></b></br>
                                        </div>
                                        
                                        
                                    </div>
                                    
                                    
                                    
                                    <hr>
                                    
                                    <div class="row" style="margin-top:5px">
                                        <div class="col-md-12 text-center">
                                            <h2>Invoice <font style="font-size: 15px;">(<?php if($PaymentMethod=='0' || $PaymentMethod=='1' || $PaymentMethod=='3'){echo 'Cash Invoice';}else{echo 'Credit invoice';} ?><?php if($PaymentStatus=='0'){ ?> -  <font style="color: #FF0000; font-weight: 700;">Not Paid</font><?php }else{?> -  <font style="color: #26580F; font-weight: 700;">Paid</font><?php } ?>)</font></h2>
                                        </div>
                                    </div>
                                        
                                        <div class="container" id="shop-details-area">
                                            <div class="row" style="display: flex; justify-content: space-between;">
                                            
                                            
                                        <div style="width: 45%; text-align: center;">
                                            <table class="invoice-head">
                                                <tbody>
                                                    <tr>
                                                        <td style="float: left;"><strong>Shop Name :</strong></td>
                                                        <td style="font-size: 17px; float: right;"><?php echo $OutletName; ?></td>
                                                    </tr>
                                                    <tr>
                                                        <td style="float: left;"><strong>Contact :</strong></td>
                                                        <td style="font-size: 17px; float: right;"><?php echo $OutletContactNumber; ?></td>
                                                    </tr>
                                                    <tr>
                                                        <td style="float: left;"><strong>Address :</strong></td>
                                                        <td style="font-size: 15px; float: right;"><?php echo $OutletAddress; ?></td>
                                                    </tr>
                                                    
                                                    
                                                </tbody>
                                            </table>
                                            
                                            
                                            
                                            
                                        </div>
                                        
                                         <div style="width: 45%; text-align: center;">
                                                
                                                <table>
                                                    <tbody>
                                                        <tr>
                                                        <td style="float: left;"><strong>Invoice #</strong></td>
                                                        <td id="area-invo-date"><?php echo $OrderId; ?></td>
                                                    </tr>
                                                    <tr>
                                                        <td style="float: left;"><strong>Date :</strong></td>
                                                        <td id="area-invo-no"><?php echo $InvoiceDate; ?></td>
                                                    </tr>
                                                    </tbody>
                                                </table>
                                                
                                            </div>
                                        
                                        
                                        
                                        
                                        
                                    </div>
                                        </div>
                                        
                                        
                                    <hr>    
                                    
                                    
                                    
                                    
                                    
                                    
                                    <div class="row">
                                        <div class="col-md-12">
                                            <p id="area-rep-name">Sales Rep : <?php echo $repName;?></p>

                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col-md-12 well invoice-body">
                                            <table class="table table-bordered">
                                                <thead>
                                                    <tr>
                                                        <th  style="font-size: 17px">No</th>
                                                        <th  id="print-image"style="font-size: 17px">Product Image</th>
                                                        <th style="font-size: 17px; width: 50%;">Product Name</th>
                                                        <th style="font-size: 17px; width: 15%; text-align:center">Qty</th>
                                                        <th style="font-size: 17px">Unit Price</th>
                                                        <th style="font-size: 17px">Discount</th>
                                                        <th style="font-size: 17px; width: 30%; text-align:center;" >TOTAL</th>
                                                    </tr>
                                                </thead>
                                                <tbody id="order-area">
                                                    <?php
                                                        $Productsql = "SELECT * FROM tbl_order_item_details toid INNER JOIN tbl_item tit ON toid.itemId=tit.itemId WHERE toid.order_id='$InvoiceID'";




                                                        $Productrs=$conn->query($Productsql);
                                                        $GrandTotal=0;
                                                        $item_no = 0;
                                                        while($Prow=$Productrs->fetch_array())
                                                        {  
                                                            $item_no += 1;
                                                            $ProductOrderId=$Prow[0];                       
                                                            $ProductItemId=$Prow[1];                       
                                                            $ProductQty=$Prow[3];                       
                                                            $ProductDiscountedPrice=$Prow[4];                        
                                                            $ProductDiscountedValue=$Prow[5];                       
                                                            // $ProductPrice=$Prow[6];     
                                                            $RPID=$Prow[7]; 

                                                            //////////////////
                                                            $ItemId=$Prow[8]; 
                                                            $ItemCode=$Prow[9]; 
                                                            $ItemName=$Prow[10]; 
                                                            $ItemPrice=$Prow[11];
                                                            $ItemImage=$Prow[25];  

                                                    ?>

                                                    <?php
                                                    ////////Calculation//////////////
                                                    $DiscountedPrice = (double)$ItemPrice-(((double)$ItemPrice*(double)$ProductDiscountedValue)/100);
                                                    //With QTY
                                                    $ItemTotal = (double)$DiscountedPrice*(double)$ProductQty;

                                                    //Grand Total
                                                    $GrandTotal += $ItemTotal;
                                                    ////////Calculation//////////////
                                                    ?>
                                                    
                                                        <tr>
                                                            <!-- <td><?php echo $ItemCode; ?></td> -->
                                                            <td style="font-weight: bold;"><?php echo $item_no; ?></td>

                                                            <td id="print-image"><img src="<?php echo $ItemImage;?>" width="70"></td>
                                                            
                                                            <td style="font-weight: bold; width: auto"><?php echo $ItemName; ?></td>


                                                            <td style="font-weight: bold;">
                                                                <font class="pull-right">
                                                                    <div class="row" style="padding-right: 10px;">
                                                                        <div class="col-md-4">
                                                                            <?php if($PaymentStatus=='0' && ($DeliveryStatus=='0' || $DeliveryStatus=='1')){ ?>
                                                                            <form id="Product-Minus">
                                                                                <input type="hidden" value="<?php echo $InvoiceID; ?>" name="invoice_id">
                                                                                <input type="hidden" value="<?php echo $ProductOrderId; ?>" name="product_id">
                                                                                <input type="hidden" value="<?php echo $ItemId; ?>" name="item_id">
                                                                                <button type="submit" id="btn-minus" class="btn btn-danger btn-sm">-</button>
                                                                            </form>
                                                                            <?php }else{ }?> 
                                                                        </div>
                                                                        <?php if($PaymentStatus=='0' && ($DeliveryStatus=='0' || $DeliveryStatus=='1')){ ?>
                                                                        <div class="col-md-4" style="padding:0; text-align:center;">
                                                                        <?php }else{?>
                                                                        <div class="col-md-12">
                                                                        <?php }?> 
                                                                            <?php echo $ProductQty; ?>  
                                                                        </div>
                                                                        <div class="col-md-4">
                                                                            <?php if($PaymentStatus=='0' && ($DeliveryStatus=='0' || $DeliveryStatus=='1')){ ?>
                                                                            <form id="Product-Plus">
                                                                                <input type="hidden" value="<?php echo $InvoiceID; ?>" name="invoice_id">
                                                                                <input type="hidden" value="<?php echo $ProductOrderId; ?>" name="product_id">
                                                                                <input type="hidden" value="<?php echo $ItemId; ?>" name="item_id">
                                                                                <button type="submit" id="btn-plus" class="btn btn-success btn-sm">+</button> 
                                                                            </form>
                                                                            <?php }else{ }?> 
                                                                        </div>
                                                                    </div>
                                                                    
                                                                    <!-- <button type="submit" id="btn-plus" class="badge badge-success">+</button>  -->
                                                                </font>
                                                            </td>

                                                            
                                                            <td><font class="pull-right" style="font-weight: bold;"><?php echo number_format($ItemPrice,2); ?></font></td>
                                                            <td style="font-weight: bold;>
                                                                <font class="pull-right">
                                                                    <?php echo $ProductDiscountedValue.'%'; ?>
                                                                    <?php if($PaymentStatus=='0' && ($DeliveryStatus=='0' || $DeliveryStatus=='1')){ ?>
                                                                        <button type="submit" id="btn-discount-model" class="btn btn-info btn-sm" data-toggle="modal" data-target="#DiscountModalCenter<?php echo $ProductOrderId; ?>"><i class="fa fa-pencil"></i></button>
                                                                    <?php }else{ }?> 
                                                                </font>
                                                            </td>
                                                            
                                                            <td><font class="pull-right" style="font-weight: bold;"><?php echo number_format($ItemTotal,2); ?></font></td>
                                                        </tr>


                                                        <!--Add Discount Modal -->
                                                        <div class="modal fade discountmodel" id="DiscountModalCenter<?php echo $ProductOrderId; ?>" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true" data-backdrop="false">
                                                          <div class="modal-dialog modal-dialog-centered" role="document">
                                                            <div class="modal-content">
                                                              <div class="modal-header">
                                                                <h6 class="modal-title" id="exampleModalLongTitle">Update discount to <?php echo $ItemName; ?></h6>
                                                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                                  <span aria-hidden="true">&times;</span>
                                                                </button>
                                                              </div>
                                                              <form id="Add-Discount">
                                                                <div class="modal-body">
                                                                    <div class="col-md-12">
                                                                        <input type="hidden" name="order_id" value="<?php echo $ProductOrderId; ?>">
                                                                        <input type="hidden" name="invoice_id" value="<?php echo $InvoiceID; ?>">
                                                                        <label>Discount <font style="color: #FF0000;">*</font></label><br>
                                                                          <div class="input-group mb-3">
                                                                            <input type="number" class="form-control" name="discounted_value" min="0" max="100" placeholder="Discount" value="<?php echo $ProductDiscountedValue; ?>" style="text-align: right;" required>
                                                                            <span class="input-group-text">%</span>
                                                                          </div>
                                                                        </div>
                                                                  </div>
                                                                  <div class="modal-footer">
                                                                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                                                    <button type="submit" id="btn-add-discount" class="btn btn-primary">Save changes</button>
                                                                  </div>
                                                              </form>
                                                            </div>
                                                          </div>
                                                        </div>
                                            

                                                    <?php } ?>
                                            
                                                </tbody>

                                                <tfoot>

                                                    <tr><td colspan="5"></td></tr>
                                                    <?php if ($OptinalDiscountPercentage=='0') { }else{ ?>
                                                    <tr>
                                                        <td colspan="2">&nbsp;</td>
                                                        <td colspan="3"><strong>Discount</strong></td>
                                                        <td><strong class="pull-right"><?php echo $OptinalDiscountPercentage.'%'; ?></strong></td>
                                                    </tr>
                                                    <?php } ?>
                                                    
                                                    
                                                    <?php
                                                    
                                                            $check_for_amendments = $conn->query("SELECT * FROM tbl_order_amendments WHERE order_id = '$InvoiceID'");
                                                            if($cars = $check_for_amendments->fetch_array()){
                                                                
                                                                $increase_value = $cars[1];
                                                                $decrease_value = $cars[2];
                                                                $message = "";
                                                                
                                                                
                                                                if($increase_value > 0){
                                                                    $GrandTotal += $increase_value;
                                                                    $message = number_format($increase_value,2)." Increased";
                                                                }
                                                                
                                                                if($decrease_value > 0){
                                                                    $GrandTotal -= $decrease_value;
                                                                    $message = number_format($decrease_value,2)." Decreased";
                                                                }
                                                                
                                                                
                                                                
                                                           
                                                           ?>
                                                           
                                                            <tr>
                                                        <td></td>
                                                        <td colspan="2">&nbsp;</td>
                                                        <td colspan="2"><strong>Total</strong></td>
                                                        <td colspan="2"><strong class="pull-right" id="grand-total"><?php echo number_format($GrandTotal,2); ?> <span style="color:red">( Changes Applied - <?php echo $message;?> )</span></strong></td>
                                                    </tr>
                                                           
                                                           <?php
                                                                
                                                                
                                                            }else{
                                                                
                                                                ?>
                                                                
                                                    <tr>
                                                        <td></td>
                                                        <td colspan="2">&nbsp;</td>
                                                        <td colspan="2"><strong>Total</strong></td>
                                                        <td colspan="2"><strong class="pull-right" id="grand-total"><?php echo number_format($GrandTotal,2); ?></strong></td>
                                                    </tr>
                                                                
                                                                <?php
                                                                
                                                            }
                                                    
                                                    
                                                    ?>
                                                    
                                                    
                                                    
                                                    <tr id="amendments-row">
                                                        <td></td>
                                                        <td colspan="2">&nbsp;</td>
                                                        <td colspan="3"><strong>Amendments</strong></td>
                                                        <td><input type="number" id="amendment-value" class="form-control"><button class="btn btn-success" id="increase-btn">Increase</button><button class="btn btn-danger" id="decrease-btn">Decrease</button></td>
                                                    </tr>
                                                    
                                                    
                                                </tfoot>

                                            </table>
                                        </div>
                                    </div>

                                    <?php 
                                        $FreeCountProductsql = "SELECT COUNT(*) FROM tbl_order_free_issues WHERE order_id='$InvoiceID'";
                                        $FreeCountProductresult = mysqli_query($conn, $FreeCountProductsql);
                                        $FreeCountProduct = mysqli_fetch_assoc($FreeCountProductresult)['COUNT(*)'];
                                        // echo $FreeCountProduct;
                                    ?>
                                    <?php if ($FreeCountProduct=='0') { }else{ ?>
                                    <div class="row">
                                        <div class="col-md-6">
                                            <h5>Free Issue</h5>
                                            <table class="table table-bordered">
                                                <thead>
                                                    <tr>
                                                        <th>#</th>
                                                        <th>Product Name</th>
                                                        <th><font style="float: right;">Quantity</font></th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <?php
                                                        $FreeItemCount=0;
                                                        $FreeProductsql = "SELECT * FROM tbl_order_free_issues WHERE order_id='$InvoiceID'";
                                                        $FreeProductrs=$conn->query($FreeProductsql);
                                                        while($Prow=$FreeProductrs->fetch_array())
                                                        {  
                                                            $FreeItemId=$Prow[1];
                                                            $FreeItemName=$Prow[2];
                                                            $FreeItemPrice=$Prow[3];
                                                            $FreeItemQty=$Prow[4];
                                                    ?>
                                                    <tr>
                                                        <td><?php echo $FreeItemCount+=1; ?></td>
                                                        <td><?php echo $FreeItemName; ?></td>
                                                        <td><font style="float: right;"><?php echo $FreeItemQty; ?></font></td>
                                                    </tr>
                                                    <?php } ?>
                                                    
                                                    
                                                    
                                                    
                                                    
                                                    
                                                    
                                                    
                                                    
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                    <?php } ?>

                                    <div class="row">
                                        <div class="col-md-12 well invoice-thank">
                                            <h5 style="text-align:center; font-size: 12px;"></h5>
                                            <h6 style="text-align:center; font-size: 12px;"></h6>
                                        </div>
                                    </div><br>
                                    <div class="row" id="print-footer" style="display: none;">
                                        <div class="col-md-1"></div>
                                        <div class="col-md-4">
                                            <strong>Phone:</strong><a href="tel:+94775757377" style="color: #000;">+94-77 575 7377</a>
                                        </div>
                                        <div class="col-md-4">
                                            <strong>Email:</strong> <a href="mailto:info@amazoft.com" style="color: #000;">info@amazoft.com</a>
                                        </div>
                                        <div class="col-md-3">
                                            <strong>Website:</strong> <a href="https://amazoft.com" style="color: #000;" target="_blank">www.amazoft.com</a>
                                        </div>
                                    </div>

                                    <br><br>

                                        <button onclick="window.print();" class="btn btn-dark btn-sm pull-right" id="btn-print"><i class="fa fa-print"></i> Print</button>

                                        <?php if ($DeliveryStatus=='0') { ?>
                                            <input type="hidden" id="invoice_id_sent" value="<?php echo $InvoiceID; ?>">
                                            <button type="submit" class="btn btn-info btn-sm pull-right" id="btn-stock-sent"><i class="fa fa-paper-plane-o"></i> Sent</button>
                                        <?php }elseif ($DeliveryStatus=='1') { ?>
                                            <input type="hidden" id="invoice_id_deliver" value="<?php echo $InvoiceID; ?>">
                                            <button type="submit" class="btn btn-info btn-sm pull-right" id="btn-stock-delivered"><i class="fa fa-check"></i> Delivered</button>
                                        <?php }else{} ?>
                                        

                                        <?php if($PaymentStatus=='1') {}else{ ?>
                                                <button type="submit" class="btn btn-success btn-sm pull-right" data-toggle="modal" data-target="#PaymentModalCenter" id="btn-print"><i class="fa fa-money"></i> Payments</button>
                                        <?php } ?> 
                                      


                                 
                                </div>
                               

                                
                            </div>

                        </div>

                        
                    </div>



                      <?php if($PaymentMethod=='2'){ ?>
                    <!--Start Payment History -->
                    <div class="container-fluid page__container">
                        <div class="card card-form">
                            <div class="row no-gutters">

                            	<div class="col-md-12" style="padding: 10px;">
                            		<h2>Payment History</h2>
                            		<div class="row">
                                        <div class="col-md-12 well invoice-body">
                                            <table class="table table-bordered">
                                                <thead>
                                                    <tr>
                                                        <th>Payment Method</th>
                                                        <th>Payment Date Time</th>
                                                        <th>Sales Rep Or Admin Name</th>
                                                        <th>Amount (.Rs)</th>
                                                        <th></th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                	<?php
                                                        $OutsatandingHistorySql = "SELECT * FROM tbl_outstanding_payments WHERE order_id='$InvoiceID' ORDER BY id DESC";
                                                        $OutsatandingHistoryQuery=$conn->query($OutsatandingHistorySql);
                                                        while($OHrow=$OutsatandingHistoryQuery->fetch_array())
                                                        {  
                                                            $OutstandingHistoryId=$OHrow[0];
                                                            $OutstandingHistoryPayedAmount=$OHrow[2];
                                                            $OutstandingHistoryDateTime=$OHrow[3];
                                                            $OutstandingHistoryRepId=$OHrow[4];
                                                            $OutstandingHistoryAdminId=$OHrow[5];
                                                            $OutstandingHistoryPaymentMethod=$OHrow[6];

                                                            if($OutstandingHistoryRepId=='0'){
                                                            	//Admin details
                                                            	$OutstandingPayWebConsoleSql = "SELECT * FROM tbl_web_console_user_account WHERE user_id='$OutstandingHistoryAdminId'";
		                                                        $OutstandingPayWebConsoleQuery=$conn->query($OutstandingPayWebConsoleSql);
		                                                        if($OPWCrow=$OutstandingPayWebConsoleQuery->fetch_array())
		                                                        {  
		                                                            $OutstandingWebConsoleName=$OPWCrow[6];
		                                                        }
                                                            }else{
                                                            	//SalesRep Details
                                                            	$OutstandingPaySalesRepSql = "SELECT * FROM tbl_user WHERE id='$OutstandingHistoryRepId'";
		                                                        $OutstandingPaySalesRepQuery=$conn->query($OutstandingPaySalesRepSql);
		                                                        if($OPSRrow=$OutstandingPaySalesRepQuery->fetch_array())
		                                                        {  
		                                                            $OutstandingSalesRepName=$OPSRrow[1];
		                                                        }
                                                            }
                                                    ?>
                                                    <tr>
                                                        <td><?php echo $OutstandingHistoryPaymentMethod; ?></td>
                                                        <td><?php echo $OutstandingHistoryDateTime; ?></td>

                                                        <td>
                                                        	<?php 
                                                        		if($OutstandingHistoryRepId=='0'){
                                                        			echo WEB.' - '.$OutstandingWebConsoleName;
                                                        		}else{
																	echo SR.' - '.$OutstandingSalesRepName;
                                                        		}
                                                        	?>
                                                        		
                                                        </td>

                                                        <td><font style="float: right; font-weight: 600;"><?php echo number_format($OutstandingHistoryPayedAmount,2); ?></font></td>
                                                        <td>
                                                        	<?php if($is_distributor){ }else{ ?>
                                                        		<?php if($PaymentStatus=='0'){ ?>
		                                                        	<center>
		                                                        		<button type="button" data-toggle="modal" data-target="#OutstandingDeleteModel<?php echo $OutstandingHistoryId; ?>" class="btn btn-danger btn-sm" data-backdrop="false">X</button>
		                                                        	</center>
                                                        		<?php }else{ }?>
                                                    		<?php } ?>
                                                        </td>
                                                    </tr>

                                                    <!-- Modal -->
													<div class="modal fade" id="OutstandingDeleteModel<?php echo $OutstandingHistoryId; ?>" tabindex="1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
													  <div class="modal-dialog modal-dialog-centered" role="document">
													    <div class="modal-content">
													      <div class="modal-header">
													        <h5 class="modal-title" id="exampleModalLongTitle">Outstanding Payment Rs.<?php echo number_format($OutstandingHistoryPayedAmount,2); ?> Delete</h5>
													        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
													          <span aria-hidden="true">&times;</span>
													        </button>
													      </div>
													      <form id="Remove-Payment-Outstanding" method="POST">
													      <div class="modal-body">
													      	
													      	<input name="payment_method" type="hidden" class="form-control" value="<?php echo $OutstandingHistoryPaymentMethod; ?>" required>
													      	<input name="amount" type="hidden" class="form-control" value="<?php echo $OutstandingHistoryPayedAmount; ?>" required>
													      	
													      	
													      	
													      	<input name="order_id" type="hidden" class="form-control" value="<?php echo $InvoiceID; ?>" required>
													      	<input name="user_id" type="hidden" class="form-control" value="<?php echo $user_id; ?>" required>
													      	<input name="outstanding_payment_id" type="hidden" class="form-control" value="<?php echo $OutstandingHistoryId; ?>" required>
													      	<input name="sales_rep_id" type="hidden" class="form-control" value="<?php echo $OutstandingHistoryRepId; ?>" required>
													      	<input name="admin_id" type="hidden" class="form-control" value="<?php echo $OutstandingHistoryAdminId; ?>" required>
													        <div class="form-group">
				                                                <label>Type your password here</label>
				                                                <input name="password" type="password" class="form-control" placeholder="XXXXXX" required>
				                                            </div>
													      </div>
													      <div class="modal-footer">
													        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
													        <button type="submit" class="btn btn-primary">Confirm to remove</button>
													      </div>
													  	  </form>
													    </div>
													  </div>
													</div>

                                                    <?php } ?>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>


                            	</div>

                            </div>
                        </div>
                    </div>

                	<?php }else{ } ?>
                    <!--End Payment History -->
                    
                    <div id="sign-area" style="visibility:hidden">
                        <br><br><br><br>
                
                    <div class="container" id="shop-details-area">
                        <div class="row" style="display: flex; justify-content: space-between;">  <div style="width: 45%; text-align: center;">  ..............................<br>
  Authorized By
    </div>
    <div style="width: 45%; text-align: center;">  ..............................<br>
    Received By
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

    <!--Add Payment Modal -->
    <div class="modal fade discountmodel" id="PaymentModalCenter" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
      <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h6 class="modal-title" id="exampleModalLongTitle">Add Payments</h6>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                  <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <?php
                $CreditPaymentSQL = "SELECT * FROM tbl_credit_orders WHERE order_id='$InvoiceID'";
                $CPS=$conn->query($CreditPaymentSQL);
                if($CPSrow=$CPS->fetch_array())
                {  
                    $CreditOrderId=$CPSrow[0];
                    $FixedTotal=$CPSrow[2];
                    $EditableTotal=$CPSrow[3];
                    $OutletID=$CPSrow[4];
                    $RouteId=$CPSrow[5];
                    $UserId=$CPSrow[6];
                  }

            ?>
            <h3 style="text-align: center;">Pending Payment</h3>
            <h2 style="text-align: center; font-weight: 600;" id="payment-total">Rs. <?php echo number_format($EditableTotal,2); ?></h2>
            <form id="Add-Payment">
                <div class="modal-body">
                    <div class="col-md-12">
                        <input type="hidden" name="invoice_id" value="<?php echo $InvoiceID; ?>">
                        <input type="hidden" name="user_id" value="<?php echo $user_id; ?>">
                                                        
                        <div class="form-group">
                            <label>Payment Amount</label>
                            <input type="text" class="form-control" name="payment_amount" min="0" max="100" placeholder="Payment Amount" value="<?php echo $EditableTotal; ?>" style="text-align: right;" required>
                        </div>
                        
                        
                        <div id="area-cheque-info" style="display:none">
                            
                            <div class="form-group">
                                <label>Cheque No</label>
                                <input type="text" class="form-control" name="cheque_no" placeholder="Cheque No" id="cheque_no">
                            </div>
                            
                            <div class="form-group">
                                <label>Bank</label>
                                <input type="text" class="form-control" name="bank_name" placeholder="Bank" id="bank_name">
                            </div>
                            
                            <div class="form-group">
                                <label>Date to cash</label>
                                <input type="date" class="form-control" name="date_to_cash" placeholder="Date to cash" id="date_to_cash">
                            </div>
                            
                            
                            
                            
                        </div>
                        
                        
                        
                        
                        
                        <div class="form-group">
                            <label>Payment Method</label>
                            <select class="form-control" name="payment_method" id="select-payment-methods">
                                <option disabled>Choose..</option>
                                <option name="Cash">Cash</option>
                                <option name="Cheque">Cheque</option>
                                <option name="Visa/Master">Visa/Master</option>
                            </select>
                        </div>
                    </div>
                    </div>
                <div class="modal-footer">
                  <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                  <button type="submit" id="btn-add-payments" class="btn btn-primary">Save changes</button>
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

    <!-- App Settings (safe to remove) -->
    <script src="assets/js/app-settings.js"></script>

    <!-- Dropzone -->
    <script src="assets/vendor/dropzone.min.js"></script>
    <script src="assets/js/dropzone.js"></script>

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


    <!-------------------------Add Discount--------------------------------------------->
    <script>
          
        $(document).on('submit', '#Add-Discount', function(e){
        e.preventDefault(); //stop default form submission

        $("#btn-add-discount").attr("disabled",true);

        var formData = new FormData($(this)[0]);

        $.ajax({
            
            
                beforeSend : function() {

                $("#progress_alert").addClass('show'); 

                },

                url:"invoice_post/add_discount.php",
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
                       $("#order-area").html(json.data);
                       $("#success_msg").html(json.msg);
                       $("#success_alert").addClass('show'); 
                       $("#grand-total").html(json.GrandTotal);
                       $("#payment-total").html(json.Payment);
                       $(".discountmodel").modal('hide');
                       $('.modal-backdrop'). remove();
                       setTimeout(function(){$("#success_alert").removeClass('show');  }, 1000);
                       $("#btn-add-discount").attr("disabled",false);
                       // document.getElementById('record-textarea').value = '';
                       
                        
                    }else{
                        $("#danger_alert").addClass('show');
                        setTimeout(function(){ $("#danger_alert").removeClass('show'); }, 1000);
                        $("#btn-add-discount").attr("disabled",false);
                    }
                    
                }

            });

        return false;
        });
    </script>

    <!-------------------------Add Product to Minus---------------------------------------->
    <script>
        
        $(document).on('submit', '#Product-Minus', function(e){
        e.preventDefault(); //stop default form submission

        $("#btn-minus").attr("disabled",true);
        $("#btn-plus").attr("disabled",true);

        var formData = new FormData($(this)[0]);

        $.ajax({
            
            
                beforeSend : function() {

                    $("#progress_alert").addClass('show'); 

                },

                url:"invoice_post/update_product_minus.php",
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
                       $("#order-area").html(json.data);
                       $("#success_msg").html(json.msg);
                       $("#success_alert").addClass('show'); 
                       $("#grand-total").html(json.GrandTotal);
                       $("#payment-total").html(json.Payment);
                       setTimeout(function(){$("#success_alert").removeClass('show');  }, 1000);
                       $("#btn-minus").attr("disabled",false);
                       $("#btn-plus").attr("disabled",false);

                    }else{

                        // $("#danger_alert_msg").addClass('show');
                        setTimeout(function(){ $("#danger_alert_msg").removeClass('show'); }, 1000);
                        $("#btn-minus").attr("disabled",false);
                        $("#btn-plus").attr("disabled",false);
                    }
                    
                }

            });

        return false;
        });
    </script>

    

<!-------------------------Add Product to Plus---------------------------------------->
    <script>
    
    //$('#payDetailForm').hide();
        
        $(document).on('submit', '#Product-Plus', function(e){
        e.preventDefault(); //stop default form submission

        $("#btn-minus").attr("disabled",true);
        $("#btn-plus").attr("disabled",true);

        var formData = new FormData($(this)[0]);

        $.ajax({
            
            
                beforeSend : function() {

                    $("#progress_alert").addClass('show'); 

                },

                url:"invoice_post/update_product_plus.php",
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
                       $("#order-area").html(json.data);
                       $("#success_msg").html(json.msg);
                       $("#success_alert").addClass('show'); 
                       $("#grand-total").html(json.GrandTotal);
                       $("#payment-total").html(json.Payment);
                       setTimeout(function(){$("#success_alert").removeClass('show');  }, 1000);
                       $("#btn-minus").attr("disabled",false);
                       $("#btn-plus").attr("disabled",false);

                    }else{
                        $("#error_msg").html(json.msg);
                        $("#danger_alert_msg").addClass('show');
                        setTimeout(function(){ $("#danger_alert_msg").removeClass('show'); }, 1000);
                        $("#btn-minus").attr("disabled",false);
                        $("#btn-plus").attr("disabled",false);
                    }
                    
                }

            });

        return false;
        });
    </script>

    <script>
    
    
    
        
        $(document).on('submit', '#Add-Payment', function(e){
        e.preventDefault(); //stop default form submission

        $("#btn-add-payments").attr("disabled",true);
        
        if( $("#select-payment-methods").val() =="Cheque" && ( $("#cheque_no").val() == "" || $("#bank_name").val() == "" || $("#date_to_cash").val() == "" )   ){
                        
                        $('#PaymentModalCenter').modal('hide');
                        $('body').removeClass('modal-open');
                        $('.modal-backdrop').remove();
            
                        $("#error_msg").html("Please enter required values to enter cheque.");
                        $("#danger_alert_msg").addClass('show');
                        setTimeout(function(){ $("#danger_alert_msg").removeClass('show'); }, 2000);
                        $("#btn-add-payments").attr("disabled",false);
           
            
        }else{
            var formData = new FormData($(this)[0]);

        $.ajax({
            
            
                beforeSend : function() {

                $("#progress_alert").addClass('show'); 

                },

                url:"invoice_post/add_payment.php",
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
                       // $("#order-area").html(json.data);
                       $("#success_msg").html(json.msg);
                       $("#success_alert").addClass('show'); 

                       setTimeout(function(){$("#success_alert").removeClass('show');  }, 1000);
                       $("#btn-add-payments").attr("disabled",false);
                       // document.getElementById('record-textarea').value = '';
                       window.location.reload();
                       
                        
                    }else{
                        $("#error_msg").html(json.msg);
                        $("#danger_alert_msg").addClass('show');
                        setTimeout(function(){ $("#danger_alert_msg").removeClass('show'); }, 1000);
                        $("#btn-add-payments").attr("disabled",false);
                    }
                    
                }

            });
        }
        
        
        
        
        

        

        return false;
        });
    </script>

<!---------------------------Sent Stock to outlet-------------------------------------------------->

    <script>


        $(document).ready(function(){
            
            
            
            $("#decrease-btn").click(function(){
                
                
                $.ajax({
            
                beforeSend : function() {

                $("#progress_alert").addClass('show'); 

                },

                url:"scripts/amend_invoice.php",
                type: 'POST',
                data: {
                    invoice:<?php echo $InvoiceID;?>,
                    value:$("#amendment-value").val(),
                    option:'decrease_'
                },
                //async: false,
                
                success: function (data) {
                    
                    alert(data);
                    
                    $("#progress_alert").removeClass('show');
                    
                    var json=JSON.parse(data);
                    
                    if(json.result){
                       $("#success_msg").html(json.msg);
                       $("#success_alert").addClass('show'); 
                       setTimeout(function(){$("#success_alert").removeClass('show');  }, 1000);
                       // $("#btn-stock-sent").attr("disabled",false);
                       // document.getElementById('record-textarea').value = '';
                       window.location.reload();
                       
                        
                    }else{
                        $("#danger_alert").addClass('show');
                        setTimeout(function(){ $("#danger_alert").removeClass('show'); }, 1000);
                        // $("#btn-stock-sent").attr("disabled",false);
                    }
                    
                }

            });
                
                
            });
            
            
            $("#increase-btn").click(function(){
                
                
                $.ajax({
            
                beforeSend : function() {

                $("#progress_alert").addClass('show'); 

                },

                url:"scripts/amend_invoice.php",
                type: 'POST',
                data: {
                    invoice:<?php echo $InvoiceID;?>,
                    value:$("#amendment-value").val(),
                    option:'increase_'
                },
                //async: false,
                
                success: function (data) {
                    
                    alert(data);
                    
                    $("#progress_alert").removeClass('show');
                    
                    var json=JSON.parse(data);
                    
                    if(json.result){
                       $("#success_msg").html(json.msg);
                       $("#success_alert").addClass('show'); 
                       setTimeout(function(){$("#success_alert").removeClass('show');  }, 1000);
                       // $("#btn-stock-sent").attr("disabled",false);
                       // document.getElementById('record-textarea').value = '';
                       window.location.reload();
                       
                        
                    }else{
                        $("#danger_alert").addClass('show');
                        setTimeout(function(){ $("#danger_alert").removeClass('show'); }, 1000);
                        // $("#btn-stock-sent").attr("disabled",false);
                    }
                    
                }

            });
                
                
            });
            
            
            
            
            $("#select-payment-methods").change(function(){
                if( $("#select-payment-methods").val() =="Cheque" ){
                    $("#area-cheque-info").slideDown();
                }else{
                    $("#area-cheque-info").hide();
                }
            });
            
            

            $("#btn-stock-sent").click(function(){
                
                $.ajax({
            
                beforeSend : function() {

                $("#progress_alert").addClass('show'); 

                },

                url:"invoice_post/sent_stock_to_outlet.php",
                type: 'POST',
                data: {
                    invo_id:$("#invoice_id_sent").val()
                },
                //async: false,
                
                success: function (data) {
                    
                    $("#progress_alert").removeClass('show');
                    
                    var json=JSON.parse(data);
                    
                    if(json.result){
                       $("#success_msg").html(json.msg);
                       $("#success_alert").addClass('show'); 
                       setTimeout(function(){$("#success_alert").removeClass('show');  }, 1000);
                       // $("#btn-stock-sent").attr("disabled",false);
                       // document.getElementById('record-textarea').value = '';
                       window.location.reload();
                       
                        
                    }else{
                        $("#danger_alert").addClass('show');
                        setTimeout(function(){ $("#danger_alert").removeClass('show'); }, 1000);
                        // $("#btn-stock-sent").attr("disabled",false);
                    }
                    
                }

            });


            });

        });

 
    </script>

    <!---------------------------Delivered Stock to outlet-------------------------------------------------->


    <script>


        $(document).ready(function(){

            $("#btn-stock-delivered").click(function(){
                
                $.ajax({
            
                beforeSend : function() {

                $("#progress_alert").addClass('show'); 

                },

                url:"invoice_post/stock_delivered.php",
                type: 'POST',
                data: {
                    invo_id_del:$("#invoice_id_deliver").val()
                },
                //async: false,
                
                success: function (data) {
                    
                    $("#progress_alert").removeClass('show');
                    
                    var json=JSON.parse(data);
                    
                    if(json.result){
                       $("#success_msg").html(json.msg);
                       $("#success_alert").addClass('show'); 
                       setTimeout(function(){$("#success_alert").removeClass('show');  }, 1000);
                       // $("#btn-stock-sent").attr("disabled",false);
                       // document.getElementById('record-textarea').value = '';
                       window.location.reload();
                       
                        
                    }else{
                        $("#danger_alert").addClass('show');
                        setTimeout(function(){ $("#danger_alert").removeClass('show'); }, 1000);
                        // $("#btn-stock-sent").attr("disabled",false);
                    }
                    
                }

            });


            });

        });


  
 
    </script>


    
    <!---------------------------------Remove Outstanding Payment--------------------------------->
    <script>
        
        $(document).on('submit', '#Remove-Payment-Outstanding', function(e){
        e.preventDefault(); //stop default form submission


        var formData = new FormData($(this)[0]);

        $.ajax({
            
            
                beforeSend : function() {

                $("#progress_alert").addClass('show'); 

                },

                url:"invoice_post/remove_outstanding_payment.php",
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
                       setTimeout(function(){$("#success_alert").removeClass('show');  }, 1000);
                       window.location.reload();
                       
                        
                    }else{
                        $("#error_msg").html(json.msg);
                        $("#danger_alert_msg").addClass('show');
                        setTimeout(function(){ $("#danger_alert_msg").removeClass('show'); }, 1000);
                    }
                    
                }

            });

        return false;
        });
    </script>

  

</body>
</html>