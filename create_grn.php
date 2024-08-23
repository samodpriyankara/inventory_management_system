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
    
    
    /////////CALCULATE GRN NUMBER////////////
    
    $get_last_grn_no = $conn->query("SELECT grn_number FROM tbl_grn_details ORDER BY grn_detail_id DESC LIMIT 1");
    if($grn_no_rs = $get_last_grn_no->fetch_array()){
       
        $calculated_grn_no_part = intval(explode("/",$grn_no_rs[0])[1]) + 1;
        $calculated_grn_no = "GRN/".sprintf('%05d', $calculated_grn_no_part);
    }else{
        $calculated_grn_no = "GRN/00001";
    }
    
    ////////////////////////////////////////
    
     /////////CALCULATE GRN INVOICE NUMBER////////////
    
    $get_last_inv_no = $conn->query("SELECT invoice_number FROM tbl_grn_details ORDER BY grn_detail_id DESC LIMIT 1");
    if($inv_no_rs = $get_last_inv_no->fetch_array()){
        
        $calculated_inv_no_part = intval(explode("/",$inv_no_rs[0])[1]) + 1;
        $calculated_inv_no = "INV/".sprintf('%05d', $calculated_inv_no_part);
        
    }else{
        $calculated_inv_no = "INV/00001";
    }
    
    ////////////////////////////////////////
    
    
    
?>
<?php
    
    function rand_code($len)
    {
     $min_lenght= 0;
     $max_lenght = 100;
     // $bigL = "ABCDEFGHIJKLMNOPQRSTUVWXYZ";
     //$smallL = "abcdefghijklmnopqrstuvwxyz";
     $number = "0123456789";
     // $bigB = str_shuffle($bigL);
     //$smallS = str_shuffle($smallL);
     $numberS = str_shuffle($number);
     // $subA = substr($bigB,0,5);
     // $subB = substr($bigB,6,5);
     // $subC = substr($bigB,10,5);
     // $subD = substr($smallS,0,5);
     // $subE = substr($smallS,6,5);
     // $subF = substr($smallS,10,5);
     $subG = substr($numberS,0,5);
     $subH = substr($numberS,6,5);
     $subI = substr($numberS,10,5);
     $RandCode1 = str_shuffle($subH.$subI.$subG);
     // $RandCode1 = str_shuffle($subA.$subH.$subC.$subI.$subB.$subG);
     $RandCode2 = str_shuffle($RandCode1);
     $RandCode = $RandCode1.$RandCode2;
     if ($len>$min_lenght && $len<$max_lenght)
     {
     $CodeEX = substr($RandCode,0,$len);
     }
     else
     {
     $CodeEX = $RandCode;
     }
     return $CodeEX;
    }
    
?>

<?php
    $getGRNDetailsQuery=$conn->query("SELECT COUNT(*) FROM tbl_grn_details WHERE stat='0' ORDER BY grn_detail_id DESC LIMIT 1");
    if ($GgrnQ=$getGRNDetailsQuery->fetch_array()) {
        $GRNOngoingCount=$GgrnQ[0];
    }
?>
<?php if($GRNOngoingCount=='0'){ ?>
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
                                    <p><strong class="headings-color">Create Goods Received Note (GRN)</strong></p>
                                    <p class="text-muted">Edit your account details and settings.</p>
                                </div>
                                <form id="Create-GRN-Details" class="col-lg-8 card-form__body card-body">
                                    <div class="row">
                                        <div class="col">
                                            <div class="form-group">
                                                <label>Supplier Name</label>
                                                <select class="js-example-basic-single custom-select" name="supplier_id" required>
                                                    <option value="" selected disabled>Select Supplier Name</option>
                                                    <?php
                                                        $getDataForDate=$conn->query("SELECT * FROM tbl_supplier");
                                                        while ($row=$getDataForDate->fetch_array()) {
                                                    ?>
                                                        <option value="<?php echo $row[0];?>"><?php echo $row[1];?></option>
                                                    <?php } ?>
                                                </select> 
                                            </div>
                                        </div>
                                        <div class="col">
                                            <div class="form-group">
                                                <label>Invoice Number</label>
                                                <input type="text" class="form-control" value="<?php echo $calculated_inv_no;?>" name="invoice_number" id="invoice_number" placeholder="Invoice Number" required>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col">
                                            <div class="form-group">
                                                <label>GRN Number</label>
                                                <input type="text" class="form-control" value="<?php echo $calculated_grn_no; ?>" name="grn_number" id="grn_number" placeholder="GRN Number" readonly required>
                                            </div>
                                        </div>
                                        <div class="col">
                                            <div class="form-group">
                                                <label>Goods Received Date</label>
                                                <input type="date" class="form-control" value="<?php echo date('Y-m-d') ?>" name="goods_received_date" id="goods_received_date" placeholder="Goods Received Date" required>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label>Note</label>
                                        <textarea class="form-control" value="" name="note" rows="5"></textarea>
                                    </div>
                                        
                                    <div class="text-right mb-5">
                                        <button type="submit" id="register" class="btn btn-primary">Create GRN</button>
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
        
        $(document).on('submit', '#Create-GRN-Details', function(e){
        e.preventDefault(); //stop default form submission
        var formData = new FormData($(this)[0]);

        $.ajax({

                beforeSend : function() {
                    $("#progress_alert").addClass('show');
                },

                url:"grn_post/submit_grn_details.php",
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
                       var j_id = json.j_id;
                       window.location.href = "grn_invoice?g="+btoa(j_id);
                        
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

<?php }else{ ?>

<?php
    $getGRNDetailsOngoingIdQuery=$conn->query("SELECT grn_detail_id FROM tbl_grn_details WHERE stat='0' ORDER BY grn_detail_id DESC LIMIT 1");
    if ($GgrnDOrs=$getGRNDetailsOngoingIdQuery->fetch_array()) {
        $GRNDetailId=$GgrnDOrs[0];
    }
?>
    <script>
        window.location.href = "grn_invoice?g=<?php echo(base64_encode($GRNDetailId)) ?>";
    </script>

<?php }?>
