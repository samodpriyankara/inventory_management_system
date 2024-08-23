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
                          @media print {
                            @page {
                              size: auto;   /* auto is the initial value */
                              size: A4 portrait;
                              margin: 0;  /* this affects the margin in the printer settings */
                              border: 1px solid red;  /* set a border for all printed pages */
                            }
                            body {
                                zoom: 80%;
                                /*transform: scale(.6);*/
                                /*margin-top: -320px;*/
                                width: 100%;
                                font-weight: 700;
                                margin-top: -100px;
                            }
                            #print-page{
                                margin-left: -320px;
                                margin-top: 40px;
                                background-color: #fff !important;
                            }
                            #supplier-details-print{
                                margin-top: -80px !important;
                            }
                            #printPageButton {
                                display: none;
                            }
                            .main-panel{
                              width: 100% !important;
                            }
                            #topbar{
                              display: none;
                            }
                            #footer{
                              display: none;
                            }
                            #default-drawer{
                              display: none;
                            }
                            #app-settings-dd__BV_toggle_{
                              display: none;
                            }
                            #sidebar{
                              visibility: hidden;
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
                        
                        <div class="card">
                            <div class="card-header">
                                <div class="row">
                                    <div class="col-md-6">
                                        <h5 class="card-title">GRN</h5>
                                    </div>
                                    <div class="col-md-6">
                                        
                                        <button type="button" id="printPageButton" onclick="window.print();" class="btn btn-primary pull-right"><i class="fa fa-print"></i> Print</button>
                                        
                                    </div>
                                </div>
                            </div>
                            
                            <div class="card-body">
                                
                                <div class="row">
                                    <div class="col-md-6">
                                        <p style="text-align: left;">
                                            <b>GRN Number - <?php echo $GRNNumber; ?></b><br>
                                            <b>Invoice Number - <?php echo $InvoiceNumber; ?></b><br>
                                            <b>GR Date - <?php echo $GoodsReceivedDate; ?></b><br>
                                        </p>
                                    </div>
                                    <div class="col-md-6" id="supplier-details-print">
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

                                <div class="example-content">
                                    <table class="table table-hover">
                                        <thead>
                                            
                                                <th scope="col">#</th>
                                                <th scope="col">Item</th>
                                                <th scope="col"><font style="float: right;">Qty</font></th>
                                                <th scope="col"><font style="float: right;">Cost (Rs)</font></th>
                                                <th scope="col"></th>
                                            
                                        </thead>
                                        <tbody id="grn-item-area">
                                        <?php
                                            $getGRNItems = "SELECT * FROM tbl_grn_items tgi INNER JOIN tbl_item tit ON tgi.price_batch_id=tit.itemId WHERE tgi.grn_detail_id='$GRNDetailId' ORDER BY tgi.grn_items_id ASC";
                                            $gGRNiRs=$conn->query($getGRNItems);
                                            while($gGRNiRsrow =$gGRNiRs->fetch_array())
                                            {
                                                $GRNItemItemId=$gGRNiRsrow[0];
                                                $ItemId=$gGRNiRsrow[2];
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
                                                <td scope="row"><?php echo $ItemCount+=1; ?></td>
                                                <td><?php echo $ItemName; ?></td>
                                                <td><b style="float: right;"><?php echo $GRNQTY; ?></b></td>
                                                <td><b style="float: right;"><?php echo number_format($GRNItemCost,2); ?></b></td>
                                            
                                                <?php
                                                    
                                                    if(isset($_SESSION['EDT_ENABLED']) && $_SESSION['EDT_ENABLED'] == true){
                                                        
                                                        ?>
                                                            <td> <button type="button" onclick="remove_item('<?php echo $GRNItemItemId;?>')" class="btn btn-danger pull-right"><i class="fa fa-remove"></i></button> </td>
                                                        <?php
                                                        
                                                        
                                                    }
                                                
                                                ?>
                                            
                                                
                                            
                                            
                                            </tr>
                                        <?php } ?>
                                        </tbody>
                                        <tfoot>
                                            
                                                <th></th>
                                                <th></th>
                                                <th></th>
                                                <th><b style="float: right; color: #000; font-size: 20px; font-weight: 700;"><?php echo number_format($GRNInvoiceCost,2); ?></b></th>
                                                <th></th>
                                           
                                        </tfoot>

                                    </table>
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
        
        
        
        
        function remove_item(grn_item_id){
            
            
            $.ajax({
                    url:'scripts/remove_grn_item.php',
                    type:'POST',
                    data:{
                        grn_item_id:grn_item_id
                    },
                    cache: false,
                    // contentType: false,
                    // processData: false,

                    beforeSend:function(){
                        // $("#btn-auth-now").prop("disabled",true);
                          $("button").prop("disabled", true);

                    },
                    success:function(data){
                        
                        var json=JSON.parse(data);
                        if(json.result){
                            location.reload();
                        }else{
                            alert(json.msg);
                        }
                       
                        // $("button").prop("disabled", false);
                                        
                    },
                    error:function(err,xhr,data){
                            console.log(err);
                    }

                });
            
            
            
            
        }
        
        
       
        
        
    </script>



  
</body>
</html>
