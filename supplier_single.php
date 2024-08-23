<?php
    session_start();
    require 'database/db.php';
    $db=new DB();
    $conn=$db->connect();
    date_default_timezone_set('Asia/Colombo');
    $currentDate=date('Y-m-d');

    $SupplierId=base64_decode($_GET['s']);

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
    $sql = "SELECT * FROM tbl_supplier ts INNER JOIN tbl_supplier_details tsd ON ts.supplier_id=tsd.supplier_id WHERE ts.supplier_id='$SupplierId'";
    $rs=$conn->query($sql);
    if($row=$rs->fetch_array())
    {
        $SupplierId=$row[0];                       
        $SupplierName=$row[1];                       
                                                                          
        $NICNumber=$row[6];                       
        $BRNumber=$row[7];                       
        $ContactPersonName=$row[8];                       
        $ContactNumber=$row[9];                       
        $Address=$row[10];                       
        $SupplierRegDate=$row[11];                       
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
                                <h3>Supplier Profile</h3>
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
                                        <a href="#!">
                                            <img class="avatar border-gray" src="assets/img/icons/suppler.png">
                                            <h5 class="title" style="color: #000;"><?php echo $SupplierName; ?></h5>
                                        </a>
                                        </center>
                                    </div>
                                    <p class="description text-center">
                                        Contact Number : <a href="tel:<?php echo $OutletContact; ?>" style='color: #000;'><?php echo $ContactNumber; ?></a><br>
                                        Address : <?php echo $Address; ?>
                                    </p>

                                    <hr>
                                    <div class="button-container">
                                      <div class="row">
                                        <div class="col-lg-6 col-md-6 col-6 text-center">
                                          <h5>
                                          <?php 
                                            $SupplierProductSQL="SELECT COUNT(*) FROM `tbl_supplier_has_products` WHERE supplier_id='$SupplierId'";
                                            $SupplierProductResult = mysqli_query($conn, $SupplierProductSQL);
                                            $SupplierProductTotal = mysqli_fetch_assoc($SupplierProductResult)['COUNT(*)'];
                                            echo $SupplierProductTotal;
                                          ?>

                                          <br><small>Supplier Has Product</small></h5>
                                        </div>
                                        <div class="col-lg-6 col-md-6 col-6 text-center">
                                          <h5>
                                            <?php
                                              $StockAddingCountSQL = "SELECT COUNT(*) FROM tbl_stock_add_details WHERE stat='1' AND supplier_id='$SupplierId' ";
                                              $StockAddingCountResult = mysqli_query($conn, $StockAddingCountSQL);
                                              $StockAddingCount = mysqli_fetch_assoc($StockAddingCountResult)['COUNT(*)'];
                                              echo $StockAddingCount;
                                            ?>
                                            <br><small>Stock Adding History</small></h5>
                                        </div>
                                        



                                      </div>


                                      <br>

                                        <div>
                                          <button class="btn btn-dark" data-toggle="modal" data-target="#AddProductModalCenter" style="width: 100%">Add Suppling Products</button>
                                        </div>



                                    </div>
                                </div>

                                <div class="card">
                                    <div class="card-header">
                                        <h4 class="card-title">Product List</h4>
                                    </div>
                                    <div class="card-body">
                                        <table class="table mb-0 thead-border-top-0" id="SupplierProductsTable">
                                            <thead>
                                                <th>Product Code</th>
                                                <th><font style="float: right;">Product Name</font></th>
                                                <th></th>
                                            </thead>
                                            <tbody class="list" id="staff">
                                                <?php
                                                    $SupplierProductsSql = "SELECT * FROM tbl_supplier_has_products tshp INNER JOIN tbl_item_details tid ON tshp.item_detail_Id=tid.item_detail_Id WHERE tshp.supplier_id='$SupplierId' ORDER BY tshp.supplier_has_products_id ASC";
                                                    $SupplierProductsQuery=$conn->query($SupplierProductsSql);
                                                    while($SProw=$SupplierProductsQuery->fetch_array())
                                                    {
                                                        $SupplierHasProductId=$SProw[0];                       
                                                        $ItemDetailId=$SProw[2];
                                                        $ItemCode=$SProw[4];
                                                        $ItemName=$SProw[5];
                                                ?>
                                                <tr>
                                                    <td><small class="text-muted" style="color: #000 !important; font-weight: 600;"><?php echo $ItemCode; ?></small></td>
                                                    <td><small class="text-muted" style="float: right; color: #000 !important; font-weight: 600;"><?php echo $ItemName; ?></small></td>
                                                    <td>
                                                        <form id="Remove-Product-List">
                                                            <input type="hidden" name=" supplier_has_products_id" value="<?php echo $SupplierHasProductId; ?>" readonly required>
                                                            <input type="hidden" name="item_detail_Id" value="<?php echo $ItemDetailId; ?>" readonly required>
                                                            <button type="submit" class="btn btn-danger btn-sm" style="float: right;"> X </button>
                                                        </form>
                                                    </td>
                                                </tr>
                                                <?php } ?>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>

                            </div>

                            <div class="col-md-8">
                                <div class="card-header card-header-tabs-basic nav" role="tablist">
                                    <ul class="nav nav-tabs">
                                        <br><br><li><a href="#Records" class="active" data-toggle="tab">Records</a></li>
                                        <br><br><li><a href="#StockAddingHistory" data-toggle="tab">Stock Adding History</a></li>
                                    </ul>
                                </div><br>
                                <div class="card">
                                    <div id="exTab2" class="container"> 

                                            <div class="card-body tab-content">
                                              <div class="tab-pane active" id="Records" style="padding: 5px;">
                                                

                                                <form id="Write-Supplier-Record" method="POST">
                                                  <input type="hidden" name="supplier_id" class="form-control" value="<?php echo $SupplierId; ?>" readonly required>
                                                  <textarea name="record" id="record-textarea" style="padding: 8px; resize: auto; max-height: 100% !important;" cols="30" rows="5" class="form-control bg-transparent" placeholder="Please type what you want...."></textarea>
                                                  <br>
                                                  <button type="submit" class="btn btn-dark" id="btn-add-record">Add Records</button>
                                                </form>

                                                <hr>

                                                <div id="supplier-record-area">
                                                    <?php
                                                        $getSupplierRecordQuery=$conn->query("SELECT * FROM supplier_records WHERE supplier_id='$SupplierId' ORDER BY supplier_record_id DESC");
                                                        while ($GSRrs=$getSupplierRecordQuery->fetch_array()) {
                                                    ?>
                                                    <div class="profile-uoloaded-post border-bottom-1 pb-5">
                                                        <h5>Remark Note <span class="pull-right" style="font-size: 12px; float: right;"><?php echo $GSRrs[3]; ?></span></h5>
                                                        <p><?php echo nl2br($GSRrs[2]); ?></p>
                                                                                    
                                                        <hr>
                                                    </div>
                                                    <?php } ?>
                                                </div>




                                              </div>

                                              <div class="tab-pane" id="StockAddingHistory">
                                                
                                                <div class="table-responsive" style="overflow-y: hidden !important;">
                                                    <table id="DistributoInvoiceTable" class="display" style="width:100%;">
                                                        <thead>
                                                            <th>#</th>
                                                            <th>Stock Add Number</th>
                                                            <th>Supplier Name</th>
                                                            <th>Added Date</th>
                                                        </thead>
                                                        <tbody>
                                                        <?php
                                                            $StockAddListSql = "SELECT * FROM tbl_stock_add_details WHERE stat='1' AND supplier_id='$SupplierId'";
                                                            $rs=$conn->query($StockAddListSql);
                                                            while($SALrs =$rs->fetch_array())
                                                            {   
                                                                $StockAddId=$SALrs[0];
                                                                $AdminId=$SALrs[1];
                                                                $SupplierId=$SALrs[2];
                                                                $Stat=$SALrs[3];
                                                                $StockAddDateTime=$SALrs[5];

                                                                $GetSupplierDetailsSql = "SELECT * FROM tbl_supplier WHERE supplier_id='$SupplierId'";
                                                                $GSDrs=$conn->query($GetSupplierDetailsSql);
                                                                if($GSDrow =$GSDrs->fetch_array())
                                                                {
                                                                    $SupplierName=$GSDrow[1];
                                                                }       
                                                        ?>
                                                        <tr class="gradeA" onclick="location.href='stock_add_view?s=<?php echo (base64_encode($StockAddId)); ?>'" style="cursor: pointer;">
                                                            <td><?php echo $StockAddId; ?></td>
                                                            <td><?php echo $StockAddId+10000; ?></td>
                                                            <td><?php echo $SupplierName; ?></td>
                                                            <td><?php echo $StockAddDateTime; ?></td>
                                                        </tr>     
                                                      <?php } ?>
                                                        
                                                    </tbody>
                                                  </table>
                                                </div>


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

    <!-- Route Modal -->
    <div class="modal fade" id="AddProductModalCenter" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLongTitle">Add Supplieres to Products</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form id="Add-Products-To-Supplier">
                    <div class="modal-body">
                        <input type="hidden" name="supplier_id" value="<?php echo $SupplierId; ?>" readonly required>
                        <div class="form-group">
                            <label>Select Products</label>
                            <select name="item_detail_Id" class="js-example-basic-single custom-select" style="width: 100%;">
                                <option selected disabled>Select Products</option>
                                <?php
                                    $SupplierHasProductQuery=$conn->query("SELECT * FROM tbl_item_details");
                                    while ($Rrow=$SupplierHasProductQuery->fetch_array()) {
                                ?>
                                    <option value="<?php echo $Rrow[0];?>"><?php echo $Rrow[2];?> (Product Code - <?php echo $Rrow[1];?>)</option>
                                <?php } ?>
                            </select>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary" id="btn-add-product">Add product</button>
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

    <!-- Chart JS -->
    <script src="assets/js/plugins/chartjs.min.js"></script>
    <script src="assets/js/plugins/chartjs-color.js"></script>

    <!-- App Settings (safe to remove) -->
    <script src="assets/js/app-settings.js"></script>

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
        $('#SalesRepTable').DataTable();
    } );
    $(document).ready( function () {
        $('#DistributoInvoiceTable').DataTable();
    } );
    $(document).ready( function () {
        $('#SupplierProductsTable').DataTable();
    } );

    
  </script>


  <script>
        
        $(document).on('submit', '#Write-Supplier-Record', function(e){
        e.preventDefault(); //stop default form submission

        $("#btn-add-record").attr("disabled",true);

        var formData = new FormData($(this)[0]);

        $.ajax({
            
            
                beforeSend : function() {
                    $("#progress_alert").addClass('show');
                },

                url:"post/add_supplier_record.php",
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
                       $("#supplier-record-area").html(json.data);
                        
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
        
        $(document).on('submit', '#Add-Products-To-Supplier', function(e){
        e.preventDefault(); //stop default form submission

        $("#btn-add-product").attr("disabled",true);

        var formData = new FormData($(this)[0]);

        $.ajax({
            
            
                beforeSend : function() {
                    $("#progress_alert").addClass('show');
                },

                url:"post/add_products_to_supplier.php",
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
                       $("#btn-add-product").attr("disabled",false);
                       location.reload();
                        
                    }else{
                    	$("#error_msg").html(json.msg);
                        $("#danger_alert_msg").addClass('show');
                        setTimeout(function(){ $("#danger_alert_msg").removeClass('show'); }, 1000);
                        $("#btn-add-product").attr("disabled",false);
                    }
                    
                }

            });

        return false;
        });
    </script>

    
    <script>
        
        $(document).on('submit', '#Remove-Product-List', function(e){
        e.preventDefault(); //stop default form submission

        var formData = new FormData($(this)[0]);

        $.ajax({
            
            
                beforeSend : function() {
                    $("#progress_alert").addClass('show');
                },

                url:"post/delete_supplier_product.php",
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
                       location.reload();
                        
                    }else{
                        $("#danger_alert").addClass('show');
                        setTimeout(function(){ $("#danger_alert").removeClass('show'); }, 1000);
                    }
                    
                }

            });

        return false;
        });
    </script>

</body>
</html>