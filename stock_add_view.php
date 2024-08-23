<?php
    session_start();
    require 'database/db.php';
    $db=new DB();
    $conn=$db->connect();
    date_default_timezone_set('Asia/Colombo');
    $currentDate=date('Y-m-d');

    $StockAddId=base64_decode($_GET['s']);

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
    $GetStockAddDetailsSql = "SELECT * FROM tbl_stock_add_details WHERE stock_add_id='$StockAddId'";
    $GSADrs=$conn->query($GetStockAddDetailsSql);
    if($GSADrow =$GSADrs->fetch_array())
    {
        $AdminId=$GSADrow[1];
        $SupplierId=$GSADrow[2];
        $Note=$GSADrow[3];
        $Stat=$GSADrow[4];
        $StockDetaileDateTime=$GSADrow[5];

        $GetSupplierDetailsSql = "SELECT * FROM tbl_supplier ts INNER JOIN tbl_supplier_details tsd ON ts.supplier_id=tsd.supplier_id WHERE ts.supplier_id='$SupplierId'";
        $GSDrs=$conn->query($GetSupplierDetailsSql);
        if($GSDrow =$GSDrs->fetch_array())
        {
            $SupplierName=$GSDrow[1];
            $SupplierNIC=$GSDrow[6];
            $SupplierBR=$GSDrow[7];
            $SupplierContactPersonName=$GSDrow[8];
            $SupplierContactNumber=$GSDrow[9];
            $SupplierAddress=$GSDrow[10];
            $SupplierRegDate=$GSDrow[11];
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


                    <div class="container-fluid page__container" id="here">
                        <div class="card card-form">
                            <div class="row no-gutters">
                                

                                <div class="col-md-12" style="padding: 10px;">
                               
                                    <?php //if($PaymentStatus=='0'){ ?>   
                                        <!-- <div class="container" id="invoice-print"> -->
                                    <?php //}else{ ?>
                                        <!-- <div class="container" id="invoice-print" style="background-image: url(assets/img/paid_stic.png); background-repeat: no-repeat; background-position: center;"> -->
                                    <?php //}?>
                                    
                                    <div class="row">
                                        <div class="col-md-8" id="company-details">
                                            <img src="assets/img/alogo.png" style="width: 30%;" class="img-rounded logo">
                                            <address>
                                                <strong>Amazoft Pvt. Ltd.</strong><br>
                                                No: 103<br>
                                                St. Anthonys lane, Colombo 00300<br>
                                                +94-77 575 7377
                                            </address>
                                        </div>
                                        <div class="col-md-4 well" id="outlet-details">
                                            <table class="invoice-head">
                                                <tbody>
                                                    <tr>
                                                        <td style="float: right;"><strong>Supplier Name :</strong></td>
                                                        <td><?php echo $SupplierName; ?></td>
                                                    </tr>
                                                    <tr>
                                                        <td style="float: right;"><strong>Contact Number :</strong></td>
                                                        <td><?php echo $SupplierContactNumber; ?></td>
                                                    </tr>
                                                    <tr>
                                                        <td style="float: right;"><strong>Address :</strong></td>
                                                        <td><?php echo $SupplierAddress; ?></td>
                                                    </tr>
                                                    <!-- <tr>
                                                        <td style="float: right;"><strong>Invoice #</strong></td>
                                                        <td><?php //echo $OrderId; ?></td>
                                                    </tr> -->
                                                    <tr>
                                                        <td style="float: right;"><strong>Date :</strong></td>
                                                        <td><?php echo $StockDetaileDateTime; ?></td>
                                                    </tr>
                                                            
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-12">
                                            <h2>Add Stock
                                                <!-- <?php if($Pay=='0'){ ?><font style="color: #FF0000; font-weight: 700; font-size: 15px;">(Not Paid)</font><?php }else{?> <font style="color: #26580F; font-weight: 700; font-size: 15px;">(Paid)</font><?php } ?> -->
                                            </h2>

                                        
                                            <!-- <p>Sales Rep : <?php echo $repName;?></p> -->

                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col-md-12 well invoice-body">
                                            <table class="table table-bordered">
                                                <thead>
                                                    <tr>
                                                        <th style="text-align: center;">Product Code</th>
                                                        <th style="text-align: center;">Product Name</th>
                                                        <!-- <th style="text-align: center;">Unit Price (Selling))</th> -->
                                                        <th style="text-align: center;">Pack</th>
                                                        <th style="text-align: center;">Quantity</th>
                                                        <th style="text-align: center;">Total Quantity</th>
                                                        <!-- <th style="text-align: center;">Amount</th> -->
                                                    </tr>
                                                </thead>
                                                <tbody id="order-area">
                                                    <?php
                                                        $StockProductsql = "SELECT * FROM tbl_stock_add_items WHERE stock_add_id='$StockAddId'";
                                                        $StockAddProductrs=$conn->query($StockProductsql);
                                                        while($SProw=$StockAddProductrs->fetch_array())
                                                        {  
                                                            $StockAddItemId=$SProw[0];
                                                            $ItemId=$SProw[2];
                                                            $ProductQty=$SProw[3];

                                                            $ProductDetailsSql = "SELECT * FROM tbl_item WHERE itemId='$ItemId'";
                                                            $ProductDetailsQuery=$conn->query($ProductDetailsSql);
                                                            if($PDrow=$ProductDetailsQuery->fetch_array())
                                                            {  
                                                                $ItemCode=$PDrow[1];
                                                                $ItemName=$PDrow[2];
                                                                $ItemDetailId=$PDrow[7]; //This is ITEM DETAIL ID as Genaric name in DB
                                                            }

                                                            $getItemsPackSize = "SELECT * FROM tbl_other_item_details WHERE item_id='$ItemDetailId'";
                                                            $GIPSRs=$conn->query($getItemsPackSize);
                                                            if($GIPSrow =$GIPSRs->fetch_array())
                                                            {
                                                                $PackSize=(double)$GIPSrow[1];
                                                            }
                                                    ?>

                                                    <?php
                                                        //Pack Cal
                                                        $Packs= $ProductQty/$PackSize;

                                                        $PacksExplode = explode(".",$Packs);
                                                        $PackView = (double)$PacksExplode[0];

                                                        $QtyView = $ProductQty - ($PackView * $PackSize);
                                                        ////////Calculation//////////////
                                                    ?>
                                                    
                                                        <tr>
                                                            <td><?php echo $ItemCode; ?></td>
                                                            <td><?php echo $ItemName; ?></td>
                                                            <!-- <td><font class="pull-right"><?php //echo number_format($CostPrice,2); ?></font></td> -->
                                                            <td>
                                                                <font style="float: right; font-weight: 600;"><?php echo $PackView; ?></font><br>
                                                                <font style="float: right; font-size: 10px;">(1 Pack <i class="fa fa-arrow-right"></i> <?php echo $PackSize; ?> Qty)</font>
                                                            </td>
                                                            <td>
                                                                <font style="float: right; font-weight: 600;"><?php echo $QtyView; ?></font>
                                                            </td>
                                                            <td>
                                                                <font style="float: right; font-weight: 600;"><?php echo $ProductQty; ?></font>
                                                            </td>
                                                            <!-- <td><font class="pull-right"><?php //echo number_format($ItemTotal,2); ?></font></td> -->
                                                        </tr>



                                                    <?php } ?>
                                            
                                                </tbody>

                                                <tfoot style="display: none;">
                                                    <tr><td colspan="6"></td></tr>
                                                    <tr>
                                                        <td colspan="2">&nbsp;</td>
                                                        <td colspan="3"><strong>Total</strong></td>
                                                        <!-- <td><strong class="pull-right" id="grand-total"><?php //echo number_format($GrandTotal,2); ?></strong></td> -->
                                                    </tr>
                                                </tfoot>

                                            </table>
                                        </div>
                                    </div>


                                    <div class="row">
                                        <div class="col-md-12 well invoice-thank">
                                            <h5 style="text-align:center;">Thank You!</h5>
                                            <h6 style="text-align:center;">Powered By AMAZOFT</h6>
                                        </div>
                                    </div><br>
                                    <div class="row" id="print-footer">
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

                                        <!-- <button onclick="window.print();" class="btn btn-dark btn-sm pull-right" id="btn-print"><i class="fa fa-print"></i> Print</button> -->

                                        <?php if ($Stat=='0') { ?>

                                            <input type="hidden" id="stock_add_id" value="<?php echo $StockAddId; ?>">
                                            <button type="submit" class="btn btn-info pull-right" id="btn-stock-sent"  data-toggle="tooltip" data-placement="bottom" title="This action can't be reverted"><i class="fa fa-floppy-o"></i> Add to main stock</button>

                                            <a href="stock_add_form?s=<?php echo base64_encode($StockAddId); ?>" class="btn btn-warning pull-right" style="margin-right: 5px;"><i class="fa fa-arrow-left"></i> Back</a>

                                        <?php }elseif ($Stat=='1') { ?>
                                            <button onclick="window.print();" class="btn btn-dark pull-right" id="btn-print"><i class="fa fa-print"></i> Print</button>

                                        <?php }else{} ?>
                                      


                                 
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
        $(function () {
            $('[data-toggle="tooltip"]').tooltip()
        })
    </script>


<!---------------------------Sent Stock to Main Stock-------------------------------------------------->

    <script>


        $(document).ready(function(){

            $("#btn-stock-sent").click(function(){
                
                $.ajax({
            
                    beforeSend : function() {
                        $("#progress_alert").addClass('show'); 
                    },

                url:"stock_post/sent_stock_to_main_stock.php",
                type: 'POST',
                data: {
                    stock_add_id:$("#stock_add_id").val()
                },
                //async: false,
                
                success: function (data) {
                    
                    $("#progress_alert").removeClass('show');
                    
                    var json=JSON.parse(data);
                    
                    if(json.result){
                       $("#success_msg").html(json.msg);
                       $("#success_alert").addClass('show'); 
                       setTimeout(function(){$("#success_alert").removeClass('show');  }, 1000);
                       window.location.reload();
                       // $( "#here" ).load(window.location.href + " #here" );
                       
                        
                    }else{
                        $("#error_msg").html(json.msg);
                        $("#danger_alert_msg").addClass('show');
                        setTimeout(function(){ $("#danger_alert_msg").removeClass('show'); }, 1000);
                    }
                    
                }

            });


            });

        });

 
    </script>


</body>
</html>