<?php
    session_start();
    require 'database/db.php';
    $db=new DB();
    $conn=$db->connect();
    date_default_timezone_set('Asia/Colombo');
    $currentDate=date('Y-m-d');
    // $currentDate=date('2022-10-29');

    $is_distributor = false;
    $user_id = 0;
    $account_type = -1;
    
    $current_year = date('Y');
    $current_month = date('m');
    
    
  
    
    
    
    

    if(empty($_SESSION['user'])){
        header('Location:index.php');
    }
    if(isset($_SESSION['DISTRIBUTOR'])){
      $is_distributor = $_SESSION['DISTRIBUTOR'];
    }
    if(isset($_SESSION['ID'])){
      $user_id = $_SESSION['ID'];
    }

    if(isset($_SESSION['ACCOUNT_TYPE'])){
      $account_type = $_SESSION['ACCOUNT_TYPE'];//dist_id (0=super admin | 1=distributor | 2=accountant etc... )
    }
    
    
     ////////////get user details/////
        if(isset($_SERVER['HTTP_USER_AGENT'])){
             $date_time = date('Y-m-d H:i:s');
             $user_agent = "DASHBOARD : ".htmlspecialchars($_SERVER['HTTP_USER_AGENT']);
             $conn->query("INSERT INTO tbl_user_agent_details VALUES(null,'$user_agent','$date_time','$user_id')");
         }
    /////////////////////////////////
    
    
    
    
    
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
                            
                            
                            <div class="col-md">
                                <div class="card card-stats">
                                    <div class="d-flex align-items-center mb-2">
                                        <div class="card-header__title flex">Total Products</div>
                                        <?php
                                            $items_count = 0;
                                            $get_item_count = $conn->query("SELECT COUNT(*) FROM tbl_item");
                                            if($crs = $get_item_count->fetch_array()){
                                                $items_count = $crs[0];
                                            }
                                          
                                        ?>
                                        <strong class="text-primary"><?php echo $items_count; ?></strong>
                                    </div>
                                    <div class="position-relative d-flex align-items-start z-0">
                                        <div class="progress flex" style="height: 4px;">
                                            <div class="progress-bar bg-primary" role="progressbar" style="width: 100%;" aria-valuenow="100" aria-valuemin="0" aria-valuemax="100"></div>
                                        </div>
                                        <i class="material-icons text-primary bg-white position-absolute" style="right: -4px; top: -10px; z-index: 2;">attach_money</i>
                                    </div>
                                </div>
                            </div>
                            
                            
                            
                            
                            
                            <div class="col-md">
                                <div class="card card-stats">
                                    <div class="d-flex align-items-center mb-2">
                                        <div class="card-header__title flex">Total Routes</div>
                                        <?php
                                          $RouteCountSQL = "SELECT COUNT(*) FROM tbl_route";
                                          $RouteCountResult = mysqli_query($conn, $RouteCountSQL);
                                          $RouteCount = mysqli_fetch_assoc($RouteCountResult)['COUNT(*)'];
                                          // echo $RouteCount;
                                        ?>
                                        <strong class="text-primary"><?php echo $RouteCount; ?></strong>
                                    </div>
                                    <div class="position-relative d-flex align-items-start z-0">
                                        <div class="progress flex" style="height: 4px;">
                                            <div class="progress-bar bg-primary" role="progressbar" style="width: 100%;" aria-valuenow="100" aria-valuemin="0" aria-valuemax="100"></div>
                                        </div>
                                        <i class="material-icons text-primary bg-white position-absolute" style="right: -4px; top: -10px; z-index: 2;">map</i>
                                    </div>
                                </div>
                            </div>
                            
                            <?php if($is_distributor){  }else{ ?>
                            <div class="col-md">
                                <div class="card card-stats">
                                    <div class="d-flex align-items-center mb-2">
                                        <div class="card-header__title flex">Total Distributors</div>
                                        <?php
                                            $dist_count = 0;
                                            $get_dist_count = $conn->query("SELECT COUNT(*) FROM tbl_distributor");
                                            if($dcrs = $get_dist_count->fetch_array()){
                                                $dist_count = $dcrs[0];
                                            }
                                          
                                        ?>
                                        <strong class="text-primary"><?php echo $dist_count; ?></strong>
                                    </div>
                                    <div class="position-relative d-flex align-items-start z-0">
                                        <div class="progress flex" style="height: 4px;">
                                            <div class="progress-bar bg-primary" role="progressbar" style="width: 100%;" aria-valuenow="100" aria-valuemin="0" aria-valuemax="100"></div>
                                        </div>
                                        <i class="material-icons text-primary bg-white position-absolute" style="right: -4px; top: -10px; z-index: 2;">attach_money</i>
                                    </div>
                                </div>
                            </div>
                            <?php } ?>
                            
                            
                            
                            
                            
                            
                            
                            
                        </div>
                        
                        
                        
                        <div class="row">
                            
                            
                            
                            
                            <div class="col-md">
                                <div class="card card-stats">
                                    <div class="d-flex align-items-center mb-2">
                                        <div class="card-header__title flex">Total Cheque Payments</div>
                                        <?php
                                          $chk_amount = 0.00;
                                          
                                          if($is_distributor){
                                              
                                            $get_cheque_sum = $conn->query("SELECT SUM(tocpd.amount) FROM tbl_order_cheque_payment_details tocpd INNER JOIN tbl_order tor ON tocpd.invoice_id=tor.id WHERE tor.distributor_id='$user_id' AND YEAR(tocpd.added_date) = '$current_year' AND MONTH(tocpd.added_date)='$current_month' ");
                                            
                                          }else{
                                              $get_cheque_sum = $conn->query("SELECT SUM(amount) FROM tbl_order_cheque_payment_details WHERE YEAR(added_date) = '$current_year' AND MONTH(added_date)='$current_month'");
                                          }
                                          
                                          
                                          if($chksrs = $get_cheque_sum->fetch_array()){
                                              $chk_amount = $chksrs[0];
                                          }
                                          
                                        ?>
                                        <strong class="text-danger">Rs. <?php echo number_format($chk_amount,2); ?></strong>
                                    </div>
                                    <div class="position-relative d-flex align-items-start z-0">
                                        <div class="progress flex" style="height: 4px;">
                                            <div class="progress-bar bg-danger" role="progressbar" style="width: 100%;" aria-valuenow="100" aria-valuemin="0" aria-valuemax="100"></div>
                                        </div>
                                        <i class="material-icons text-danger bg-white position-absolute" style="right: -4px; top: -10px; z-index: 2;">attach_money</i>
                                    </div>
                                </div>
                            </div>
                            
                            
                            
                            
                            
                            
                            
                            <div class="col-md">
                                <div class="card card-stats">
                                    <div class="d-flex align-items-center mb-2">
                                        <div class="card-header__title flex">Cash Sales</div>
                                        <?php
                                            
                                            
                                            $cash_income = 0.00;
                                            
                                            if($is_distributor){
                                                $get_cash_invoices = $conn->query("SELECT id FROM tbl_order WHERE distributor_id='$user_id' AND (payment_method='0' OR payment_method='1') AND YEAR(invoice_date) = '$current_year' AND MONTH(invoice_date)='$current_month'");
                                            }else{
                                                $get_cash_invoices = $conn->query("SELECT id FROM tbl_order WHERE (payment_method='0' OR payment_method='1') AND YEAR(invoice_date) = '$current_year' AND MONTH(invoice_date)='$current_month'");
                                            }
                                            
                                            
                                          
                                            while($ci_rs = $get_cash_invoices->fetch_array()){
                                                
                                                $invoice_id = $ci_rs[0];
                                                $total_order_value = 0;
                                                
                                                
                                                $get_order_details = $conn->query("SELECT * FROM tbl_order_item_details WHERE order_id = '$invoice_id'");
                                                while($od_rs = $get_order_details->fetch_array()){
                                                    
                                                    $qty = $od_rs[3];
                                                    $price = $od_rs[6];
                                                    
                                                    $total_order_value += ($qty * $price); 
                                                    
                                                    
                                                    
                                                }
                                                
                                                
                                                $cash_income += $total_order_value;
                                                
                                                
                                            }
                                            
                                          
                                        ?>
                                        <strong class="text-primary">Rs. <?php echo number_format($cash_income,2); ?></strong>
                                    </div>
                                    <div class="position-relative d-flex align-items-start z-0">
                                        <div class="progress flex" style="height: 4px;">
                                            <div class="progress-bar bg-primary" role="progressbar" style="width: 100%;" aria-valuenow="100" aria-valuemin="0" aria-valuemax="100"></div>
                                        </div>
                                        <i class="material-icons text-primary bg-white position-absolute" style="right: -4px; top: -10px; z-index: 2;">attach_money</i>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="col-md">
                                <div class="card card-stats">
                                    <div class="d-flex align-items-center mb-2">
                                        <div class="card-header__title flex">Credit Sales</div>
                                        <?php
                                            $credit_income = 0.00;
                                            
                                            if($is_distributor){
                                                $get_credit_invoices = $conn->query("SELECT id FROM tbl_order WHERE distributor_id='$user_id' AND payment_method = 2 AND YEAR(invoice_date) = '$current_year' AND MONTH(invoice_date)='$current_month'");
                                            }else{
                                                $get_credit_invoices = $conn->query("SELECT id FROM tbl_order WHERE payment_method = 2 AND YEAR(invoice_date) = '$current_year' AND MONTH(invoice_date)='$current_month'");
                                            }
                                            
                                            
                                           
                                            while($ci_rs = $get_credit_invoices->fetch_array()){
                                                
                                                $invoice_id = $ci_rs[0];
                                                $total_credit_order_value = 0;
                                                
                                                
                                                $get_order_details = $conn->query("SELECT * FROM tbl_order_item_details WHERE order_id = '$invoice_id'");
                                                while($od_rs = $get_order_details->fetch_array()){
                                                    
                                                    $qty = $od_rs[3];
                                                    $price = $od_rs[6];
                                                    
                                                    $total_credit_order_value += ($qty * $price); 
                                                    
                                                    
                                                    
                                                }
                                                
                                                
                                                $credit_income += $total_credit_order_value;
                                                
                                                
                                            }
                                            
                                          
                                        ?>
                                        <strong class="text-primary">Rs. <?php echo number_format($credit_income,2); ?></strong>
                                    </div>
                                    <div class="position-relative d-flex align-items-start z-0">
                                        <div class="progress flex" style="height: 4px;">
                                            <div class="progress-bar bg-primary" role="progressbar" style="width: 100%;" aria-valuenow="100" aria-valuemin="0" aria-valuemax="100"></div>
                                        </div>
                                        <i class="material-icons text-primary bg-white position-absolute" style="right: -4px; top: -10px; z-index: 2;">attach_money</i>
                                    </div>
                                </div>
                            </div>
                            
                            
                           
                            
                            
                            
                            
                            
                            
                            
                            
                            
                        </div>
                        
                        
                        <div class="row">
                            
                            
                             <div class="col-md">
                                <div class="card card-stats">
                                    <div class="d-flex align-items-center mb-2">
                                        <div class="card-header__title flex">Today Sales</div>
                                        <?php

                                            if($is_distributor){
                                              
                                              $GetCashInvoicesql = "SELECT * FROM tbl_order_item_details toid INNER JOIN tbl_order tor ON toid.order_id=tor.id WHERE tor.payment_status='1' AND tor.distributor_id = '$user_id' AND tor.invoice_date LIKE '%$currentDate%' ";
                                            }else{
                                              $GetCashInvoicesql = "SELECT * FROM tbl_order_item_details toid INNER JOIN tbl_order tor ON toid.order_id=tor.id WHERE tor.payment_status='1' AND tor.invoice_date LIKE '%$currentDate%' ";
                                            }


                                              
                                              
                                              $CashInvoicers=$conn->query($GetCashInvoicesql);
                                              $TodayCashIncome=0;
                                              while($Casrow =$CashInvoicers->fetch_array())
                                              {
                                                $ProductDiscountedValue=$Casrow[5];   
                                                $ItemPrice=$Casrow[6]; 
                                                $ProductQty=$Casrow[3];  

                                                  ////////Calculation//////////////
                                                  $DiscountedPrice = (double)$ItemPrice-(((double)$ItemPrice*(double)$ProductDiscountedValue)/100);
                                                  //With QTY
                                                  $ItemTotal = (double)$DiscountedPrice*(double)$ProductQty;
                                                  //Grand Total
                                                  $TodayCashIncome += $ItemTotal;
                                                  ////////Calculation//////////////
                                              }
                                        ?>
                                        <strong class="text-primary">Rs. <?php echo number_format($TodayCashIncome,2); ?></strong>
                                    </div>
                                    <div class="position-relative d-flex align-items-start z-0">
                                        <div class="progress flex" style="height: 4px;">
                                            <div class="progress-bar bg-primary" role="progressbar" style="width: 100%;" aria-valuenow="100" aria-valuemin="0" aria-valuemax="100"></div>
                                        </div>
                                        <i class="material-icons text-primary bg-white position-absolute" style="right: -4px; top: -10px; z-index: 2;">attach_money</i>
                                    </div>
                                </div>
                            </div>
                            
                            
                            <div class="col-md">
                                <div class="card card-stats">
                                    <div class="d-flex align-items-center mb-2">
                                        <div class="card-header__title flex">Total Sales</div>
                                        <?php
                                            $total_income = 0.00;
                                            
                                            if($is_distributor){
                                                
                                                $get_total_invoices = $conn->query("SELECT id FROM tbl_order WHERE distributor_id = '$user_id' AND (payment_method='0' OR payment_method='1' OR payment_method='2') AND YEAR(invoice_date) = '$current_year' AND MONTH(invoice_date)='$current_month'");
                                                
                                            }else{
                                                $get_total_invoices = $conn->query("SELECT id FROM tbl_order WHERE (payment_method='0' OR payment_method='1' OR payment_method='2') AND YEAR(invoice_date) = '$current_year' AND MONTH(invoice_date)='$current_month'");
                                            }
                                            
                                            
                                            
                                            
                                            while($ci_rs = $get_total_invoices->fetch_array()){
                                                
                                                $invoice_id = $ci_rs[0];
                                                $total_order_value = 0;
                                                
                                                
                                                $get_order_details = $conn->query("SELECT * FROM tbl_order_item_details WHERE order_id = '$invoice_id'");
                                                while($od_rs = $get_order_details->fetch_array()){
                                                    
                                                    $qty = $od_rs[3];
                                                    $price = $od_rs[6];
                                                    
                                                    $total_order_value += ($qty * $price); 
                                                    
                                                    
                                                    
                                                }
                                                
                                                
                                                $total_income += $total_order_value;
                                                
                                                
                                            }
                                            
                                          
                                        ?>
                                        <strong class="text-warning">Rs. <?php echo number_format($total_income,2); ?></strong>
                                    </div>
                                    <div class="position-relative d-flex align-items-start z-0">
                                        <div class="progress flex" style="height: 4px;">
                                            <div class="progress-bar bg-warning" role="progressbar" style="width: 100%;" aria-valuenow="100" aria-valuemin="0" aria-valuemax="100"></div>
                                        </div>
                                        <i class="material-icons text-warning bg-white position-absolute" style="right: -4px; top: -10px; z-index: 2;">attach_money</i>
                                    </div>
                                </div>
                            </div>
                            
                            
                            
                            <div class="col-md">
                                <div class="card card-stats">
                                    <div class="d-flex align-items-center mb-2">
                                        <div class="card-header__title flex">Total Payments Collections</div>
                                        <?php
                                            $RealPaymentCollection = 0.00;
                                            $GetOutstandingPayments = 0.00;
                                            
                                            if($is_distributor){
                                                $get_out_pay = $conn->query("SELECT SUM(tocpd.amount) FROM tbl_outstanding_payments tocpd INNER JOIN tbl_order tor ON tocpd.order_id=tor.id WHERE tor.distributor_id='$user_id' AND YEAR(tocpd.date_time) = '$current_year' AND MONTH(tocpd.date_time)='$current_month'");
                                            }else{
                                                $get_out_pay = $conn->query("SELECT SUM(amount) FROM tbl_outstanding_payments WHERE YEAR(date_time) = '$current_year' AND MONTH(date_time)='$current_month'");
                                            }
                                            
                                            
                                            if($GOPrs = $get_out_pay->fetch_array()){
                                                $GetOutstandingPayments = $GOPrs[0];
                                              
                                            }
                                            $GetNotPaidCheque = 0.00;
                                            
                                            if($is_distributor){
                                                $get_check_not_pay = $conn->query("SELECT SUM(amount) FROM tbl_order_cheque_payment_details tocpd INNER JOIN tbl_order tor ON tocpd.invoice_id=tor.id WHERE tor.distributor_id='$user_id' AND is_cleared='0' AND YEAR(added_date) = '$current_year' AND MONTH(added_date)='$current_month' ");
                                            }else{
                                                $get_check_not_pay = $conn->query("SELECT SUM(amount) FROM tbl_order_cheque_payment_details WHERE is_cleared='0' AND YEAR(added_date) = '$current_year' AND MONTH(added_date)='$current_month' ");
                                            }
                                            
                                            
                                            
                                            if($GCNPrs = $get_check_not_pay->fetch_array()){
                                                $GetNotPaidCheque = $GCNPrs[0];
                                            }
                                              
                                                $RealPaymentCollection = $GetOutstandingPayments - $GetNotPaidCheque + $cash_income;
                                          
                                        ?>
                                        <strong class="text-danger">Rs. <?php echo number_format($RealPaymentCollection,2); ?></strong>
                                    </div>
                                    <div class="position-relative d-flex align-items-start z-0">
                                        <div class="progress flex" style="height: 4px;">
                                            <div class="progress-bar bg-danger" role="progressbar" style="width: 100%;" aria-valuenow="100" aria-valuemin="0" aria-valuemax="100"></div>
                                        </div>
                                        <i class="material-icons text-danger bg-white position-absolute" style="right: -4px; top: -10px; z-index: 2;">attach_money</i>
                                    </div>
                                </div>
                            </div>
                            
                        </div>
                        
                        
                      
                        
                        
                        
                        
                        

                        <div class="row card-group-row">
                            <!-- <div class="col-lg-4 col-md-5 card-group-row__col">
                                <div class="card card-group-row__card">
                                    <div class="card-header card-header-large bg-light d-flex align-items-center">
                                        <div class="flex">
                                            <h4 class="card-header__title">Current Balance</h4>
                                            <div class="card-subtitle text-muted">This billing cycle</div>
                                        </div>
                                        <div class="dropdown ml-auto">
                                            <a href="#" data-toggle="dropdown" data-caret="false" class="text-dark-gray"><i class="material-icons">more_horiz</i></a>
                                            <div class="dropdown-menu dropdown-menu-right">
                                                <a href="javascript:void(0)" class="dropdown-item">Go to Report</a>
                                                <a href="javascript:void(0)" class="dropdown-item">Next Cycle</a>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="card-body d-flex align-items-center justify-content-center" style="height: 250px;">
                                        <div class="chart z-0" style="width: calc(250px - 1.25rem * 2); height: calc(250px - 1.25rem * 2);">
                                            <div style="width: calc(250px - 1.25rem * 2); height: calc(250px - 1.25rem * 2); position: absolute; top: 0; left: 0;" class="d-flex flex-column align-items-center justify-content-center">
                                                <div class="text-muted mb-1">Next bill</div>
                                                <div class="card-header__title">15.03.2019</div>
                                            </div>
                                            <canvas class="position-relative" id="billingChart" style="z-index: 2;"></canvas>
                                        </div>
                                    </div>
                                    <div class="card-body pt-0 text-center">
                                        <div class="text-amount mb-1">&dollar;37,290</div>
                                        <div class="text-muted">Current balance this billing cycle</div>
                                    </div>
                                </div>
                            </div> -->
                            <!-- <div class="col-lg-8 col-md-7 card-group-row__col">
                                <div class="card card-group-row__card">
                                    <div class="card-header card-header-large bg-light d-flex align-items-center">
                                        <div class="flex">
                                            <h4 class="card-header__title">Total Transactions</h4>
                                            <div class="card-subtitle text-muted">This billing cycle</div>
                                        </div>
                                        <div class="dropdown ml-auto">
                                            <a href="#" data-toggle="dropdown" data-caret="false" class="text-dark-gray"><i class="material-icons">more_horiz</i></a>
                                            <div class="dropdown-menu dropdown-menu-right">
                                                <a href="javascript:void(0)" class="dropdown-item">Action</a>
                                                <a href="javascript:void(0)" class="dropdown-item">Other Action</a>
                                                <div class="dropdown-divider"></div>
                                                <a href="javascript:void(0)" class="dropdown-item">Some Other Action</a>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="card-body d-flex align-items-center">
                                        <div class="chart w-100" style="height: calc(328px - 1.25rem * 2);">
                                            <canvas id="transactionsChart"></canvas>
                                        </div>
                                    </div>
                                </div>
                            </div> -->
                        </div>

                        

                        <div class="row" style="display: none;">
                          
                            <div class="col-lg">
                                <div class="row card-group-row">
                                    <div class="col-lg-6 card-group-row__col">
                                        <div class="card card-group-row__card card-body card-body-x-lg" style="position: relative; padding-bottom: calc(80px - 1.25rem); overflow: hidden; z-index: 0;">
                                            <div class="card-header__title text-muted mb-2">Products</div>
                                            <div class="text-amount">&dollar;8,391</div>
                                            <div class="text-stats text-success">31.5% <i class="material-icons">arrow_upward</i></div>
                                            <div class="chart" style="height: 80px; position: absolute; left: 0; right: 0; bottom: 0;">
                                                <canvas id="productsChart"></canvas>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-lg-6 card-group-row__col">
                                        <div class="card card-group-row__card card-body card-body-x-lg" style="position: relative; padding-bottom: calc(80px - 1.25rem); overflow: hidden; z-index: 0;">
                                            <div class="card-header__title text-muted mb-2">Courses</div>
                                            <div class="text-amount">15,021</div>
                                            <div class="text-stats text-danger">31.5% <i class="material-icons">arrow_downward</i></div>
                                            <div class="chart" style="height: 80px; position: absolute; left: 0; right: 0; bottom: 0;">
                                                <canvas id="coursesChart"></canvas>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            
                            </div>
                        </div>



                    <div class="row">
                        <div class="col-md-6">
                            <h3>Cheques are to be deposited today</h3>
                        </div>
                    </div>
                        
                    <div class="card card-form">
                        <div class="row no-gutters">
                                <div class="col-lg-12 card-form__body">
                                    <div class="table-responsive border-bottom" style="padding: 10px;">

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
                                                    <th class="text-right">Cheque Amount</th>
                                                </tr>
                                            </thead>
                                            <tbody class="list">
                                                <?php
                                                    if($is_distributor){
                                                        $ChequePaymentDetailsSql = "SELECT * FROM tbl_order_cheque_payment_details tocpd INNER JOIN tbl_order tor ON tocpd.invoice_id=tor.id WHERE tocpd.is_cleared='0' AND tocpd.date_to_cash LIKE '%$currentDate%' ORDER BY tocpd.id DESC";
                                                    }else{
                                                        $ChequePaymentDetailsSql = "SELECT * FROM tbl_order_cheque_payment_details tocpd INNER JOIN tbl_order tor ON tocpd.invoice_id=tor.id WHERE tocpd.is_cleared='0' AND tocpd.date_to_cash LIKE '%$currentDate%' ORDER BY tocpd.id DESC";
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
                                                    <td><font style="float: right; font-weight: 700;">Rs. <?php echo number_format($ChequeAmount,2); ?></font></td>
                                                </tr>
                                                <?php } ?>
                                            </tbody>
                                        </table>

                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        
                    
                    <!-- <h3>All Outlet</h3>
                    <div class="pull-right">
                      <a href="register_outlet" class="btn btn-primary" style="color: #FFF;">Register Outlet</a>
                    </div> -->
                    
                    <?php if($is_distributor){  }else{ ?>
                    <div class="row">
                        <div class="col-md-6">
                            <h3>Sales Tracking</h3>
                        </div>
                    </div>

                        <div class="card card-form">
                            <div class="row no-gutters">
                                <div class="col-lg-12 card-form__body">

                                    <div class="border-bottom" style="padding: 10px;">

                                        
                                        <div class="row">
                                            <div class="col-md-6">
                                                <label class="form-label">Start Date *</label>
                                                <input type="date" id="income-start-date" value="<?php echo date('Y-m-d'); ?>" class="form-control" min="0" placeholder="Start Date" required>
                                            </div>
                                            <div class="col-md-6">
                                                <label class="form-label">End Date *</label>
                                                <input type="date" id="income-end-date" value="<?php echo date('Y-m-d'); ?>" class="form-control" min="0" placeholder="Start Date" required>
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
                                                      <p class="card-category" style="color: #000; float: right;">Cash Sales</p>
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
                                                      <p class="card-category" style="color: #000; float: right;">Credit Sales</p>
                                                      <p class="card-title" id="lbl-credit-income" style="font-weight: 600; font-size: 20px; float: right;">Rs. 0.00<p>
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
                                                      <p class="card-category" style="color: #000; float: right;">Total Sales</p>
                                                      <p class="card-title" id="lbl-grand-income" style="font-weight: 600; font-size: 20px; float: right;">Rs. 0.00<p>
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
                                                      <p class="card-category" style="color: #000; float: right;">Bill Count (All)</p>
                                                      <p class="card-title" style="font-weight: 600; font-size: 20px; float: right;">Count <span id="lbl-all-bill-count">0</span><p>
                                                    </div>
                                                  </div>
                                                </div>
                                              </div>
                                            </div>
                                          </div>
                                        </div>
                                        
                                        <div class="col-md-12">
                                            <h3 class="card-title">Income Report</h3>
                                            <div class="table-responsive">
                                                <table class="table m-b-0" id="income-in-detail-tbl" style="width: 100%;">
                                                    <thead class="thead-dark">
                                                        <tr>
                                                            <th scope="col" style="display: none;">#</th>
                                                            <th scope="col">Details</th>
                                                            <th scope="col"></th>
                                                            <th scope="col" style="text-align: right;">Calcualtion (.Rs)</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody id="profit-sumary-area">
                                                            
                                                    </tbody>
                                                    <!--<tfoot id="-area">-->
                                                            
                                                    <!--</tfoot>-->
                                                </table>
                                            </div>
                                            <br><br>
                                        </div>
                                        

                                        <div class="row">
                                            <div class="col-md-6">
                                                <canvas id="TotalIncomePieChart" width="100%" height="50"></canvas>
                                            </div>
                                            <div class="col-md-6">
                                                <canvas id="TotalIncomeBarChart" width="100%" height="50"></canvas>
                                            </div>                   
                                        </div>

                                        

                                    </div>
                                </div>
                            </div>
                        </div>

                        
                    <?php } ?>
                        
                        


                        <div class="row" style="display: none;">
                        	<div class="col-lg">
                        		<div class="row card-group-row">
                        			<div class="col-lg-12 card-group-row__col">
			                        	<div class="card-header" style="background-color: #FFF; width: 100%;">
			                                <h5 class="card-title">All Shops and Sales-Reps</h5>
			                                <div id="map-layer"  style="background-color:gray;width:100%;height:750px;"></div>
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



    <!-- Flatpickr -->
    <script src="assets/vendor/flatpickr/flatpickr.min.js"></script>
    <script src="assets/js/flatpickr.js"></script>

    <!-- Global Settings -->
    <script src="assets/js/settings.js"></script>

    <!-- Chart.js -->
    <script src="assets/vendor/Chart.min.js"></script>

    <!-- App Charts JS -->
    <script src="assets/js/chartjs-rounded-bar.js"></script>
    <script src="assets/js/charts.js"></script>

    <!-- Vector Maps -->
    <script src="assets/vendor/jqvmap/jquery.vmap.min.js"></script>
    <script src="assets/vendor/jqvmap/maps/jquery.vmap.world.js"></script>
    <script src="assets/js/vector-maps.js"></script>

    <!-- Chart Samples -->
    <script src="assets/js/page.dashboard.js"></script>

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyC7FUnTem9bn1mO2KE1P5bitnPUbxhl8EM&sensor=false"></script>
    
    
    <script src="https://cdn.datatables.net/1.11.3/js/jquery.dataTables.min.js"></script>

    <script src="https://cdn.datatables.net/buttons/2.1.0/js/dataTables.buttons.min.js"></script>
	<script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>
	<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/pdfmake.min.js"></script>
	<script src="https://cdn.datatables.net/buttons/2.1.0/js/buttons.html5.min.js"></script>
	<script src="https://cdn.datatables.net/buttons/2.1.0/js/buttons.print.min.js"></script>


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

    <script>
        $(document).ready( function () {
            $('#ChequeTable').DataTable();
        } );
    </script>


    <script>

        var map = null;
        var outlet_info = new google.maps.InfoWindow();

        $(document).ready(function(){
            download_and_setup_coordinates();
        });


        function download_and_setup_coordinates(){



            $.ajax({
                        url: "scripts/download_shops_and_rep_coordinates_summary.php",
                        type: 'POST',
                        data: {},
                        success: function (data) {

                            
                           var json=JSON.parse(data);
                           if(json.result){

                             lineCoordinates = [];
                             outletCoordinates = [];

                            if(map != null){
                                setMapOnAll(map);
                            }

                            



                              var dataList = json.locations;
                              var outlets = json.outlets;






                          for (i = 0; i < dataList.length; i++) {
                              
                              var lat=dataList[i].lat;
                              var lon=dataList[i].lng;

                              var name = dataList[i]['name'];
                              var contact = dataList[i]['contact'];
                              var id = dataList[i]['id'];
                              
                              var date = dataList[i]['date'];
                              var time = dataList[i]['time'];
                              var battery = dataList[i]['battery'];

                              var rep_obj = [];

                              rep_obj['lat_lng'] = new google.maps.LatLng(lat, lon);
                              rep_obj['name'] = name;
                              rep_obj['contact'] = contact;
                              rep_obj['id'] = id;
                              
                              rep_obj['date'] = date;
                              rep_obj['time'] = time;
                              rep_obj['battery'] = battery;
                              



                              lineCoordinates.push(rep_obj);
                          }

                          


                          for (i = 0; i < outlets.length; i++) {
                              
                              var lat=outlets[i].lat;
                              var lon=outlets[i].lng;



                              var owner = outlets[i]['owner'];
                              var contact = outlets[i]['contact'];
                              var name = outlets[i]['name'];
                              var outlet_id = outlets[i]['outlet_id'];



                              var outlet_obj = [];
                              outlet_obj['lat_lng'] = new google.maps.LatLng(lat, lon);
                              outlet_obj['name'] = name;

                              outlet_obj['owner'] = owner;
                              outlet_obj['outlet_id'] = outlet_id;
                              outlet_obj['contact'] = contact;





                              // outletCoordinates.push(new google.maps.LatLng(lat, lon));
                              outletCoordinates.push(outlet_obj);
                          }







                          initMap(lineCoordinates,outletCoordinates);
                        
                          // google.maps.event.addDomListener(window, 'load', initialize);

                           }
                          



                        },
                        error: function (jqXHR, textStatus, errorThrown) {
                            console.log(errorThrown);
                        }

                    });




            
        }
        

        function initMap(coordinates,outletcoordinate) {

           

          // var myLatLng = {lat: 7.1667, lng: 80.1233};
            //Enabling new cartography and themes
            google.maps.visualRefresh = true;
 
            //Setting starting options of map
            var mapOptions = {
                center: new google.maps.LatLng(7.8731,80.7718),
                zoom: 8,
                mapTypeId: google.maps.MapTypeId.ROADMAP
            };


        


 
            //Getting map DOM element
            var mapElement = document.getElementById('map-layer');
 
            //Creating a map with DOM element which is just obtained
            map = new google.maps.Map(mapElement, mapOptions);


  


          for(var i = 0;i<coordinates.length;i++){

            var position = coordinates[i]['lat_lng'];
            var name = coordinates[i]['name'];
            var id = coordinates[i]['id'];
            var contact = coordinates[i]['contact'];
            
            var date = coordinates[i]['date']+" "+coordinates[i]['time'];
            
            var battery = coordinates[i]['battery'];



            var marker_icon = 'assets/img/sales-rep-small.png';




            var markerContent = '<div class="table-responsive table-hover" style="overflow:auto">'+
                                    
                                        '<table class="table">'+
                                          '<tbody>'+
                                          	'<tr style="background-color:blue;color:white;font-size:17px;">'+
                                                '<td><b>#</b></td>'+
                                                '<td><b>Last Known Location</b></td>'+'</tr>'+
                                                '<tr style="cursor:pointer">'+
                                                '<td>Rep Name</td>'+
                                                '<td class="text-danger" style="font-size:18px"><b>'+name+'</b></td>'+
                                              '</tr>'+'<tr>'+
                                                '<td>Contact No</td>'+
                                                '<td class="text-danger" style="font-size:18px"><b>'+contact+'</b></td>'+
                                              '</tr><tr>'+
                                                '<td>Date/Time</td>'+
                                                '<td class="text-danger" style="font-size:18px"><b>'+date+'</b></td>'+
                                              '</tr><tr>'+
                                                '<td>Battery Level</td>'+
                                                '<td class="text-danger" style="font-size:18px"><b>'+battery+'%</b></td>'+
                                              '</tr></tbody>'+
                                          '</table>'+
                                          '</div>'+
                                          '</div>';





            

            var marker = new google.maps.Marker({
              position: position,
              title: name,
              icon:marker_icon
            });


            marker.setMap(map);
           google.maps.event.addListener(marker, 'click',handleRepMarkerClick(marker,markerContent));



          }

        




          for(var i= 0;i<outletcoordinate.length;i++){

            


            var position = outletCoordinates[i]['lat_lng'];
            var outlet_name = outletCoordinates[i]['name'];
            var id = outletCoordinates[i]['outlet_id'];
            var owner = outletCoordinates[i]['owner'];
            var contact = outletCoordinates[i]['contact'];
            var marker_icon = "assets/img/shop_small.png";



            var markerContent = '<div class="table-responsive table-hover" style="overflow:auto">'+
                                    
                                        '<table class="table">'+
                                          '<tbody>'+
                                                '<tr style="cursor:pointer">'+
                                                '<td>Outlet</td>'+
                                                '<td class="text-danger" style="font-size:18px">'+outlet_name+'</td>'+
                                              '</tr>'+'<tr>'+
                                                '<td>Owner</td>'+
                                                '<td class="text-danger" style="font-size:18px">'+owner+'</td>'+
                                              '</tr>'+

                                              '<tr>'+
                                                '<td>Contact No</td>'+
                                                '<td class="text-danger" style="font-size:18px">'+contact+'</td>'+
                                              '</tr></tbody>'+
                                          '</table>'+
                                          '</div>'+
                                          '</div>';





            

           var marker = new google.maps.Marker({
              position: position,
              
              title: outlet_name,
              icon:marker_icon
            });

           marker.setMap(map);
           google.maps.event.addListener(marker, 'click',handleOutletMarkerClick(marker,markerContent));

          }


          

        }


        function handleOutletMarkerClick(marker,content) {
          return function() {


          	

            outlet_info.setContent(content);
            outlet_info.open(map, marker);
          };
        }


        function handleRepMarkerClick(marker,content) {
          return function() {
            outlet_info.setContent(content);
            outlet_info.open(map, marker);
          };
        }



    </script>








    <!------------------ Start Total Income---------------------------------------->
    <script type="text/javascript">

            $(document).ready(function () {

               loadChart(0,0,0);

                $("#btn-get-income").click(function(){

                    var startDate = $("#income-start-date").val();
                    var endDate = $("#income-end-date").val();

                    $.ajax({

                        url:'analytics/get_income_summary.php',
                        type:'POST',
                        data:{
                            start_date:startDate,
                            end_date:endDate
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
                            var credit_income = 0.00;
                            var grand_income = 0.00;

                            var all_bill_count = 0;


                            if(json.result){

                                if(json.full_income != 'null'){
                                    full_income = json.full_income;
                                }else{
                                    full_income = 0.00;
                                }

                                if(json.credit_income != 'null'){
                                    credit_income = json.credit_income;
                                }else{
                                    credit_income = 0.00;
                                }

                                if(json.grand_income != 'null'){
                                    grand_income = json.grand_income;
                                }else{
                                    grand_income = 0.00;
                                }

                                if(json.all_bill_count != 'null'){
                                    all_bill_count = json.all_bill_count;
                                }else{
                                    all_bill_count = 0;
                                }
                                
                                ///////Income Summery Details Area//////////
                                $("#profit-sumary-area").html(json.data);
                                incometbl = $('#income-in-detail-tbl').DataTable({ 
                                      "order": [[ 0, "asc" ]],
                                      searching: false,
                                      "destroy": true, //use for reinitialize datatable
                                      dom: 'Bfrtip',
                                        buttons: [
                                                    {
                                                        extend: 'print',
                                                        title: 'Profit and Loss Statement (P&L - Using This Software)',
                                                        exportOptions: {
                                                            columns: ':visible'
                                                        }
                                                    },
                                                    {
                                                        extend: 'copy',
                                                        title: 'Profit and Loss Statement (P&L - Using This Software)',
                                                        exportOptions: {
                                                            columns: ':visible'
                                                        }
                                                    },
                                                    {
                                                        extend: 'excelHtml5',
                                                        title: 'Profit and Loss Statement (P&L - Using This Software)',
                                                        exportOptions: {
                                                            columns: ':visible'
                                                        }
                                                    },
                                                    {
                                                        extend: 'pdfHtml5',
                                                        title: 'Profit and Loss Statement (P&L - Using This Software)',
                                                        exportOptions: {
                                                            columns: ':visible'
                                                        }
                                                    },
                                                    'colvis'
                                                ]
                                });
                                ///////////////////

                                
                                $("#lbl-full-income").html('Rs. '+parseFloat(full_income).toFixed(2));
                                $("#lbl-credit-income").html('Rs. '+parseFloat(credit_income).toFixed(2));
                                $("#lbl-grand-income").html('Rs. '+parseFloat(grand_income).toFixed(2));
                                

                                $("#lbl-all-bill-count").html(all_bill_count);
                             

                                loadChart(full_income,credit_income);
                               
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


        <script>

            function loadChart(full_income,credit_income){

                var ctx = document.getElementById('TotalIncomePieChart');
                var TotalIncomePieChart = new Chart(ctx, {
                type: 'pie',
                data: {
                    labels: [
                        'Cash Income',
                        'Credit Income'
                    ],
                    datasets: [{
                        data: [full_income,credit_income],
                        backgroundColor: ["#4BAD48", "#FF9500"]
                    }]
                },
                options: {
                    responsive: true,
                    title:{
                        display: true,
                        text: "Income Summary"
                    }
                }
            });


                var ctx = document.getElementById('TotalIncomeBarChart');
                    var TotalIncomeBarChart = new Chart(ctx, {
                        type: 'bar',
                        data: {
                            labels: ['Cash Income', 'Credit Income'],
                            datasets: [{
                                label: 'Income Summary',
                                data: [full_income,credit_income],
                                backgroundColor: [
                                    '#4BAD48',
                                    '#FF9500'
                                ],
                                borderColor: [
                                    '#4BAD48',
                                    '#FF9500'
                                ],
                                borderWidth: 1
                            }]
                        },
                        options: {
                            responsive: true,
                            scales: {
                                yAxes: [{
                                    ticks: {
                                        beginAtZero: true
                                    }
                                }]
                            }
                        }
                    });




            }

        </script>

        <!------------------ End Total Income---------------------------------------->














    

    <!-- <script src="https://maps.googleapis.com/maps/api/staticmap?key=AIzaSyC7FUnTem9bn1mO2KE1P5bitnPUbxhl8EM&center=7.863391506429551,80.70624996929112&zoom=8&format=png&maptype=roadmap&style=element:geometry%7Ccolor:0x242f3e&style=element:labels.text.fill%7Ccolor:0x746855&style=element:labels.text.stroke%7Ccolor:0x242f3e&style=feature:administrative%7Celement:geometry%7Cvisibility:off&style=feature:administrative.land_parcel%7Cvisibility:off&style=feature:administrative.locality%7Celement:labels.text.fill%7Ccolor:0xd59563&style=feature:administrative.neighborhood%7Cvisibility:off&style=feature:poi%7Cvisibility:off&style=feature:poi%7Celement:labels.text%7Cvisibility:off&style=feature:poi%7Celement:labels.text.fill%7Ccolor:0xd59563&style=feature:poi.park%7Celement:geometry%7Ccolor:0x263c3f&style=feature:poi.park%7Celement:labels.text.fill%7Ccolor:0x6b9a76&style=feature:road%7Celement:geometry%7Ccolor:0x38414e&style=feature:road%7Celement:geometry.stroke%7Ccolor:0x212a37&style=feature:road%7Celement:labels%7Cvisibility:off&style=feature:road%7Celement:labels.icon%7Cvisibility:off&style=feature:road%7Celement:labels.text.fill%7Ccolor:0x9ca5b3&style=feature:road.highway%7Celement:geometry%7Ccolor:0x746855&style=feature:road.highway%7Celement:geometry.stroke%7Ccolor:0x1f2835&style=feature:road.highway%7Celement:labels.text.fill%7Ccolor:0xf3d19c&style=feature:transit%7Cvisibility:off&style=feature:transit%7Celement:geometry%7Ccolor:0x2f3948&style=feature:transit.station%7Celement:labels.text.fill%7Ccolor:0xd59563&style=feature:water%7Celement:geometry%7Ccolor:0x17263c&style=feature:water%7Celement:labels.text%7Cvisibility:off&style=feature:water%7Celement:labels.text.fill%7Ccolor:0x515c6d&style=feature:water%7Celement:labels.text.stroke%7Ccolor:0x17263c&size=480x360"></script> -->


    <script>
    	[
  {
    "elementType": "geometry",
    "stylers": [
      {
        "color": "#242f3e"
      }
    ]
  },
  {
    "elementType": "labels.text.fill",
    "stylers": [
      {
        "color": "#746855"
      }
    ]
  },
  {
    "elementType": "labels.text.stroke",
    "stylers": [
      {
        "color": "#242f3e"
      }
    ]
  },
  {
    "featureType": "administrative",
    "elementType": "geometry",
    "stylers": [
      {
        "visibility": "off"
      }
    ]
  },
  {
    "featureType": "administrative.land_parcel",
    "stylers": [
      {
        "visibility": "off"
      }
    ]
  },
  {
    "featureType": "administrative.locality",
    "elementType": "labels.text.fill",
    "stylers": [
      {
        "color": "#d59563"
      }
    ]
  },
  {
    "featureType": "administrative.neighborhood",
    "stylers": [
      {
        "visibility": "off"
      }
    ]
  },
  {
    "featureType": "poi",
    "stylers": [
      {
        "visibility": "off"
      }
    ]
  },
  {
    "featureType": "poi",
    "elementType": "labels.text",
    "stylers": [
      {
        "visibility": "off"
      }
    ]
  },
  {
    "featureType": "poi",
    "elementType": "labels.text.fill",
    "stylers": [
      {
        "color": "#d59563"
      }
    ]
  },
  {
    "featureType": "poi.park",
    "elementType": "geometry",
    "stylers": [
      {
        "color": "#263c3f"
      }
    ]
  },
  {
    "featureType": "poi.park",
    "elementType": "labels.text.fill",
    "stylers": [
      {
        "color": "#6b9a76"
      }
    ]
  },
  {
    "featureType": "road",
    "elementType": "geometry",
    "stylers": [
      {
        "color": "#38414e"
      }
    ]
  },
  {
    "featureType": "road",
    "elementType": "geometry.stroke",
    "stylers": [
      {
        "color": "#212a37"
      }
    ]
  },
  {
    "featureType": "road",
    "elementType": "labels",
    "stylers": [
      {
        "visibility": "off"
      }
    ]
  },
  {
    "featureType": "road",
    "elementType": "labels.icon",
    "stylers": [
      {
        "visibility": "off"
      }
    ]
  },
  {
    "featureType": "road",
    "elementType": "labels.text.fill",
    "stylers": [
      {
        "color": "#9ca5b3"
      }
    ]
  },
  {
    "featureType": "road.highway",
    "elementType": "geometry",
    "stylers": [
      {
        "color": "#746855"
      }
    ]
  },
  {
    "featureType": "road.highway",
    "elementType": "geometry.stroke",
    "stylers": [
      {
        "color": "#1f2835"
      }
    ]
  },
  {
    "featureType": "road.highway",
    "elementType": "labels.text.fill",
    "stylers": [
      {
        "color": "#f3d19c"
      }
    ]
  },
  {
    "featureType": "transit",
    "stylers": [
      {
        "visibility": "off"
      }
    ]
  },
  {
    "featureType": "transit",
    "elementType": "geometry",
    "stylers": [
      {
        "color": "#2f3948"
      }
    ]
  },
  {
    "featureType": "transit.station",
    "elementType": "labels.text.fill",
    "stylers": [
      {
        "color": "#d59563"
      }
    ]
  },
  {
    "featureType": "water",
    "elementType": "geometry",
    "stylers": [
      {
        "color": "#17263c"
      }
    ]
  },
  {
    "featureType": "water",
    "elementType": "labels.text",
    "stylers": [
      {
        "visibility": "off"
      }
    ]
  },
  {
    "featureType": "water",
    "elementType": "labels.text.fill",
    "stylers": [
      {
        "color": "#515c6d"
      }
    ]
  },
  {
    "featureType": "water",
    "elementType": "labels.text.stroke",
    "stylers": [
      {
        "color": "#17263c"
      }
    ]
  }
]
</script>



</body>

</html>