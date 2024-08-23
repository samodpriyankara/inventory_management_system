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
                            <h3>Payment Collection Summary</h3>
                        </div>
                    </div>

                        <div class="card card-form">
                            <div class="row no-gutters">
                                <div class="col-lg-12 card-form__body">

                                    <div class="border-bottom" style="padding: 10px;">

                                        
                                        <div class="row">
                                            <div class="col-md-4">
                                                <label class="form-label">Start Date *</label>
                                                <input type="date" id="income-start-date" value="<?php echo date('Y-m-d'); ?>" class="form-control" min="0" placeholder="Start Date" required>
                                            </div>
                                            <div class="col-md-4">
                                                <label class="form-label">End Date *</label>
                                                <input type="date" id="income-end-date" value="<?php echo date('Y-m-d'); ?>" class="form-control" min="0" placeholder="Start Date" required>
                                            </div>
                                            <div class="col-md-4">
                                                <label class="form-label">Select Distributor</label>
                                                <select class="form-control" id="debtor-reports-distributor-id" onchange="onDistributorChangeSalesRep()">
                                                  <option value="0">Only All Distributors</option>
                                                  <?php
                                                      $getDisQuery=$conn->query("SELECT td.distributor_id,td.name FROM tbl_distributor td ORDER BY td.name ASC");
                                                      while ($dis=$getDisQuery->fetch_array()) {
                                                  ?>
                                                    <option value=<?php echo $dis[0];?>><?php echo $dis[1];?></option>
                                                  <?php } ?>
                                                </select>
                                                
                                            </div>
                                                                         
                                            <div class="col-md-12"><br>
                                                <button type="submit" id="btn-get-income" class="btn btn-primary">Get Income</button>
                                            </div>
                                        </div>
                                           
                                        <hr>


                                        <div class="row">
                                          <div class="col-lg-3 col-md-6 col-sm-6">
                                            <div class="card card-stats" style="background-color: #F0F8FF; border-radius: 10px;">
                                              <div class="card-body" style="padding: 0rem !important;">
                                                <div class="row">
                                                  <div class="col-5 col-md-4">
                                                    <div class="icon-big text-center icon-warning">
                                                      <!-- <i class="nc-icon nc-favourite-28 text-primary"></i> -->
                                                      <img src="assets/img/icons/money-bag.png" style="width: 120%;">
                                                    </div>
                                                  </div>
                                                  <div class="col-7 col-md-8">
                                                    <div class="numbers">
                                                      <p class="card-category" style="color: #000; float: right;">Sales Invoice (Cash)</p>
                                                      <p class="card-title" id="lbl-full-income" style="font-weight: 600; font-size: 20px; float: right;">Rs. 0.00<p>
                                                    </div>
                                                  </div>
                                                </div>
                                              </div>
                                            </div>
                                          </div>
                                          
                                          <div class="col-lg-3 col-md-6 col-sm-6">
                                            <div class="card card-stats" style="background-color: #F0F8FF; border-radius: 10px;">
                                              <div class="card-body" style="padding: 0rem !important;">
                                                <div class="row">
                                                  <div class="col-5 col-md-4">
                                                    <div class="icon-big text-center icon-warning">
                                                      <!-- <i class="nc-icon nc-favourite-28 text-primary"></i> -->
                                                      <img src="assets/img/icons/credit-card.png" style="width: 120%;">
                                                    </div>
                                                  </div>
                                                  <div class="col-7 col-md-8">
                                                    <div class="numbers">
                                                      <p class="card-category" style="color: #000; float: right;">Cash Collection</p>
                                                      <p class="card-title" id="lbl-collection-income" style="font-weight: 600; font-size: 20px; float: right;">Rs. 0.00<p>
                                                    </div>
                                                  </div>
                                                </div>
                                              </div>
                                            </div>
                                          </div>
                                          
                                          <div class="col-lg-3 col-md-6 col-sm-6">
                                            <div class="card card-stats" style="background-color: #F0F8FF; border-radius: 10px;">
                                              <div class="card-body" style="padding: 0rem !important;">
                                                <div class="row">
                                                  <div class="col-5 col-md-4">
                                                    <div class="icon-big text-center icon-warning">
                                                      <!-- <i class="nc-icon nc-favourite-28 text-primary"></i> -->
                                                      <img src="assets/img/icons/cheque1.png" style="width: 120%;">
                                                    </div>
                                                  </div>
                                                  <div class="col-7 col-md-8">
                                                    <div class="numbers">
                                                      <p class="card-category" style="color: #000; float: right;">Cheque Realized Collection</p>
                                                      <p class="card-title" id="lbl-collection-cheque-income" style="font-weight: 600; font-size: 20px; float: right;">Rs. 0.00<p>
                                                    </div>
                                                  </div>
                                                </div>
                                              </div>
                                            </div>
                                          </div>
                                          
                                          <div class="col-lg-3 col-md-6 col-sm-6">
                                            <div class="card card-stats" style="background-color: #F0F8FF; border-radius: 10px;">
                                              <div class="card-body" style="padding: 0rem !important;">
                                                <div class="row">
                                                  <div class="col-5 col-md-4">
                                                    <div class="icon-big text-center icon-warning">
                                                      <!-- <i class="nc-icon nc-favourite-28 text-primary"></i> -->
                                                      <img src="assets/img/icons/profits.png" style="width: 120%;">
                                                    </div>
                                                  </div>
                                                  <div class="col-7 col-md-8">
                                                    <div class="numbers">
                                                      <p class="card-category" style="color: #000; float: right;">Total Collection</p>
                                                      <p class="card-title" id="lbl-today-income" style="font-weight: 600; font-size: 20px; float: right;">Rs. 0.00<p>
                                                    </div>
                                                  </div>
                                                </div>
                                              </div>
                                            </div>
                                          </div>
                                          <div class="col-lg-3 col-md-6 col-sm-6">
                                            <div class="card card-stats" style="background-color: #F0F8FF; border-radius: 10px;">
                                              <div class="card-body" style="padding: 0rem !important;">
                                                <div class="row">
                                                  <div class="col-5 col-md-4">
                                                    <div class="icon-big text-center icon-warning">
                                                      <!-- <i class="nc-icon nc-favourite-28 text-primary"></i> -->
                                                      <img src="assets/img/icons/invoice.png" style="width: 120%;">
                                                    </div>
                                                  </div>
                                                  <div class="col-7 col-md-8">
                                                    <div class="numbers">
                                                      <p class="card-category" style="color: #000; float: right;">Invoice / Collection</p>
                                                      <p class="card-title" style="font-weight: 600; font-size: 20px; float: right;">Count <span id="lbl-all-bill-count">0 / 0</span><p>
                                                    </div>
                                                  </div>
                                                </div>
                                              </div>
                                            </div>
                                          </div>
                                        </div>


                                        

                                        <div class="col-md-12">
                                            <h3 class="card-title">Collection Report</h3>
                                            <div class="table-responsive">
                                                <table class="table m-b-0" id="cheque-detail-tbl" style="width: 100%;">
                                                    <thead class="thead-dark">
                                                        <tr>
                                                            <th>Invoice Number</th>
                                                            <th>Payment Method</th>
                                                            <th>Date Time</th>
                                                            <th class="text-right">Amount</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody id="cheque-sumary-area">
                                                            
                                                    </tbody>
                                                    <!--<tfoot id="-area">-->
                                                            
                                                    <!--</tfoot>-->
                                                </table>
                                            </div>
                                            <br><br>
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

  <script src="https://cdn.datatables.net/buttons/2.1.0/js/dataTables.buttons.min.js"></script>
	<script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>
	<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/pdfmake.min.js"></script>
	<script src="https://cdn.datatables.net/buttons/2.1.0/js/buttons.html5.min.js"></script>
	<script src="https://cdn.datatables.net/buttons/2.1.0/js/buttons.print.min.js"></script>

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <!-- Chart JS -->
    <script src="assets/js/plugins/chartjs.min.js"></script>

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


    <!------------------ Start Total Income---------------------------------------->
    <script type="text/javascript">

            $(document).ready(function () {

            //   loadChart(0,0,0);
            
                var incometbl = null;

                $("#btn-get-income").click(function(){

                    var startDate = $("#income-start-date").val();
                    var endDate = $("#income-end-date").val();
                    var disid = $("#debtor-reports-distributor-id").val();
                    
                    if(incometbl == null){
                        
                    }else{
                                                    
                            incometbl.clear().draw();
                            incometbl.destroy();
                    }

                    $.ajax({

                        url:'analytics/get_collection_check.php',
                        type:'POST',
                        data:{
                            start_date:startDate,
                            end_date:endDate,
                            dis_id:disid
                        },
                        beforeSend:function(){
                            Swal.fire({
                              text: "Please wait...",
                              imageUrl:"assets/img/income.gif",
                              showConfirmButton: false,
                              allowOutsideClick: false
                            });
                        },
                        success:function(data){
                            var json = JSON.parse(data);
                            var full_income = 0.00;
                            var collection_income = 0.00;
                            var today_income = 0.00;
                            var cheque_realized_amount = 0.00;
                            var today_income = 0 / 0;
                            
                            if(json.result){
                                
                                if(json.full_income != 'null'){
                                    full_income = json.full_income;
                                }else{
                                    full_income = 0.00;
                                }
                                if(json.collection_income != 'null'){
                                    collection_income = json.collection_income;
                                }else{
                                    collection_income = 0.00;
                                }
                                if(json.today_income != 'null'){
                                    today_income = json.today_income;
                                }else{
                                    today_income = 0.00;
                                }
                                if(json.invoice_collection_count != 'null'){
                                    invoice_collection_count = json.invoice_collection_count;
                                }else{
                                    invoice_collection_count = 0 / 0;
                                }
                                if(json.cheque_realized_amount != 'null'){
                                    cheque_realized_amount = json.cheque_realized_amount;
                                }else{
                                    cheque_realized_amount = 0 / 0;
                                }

                                ///////Cheque Summery Details Area//////////
                                $("#cheque-sumary-area").html(json.data_collection);
                                incometbl = $('#cheque-detail-tbl').DataTable({ 
                                      "order": [[ 0, "asc" ]],
                                      searching: false,
                                      "destroy": true, //use for reinitialize datatable
                                        dom: 'Bfrtip',
                                        buttons: [
                                                    {
                                                        extend: 'print',
                                                        title: 'Cheque Summery (P&L - Using This Software)',
                                                        exportOptions: {
                                                            columns: ':visible'
                                                        }
                                                    },
                                                    {
                                                        extend: 'copy',
                                                        title: 'Cheque Summery (P&L - Using This Software)',
                                                        exportOptions: {
                                                            columns: ':visible'
                                                        }
                                                    },
                                                    {
                                                        extend: 'excelHtml5',
                                                        title: 'Cheque Summery (P&L - Using This Software)',
                                                        exportOptions: {
                                                            columns: ':visible'
                                                        }
                                                    },
                                                    {
                                                        extend: 'pdfHtml5',
                                                        title: 'Cheque Summery (P&L - Using This Software)',
                                                        exportOptions: {
                                                            columns: ':visible'
                                                        }
                                                    },
                                                    'colvis'
                                                ]
                                });
                                ///////////////////
                                
                                $("#lbl-full-income").html('Rs. '+parseFloat(full_income).toFixed(2));
                                $("#lbl-collection-income").html('Rs. '+parseFloat(collection_income).toFixed(2));
                                $("#lbl-today-income").html('Rs. '+parseFloat(today_income).toFixed(2));
                                $("#lbl-collection-cheque-income").html('Rs. '+parseFloat(cheque_realized_amount).toFixed(2));
                                $("#lbl-all-bill-count").html(invoice_collection_count);

                               
                            }

                            Swal.close();
                        },
                        error:function(err){
                            console.log(err);
                        }


                    });



                });




            } );
       
        </script>


      

        <!------------------ End Total Income---------------------------------------->

</body>
</html>