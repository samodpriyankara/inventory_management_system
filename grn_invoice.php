<?php
    session_start();
    require 'database/db.php';
    $db=new DB();
    $conn=$db->connect();
    date_default_timezone_set('Asia/Colombo');
    $currentDate=date('Y-m-d');

    $GRNDetailId= base64_decode($_GET['g']);
    $ItemCount=0;
    $GRNInvoiceCost=0;

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
    $getDataQuery=$conn->query("SELECT * FROM tbl_grn_details tgd INNER JOIN tbl_supplier tsu ON tgd.supplier_id=tsu.supplier_id WHERE tgd.grn_detail_id = '$GRNDetailId' ");
    if ($GRNrs=$getDataQuery->fetch_array()) {

        $SupplierId=$GRNrs[1];
        $InvoiceNumber=$GRNrs[2];
        $GRNNumber=$GRNrs[3];
        $GoodsReceivedDate=$GRNrs[4];
        $Note=$GRNrs[5];
        $Stat=$GRNrs[6];
        $GRNDateTime=$GRNrs[7];

        /////////////////////////////////

        $SupplierName=$GRNrs[9];
    }
?>
<!DOCTYPE html>
<html lang="en" dir="ltr">

<?php include_once('controls/meta.php'); ?>

<body class="layout-default" onload="startTime()">

    <style>
        .select2-container .select2-selection--single{
            height: 35px !important;
        }
        .select2-container--default .select2-selection--single .select2-selection__rendered {
            line-height: 35px !important;
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
                            <div class="card-header">
                                <h5 class="card-title"><?php echo $SupplierName; ?> | GRN Date - <?php echo $GRNDateTime; ?></h5>
                            </div>
                            <div class="row no-gutters">
                                
                                <div class="col-md-12" style="padding: 10px;">
                                    
                                    <div class="row">
                                        <div class="col-md-4">
                                            <form class="row g-3" id="Add-GRN-Bill-Item">
                                                <input type="hidden" name="grn_detail_id" value="<?php echo $GRNDetailId;?>" required readonly>
                                                <div class="col-md-12">
                                                    <label class="form-label">Select Item <font style="color: #FF0000;">*</font></label>
                                                    <select class="js-example-basic-single form-control" name="item_id" id="select-item" style="padding: 0.375rem 0.75rem !important;" onchange="onItemChanged()" required>
                                                        <option value="" selected disabled>Select Item</option>
                                                            <?php
                                                                $itemsQuery=$conn->query("SELECT DISTINCT item_detail_Id,   itemDescription,itemCode FROM tbl_item_details");
                                                                while ($row=$itemsQuery->fetch_array()) {
                                                            ?>
                                                                <option value="<?php echo $row[0];?>"><?php echo $row[1];?> (<?php echo $row[2];?>)</option>
                                                            <?php } ?>
                                                    </select>
                                                </div>

                                                <div class="col-md-9">
                                                    <label class="form-label">Select Price Batch <font style="color: #FF0000;">*</font></label>
                                                    <select style="width: 100% !important;" class="form-control" id="price_batch_id" name="price_batch_id" onchange="onPriceBatchChanged()" required>
                                                        <option value="" selected disabled>Select Price Batch</option>
                                                    </select>
                                                </div>

                                                <div class="col-md-3" style="margin-top: 15px;">
                                                    <label class="form-label" style="color: #FFF;">1</label>
                                                    <button type="button" id="btn-add-new-price-batch" class="btn btn-primary" aria-hidden="true" data-toggle="tooltip" data-placement="bottom" title="Add new price batch here"><i class="fa fa-plus"></i></button>
                                                </div>

                                                <div class="col-md-12">
                                                    <label class="form-label">Cost Price (.Rs) <font style="color: #FF0000;">*</font></label>
                                                    <input type="number" name="cost_price" id="cost-input" step="any" class="form-control" placeholder="Cost Price" required readonly>
                                                </div>

                                                <div class="col-md-12">
                                                    <label class="form-label">Selling Price (.Rs) <font style="color: #FF0000;">*</font></label>
                                                    <input type="number" name="selling_price" id="selling-input" step="any" class="form-control" placeholder="Selling Price" required readonly>
                                                </div>


                                                <div class="col-md-12">
                                                    <label class="form-label">Quantity <font style="color: #FF0000;">*</font></label>
                                                    <input type="number" class="form-control" min="1" name="qty" step="any" aria-describedby="basic-addon2" placeholder="Quantity" style="text-align: right;" id="stock-input" required>
                                                        <!-- <span class="input-group-text" id="basic-addon2">kg</span> -->
                                                    <span style="color: #FF0000; font-size: 12px;">Don't put Unit Type</span>
                                                </div>

                                                <div class="col-12">
                                                    <button type="submit" id="btn-add-grn-bill-item" class="btn btn-primary">Add</button>
                                                </div>
                                            </form>
                                                        
                                        </div>

                                        <div class="col-md-8">
                                            <div class="row">
                                                <div class="col-md-6">
                                                    <p style="text-align: left;">
                                                        <b>GRN Number - <?php echo $GRNNumber; ?></b><br>
                                                        <b>Invoice Number - <?php echo $InvoiceNumber; ?></b><br>
                                                        <b>GR Date - <?php echo $GoodsReceivedDate; ?></b><br>
                                                    </p>
                                                </div>
                                                <div class="col-md-6">
                                                    <p style="text-align: right;">
                                                        <b>Supplier Details</b><br>
                                                        <?php echo $SupplierName; ?>
                                                    </p>
                                                </div>
                                            </div>

                                            <?php      
                                                $getGRNInvoiceCost = "SELECT * FROM tbl_grn_items WHERE grn_detail_id='$GRNDetailId'";
                                                $gGRNiRs=$conn->query($getGRNInvoiceCost);
                                                $ResultCount = 0;
                                                while($gGRNirow =$gGRNiRs->fetch_array())
                                                {
                                                    $ResultCount += 1;
                                                    //////
                                                    $GCostPrice=(double)$gGRNirow[4];
                                                    $GGRNQTY=(double)$gGRNirow[6];
                                                    $GGRNItemCost = $GCostPrice * $GGRNQTY;
                                                    //////
                                                    $GRNInvoiceCost+=$GGRNItemCost;
                                                }
                                            ?>
                                                        
                                            <div class="row">
                                                <div class="col-md-6">
                                                    <div class="card widget widget-info-navigation" style="background-color: #e7ecf8; border-radius: 5%;">
                                                        <div class="card-body">
                                                            <div class="widget-info-navigation-container">
                                                                <div class="row">
                                                                    <div class="col-md-9">
                                                                        <div class="widget-info-navigation-content">
                                                                            <span class="text-dark">Item Count</span><br><br>
                                                                            <span class="text-danger fw-bolder fs-2" id="grn-item-count" style="color: #ff4857!important; font-size: 30px; font-weight: 700;"><?php echo $ResultCount; ?></span>
                                                                        </div>
                                                                    </div>
                                                                    <div class="col-md-3">
                                                                        <div class="widget-info-navigation-action">
                                                                            <a href="#!" class="btn btn-light btn-rounded" style="border-radius: 18px;"><i class="fa fa-dot-circle-o"></i></a>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="card widget widget-info-navigation" style="background-color: #e7ecf8; border-radius: 5%;">
                                                        <div class="card-body">
                                                            <div class="widget-info-navigation-container">
                                                                <div class="row">
                                                                    <div class="col-md-9">
                                                                        <div class="widget-info-navigation-content">
                                                                            <span class="text-dark">Total Cost</span><br><br>
                                                                                <span class="text-danger fw-bolder fs-2" id="grn-total-cost" style="color: #ff4857!important; font-size: 30px; font-weight: 700;"><?php echo number_format($GRNInvoiceCost,2); ?></span>
                                                                        </div>
                                                                    </div>
                                                                    <div class="col-md-3">
                                                                        <div class="widget-info-navigation-action">
                                                                            <a href="#!" class="btn btn-light btn-rounded" style="border-radius: 18px;"><i class="fa fa-money"></i></a>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                                        

                                            <div class="example-content">
                                                <table class="table table-hover">
                                                    <thead>
                                                        <tr>
                                                            <th scope="col">#</th>
                                                            <th scope="col">Item</th>
                                                            <th scope="col"><font style="float: right;">Qty</font></th>
                                                            <th scope="col"><font style="float: right;">Cost (Rs)</font></th>
                                                            <th></th>
                                                        </tr>
                                                    </thead>
                                                    <tbody id="grn-item-area">
                                                    <?php
                                                        // $getGRNItems = "SELECT * FROM tbl_grn_items tgi INNER JOIN tbl_item tit ON tgi.item_detail_id=tit.itemId WHERE tgi.grn_detail_id='$GRNDetailId' ORDER BY tgi.grn_items_id ASC";
                                                        $getGRNItems = "SELECT * FROM tbl_grn_items tgi INNER JOIN tbl_item tit ON tgi.price_batch_id=tit.itemId WHERE tgi.grn_detail_id='$GRNDetailId' ORDER BY tgi.grn_items_id ASC";
                                                       
                                                       
                                                        $gGRNiRs=$conn->query($getGRNItems);
                                                        while($gGRNiRsrow =$gGRNiRs->fetch_array())
                                                        {
                                                            $GRNItemItemId=$gGRNiRsrow[0];
                                                            $ItemDetailId=$gGRNiRsrow[2];
                                                            $PriceBatchId=$gGRNiRsrow[3];
                                                            $CostPrice=(double)$gGRNiRsrow[4];
                                                            $SellingPrice=$gGRNiRsrow[5];
                                                            $GRNQTY=(double)$gGRNiRsrow[6];
                                                            $GRNItemStat=$gGRNiRsrow[7];
                                                            ////////////////////////////
                                                            $ItemName=$gGRNiRsrow[10];

                                                            $GRNItemCost = $CostPrice * $GRNQTY;
                                                    ?>
                                                        <tr>
                                                            <th scope="row"><?php echo $ItemCount+=1; ?></th>
                                                            <td><?php echo $ItemName; ?></td>
                                                            <td><b style="float: right;"><?php echo $GRNQTY; ?></b></td>
                                                            <td><b style="float: right;"><?php echo number_format($GRNItemCost,2); ?></b></td>
                                                            <td>
                                                                <form id="Delete-GRN-Item" method="POST">
                                                                    <input type="hidden" name="grn_items_id" value="<?php echo $GRNItemItemId; ?>" required readonly>
                                                                    <input type="hidden" name="grn_detail_id" value="<?php echo $GRNDetailId; ?>" required readonly>
                                                                    <input type="hidden" name="item_id" value="<?php echo $ItemDetailId; ?>" required readonly>
                                                                    <input type="hidden" name="price_batch_id" value="<?php echo $PriceBatchId; ?>" required readonly>
                                                                    <input type="hidden" name="qty" value="<?php echo $GRNQTY; ?>" required readonly>
                                                                    <button type="submit" id="btn-delete-grn-item" class="btn btn-danger" style="float: right;"><i class="fa fa-trash"></i> Remove</button>   
                                                                </form>
                                                            </td>
                                                        </tr>
                                                        <?php } ?>
                                                    </tbody>
                                                </table>
                                            </div>


                                            <form method="POST" id="GRN-Finalize">
                                                <input type="hidden" name="grn_detail_id" value="<?php echo $GRNDetailId; ?>" required readonly>
                                                <button type="submit" id="btn-final-grn-bill" class="btn btn-primary" style="width: 100%;" aria-hidden="true" data-toggle="tooltip" data-placement="bottom" title="This action can't be reverted">Finalize GRN</button>
                                            </form>
                                                        

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
    </div>
    <!-- // END drawer-layout -->

        <!--Price Batch Modal -->
        <div class="modal fade" id="modal_add_price_batch" data-backdrop='static' data-keyboard='false' tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLongTitle">Add Price Batch <span id="selected-part-name"></span></h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                          <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                                                            
                        <form id="Add-Price-Batch" method="POST">
                            <input type="hidden" class="form-control" name="selected_item_id" id="selected_item_id" readonly required>
                                <!-- <div class="panel-heading clearfix">
                                    <h4 class="panel-title">Register Client Details</h4>
                                </div> -->
                                <div class="panel-body">

                                    <div class="form-group">
                                        <label for="batch_label">Price Batch Label <font style="color: #FF0000;">*</font></label>
                                        <input type="text" class="form-control" name="batch_label" placeholder="Price Batch Label" required>
                                    </div>
                                    
                                    <div class="form-group">
                                        <label for="mrp_price">MRP Price <font style="color: #FF0000;">*</font></label>
                                        <input type="number" class="form-control" name="mrp_price" step="any" placeholder="MRP Price" required>
                                    </div>

                                    <div class="form-group">
                                        <label for="cost_price">Cost Price <font style="color: #FF0000;">*</font></label>
                                        <input type="number" class="form-control" name="cost_price" step="any" placeholder="Cost Price" required>
                                    </div>

                                    <div class="form-group">
                                        <label for="selling_price">Selling Price <font style="color: #FF0000;">*</font></label>
                                        <input type="number" class="form-control" name="selling_price" step="any" placeholder="Selling Price" required>
                                    </div>

                                    <div class="form-group">
                                        <label for="selling_price">Distributor Price <font style="color: #FF0000;">*</font></label>
                                        <input type="number" class="form-control" name="distributor_price" step="any" placeholder="Distributor Price" required>
                                    </div>

                                    <div class="form-group">
                                        <label for="selling_price">Return Price <font style="color: #FF0000;">*</font></label>
                                        <input type="number" class="form-control" name="return_price" step="any" placeholder="Return Price" required>
                                    </div>

                                </div>
                            <button type="submit" id="btn-add-new-price-batch" class="btn btn-primary waves-effect waves-light">Add Price Batch</button>
                                                                
                        </form>

                    </div>
                    <div class="modal-footer">
                      <!-- <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                      <button type="button" class="btn btn-primary">Save changes</button> -->
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

    <!-- App Settings (safe to remove) -->
    <script src="assets/js/app-settings.js"></script>

    <!-- Dropzone -->
    <script src="assets/vendor/dropzone.min.js"></script>
    <script src="assets/js/dropzone.js"></script>

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


        function onItemChanged(){


        $("#cost-input").val("");
        $("#selling-input").val("");

        var val=$("#select-item").val();   

        $.ajax({
            url:'grn_post/get_price_batch_for_add_stock.php',
            type:'POST',
            data:{
                item_no:val
            },
            success:function(data){
               
                var json = JSON.parse(data);
                if(json.result){

                    $("#price_batch_id").html(json.data);
                }

            },error:function(err){
                console.log(err);
            }
        //
    });


    }
    ///////////////////////////////////////////////
    function onPriceBatchChanged(){

        var valp=$("#select-item").val();  
        var valb=$("#price_batch_id").val();  


        $.ajax({
            url:'grn_post/get_cost_price.php',
            type:'POST',
            data:{
                item_no:valp,
                price_batch_id:valb
            },
            success:function(data){
               
                var json = JSON.parse(data);
                if(json.result){

                    $("#cost-input").val(json.cp);
                    $("#selling-input").val(json.sp);
                }

            },error:function(err){
                console.log(err);
            }
        //
    });


    }
    </script>


    <script>
       
        $(document).ready(function() {
            $("#btn-add-new-price-batch").click(function(){

                

                if($("#select-item").val() === null || $("#select-item").val() === ''){

                }else{
                   $("#selected-part-name").html($("#select-item").children("option").filter(":selected").text());
                $("#selected_item_id").val($("#select-item").val());
                $("#modal_add_price_batch").modal('show'); 
                }



                

            });
        });
    </script>


    <script>
        
        $(document).on('submit', '#Add-GRN-Bill-Item', function(e){
        e.preventDefault(); //stop default form submission

        $("#btn-add-grn-bill-item").attr("disabled",true);

        var formData = new FormData($(this)[0]);

        $.ajax({
            
            
                beforeSend : function() {
                    $("#progress_alert").addClass('show');
                },

                url:"grn_post/add_grn_item.php",
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
                    
                       $("#grn-item-area").html(json.data);
                       $("#grn-total-cost").html(json.GRNItemTotalCost);
                       $("#grn-item-count").html(json.ResultCount);
                       $("#success_msg").html(json.msg);
                       $("#success_alert").addClass('show');
                       
                       setTimeout(function(){$("#success_alert").removeClass('show');  }, 1000);
                       $("#btn-add-grn-bill-item").attr("disabled",false);
                       document.getElementById('stock-input').value = '';
                       document.getElementById('cost-input').value = '';
                       document.getElementById('selling-input').value = '';
                       document.getElementById('price_batch_id').value = '';
                       

                    }else{
                        $("#danger_alert").addClass('show');
                        setTimeout(function(){ $("#danger_alert").removeClass('show'); }, 1000);
                        $("#btn-add-grn-bill-item").attr("disabled",false);
                    }
                    
                }

            });

        return false;
        });
    </script>


    <script>
        
        $(document).on('submit', '#Delete-GRN-Item', function(e){
        e.preventDefault(); //stop default form submission

        $("#btn-delete-grn-item").attr("disabled",true);

        var formData = new FormData($(this)[0]);

        $.ajax({
            
            
                beforeSend : function() {
                    $("#progress_alert").addClass('show'); 

                },

                url:"grn_post/delete_grn_item.php",
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

                       $("#grn-item-area").html(json.data);
                       $("#grn-total-cost").html(json.GRNItemTotalCost);
                       $("#grn-item-count").html(json.ResultCount);
                       $("#success_msg").html(json.msg);
                       $("#success_alert").addClass('show'); 
                       
                       setTimeout(function(){$("#success_alert").removeClass('show');  }, 1000);

                       // window.location.href = "products";
                        
                    }else{
                        $("#danger_alert").addClass('show');
                        setTimeout(function(){ $("#danger_alert").removeClass('show'); }, 1000);
                        $("#btn-delete-grn-item").attr("disabled",false);
                    }
                    
                }

            });

        return false;
        });
    </script>

    <script>
        
        $(document).on('submit', '#Add-Price-Batch', function(e){
        e.preventDefault(); //stop default form submission
        var formData = new FormData($(this)[0]);

        $.ajax({

                beforeSend : function() {

                     $("#progress_alert").addClass('show');

                },

                url:"grn_post/add_price_batch.php",
                type: 'POST',
                data: formData,
                //async: false,
                cache: false,
                contentType: false,
                processData: false,

                success: function (data) {

                   var json = JSON.parse(data);
                   if(json.result){

                        $("#price_batch_id").append(json.data);

                   }

                    $("#modal_add_price_batch").modal('hide'); 
                    $("#progress_alert").removeClass('show');
                    $("#success_msg").html(json.msg);
                    $("#success_alert").addClass('show');

                }

            });

        return false;
        });
    </script>

    

    <script>
        
        $(document).on('submit', '#GRN-Finalize', function(e){
        e.preventDefault(); //stop default form submission

        $("#btn-final-grn-bill").attr("disabled",true);

        var formData = new FormData($(this)[0]);

        $.ajax({
            
            
                beforeSend : function() {
                    $("#progress_alert").addClass('show'); 

                },

                url:"grn_post/update_status_grn.php",
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
                       window.location.href = "view_grn_list";
                        
                    }else{
                        $("#danger_alert").addClass('show');
                        setTimeout(function(){ $("#danger_alert").removeClass('show'); }, 1000);
                        $("#btn-final-grn-bill").attr("disabled",false);
                    }
                    
                }

            });

        return false;
        });
    </script>

    

  

</body>
</html>