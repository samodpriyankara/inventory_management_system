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
                    
                    <!-- <h3>All Outlet</h3>
                    <div class="pull-right">
                      <a href="register_outlet" class="btn btn-primary" style="color: #FFF;">Register Outlet</a>
                    </div> -->
                    <div class="row">
                        <div class="col-md-6">
                            <h3>Cheque History</h3>
                        </div>
                        <!-- <div class="col-md-6">
                            <div class="pull-right" style="float: right;">
                                <a href="create_invoice" class="btn btn-primary">Create Invoice</a>
                            </div>
                        </div> -->
                    </div>

                        <div class="card card-form">
                            <div class="row no-gutters">
                                <!-- <div class="col-lg-4 card-body">
                                    <p><strong class="headings-color">Search</strong></p>
                                    <p class="text-muted">Add search functionality to your tables with List.js. Please read the <a href="http://listjs.com/" target="_blank">official plugin documentation</a> for a full list of options.</p>
                                </div> -->
                                <div class="col-lg-12 card-form__body">

                                    <div class="table-responsive border-bottom" style="padding: 10px;">

                                        <!-- <div class="search-form search-form--light m-3">
                                            <input type="text" class="form-control search" placeholder="Search">
                                            <button class="btn" type="button" role="button"><i class="material-icons">search</i></button>
                                        </div> -->

                                        <style>
                                            .modal-backdrop {
                                                display: none;    
                                            }
                                        </style>

                                        <table class="table mb-0 thead-border-top-0" id="ChequeTable">
                                            <thead>
                                                <tr>
                                                    <th style="display: none;"></th>
                                                    <th>Invoice Number</th>
                                                    <th>Outlet Name</th>
                                                    <th>Cheque Number</th>
                                                    <th>Bank</th>
                                                    <th>Deposit Date</th>
                                                    <th>Cheque Collect Date</th>
                                                    <th>Cheque Realized Date</th>
                                                    <th class="text-right">Cheque Amount</th>
                                                    <th></th>
                                                </tr>
                                            </thead>
                                            <tbody class="list">
                                                <?php
                                                    if($is_distributor){
                                                        $ChequePaymentDetailsSql = "SELECT * FROM tbl_order_cheque_payment_details tocpd INNER JOIN tbl_order tor ON tocpd.invoice_id=tor.id ORDER BY tocpd.id DESC";
                                                    }else{
                                                        $ChequePaymentDetailsSql = "SELECT * FROM tbl_order_cheque_payment_details tocpd INNER JOIN tbl_order tor ON tocpd.invoice_id=tor.id ORDER BY tocpd.id DESC";
                                                    }

                                                        $ChequePaymentDetailsRs=$conn->query($ChequePaymentDetailsSql);
                                                        while($CPDrow=$ChequePaymentDetailsRs->fetch_array())
                                                        {
                                                            $ChequeId=$CPDrow[0];                       
                                                            $ChequeNumber=$CPDrow[1];   
                                                            $ChequeBank=$CPDrow[2];     
                                                            $ChequeDateToCash=$CPDrow[3];   
                                                            $ChequeAmount=$CPDrow[4];   
                                                            $ChequeIsCleared=$CPDrow[5];    
                                                            $ChequeAddedDate=$CPDrow[6];    
                                                            $ChequeInvoiceId=$CPDrow[7];    
                                                            $ChequeAddedUserId=$CPDrow[8];    
                                                            $ChequePaymentHistoryId=$CPDrow[9];  

                                                            //////
                                                            $InvoiceNumber=$CPDrow[11];
                                                            $OutletId=$CPDrow[25];
                                                            
                                                            $OutletDetailsSql = "SELECT * FROM tbl_outlet WHERE outlet_id='$OutletId' ";
                                                            $OutletDetailsRs=$conn->query($OutletDetailsSql);
                                                            if($ODrow=$OutletDetailsRs->fetch_array())
                                                            {
                                                                $OutletName=$ODrow[1];     
                                                            }
                                                            
                                                            
                                                            

                                                ?>
                                                <tr <?php if($ChequeIsCleared=='0'){?> style="color: #4a4a4a;" <?php }else{ ?> style="color: #26580F;" <?php }?> >
                                                    <td style="display: none;"><?php echo $ChequeId; ?></td>
                                                    <td><?php echo $InvoiceNumber; ?></td>
                                                    <td><?php echo $OutletName; ?></td>
                                                    <td><?php echo $ChequeNumber; ?></td>
                                                    <td><?php echo $ChequeBank; ?></td>
                                                    <td><?php echo $ChequeDateToCash; ?></td>
                                                    <td><?php echo $ChequeAddedDate; ?></td>
                                                    <td>
                                                        <?php if($ChequeIsCleared=='0'){ ?>
                                                            -
                                                        <?php }else{ 

                                                            $RealizedSql = "SELECT * FROM tbl_order_cheque_payment_realized_date WHERE cheque_id='$ChequeId' ";
                                                            $RRs=$conn->query($RealizedSql);
                                                            if($RRrow=$RRs->fetch_array())
                                                            {
                                                                $ChequeRealizedDate=$RRrow[2];
                                                            }
                                                            echo $ChequeRealizedDate;

                                                        } ?>
                                                    </td>
                                                    <td><font style="float: right; font-weight: 700;">Rs. <?php echo number_format($ChequeAmount,2); ?></font></td>

                                                    <td>
                                                        <a href="#!" data-toggle="modal" data-target="#ChequeRealizedDate<?php echo $ChequeId; ?>" class="btn btn-primary btn-sm" style="color: #FFF;">Realized</a>
                                                    </td>
                                                </tr>

                                                        <!-- Modal -->
                                                        <div class="modal fade" id="ChequeRealizedDate<?php echo $ChequeId; ?>" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
                                                          <div class="modal-dialog modal-dialog-centered" role="document">
                                                            <div class="modal-content">
                                                              <div class="modal-header">
                                                                <h5 class="modal-title" id="exampleModalLongTitle"><?php echo $ChequeNumber; ?> Realized Confirm</h5>
                                                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                                  <span aria-hidden="true">&times;</span>
                                                                </button>
                                                              </div>

                                                              <form id="Cheque-Realized" method="POST">
                                                                  <div class="modal-body">
                                                                        <input type="hidden" class="form-control" value="<?php echo $ChequeId; ?>" name="cheque_id" id="cheque_id">
                                                                        <div class="form-group">
                                                                            <label>Cheque Realized Date</label>
                                                                            <input type="date" class="form-control" name="cheque_realized_date" id="cheque_realized_date">
                                                                        </div>
                                                                    
                                                                  </div>
                                                                  <div class="modal-footer">
                                                                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                                                    <button type="submit" class="btn btn-primary">Save changes</button>
                                                                  </div>
                                                              </form>

                                                            </div>
                                                          </div>
                                                        </div>


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

        <?php include_once('controls/sidebar.php'); ?>
    <!-- // END drawer-layout -->

    

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

    <!-- List.js -->
    <script src="assets/vendor/list.min.js"></script>
    <script src="assets/js/list.js"></script>

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
        $(document).ready( function () {
            $('#ChequeTable').DataTable();
        } );
    </script>

    

    <!-------------------------Add Check Realized--------------------------------------------->
    <script>
          
        $(document).on('submit', '#Cheque-Realized', function(e){
        e.preventDefault(); //stop default form submission

        // $("#btn-add-discount").attr("disabled",true);

        var formData = new FormData($(this)[0]);

        $.ajax({
            
            
                beforeSend : function() {

                $("#progress_alert").addClass('show'); 

                },

                url:"post/cheque_realized.php",
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
                       // $("#btn-add-discount").attr("disabled",false);
                       location.reload();
                        
                    }else{
                        $("#danger_alert").addClass('show');
                        setTimeout(function(){ $("#danger_alert").removeClass('show'); }, 1000);
                        // $("#btn-add-discount").attr("disabled",false);
                    }
                    
                }

            });

        return false;
        });
    </script>

</body>
</html>