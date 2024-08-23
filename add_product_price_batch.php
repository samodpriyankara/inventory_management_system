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
                                <div class="col-lg-4 card-body">
                                    <p><strong class="headings-color">Create New Product Batch</strong></p>
                                    <p class="text-muted">Edit your account details and settings.</p>
                                </div>
                                <form id="Add-Price-Batch" class="col-lg-8 card-form__body card-body">
                                    
                                    <div class="row">
                                        <div class="col">
                                            <div class="form-group">
                                                <label>Select Item <font style="color: #FF0000;">*</font></label>
                                                <select name="selected_item_id" class="js-example-basic-single custom-select" style="width: 100%;">
                                                    <option selected disabled>Select Item</option>
                                                    <?php
                                                        $GetItemDetailsQuery=$conn->query("SELECT DISTINCT item_detail_Id,itemCode,itemDescription FROM tbl_item_details WHERE sequenceId='1'");
                                                        while ($GIDrow=$GetItemDetailsQuery->fetch_array()) {
                                                    ?>
                                                        <option value="<?php echo $GIDrow[0];?>"><?php echo $GIDrow[2];?> (<?php echo $GIDrow[1];?>)</option>
                                                    <?php } ?>
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col">
                                            <div class="form-group">
                                                <label>Price Batch Code <font style="color: #FF0000;">*</font></label>
                                                <input type="text" class="form-control" name="price_batch_code" placeholder="Price Batch Code" required>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col">
                                            <div class="form-group">
                                                <label for="mrp_price">MRP Price <font style="color: #FF0000;">*</font></label>
                                                <input type="number" class="form-control" min="0" name="mrp_price" step="any" id="mrp_price" placeholder="MRP Price" required>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col">
                                            <div class="form-group">
                                                <label for="cost_price">Cost Price <font style="color: #FF0000;">*</font></label>
                                                <input type="number" class="form-control" min="0" name="cost_price" step="any" id="cost_price" placeholder="Cost Price" required>
                                            </div>
                                        </div>
                                        <div class="col">
                                            <div class="form-group">
                                                <label for="selling_price">Retailer Price<font style="color: #FF0000;">*</font></label>
                                                <input type="number" class="form-control" min="0" name="selling_price" id="sel_price" step="any" placeholder="Retailer Price" required>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col">
                                            <div class="form-group">
                                                <label for="distributor_price">Distributor Price <font style="color: #FF0000;">*</font></label>
                                                <input type="number" class="form-control" min="0" name="distributor_price" id="distributor_price" step="any" placeholder="Distributor Price" required>
                                            </div>
                                        </div>
                                        <div class="col">
                                            <div class="form-group">
                                                <label for="return_price">Return Price <font style="color: #FF0000;">*</font></label>
                                                <input type="number" class="form-control" min="0" name="return_price" step="any" placeholder="Return Price" required>
                                            </div>
                                        </div>
                                    </div>

                                    
                                    
                                    

                                    <div class="text-right mb-5">
                                        <button type="submit" id="btn-reg-outlet" class="btn btn-dark">Add Price Batch</button>
                                    </div>

                                </form>

                                
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

    <script>

    	function calculateDistributorPrice(){
            var SelP = document.getElementById('mrp_price').value;
            var dis_price = (20/100)*SelP;
            var dis_real_price = parseFloat(SelP) - parseFloat(dis_price);

            // var DisnewPRice = parseFloat($("#distributor_price1").val());
            var mrp_pricer = (12/100)*SelP;
            var mrp_real_price = parseFloat(SelP) - parseFloat(mrp_pricer);
            
            var mrp_pricer = (20/100)*SelP;
            var mrp_cost_price = parseFloat(SelP) - parseFloat(mrp_pricer);
            
            
            


            $("#distributor_price").val(dis_real_price);
            $("#cost_price").val(mrp_cost_price);
            
            $("#sel_price").val(mrp_real_price);
            
        }

        setInterval(function(){
          //calculateDistributorPrice();
        }, 1000);
        
        
        
        

    </script>	

    <script type="text/javascript">
       
        $(document).ready(function() {
            $('.js-example-basic-single').select2();
        });
    </script>

    <script>
        
        $(document).on('submit', '#Add-Price-Batch', function(e){
        e.preventDefault(); //stop default form submission

        $("#btn-reg-outlet").attr("disabled",true);

        var formData = new FormData($(this)[0]);

        $.ajax({
            
            
                beforeSend : function() {
                    $("#progress_alert").addClass('show'); 
                },

                url:"post/add_price_batch.php",
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
                       $("#btn-reg-outlet").attr("disabled",false);
                       window.location.href = "products";
                        
                    }else{
                        $("#danger_alert").addClass('show');
                        setTimeout(function(){ $("#danger_alert").removeClass('show'); }, 1000);
                        $("#btn-reg-outlet").attr("disabled",false);
                    }
                    
                }

            });

        return false;
        });
    </script>


</body>
</html>