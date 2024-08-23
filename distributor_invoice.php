<?php
    session_start();
    require 'database/db.php';
    $db=new DB();
    $conn=$db->connect();
    date_default_timezone_set('Asia/Colombo');
    $currentDate=date('Y-m-d');

    $DistributorProductInvoiceId = base64_decode($_GET['d']);

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
                                            <strong>Goal Lanka Marketing (PVT) LTD</strong><br>
                                            09, Moragahapitiya Estate,<br>
                                            Balagolla,Sri Lanka.<br>
                                            +94773 895 383 / +94812 375 383<br>
                                            globalwin20@gmail.com
                                        </address>
                                </div> 
                                <div class="col-md-4">
                                    <table class="invoice-head">
                                        <tbody>
                                            <tr>
                                                <td style="float: right;"><strong>Distributor Name :</strong></td>
                                                <td><?php echo $DistributorName; ?></td>
                                            </tr>
                                            <tr>
                                                <td style="float: right;"><strong>Contact Number :</strong></td>
                                                <td><?php echo $DistributorContact; ?></td>
                                            </tr>
                                            <tr>
                                                <td style="float: right;"><strong>Address :</strong></td>
                                                <td><?php echo $DistributorAddress; ?></td>
                                            </tr>
                                            <!-- <tr>
                                                <td style="float: right;"><strong>Invoice #</strong></td>
                                                <td><?php //echo $OrderId; ?></td>
                                            </tr> -->
                                            <tr>
                                                <td style="float: right;"><strong>Date :</strong></td>
                                                <td><?php echo $DistributorInvoiceDateTime; ?></td>
                                            </tr>
                                                    
                                        </tbody>
                                    </table>
                                </div>    
                            </div>

                            <div class="row" style="padding: 10px;">
                                <div class="col-md-12">
                                    <h2>Distributor Invoice</h2>
                                    <form id="form-add-all-products" class="pull-right">
                                        <input type="hidden" name = "dist_product_invo_id" value="<?php echo $DistributorProductInvoiceId;?>">
                                        <button type="submit" class="btn btn-warning">Add all products to distributor</button>
                                    </form>
                                </div>
                            </div>
                            
                            <br>

                            <div class="row no-gutters">
                                
                                


                                <div class="col-md-12">
                               
                                    <div class="table-view" id="table-host" style="padding: 5px;">

                                        <table class="table table-hover" id="here">
                                            <thead>
                                                <th>#</th>
                                                <th>Product Image</th>
                                                <th>Product Name</th>
                                                <th>Available Qty</th>
                                                <th>Distributor Price</th>
                                                <th>Retail Price</th>
                                                <th>Pack Wise</th>
                                                <th>Quantity Wise</th>
                                                <th>Clear</th>
                                            </thead>
                                            <tbody style="height: 500px; overflow: auto;">
                                                <?php      
                                                    $getItems = "SELECT * FROM tbl_item WHERE stock > '0'";
                                                    $GIRs=$conn->query($getItems);
                                                    $ItemCount = 0;
                                                    $GrandTotal = 0;
                                                    while($GIrow =$GIRs->fetch_array())
                                                    {
                                                        $ItemId=$GIrow[0];
                                                        $ItemCode=$GIrow[1];
                                                        $ProductName=$GIrow[2];
                                                        $RetailPrice=$GIrow[3];
                                                        $AvailableStock=$GIrow[5];
                                                        $ItemDetailId=$GIrow[7]; //This is ITEM DETAIL ID as Genaric name in DB
                                                        $ItemImg=$GIrow[17];
                                                        
                                                        

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
                                                        
                                                        $egetDistributorInvoiceItem = "SELECT * FROM tbl_distributor_product_invoice_items WHERE distributor_invoice_id='$DistributorProductInvoiceId' AND item_id='$ItemId'";
                                                        $eGDIIRs=$conn->query($egetDistributorInvoiceItem);
                                                        $eAddedQuantity = 0;
                                                        $ePackView = 0;
                                                        $eQtyView = 0;
                                                        if($eGDIIrow =$eGDIIRs->fetch_array())
                                                        {
                                                            $eAddedQuantity=(double)$eGDIIrow[3];

                                                            $ePacks= $eAddedQuantity/$PackSize;

                                                            $ePacksExplode = explode(".",$ePacks);
                                                            $ePackView = (double)$ePacksExplode[0];
                                                            // $QtyView = (double)$PacksExplode[1];
                                                            $eQtyView = $eAddedQuantity - ($ePackView * $PackSize);
                                                            
                                                            

                                                        }
                                                        $av_qty = ($AvailableStock - $eAddedQuantity);
                                                        
                                                        
                                                ?>
                                                <tr>
                                                    <td><?php echo $ItemCount += 1; ?></td>
                                                    <td><img src="<?php echo $ItemImg; ?>" width=50 height=50/></td>
                                                    <td><?php echo $ProductName; ?></td>
                                                    <!--<td><span id="change-av-stock"><?php echo ($AvailableStock-$eQtyView); ?></span></td>-->
                                                    <td><span id="change-av-stock"><?php echo $av_qty; ?></span></td>
                                                    <td>LKR <?php echo number_format($DistributorPrice,2); ?></td>
                                                    <td>LKR <?php echo number_format($RetailPrice,2); ?></td>

                                                   <?php
                                                        $getDistributorInvoiceItem = "SELECT * FROM tbl_distributor_product_invoice_items WHERE distributor_invoice_id='$DistributorProductInvoiceId' AND item_id='$ItemId'";
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
                                                            <input type="hidden" name="distributor_id" value="<?php echo $DistributorId; ?>" required readonly>
                                                            <input type="hidden" name="cost_price" value="<?php echo $DistributorPrice; ?>" required readonly>
                                                            <input type="hidden" name="distributor_invoice_id" value="<?php echo $DistributorProductInvoiceId; ?>" required readonly>
                                                            <input type="hidden" name="item_detail_id" value="<?php echo $ItemDetailId; ?>" required readonly>
                                                            <input type="number" name="pack" min="1" class="form-control" style="width:80px;text-align:right;font-size: 15px;border-radius:0px;" />
                                                            <input type="text" value="<?php echo $PackView; ?>" class="form-control" style="width:80px;text-align:center;color:red;font-size: 15px;border-radius:0px;" readonly disabled/>
                                                            <button class="btn btn-dark btn-sm" style="width:80px;text-align:center;border-radius:0px">ADD</button>
                                                        </form> 
                                                    </td>
                                                    <td>
                                                        <form id="Add-Quantity">
                                                            <input type="hidden" name="item_id" value="<?php echo $ItemId; ?>" required readonly>
                                                            <input type="hidden" name="distributor_id" value="<?php echo $DistributorId; ?>" required readonly>
                                                            <input type="hidden" name="cost_price" value="<?php echo $DistributorPrice; ?>" required readonly>
                                                            <input type="hidden" name="added_qty" value="<?php echo $eQtyView; ?>" required readonly>
                                                            <input type="hidden" name="distributor_invoice_id" value="<?php echo $DistributorProductInvoiceId; ?>" required readonly>
                                                            <input type="number" name="qty" min="1" class="form-control" style="width:80px;text-align:right;font-size: 15px;border-radius:0px;" />
                                                            <input type="text" value="<?php echo $QtyView; ?>" class="form-control" style="width:80px;text-align:center;color:red;font-size: 15px;border-radius:0px;" readonly disabled/>
                                                            <button class="btn btn-dark btn-sm" style="width:80px;text-align:center;border-radius:0px">ADD</button>
                                                        </form>
                                                    </td>


                                                    <td>
                                                        <form id="Remove-Qty">
                                                            <input type="hidden" name="item_id" value="<?php echo $ItemId; ?>" required readonly>
                                                            <input type="hidden" name="distributor_invoice_id" value="<?php echo $DistributorProductInvoiceId; ?>" required readonly>
                                                            <button class="btn btn-danger btn-sm">Clear</button>
                                                        </form>
                                                    </td>
                                                </tr>

                                                <?php } ?>
                                            </tbody>
                                        </table>
                                        
                                    </div>  
                                    
                                    <!-- <h4 class="pull-right" id="lbl_invoice_total" style="padding: 10px; font-weight: 600;font-size: 30px;color: red;margin-right: 50px; float: right;">LKR <?php //echo number_format($GrandTotal,2); ?></h4><br> -->
                                        
                                  </div>
                               
                                <div class="col-md-12">
                                    <br>
                                    <a href="dis_invoice?d=<?php echo base64_encode($DistributorProductInvoiceId); ?>" class="btn btn-secondary" style="padding: 10px; float: right; margin-right: 50px;"><i class="fa fa-eye"></i> View Issue Invoice</a>
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
    
        var stock_table = null;

        $(document).ready(function(){
            stock_table = $('#here').DataTable({
                stateSave : true,
                destroy: true,
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

                url:"distributor_invoice_post/add_pack.php",
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
                            destroy: true,
                            stateSave : true,  
                            pageLength : 5,
                            lengthMenu: [[5, 10, 20, -1], [5, 10, 20, 'All']]
                          });}, 1000);
                        location.reload();
                       //$( "#table-host" ).load(window.location.href + " #table-host" );
                        
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

                url:"distributor_invoice_post/add_quantity.php",
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
                       
                       
                       stock_table.destroy();
                       
                       
                          stock_table = $('#here').DataTable({
                            stateSave : true,  
                            destroy: true,
                            pageLength : 5,
                            lengthMenu: [[5, 10, 20, -1], [5, 10, 20, 'All']]
                          });}, 1000);
                          
                         
                          
                          
                          
                        location.reload();
                       //$( "#table-host" ).load(window.location.href + " #table-host" );
                        
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

                url:"distributor_invoice_post/remove_quantity.php",
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
                            destroy: true,
                            stateSave : true,  
                            pageLength : 5,
                            lengthMenu: [[5, 10, 20, -1], [5, 10, 20, 'All']]
                          });}, 1000);
                        location.reload();
                       //$( "#table-host" ).load(window.location.href + " #table-host" );
                        
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
        
        $("#form-add-all-products").submit(function(e){
            e.preventDefault();
            
            var formData = new FormData(e.target);

            $.ajax({

                beforeSend : function() {
                    $("#progress_alert").addClass('show');
                },

                url:"distributor_invoice_post/add_all_stock_to_distributor.php",
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
                        
                        location.reload();
                        
                    //   $("#success_msg").html(json.msg);
                    //   $("#success_alert").addClass('show');
                    //   setTimeout(function(){$("#success_alert").removeClass('show');  
                    //   stock_table = $('#here').DataTable({
                    //         destroy: true,
                    //         stateSave : true,  
                    //         pageLength : 5,
                    //         lengthMenu: [[5, 10, 20, -1], [5, 10, 20, 'All']]
                    //       });}, 1000);
                    //     location.reload();
                    //   //$( "#table-host" ).load(window.location.href + " #table-host" );
                        
                    }else{
                        $("#error_msg").html(json.msg);
                        $("#danger_alert_msg").addClass('show');
                        setTimeout(function(){ $("#danger_alert_msg").removeClass('show'); }, 1000);
                    }
                    
                }



            });
            
            
            
            
            
        });
        
        
    </script>

</body>
</html>