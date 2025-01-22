<?php
    session_start();
    require 'database/db.php';
    $db=new DB();
    $conn=$db->connect();
    date_default_timezone_set('Asia/Colombo');
    $currentDate=date('Y-m-d');

    $ReturnInvoiceID=base64_decode($_GET['i']);

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
    $sql = "SELECT * FROM tbl_return_order tor INNER JOIN tbl_outlet tou ON tor.outlet_id=tou.outlet_id INNER JOIN tbl_route tr ON tor.route_id=tr.route_id INNER JOIN tbl_distributor tdi ON tor.distributor_id=tdi.distributor_id WHERE tor.id='$ReturnInvoiceID'";
    $rs=$conn->query($sql);
    if($row=$rs->fetch_array())
    {    
      //Order Details          
      $ReturnId=$row[1];                       
      $ReturnType=$row[2];                       
      $ReturnDate=$row[3];                       
      $ReturnTime=$row[4];                       
      $Lon=$row[5];                       
      $Lat=$row[6];                       
      $BattryLevel=$row[7];                       
      $TimeStamp=$row[8];                       
      $AppVersion=$row[9];                       
                            
      $SessionId=$row[10];                       
      $OutletId=$row[11];                       
      $RouteId=$row[12];                       
      $DistributorId=$row[13];                       
      $UserId=$row[14]; 

      //Outlet Details
      /////////////////////////
      $OutletName=$row[16];
      $OwnerName=$row[17];
      $OutletContactNumber=$row[18];
      $OutletAddress=$row[19];

      //Route Details
      /////////////////////////
      $RouteName=$row[35]; 

      //Distributor Details
      ////////////////////////
      $DistributorName=$row[39]; 



       $repName = "N/A";



      if($UserId == 0){
        $split = explode("/",$ReturnId);
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
                              transform: scale(.9);
                              margin-top: -100px;
                              /*width: auto !important;*/
                            }
                            #btn-print {
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
                                margin-left: 650px !important;
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
                        <div class="card card-form">
                            <div class="row no-gutters">
                                

                                <div class="col-md-12" style="padding: 10px;">
                                    
                                    <div class="row">
                                        <div class="col-md-8" id="company-details">
                                            <img src="assets/img/shop_logo.png" style="width: 30%;" class="img-rounded logo">
                                               <address>
                                                <strong>Goal Lanka Marketing (PVT) LTD</strong><br>
                                                09, Moragahapitiya Estate,<br>
                                                Balagolla,Sri Lanka.<br>
                                                +94773 895 383 / +94812 375 383<br>
                                                globalwin20@gmail.com
                                            </address>
                                        </div>
                                        <div class="col-md-4 well" id="outlet-details">
                                            <table class="invoice-head">
                                                <tbody>
                                                    <tr>
                                                        <td class="pull-right"><strong>Shop Name :</strong></td>
                                                        <td><?php echo $OutletName; ?></td>
                                                    </tr>
                                                    <tr>
                                                        <td class="pull-right"><strong>Contact Number :</strong></td>
                                                        <td><?php echo $OutletContactNumber; ?></td>
                                                    </tr>
                                                    <tr>
                                                        <td class="pull-right"><strong>Address :</strong></td>
                                                        <td><?php echo $OutletAddress; ?></td>
                                                    </tr>
                                                    <tr>
                                                        <td class="pull-right"><strong>Invoice #</strong></td>
                                                        <td><?php echo $ReturnId; ?></td>
                                                    </tr>
                                                    <tr>
                                                        <td class="pull-right"><strong>Date :</strong></td>
                                                        <td><?php echo $ReturnDate; ?></td>
                                                    </tr>
                                                    
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col-md-12">
                                            <h2>Return Invoice <font style="font-size: 15px;">(<?php if($ReturnType=='1'){echo 'Damage Return';}else{echo 'Sales Return';} ?>)</font></h2>
                                            <p>Sales Rep : <?php echo $repName;?></p>
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col-md-12 well invoice-body">
                                            <table class="table table-bordered">
                                                <thead>
                                                    <tr>
                                                        <th>Product Code</th>
                                                        <th>Product Name</th>
                                                        <th>Unit Price</th>
                                                        <th>Quantity</th>
                                                        <th>Amount</th>
                                                    </tr>
                                                </thead>
                                                <tbody id="order-area">
                                                    <?php
                                                        $Productsql = "SELECT * FROM tbl_return_order_item_details toid INNER JOIN tbl_item tit ON toid.itemId=tit.itemId WHERE toid.order_id='$ReturnInvoiceID'";
                                                        $Productrs=$conn->query($Productsql);
                                                        $GrandTotal=0;
                                                        while($Prow=$Productrs->fetch_array())
                                                        {  
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
                                                            $ItemPrice=$Prow[6]; 

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
                                                            <td><?php echo $ItemCode; ?></td>
                                                            <td><?php echo $ItemName; ?></td>
                                                            <td><font class="pull-right"><?php echo number_format($ItemPrice,2); ?></font></td>
                                                            <td><font class="pull-right"><?php echo $ProductQty; ?></font></td>
                                                            <td><font class="pull-right"><?php echo number_format($ItemTotal,2); ?></font></td>
                                                        </tr>
                                            

                                                    <?php } ?>
                                            
                                                </tbody>

                                                <tfoot>
                                                    <tr><td colspan="4"></td></tr>
                                                    <tr>
                                                        <td colspan="2">&nbsp;</td>
                                                        <td colspan="2"><strong>Total</strong></td>
                                                        <td><strong class="pull-right" id="grand-total"><?php echo number_format($GrandTotal,2) ?></strong></td>
                                                    </tr>
                                                </tfoot>

                                            </table>
                                        </div>
                                    </div>


                                    <div class="row">
                                        <div class="col-md-12 well invoice-thank">
                                            <h5 style="text-align:center; font-size: 12px;">Thank You!</h5>
                                            <h6 style="text-align:center; font-size: 12px;">Powered By AMAZOFT</h6>
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

    
</body>
</html>