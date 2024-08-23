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
                                <div class="col-lg-12 card-form__body">

                                    <div class="border-bottom" style="padding: 10px;">

                                        <div class="row">
                                            
                                            
                                            <div class="col-lg-3 col-md-6 col-sm-6">
                                              <a href="pettycash" style="text-decoration: none;">
                                              <div class="card card-stats" style="background-color: #F0F8FF; border-radius: 10px;">
                                                <div class="card-body" style="padding: 0rem !important;">
                                                  <div class="row">
                                                    <div class="col-5 col-md-4">
                                                      <div class="icon-big text-center icon-warning">
                                                        <img src="assets/img/icons/box.png" style="width: 100%;">
                                                      </div>
                                                    </div>
                                                    <div class="col-7 col-md-8">
                                                      <div class="numbers">
                                                        <p class="card-category" style="font-weight: 700; color: #000; float: right;">Petty cash</p>
                                                        <p class="card-title" style="font-weight: 600; color: #000; font-size: 20px; float: right;">
                                                         
                                                        <p>
                                                      </div>
                                                    </div>
                                                  </div>
                                                </div>
                                              </div>
                                              </a>
                                            </div>

                                          
                                            <div class="col-lg-3 col-md-6 col-sm-6">
                                              <a href="expenses" style="text-decoration: none;">
                                              <div class="card card-stats" style="background-color: #F0F8FF; border-radius: 10px;">
                                                <div class="card-body" style="padding: 0rem !important;">
                                                  <div class="row">
                                                    <div class="col-5 col-md-4">
                                                      <div class="icon-big text-center icon-warning">
                                                        <img src="assets/img/icons/box.png" style="width: 100%;">
                                                      </div>
                                                    </div>
                                                    <div class="col-7 col-md-8">
                                                      <div class="numbers">
                                                        <p class="card-category" style="font-weight: 700; color: #000; float: right;">Expenses</p>
                                                        <p class="card-title" style="font-weight: 600; color: #000; font-size: 20px; float: right;">
                                                         
                                                        <p>
                                                      </div>
                                                    </div>
                                                  </div>
                                                </div>
                                              </div>
                                              </a>
                                            </div>
                                          

                                        </div>
                                        

                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        
                        
                        <h5>Petty Cash Ledger</h5>
                        <hr>
                        <div class="card card-form">
                            
                            
                            
                             <div class="table-responsive border-bottom" style="padding: 10px;">

                                        

                                        <table class="table mb-0 thead-border-top-0" id="tbl_product_view">
                                            <thead>
                                                <th>Date</th>
                                                <th>Description</th>
                                                <th>Credit</th>
                                                <th>Debit</th>
                                                <th>Balance</th>
                                            </thead>
                                            <tbody class="list">
                                               
                                               <?php
                                               
                                               $total_credits = 0;
                                               $total_debits = 0;
                                    
                                    $get_petty_cash = $conn->query("SELECT * FROM tbl_petty_cash_and_expenses WHERE status = 1");
                                    while($prs = $get_petty_cash->fetch_array()){
                                        
                                        $credit = $prs[3];
                                        $debit = $prs[4];
                                        $credit_style = "";
                                        
                                        $total_credits += $credit;
                                        $total_debits += $debit;
                                        
                                        if($credit > 0){
                                            $credit_style = "background-color:#B2FFDA";
                                        }
                                        
                                        ?>
                                        
                                        <tr style="<?php echo $credit_style?>">
                                            
                                            <td><?php echo $prs[1]?></td>
                                            <td><?php echo $prs[2]?></td>
                                            <td><?php echo number_format($credit,2)?></td>
                                            <td><?php echo number_format($debit,2)?></td>
                                            <td><?php echo number_format($prs[5],2)?></td>
                                            
                                        </tr>
                                        
                                        <?php
                                        
                                        
                                    }
                                    
                                    ?>
                                               
                                            </tbody>
                                            
                                            <tfoot>
                                                <tr style = "background-color:#FF9999">
                                                    <td style = "background-color:white"></td>
                                                    <td style = "background-color:white"></td>
                                                    <td style = "font-weight:bold;background-color:#B2FFDA;color:black;text-align:center"><?php echo number_format($total_credits,2)?></td>
                                                    <td style = "font-weight:bold;background-color:darkred;color:white;text-align:center"><?php echo number_format($total_debits,2)?></td>
                                                    <td style = "background-color:white"></td>
                                                </tr>
                                            </tfoot>
                                            
                                        </table>

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


</body>
</html>