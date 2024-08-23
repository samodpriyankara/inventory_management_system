<?php
    session_start();
    require 'database/db.php';
    $db=new DB();
    $conn=$db->connect();
    date_default_timezone_set('Asia/Colombo');
    $currentDate=date('Y-m-d');

    $DistributorProductInvoiceId=base64_decode($_GET['d']);

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
    $getDistributorProductInvoiceDetails = "SELECT * FROM tbl_distributor_product_invoice WHERE distributor_invoice_id='$DistributorProductInvoiceId'";
    $GDPDRs=$conn->query($getDistributorProductInvoiceDetails);
    if($GDPDrow =$GDPDRs->fetch_array())
    {
        $DistributorId=$GDPDrow[1];
        $AdminId=$GDPDrow[2];
        $Note=$GDPDrow[3];
        $Stat=$GDPDrow[4];
        $Pay=$GDPDrow[5];
        $GrandTotal=$GDPDrow[6];
        $DistributorInvoiceDateTime=$GDPDrow[7];

        $getDistributorDetails = "SELECT * FROM tbl_distributor WHERE distributor_id='$DistributorId'";
        $GDDRs=$conn->query($getDistributorDetails);
        if($GDDRow =$GDDRs->fetch_array())
        {
            $DistributorName=$GDDRow[1];
            $DistributorAddress=$GDDRow[2];
            $DistributorContact=$GDDRow[3];
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
                              /*margin-top: -300px;*/
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
                                font-size:20px !important;
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
                            
                             #shop-details-area{
                                margin-top:120px;
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
                        <div class="card card-form" id="area-card">
                            <div class="row no-gutters">
                                

                                <div class="col-md-12" style="padding: 10px;">
                               
                                    <?php //if($PaymentStatus=='0'){ ?>   
                                        <!-- <div class="container" id="invoice-print"> -->
                                    <?php //}else{ ?>
                                        <!-- <div class="container" id="invoice-print" style="background-image: url(assets/img/paid_stic.png); background-repeat: no-repeat; background-position: center;"> -->
                                    <?php //}?>
                                    
                                    <div class="row" id="area-letter-head">
                                        
                                        <div class="col-md-12 text-center" id="company-details">
                                            <img src="assets/img/shop_logo.png" style="width: 30%;" class="img-rounded logo" id="area-img">
                                        </div>
                                        
                                    </div> 
                                    <div class="row">
                                        
                                        <div class="col-md-12 text-center">
                                           <b> <span style="font-size:17px" id="header-text">No:09,Moragahapitiya Estate,Balagolla,Kandy.</span></b>
                                        </div>
                                        
                                        
                                    </div>
                                    
                                    <hr>
                                    
                                    <div class="container" id="shop-details-area">
                                        
                                        <div class="row">
                                            <div class="col-md-6 well" id="outlet-details">
                                            <table class="invoice-head">
                                                <tbody>
                                                    <tr>
                                                        <td style="float: left;"><strong>Distributor Name :</strong></td>
                                                        <td><?php echo $DistributorName; ?></td>
                                                    </tr>
                                                    <tr>
                                                        <td style="float: left;"><strong>Contact Number :</strong></td>
                                                        <td><?php echo $DistributorContact; ?></td>
                                                    </tr>
                                                    <tr>
                                                        <td style="float: left;"><strong>Address :</strong></td>
                                                        <td><?php echo $DistributorAddress; ?></td>
                                                    </tr>
                                                    
                                                            
                                                </tbody>
                                            </table>
                                        </div>
                                        
                                        <div class="col-md-6 well" id="invoice-details-area">
                                            <table>
                                                <tbody>
                                                    <tr>
                                                        <td style="float: left;"><strong>Invoice #</strong></td>
                                                        <td id="area-invo-no">AMA/DIN/2022/<?php echo $DistributorProductInvoiceId+10000; ?></td>
                                                    </tr>
                                                    <tr>
                                                        <td style="float: left;" ><strong>Date :</strong></td>
                                                        <td id="area-invo-date"><?php echo $DistributorInvoiceDateTime; ?></td>
                                                    </tr>
                                                </tbody>
                                            </table>
                                        </div>
                                        
                                        </div>
                                        
                                    </div>
                                    
                                        
                                        
                                        <hr>
                                        
                                        
                                        
                                        
                                    
                                    <div class="row">
                                        <div class="col-md-12">
                                            <h2>Distributor Invoice 
                                                <?php if($Pay=='0'){ ?><font style="color: #FF0000; font-weight: 700; font-size: 15px;">(Not Paid)</font><?php }else{?> <font style="color: #26580F; font-weight: 700; font-size: 15px;">(Paid)</font><?php } ?>
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
                                                        <th style="text-align: center;">Retail Price</th>
                                                        <th style="text-align: center;">Unit Price</th>
                                                        <th style="text-align: center;">Pack</th>
                                                        <th style="text-align: center;">Quantity</th>
                                                        <th style="text-align: center;">Amount</th>
                                                    </tr>
                                                </thead>
                                                <tbody id="order-area">
                                                    <?php
                                                        $DistributorInvoiceProductsql = "SELECT * FROM tbl_distributor_product_invoice_items WHERE distributor_invoice_id='$DistributorProductInvoiceId'";
                                                        $DisInvoiceProductrs=$conn->query($DistributorInvoiceProductsql);
                                                        $GrandTotal=0;
                                                        while($DIProw=$DisInvoiceProductrs->fetch_array())
                                                        {  
                                                            $DistributorProductId=$DIProw[0];
                                                            $ItemId=$DIProw[2];
                                                            $ProductQty=$DIProw[3];
                                                            $CostPrice=$DIProw[4];

                                                            $ProductDetailsSql = "SELECT * FROM tbl_item WHERE itemId='$ItemId'";
                                                            $ProductDetailsQuery=$conn->query($ProductDetailsSql);
                                                            if($PDrow=$ProductDetailsQuery->fetch_array())
                                                            {  
                                                                $ItemCode=$PDrow[1];
                                                                $ItemName=$PDrow[2];
                                                                $RetailPrice=$PDrow[3];
                                                                $ItemDetailId=$PDrow[7]; //This is ITEM DETAIL ID as Genaric name in DB
                                                            }

                                                            $getItemsOtherPrices = "SELECT * FROM tbl_item_other_prices WHERE item_id='$ItemId'";
                                                            $GIOPRs=$conn->query($getItemsOtherPrices);
                                                            if($GIOProw =$GIOPRs->fetch_array())
                                                            {
                                                                $DistributorPrice=(double)$GIOProw[2];
                                                            }

                                                            $getItemsPackSize = "SELECT * FROM tbl_other_item_details WHERE item_id='$ItemDetailId'";
                                                            $GIPSRs=$conn->query($getItemsPackSize);
                                                            if($GIPSrow =$GIPSRs->fetch_array())
                                                            {
                                                                $PackSize=(double)$GIPSrow[1];
                                                            }
                                                    ?>

                                                    <?php
                                                        ////////Calculation//////////////
                                                        $ItemTotal = (double)$DistributorPrice*(double)$ProductQty;

                                                        //Grand Total
                                                        $GrandTotal += $ItemTotal;

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
                                                            <td><font class="pull-right"><?php echo number_format($RetailPrice,2); ?></font></td>
                                                            <td><font class="pull-right"><?php echo number_format($CostPrice,2); ?></font></td>
                                                            <td>
                                                                <font style="float: right; font-weight: 600;"><?php echo $PackView; ?></font><br>
                                                                <font style="float: right; font-size: 10px;">(1 Pack <i class="fa fa-arrow-right"></i> <?php echo $PackSize; ?> Qty)</font>
                                                            </td>
                                                            <td>
                                                                <font style="float: right; font-weight: 600;"><?php echo $QtyView; ?></font>
                                                            </td>
                                                            <td><font class="pull-right"><?php echo number_format($ItemTotal,2); ?></font></td>
                                                        </tr>



                                                    <?php } ?>
                                            
                                                </tbody>

                                                <tfoot>
                                                    <tr><td colspan="6"></td></tr>
                                                    <tr>
                                                        <td colspan="2">&nbsp;</td>
                                                        <td colspan="4"><strong>Total</strong></td>
                                                        <td><strong class="pull-right" id="grand-total"><?php echo number_format($GrandTotal,2); ?></strong></td>
                                                    </tr>
                                                </tfoot>

                                            </table>
                                        </div>
                                    </div>
                                    
                                    
                                     <div id="sign-area" style="visibility:hidden">
                        <br><br><br><br>
                
                    <div class="container">
                        
                        <div class="row">
                            <div class="col-md-3">
                                <center>
                                    <spanspan><br>
                                    <span>..............................</span><br>
                                <p>Signature</p>
                                </center>
                            </div>
                            
                            <div class="col-md-3">
                                <center>
                                    <span><?php echo date("Y-m-d");?></span><br>
                                <span>..............................</span><br>
                                <p>Date</p>
                                </center>
                            </div>
                            
                            
                        </div>
                        
                            
                        
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

                                        <!-- <button onclick="window.print();" class="btn btn-dark btn-sm pull-right" id="btn-print"><i class="fa fa-print"></i> Print</button> -->

                                        <?php if ($Stat=='0') { ?>

                                            <input type="hidden" id="distributor_invoice_id" value="<?php echo $DistributorProductInvoiceId; ?>">
                                            <button type="submit" class="btn btn-info btn-sm pull-right" id="btn-stock-sent"><i class="fa fa-floppy-o"></i> Save</button>

                                            <a href="distributor_invoice?d=<?php echo base64_encode($DistributorProductInvoiceId); ?>" class="btn btn-warning btn-sm pull-right" style="margin-right: 5px;"><i class="fa fa-arrow-left"></i> Back</a>

                                        <?php }elseif ($Stat=='1') { ?>

                                            <button onclick="window.print();" class="btn btn-dark btn-sm pull-right" id="btn-print"><i class="fa fa-print"></i> Print</button>
                                            <?php if ($Pay=='0') { ?>
                                                <form id="Payment-Complete">
                                                    <input type="hidden" name="distributor_invoice_id" value="<?php echo $DistributorProductInvoiceId; ?>">
                                                    <button type="submit" class="btn btn-info btn-sm pull-right" id="btn-print" style="margin-right: 5px;"><i class="fa fa-money"></i> Payments</button>
                                                </form>
                                            <?php }else{} ?>

                                        <?php }else{} ?>
                                        

                                        <?php //if($Pay=='1' && $Stat=='1') {}else{ ?>
                                                <!-- <button type="submit" class="btn btn-success btn-sm pull-right" data-toggle="modal" data-target="#PaymentModalCenter" id="btn-print"><i class="fa fa-money"></i> Payments</button> -->
                                        <?php //} ?> 
                                      


                                 
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
                        <input type="hidden" name="user_id" value="<?php echo $userId; ?>">
                                                        
                        <div class="form-group">
                            <label>Payment Amount</label>
                            <input type="text" class="form-control" name="payment_amount" min="0" max="100" placeholder="Payment Amount" style="text-align: right;" required>
                        </div>
                        <div class="form-group">
                            <label>Payment Method</label>
                            <select class="form-control" name="payment_method">
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


<!---------------------------Sent Stock to Distributor-------------------------------------------------->

    <script>


        $(document).ready(function(){

            $("#btn-stock-sent").click(function(){
                
                $.ajax({
            
                    beforeSend : function() {
                        $("#progress_alert").addClass('show'); 
                    },

                url:"distributor_invoice_post/sent_stock_to_outlet.php",
                type: 'POST',
                data: {
                    dis_invo_id:$("#distributor_invoice_id").val()
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

    

    <script>
        
        $(document).on('submit', '#Payment-Complete', function(e){
        e.preventDefault(); //stop default form submission
        var formData = new FormData($(this)[0]);

        $.ajax({

                beforeSend : function() {
                    $("#progress_alert").addClass('show');
                },

                url:"distributor_invoice_post/payment_complete.php",
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
                       // location.reload();
                       $( "#here" ).load(window.location.href + " #here" );
                        
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