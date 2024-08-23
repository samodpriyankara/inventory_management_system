<?php
    session_start();
    require 'database/db.php';
    $db=new DB();
    $conn=$db->connect();
    date_default_timezone_set('Asia/Colombo');
    $currentDate=date('Y-m-d');

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
                            <div class="row no-gutters">
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <p><strong class="headings-color">Sales-Rep Targets (Product Wise)</strong></p>
                                            <p class="text-muted">Available Sales-Rep Targets here.</p>
                                        </div>
                                        <div class="col-md-6">
                                            <!-- Button trigger modal -->
                                            <button type="button" class="btn btn-dark" data-toggle="modal" data-target="#ProductViceTargetsModalCenter" style="float: right;">
                                              Set Sales-Rep Targets (Product Wise)
                                            </button>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-lg-12 card-form__body">
                                    <div class="table-responsive border-bottom" style="padding: 10px;">

                                        <table class="table mb-0 thead-border-top-0" id="ProductViceTargetsTable">
                                            <thead>
                                                <tr>
                                                    <th>#</th>
                                                    <th>Product Name</th>
                                                    <th>Quantity</th>
                                                    <th>Start Date</th>
                                                    <th>End Date</th>
                                                    <th>Status</th>
                                                    <!-- <th></th> -->
                                                </tr>
                                            </thead>
                                            <tbody class="list">
                                                <?php
                                                    $getProductViceTargets=$conn->query("SELECT * FROM tbl_rep_target_qty_wise ORDER BY id ASC");
                                                    while ($PVTrs=$getProductViceTargets->fetch_array()) {
                                                        $PId = $PVTrs[0];
                                                        $PItemId = $PVTrs[1];
                                                        $PQty = $PVTrs[2];
                                                        $PValidFrom = $PVTrs[3];
                                                        $PValidTo = $PVTrs[4];
                                                        $PStatus = $PVTrs[5];

                                                        $ItemDetails=$conn->query("SELECT * FROM tbl_item WHERE itemId='$PItemId'");
                                                        if ($IDrs=$ItemDetails->fetch_array()) {
                                                            $ItemCode = $IDrs[1];
                                                            $ItemName = $IDrs[2];
                                                        }

                                                ?>
                                                    <?php if($PValidTo < $currentDate){ }else{?>
                                                    <tr>
                                                        <td><?php echo $PId; ?></td>
                                                        <td><?php echo $ItemName.' ('.$ItemCode.')'; ?></td>
                                                        <td><?php echo $PQty; ?></td>
                                                        <td><?php echo $PValidFrom; ?></td>
                                                        <td><?php echo $PValidTo; ?></td>
                                                        <td><?php echo $PStatus; ?></td>
                                                    </tr>
                                                    <?php } ?>
                                                <?php } ?>
                                            </tbody>
                                        </table>

                                    </div>
                                </div>
                            </div>
                        </div>


                        
                    </div>

                    <!---------------AMOUNT VICE-------------->

                    <div class="container-fluid page__container">

                        

                        
                        <div class="card card-form">
                            <div class="row no-gutters">
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <p><strong class="headings-color">Sales-Rep Targets (Amount Wise)</strong></p>
                                            <p class="text-muted">Available Sales-Rep Targets here.</p>
                                        </div>
                                        <div class="col-md-6">
                                            <!-- Button trigger modal -->
                                            <button type="button" class="btn btn-dark" data-toggle="modal" data-target="#AmountViceTargetsModalCenter" style="float: right;">
                                              Set Sales-Rep Targets (Amount Wise)
                                            </button>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-lg-12 card-form__body">
                                    <div class="table-responsive border-bottom" style="padding: 10px;">

                                        <table class="table mb-0 thead-border-top-0" id="ProductViceTargetsTable">
                                            <thead>
                                                <tr>
                                                    <th>#</th>
                                                    <th>Amount (.Rs)</th>
                                                    <th>Start Date</th>
                                                    <th>End Date</th>
                                                    <!-- <th></th> -->
                                                </tr>
                                            </thead>
                                            <tbody class="list">
                                                <?php
                                                    $getAmountViceTargets=$conn->query("SELECT * FROM tbl_rep_target_amount_wise ORDER BY id ASC");
                                                    while ($AVTrs=$getAmountViceTargets->fetch_array()) {
                                                        $AId = $AVTrs[0];
                                                        $Amount = $AVTrs[1];
                                                        $AValidFrom = $AVTrs[2];
                                                        $AValidTo = $AVTrs[3];
                                                ?>
                                                    <?php if($AValidTo < $currentDate){ }else{?>
                                                    <tr>
                                                        <td><?php echo $AId; ?></td>
                                                        <td><?php echo number_format($Amount,2); ?></td>
                                                        <td><?php echo $AValidFrom; ?></td>
                                                        <td><?php echo $AValidTo; ?></td>
                                                    </tr>
                                                    <?php } ?>
                                                <?php } ?>
                                            </tbody>
                                        </table>

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



    <!-- Set Sales-Rep Targets (Product Wise) Modal -->
    <div class="modal fade" id="ProductViceTargetsModalCenter" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLongTitle">Set Sales-Rep Targets (Product Wise)</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form id="Add-Target-By-Product">
                    <div class="modal-body card-form__body card-body">
                                  
                        <div class="row">
                            <div class="col">
                                <div class="form-group">
                                    <label>Select Prodcut</label>
                                    <!-- <input name="item_id" type="text" class="form-control" placeholder="Select Prodcut" required> -->
                                    <select class="js-example-basic-single form-control" name="item_id" style="padding: 0.375rem 0.75rem !important; width: 100%;" required>
                                        <option value="" selected disabled>Select Item</option>
                                        <?php
                                            $itemsQuery=$conn->query("SELECT itemId,itemDescription,itemCode FROM tbl_item WHERE stock > 0 ORDER BY itemId DESC");
                                            while ($row=$itemsQuery->fetch_array()) {
                                        ?>
                                            <option value="<?php echo $row[0];?>"><?php echo $row[1];?> (<?php echo $row[2];?>)</option>
                                        <?php } ?>
                                    </select>
                                </div>
                            </div>
                            <div class="col">
                                <div class="form-group">
                                    <label>Target Quantity</label>
                                    <input name="qty" type="number" min="1" class="form-control" placeholder="Target Quantity" required>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col">
                                <div class="form-group">
                                    <label>Start From</label>
                                    <input name="valid_from" type="date" class="form-control" value="<?php echo $currentDate; ?>" placeholder="Start From" required>
                                </div>
                            </div>
                            <div class="col">
                                <div class="form-group">
                                    <label>Valid To</label>
                                    <input name="valid_to" type="date" class="form-control" placeholder="Valid To" required>
                                </div>
                            </div>
                        </div>
                                
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                        <button type="submit" id="btn-add-target-by-product" class="btn btn-primary">Add Target</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Set Sales-Rep Targets (Amount Wise) Modal -->
    <div class="modal fade" id="AmountViceTargetsModalCenter" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLongTitle">Set Sales-Rep Targets (Amount Wise)</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form id="Add-Target-By-Amount">
                    <div class="modal-body card-form__body card-body">
                                  
                        <div class="row">
                            <div class="col">
                                <div class="form-group">
                                    <label>Amount</label>
                                    <input name="amount" type="number" min="1" class="form-control" placeholder="Amount" required>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col">
                                <div class="form-group">
                                    <label>Start From</label>
                                    <input name="a_valid_from" type="date" class="form-control" value="<?php echo $currentDate; ?>" placeholder="Start From" required>
                                </div>
                            </div>
                            <div class="col">
                                <div class="form-group">
                                    <label>Valid To</label>
                                    <input name="a_valid_to" type="date" class="form-control" placeholder="Valid To" required>
                                </div>
                            </div>
                        </div>
                                
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                        <button type="submit" id="btn-add-target-by-amount" class="btn btn-primary">Add Target</button>
                    </div>
                </form>
            </div>
        </div>
    </div>


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
        
        $(document).on('submit', '#Add-Target-By-Product', function(e){
        e.preventDefault(); //stop default form submission

        $("#btn-add-target-by-product").attr("disabled",true);

        var formData = new FormData($(this)[0]);

        $.ajax({
            
            
                beforeSend : function() {

                $("#progress_alert").addClass('show'); 

                },

                url:"post/add_sales_rep_targets_by_product.php",
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
                       // $("#outlet-record-area").html(json.data);
                        
                       $("#success_msg").html(json.msg);
                       $("#success_alert").addClass('show'); 
                       
                       setTimeout(function(){$("#success_alert").removeClass('show');  }, 1000);
                       $("#btn-add-target-by-product").attr("disabled",false);
                       // document.getElementById('record-textarea').value = '';
                       location.reload();
                        
                    }else{
                        $("#danger_alert").addClass('show');
                        setTimeout(function(){ $("#danger_alert").removeClass('show'); }, 1000);
                        $("#btn-add-target-by-product").attr("disabled",false);
                    }
                    
                }

            });

        return false;
        });
    </script>

    
    <script>
        
        $(document).on('submit', '#Add-Target-By-Amount', function(e){
        e.preventDefault(); //stop default form submission

        $("#btn-add-target-by-amount").attr("disabled",true);

        var formData = new FormData($(this)[0]);

        $.ajax({
            
            
                beforeSend : function() {

                $("#progress_alert").addClass('show'); 

                },

                url:"post/add_sales_rep_targets_by_amount.php",
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
                       // $("#outlet-record-area").html(json.data);
                        
                       $("#success_msg").html(json.msg);
                       $("#success_alert").addClass('show'); 
                       
                       setTimeout(function(){$("#success_alert").removeClass('show');  }, 1000);
                       $("#btn-add-target-by-amount").attr("disabled",false);
                       // document.getElementById('record-textarea').value = '';
                       location.reload();
                        
                    }else{
                        $("#danger_alert").addClass('show');
                        setTimeout(function(){ $("#danger_alert").removeClass('show'); }, 1000);
                        $("#btn-add-target-by-amount").attr("disabled",false);
                    }
                    
                }

            });

        return false;
        });
    </script>


</body>
</html>