<?php
    session_start();
    require 'database/db.php';
    $db=new DB();
    $conn=$db->connect();
    date_default_timezone_set('Asia/Colombo');
    $currentDate=date('Y-m-d');

    $StockAddId= base64_decode($_GET['s']);

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
                            <div class="row" style="padding: 10px;">
                                <div class="col-md-8">
                                    <img src="assets/img/shop_logo.png" style="width: 30%;" class="img-rounded logo">
                                        <address>
                                            <strong>Lasantha Distributores</strong><br>
                                            No: 145<br>
                                            Gawarawela, Demodara<br>
                                            +94-77 98 888 98<br>
                                            lasanthajayaweera164@gmail.com
                                        </address>
                                </div> 
                                <div class="col-md-4">
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

                            <div class="row" style="padding: 10px;">
                                <div class="col-md-12">
                                    <h2>Add Stock</h2>
                                </div>
                            </div>

                            <div class="row no-gutters">
                                
                                
                                <div class="col-md-12">
                               
                                    <div class="table-view" id="table-host" style="padding: 5px;">

                                        <table class="table table-hover" id="here">
                                            <thead>
                                                <th>#</th>
                                                <th>Product Image</th>
                                                <th>Product Name</th>
                                                <th>PB Code</th>
                                                <th>Unit Price (Selling)</th>
                                                <th>Pack Wise</th>
                                                <th>Quantity Wise</th>
                                                <th>Clear</th>
                                            </thead>
                                            <tbody style="height: 500px; overflow: auto;">
                                                <?php
                                                    $getSupplierHasProductsDetails = "SELECT * FROM tbl_supplier_has_products WHERE supplier_id='$SupplierId'";
                                                    $GSHPDRs=$conn->query($getSupplierHasProductsDetails);
                                                    $ItemCount = 0;
                                                    while($GSHPDrow =$GSHPDRs->fetch_array())
                                                    {
                                                        $ItemDetailId=$GSHPDrow[2]; //This is ITEM DETAIL ID as Genaric name in DB

                                                        $getItemDetails = "SELECT * FROM tbl_item_details WHERE item_detail_Id='$ItemDetailId'";
                                                        $GIDRs=$conn->query($getItemDetails);
                                                        if($GIDrow =$GIDRs->fetch_array())
                                                        {
                                                            // $ItemDetailId=$GIDrow[0]; //This is ITEM DETAIL ID as Genaric name in DB
                                                            $ItemCode=$GIDrow[1];
                                                            $ProductName=$GIDrow[2];
                                                            $ItemImg=$GIDrow[14];

                                                                $getItems = "SELECT * FROM tbl_item WHERE genaricName='$ItemDetailId' ORDER BY itemId DESC LIMIT 1";
                                                                $GIRs=$conn->query($getItems);
                                                                $ItemSellingPrice = 0;
                                                                $GrandTotal = 0;
                                                                $ItemId = '';
                                                                if($GIrow =$GIRs->fetch_array())
                                                                {
                                                                    $ItemId=$GIrow[0];
                                                                    $ItemSellingPrice=$GIrow[3];
                                                                    $AvailableStock=$GIrow[5];
                                                                    // $ItemDetailId=$GIrow[7]; //This is ITEM DETAIL ID as Genaric name in DB
                                                                    
                                                                    $getItemsOtherPrices = "SELECT * FROM tbl_item_other_prices WHERE item_id='$ItemId'";
                                                                    $GIOPRs=$conn->query($getItemsOtherPrices);
                                                                    if($GIOProw =$GIOPRs->fetch_array())
                                                                    {
                                                                        $DistributorPrice=(double)$GIOProw[2];
                                                                        $PBCode=$GIOProw[5];
                                                                    }

                                                                    $getItemsPackSize = "SELECT * FROM tbl_other_item_details WHERE item_id='$ItemDetailId'";
                                                                    $GIPSRs=$conn->query($getItemsPackSize);
                                                                    if($GIPSrow =$GIPSRs->fetch_array())
                                                                    {
                                                                        $PackSize=(double)$GIPSrow[1];
                                                                    }
                                                                }
                                                        }
                                                ?>
	                                                <?php if($ItemSellingPrice>0){ ?>
	                                                <tr>
	                                                    <td><?php echo $ItemCount += 1;; ?></td>
	                                                    <td><img src="<?php echo $ItemImg; ?>" width=50 height=50/></td>
	                                                    <td><?php echo $ProductName; ?></td>
	                                                    <td><?php echo $PBCode; ?></td>
	                                                    <td>LKR <?php echo number_format($ItemSellingPrice,2); ?></td>

	                                                   <?php
	                                                        $getDistributorInvoiceItem = "SELECT * FROM tbl_stock_add_items WHERE stock_add_id='$StockAddId' AND item_id='$ItemId'";
	                                                        $GDIIRs=$conn->query($getDistributorInvoiceItem);
	                                                        $AddedQuantity = 0;
	                                                        $PackView = 0;
	                                                        $QtyView = 0;
	                                                        if($GDIIrow =$GDIIRs->fetch_array())
	                                                        {
	                                                            $AddedQuantity=(double)$GDIIrow[3];

	                                                            $Packs= $AddedQuantity/$PackSize;

	                                                            $PacksExplode = explode(".",$Packs);
	                                                            $PackView = (double)$PacksExplode[0];
	                                                            // $QtyView = (double)$PacksExplode[1];
	                                                            $QtyView = $AddedQuantity - ($PackView * $PackSize);

	                                                            $ItemTotal = (double)$DistributorPrice * (double)$AddedQuantity;
	                                                            $GrandTotal += (double)$ItemTotal;
	                                                        }
	                                                    ?>  
	                                                    <td>
	                                                        <form id="Add-Packs">
	                                                            <input type="hidden" name="item_id" value="<?php echo $ItemId; ?>" required readonly>
	                                                            <input type="hidden" name="stock_add_id" value="<?php echo $StockAddId; ?>" required readonly>
	                                                            <input type="hidden" name="item_detail_id" value="<?php echo $ItemDetailId; ?>" required readonly>
	                                                            <input type="number" name="pack" min="1" class="form-control" style="width:80px;text-align:right;font-size: 15px;border-radius:0px;" />
	                                                            <input type="text" value="<?php echo $PackView; ?>" class="form-control" style="width:80px;text-align:center;color:red;font-size: 15px;border-radius:0px;" readonly disabled/>
	                                                            <button class="btn btn-dark btn-sm" style="width:80px;text-align:center;border-radius:0px">ADD</button>
	                                                        </form> 
	                                                    </td>
	                                                    <td>
	                                                        <form id="Add-Quantity">
	                                                            <input type="hidden" name="item_id" value="<?php echo $ItemId; ?>" required readonly>
	                                                            <input type="hidden" name="stock_add_id" value="<?php echo $StockAddId; ?>" required readonly>
	                                                            <input type="number" name="qty" min="1" class="form-control" style="width:80px;text-align:right;font-size: 15px;border-radius:0px;" />
	                                                            <input type="text" value="<?php echo $QtyView; ?>" class="form-control" style="width:80px;text-align:center;color:red;font-size: 15px;border-radius:0px;" readonly disabled/>
	                                                            <button class="btn btn-dark btn-sm" style="width:80px;text-align:center;border-radius:0px">ADD</button>
	                                                        </form>
	                                                    </td>


	                                                    <td>
	                                                        <form id="Remove-Qty">
	                                                            <input type="hidden" name="item_id" value="<?php echo $ItemId; ?>" required readonly>
	                                                            <input type="hidden" name="stock_add_id" value="<?php echo $StockAddId; ?>" required readonly>
	                                                            <button class="btn btn-danger btn-sm">Clear</button>
	                                                        </form>
	                                                    </td>
	                                                </tr>
	                                            	<?php } ?>
                                                <?php } ?>
                                            </tbody>
                                        </table>
                                        
                                    </div>  
                                    
                                    <!-- <h4 class="pull-right" id="lbl_invoice_total" style="padding: 10px; font-weight: 600;font-size: 30px;color: red;margin-right: 50px; float: right;">LKR <?php //echo number_format($GrandTotal,2); ?></h4><br> -->
                                        
                                  </div>
                               
                                <div class="col-md-12">
                                    <br>
                                    <a href="stock_add_view?s=<?php echo base64_encode($StockAddId); ?>" class="btn btn-secondary" style="padding: 10px; float: right; margin-right: 50px;"><i class="fa fa-eye"></i> Verify Stock</a>
                                </div>

                                <br><br><br><br>
                                
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

    	var stock_table = null;

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



    	$(document).ready(function(){
			stock_table = $('#here').DataTable({
			    pageLength : 5,
			    lengthMenu: [[5, 10, 20, -1], [5, 10, 20, 'All']]
			  });
		});

        
        $(document).on('submit', '#Add-Packs', function(e){
        e.preventDefault(); //stop default form submission
        var formData = new FormData($(this)[0]);

        $.ajax({

                beforeSend : function() {
                    $("#progress_alert").addClass('show');
                },

                url:"stock_post/add_pack.php",
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
                       setTimeout(function(){$("#success_alert").removeClass('show');  
                   	   stock_table = $('#here').DataTable({
						    pageLength : 5,
						    lengthMenu: [[5, 10, 20, -1], [5, 10, 20, 'All']]
						  });}, 1000);
                       // location.reload();
                       $( "#table-host" ).load(window.location.href + " #table-host" );
                        
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

    <script>
        
        $(document).on('submit', '#Add-Quantity', function(e){
        e.preventDefault(); //stop default form submission
        var formData = new FormData($(this)[0]);


        	



        $.ajax({

                beforeSend : function() {
                    $("#progress_alert").addClass('show');
                },

                url:"stock_post/add_quantity.php",
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
                       setTimeout(function(){$("#success_alert").removeClass('show');

                       	stock_table = $('#here').DataTable({
						    pageLength : 5,
						    lengthMenu: [[5, 10, 20, -1], [5, 10, 20, 'All']]
						  });  }, 1000);
                       // location.reload();

                       
                       $( "#table-host" ).load(window.location.href + " #table-host" );

                       

                        
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

    <script>
        
        $(document).on('submit', '#Remove-Qty', function(e){
        e.preventDefault(); //stop default form submission
        var formData = new FormData($(this)[0]);

        $.ajax({

                beforeSend : function() {
                    $("#progress_alert").addClass('show');
                },

                url:"stock_post/remove_quantity.php",
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
                       setTimeout(function(){$("#success_alert").removeClass('show');  

                   	   stock_table = $('#here').DataTable({
						    pageLength : 5,
						    lengthMenu: [[5, 10, 20, -1], [5, 10, 20, 'All']]
						  });}, 1000);
                       // location.reload();
                       $( "#table-host" ).load(window.location.href + " #table-host" );
                        
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