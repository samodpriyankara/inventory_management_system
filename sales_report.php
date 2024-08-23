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




                                        <div class="row">
                                            <div class="col-lg-4 col-md-6 col-sm-6">
                                                <div class="card card-stats" style="background-color: #F0F8FF; border-radius: 10px;">
                                                  <div class="card-body" style="padding: 0rem !important;">
                                                    <div class="row">
                                                      <div class="col-5 col-md-4">
                                                        <div class="icon-big text-center icon-warning">
                                                          <!-- <i class="nc-icon nc-favourite-28 text-primary"></i> -->
                                                          <img src="assets/img/icons/cheque2.png" style="width: 80%;">
                                                        </div>
                                                      </div>
                                                      <div class="col-7 col-md-8">
                                                        <div class="numbers">
                                                          <p class="card-category" style="color: #000; float: right;"><font style="color: #F0F8FF;">----</font>Amount Unrealized Cheques</p>
                                                          <p class="card-title" id="lbl-cheque-collect" style="font-weight: 600; font-size: 20px; float: right;">Rs. 0.00<p>
                                                        </div>
                                                      </div>
                                                    </div>
                                                  </div>
                                                </div>
                                            </div>
                                            <div class="col-lg-4 col-md-6 col-sm-6">
                                                <div class="card card-stats" style="background-color: #F0F8FF; border-radius: 10px;">
                                                  <div class="card-body" style="padding: 0rem !important;">
                                                    <div class="row">
                                                      <div class="col-5 col-md-4">
                                                        <div class="icon-big text-center icon-warning">
                                                          <!-- <i class="nc-icon nc-favourite-28 text-primary"></i> -->
                                                          <img src="assets/img/icons/cheque1.png" style="width: 80%;">
                                                        </div>
                                                      </div>
                                                      <div class="col-7 col-md-8">
                                                        <div class="numbers">
                                                          <p class="card-category" style="color: #000; float: right;"><font style="color: #F0F8FF;">----------</font>Amount Realized Cheques</p>
                                                          <p class="card-title" id="lbl-cheque-realized" style="font-weight: 600; font-size: 20px; float: right;">Rs. 0.00<p>
                                                        </div>
                                                      </div>
                                                    </div>
                                                  </div>
                                                </div>
                                            </div>
                                            <div class="col-lg-4 col-md-6 col-sm-6">
                                                <div class="card card-stats" style="background-color: #F0F8FF; border-radius: 10px;">
                                                  <div class="card-body" style="padding: 0rem !important;">
                                                    <div class="row">
                                                      <div class="col-5 col-md-4">
                                                        <div class="icon-big text-center icon-warning">
                                                          <!-- <i class="nc-icon nc-favourite-28 text-primary"></i> -->
                                                          <img src="assets/img/icons/cheque.png" style="width: 80%;">
                                                        </div>
                                                      </div>
                                                      <div class="col-7 col-md-8">
                                                        <div class="numbers">
                                                          <p class="card-category" style="color: #000; float: right;"><font style="color: #F0F8FF;">----------</font>Cheque Count (All)</p>
                                                          <p class="card-title" style="font-weight: 600; font-size: 20px; float: right;">Count <span id="lbl-all-cheque-count">0</span><p>
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

                                        <div class="col-md-12">
                                            <h3 class="card-title">Cheque Report</h3>
                                            <div class="table-responsive">
                                                <table class="table m-b-0" id="cheque-detail-tbl" style="width: 100%;">
                                                    <thead class="thead-dark">
                                                        <tr>
                                                            <th>Invoice Number</th>
                                                            <th>Outlet Name</th>
                                                            <th>Cheque Number</th>
                                                            <th>Bank</th>
                                                            <th>Deposit Date</th>
                                                            <th>Cheque Collect Date</th>
                                                            <th>Cheque Realized Date</th>
                                                            <th class="text-right">Cheque Amount</th>
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

                        
                    </div>




                    <div class="container-fluid page__container">
                    
                    <div class="row">
                        <div class="col-md-6">
                            <h3>Product Selling Summery</h3>
                        </div>
                    </div>

                        <div class="card card-form">
                            <div class="row no-gutters">
                                <div class="col-lg-12 card-form__body">

                                    <div class="border-bottom" style="padding: 10px;">

                                      <div class="row">
                                            <div class="col-md-3">
                                                <label class="form-label">Start Date *</label>
                                                <input type="date" id="product-selling-start-date" value="<?php echo date('Y-m-d'); ?>" class="form-control" min="0" placeholder="Start Date" required>
                                            </div>
                                            <div class="col-md-3">
                                                <label class="form-label">End Date *</label>
                                                <input type="date" id="product-selling-end-date" value="<?php echo date('Y-m-d'); ?>" class="form-control" min="0" placeholder="Start Date" required>
                                            </div>
                                            <div class="col-md-3">
                                                <label class="form-label">Select Distributor</label>
                                                <select class="form-control" id="product-selling-distributor-id" onchange="onDistributorChangeSalesRep()">
                                                  <option value="0">Selcet Distributor</option>
                                                  <?php
                                                      $getDisQuery=$conn->query("SELECT td.distributor_id,td.name FROM tbl_distributor td ORDER BY td.name ASC");
                                                      while ($dis=$getDisQuery->fetch_array()) {
                                                  ?>
                                                    <option value=<?php echo $dis[0];?>><?php echo $dis[1];?></option>
                                                  <?php } ?>
                                                </select>
                                                
                                            </div>
                                            <div class="col-md-3">
                                                <label class="form-label">Select Sales-Rep</label>
                                                <select class="form-control" id="product-selling-sales-rep-id">
                                                  <option value="0">Selcet Sales-Rep</option>
                                                    
                                                </select>
                                                
                                            </div>
                                                                         
                                            <div class="col-md-12"><br>
                                                <button type="submit" id="btn-get-product-selling" class="btn btn-primary">Get Product Summery</button>
                                            </div>
                                        </div>
                                           
                                        <hr>

                                        

                                    	<table class="table mb-0 thead-border-top-0" id="ProductSaleTable">
                                            <thead>
                                                <tr>
                                                    <th>Product Name</th>
                                                    <th>Product Code</th>
                                                    <th class="text-right">Quantity</th>
                                                </tr>
                                            </thead>
                                            <tbody class="list" id="product-sale-area">
                                               
                                                
                                            </tbody>
                                        </table>
                                        

                                        

                                    </div>
                                </div>
                            </div>
                        </div>

                        
                    </div>




                    <div class="container-fluid page__container">
                    
                    <div class="row">
                        <div class="col-md-6">
                            <h3>Distributors Debtor Reports</h3>
                        </div>
                    </div>

                        <div class="card card-form">
                            <div class="row no-gutters">
                                <div class="col-lg-12 card-form__body">

                                    <div class="border-bottom" style="padding: 10px;">

                                      <div class="row">
                                            <div class="col-md-4">
                                                <label class="form-label">Start Date *</label>
                                                <input type="date" id="debtor-reports-start-date" value="<?php echo date('Y-m-d'); ?>" class="form-control" min="0" placeholder="Start Date" required>
                                            </div>
                                            <div class="col-md-4">
                                                <label class="form-label">End Date *</label>
                                                <input type="date" id="debtor-reports-end-date" value="<?php echo date('Y-m-d'); ?>" class="form-control" min="0" placeholder="Start Date" required>
                                            </div>
                                            <!-- <div class="col-md-4">
                                                <label class="form-label">Select Data Type</label>
                                                <select class="form-control" id="debtor-reports-type">
                                                  <option value="0" disabled>Selcet Data type</option>
                                                  <option value="1">Distributor</option>
                                                  <option value="2">Sales-Rep</option>
                                                </select>
                                                
                                            </div> -->
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
                                                <button type="submit" id="btn-get-debtor-reports" class="btn btn-primary">Get Debtor Reports</button>
                                            </div>
                                        </div>
                                           
                                        <hr>

                                        
                                        <h6>Distributors Debtor Report</h6>
                                    	<table class="table mb-0 thead-border-top-0" id="Debtor_Table">
                                            <thead>
                                                <tr>
                                                    <th>Distributors Name</th>
                                                    <th class="text-right">Debtor Value (.Rs)</th>
                                                </tr>
                                            </thead>
                                            <tbody class="list" id="debtor-area">
                                               
                                                
                                            </tbody>
                                        </table>
                                        

                                        

                                    </div>
                                </div>
                            </div>
                        </div>

                        
                    </div>
                    
                    
                    <div class="container-fluid page__container">
                    
                    <div class="row">
                        <div class="col-md-6">
                            <h3>Shops Debtor Reports</h3>
                        </div>
                    </div>

                        <div class="card card-form">
                            <div class="row no-gutters">
                                <div class="col-lg-12 card-form__body">

                                    <div class="border-bottom" style="padding: 10px;">

                                      <div class="row">
                                          
                                            <div class="col-md-4">
                                                <label class="form-label">Start Date *</label>
                                                <input type="date" id="shop-debtor-reports-start-date" value="<?php echo date('Y-m-d'); ?>" class="form-control" min="0" placeholder="Start Date" required>
                                            </div>
                                            
                                            <div class="col-md-4">
                                                <label class="form-label">End Date *</label>
                                                <input type="date" id="shop-debtor-reports-end-date" value="<?php echo date('Y-m-d'); ?>" class="form-control" min="0" placeholder="Start Date" required>
                                            </div>
                                            
                                            <div class="col-md-4">
                                                <label class="form-label">Select Route</label>
                                                <select class="form-control" id="shop-debtor-reports-route-id">
                                                  <option value="0">Only All Routes</option>
                                                  <?php
                                                      $getDisQuery=$conn->query("SELECT tr.route_id,tr.route_name FROM tbl_route tr ORDER BY tr.route_id ASC");
                                                      while ($dis=$getDisQuery->fetch_array()) {
                                                  ?>
                                                    <option value=<?php echo $dis[0];?>><?php echo $dis[1];?></option>
                                                  <?php } ?>
                                                </select>
                                                
                                            </div>
                                                                      
                                            <div class="col-md-12"><br>
                                                <button type="submit" id="btn-get-shop-debtor-reports" class="btn btn-primary">Get Debtor Reports</button>
                                            </div>
                                        </div>
                                           
                                        <hr>

                                        
                                        <h6>Shop Debtor Report</h6>
                                    	<table class="table mb-0 thead-border-top-0" id="Shop_Debtor_Table">
                                            <thead>
                                                <tr>
                                                    <th>Shop Name</th>
                                                    <th class="text-right">Credit Value (.Rs)</th>
                                                    <th class="text-right">Outstanding Value (.Rs)</th>
                                                </tr>
                                            </thead>
                                            <tbody class="list" id="shop-debtor-area">
                                               
                                                
                                            </tbody>
                                        </table>
                                        

                                        

                                    </div>
                                </div>
                            </div>
                        </div>

                        
                    </div>
                    
                    
                    
                    <div class="container-fluid page__container">
                    
                    <div class="row">
                        <div class="col-md-6">
                            <h3>Free Issue Reports</h3>
                        </div>
                    </div>

                        <div class="card card-form">
                            <div class="row no-gutters">
                                <div class="col-lg-12 card-form__body">

                                    <div class="border-bottom" style="padding: 10px;">

                                      <div class="row">
                                            <div class="col-md-4">
                                                <label class="form-label">Start Date *</label>
                                                <input type="date" id="free-issue-reports-start-date" value="<?php echo date('Y-m-d'); ?>" class="form-control" min="0" placeholder="Start Date" required>
                                            </div>
                                            <div class="col-md-4">
                                                <label class="form-label">End Date *</label>
                                                <input type="date" id="free-issue-reports-end-date" value="<?php echo date('Y-m-d'); ?>" class="form-control" min="0" placeholder="Start Date" required>
                                            </div>
                                            <!-- <div class="col-md-4">
                                                <label class="form-label">Select Data Type</label>
                                                <select class="form-control" id="debtor-reports-type">
                                                  <option value="0" disabled>Selcet Data type</option>
                                                  <option value="1">Distributor</option>
                                                  <option value="2">Sales-Rep</option>
                                                </select>
                                                
                                            </div> -->
                                            <div class="col-md-4">
                                                <label class="form-label">Select Distributor</label>
                                                <select class="form-control" id="free-issue-reports-distributor-id" onchange="onDistributorChangeSalesRep()">
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
                                                <button type="submit" id="btn-get-free-issue-reports" class="btn btn-primary">Get Free Issue Reports</button>
                                            </div>
                                        </div>
                                           
                                        <hr>

                                        
                                        <h6>Free Issue Report</h6>
                                    	<table class="table mb-0 thead-border-top-0" id="Free_Issue_Table">
                                            <thead>
                                                <tr>
                                                    <th>Product Name</th>
                                                    <th>Product Code</th>
                                                    <th class="text-right">Quantity</th>
                                                    <th class="text-right">Cost</th>
                                                </tr>
                                            </thead>
                                            <tbody class="list" id="free-issue-area">
                                               
                                                
                                            </tbody>
                                        </table>
                                        

                                        

                                    </div>
                                </div>
                            </div>
                        </div>

                        
                    </div>




                    
                    
                    
                    <div class="container-fluid page__container">
                    
                    <div class="row">
                        <div class="col-md-6">
                            <h3>Return Items Reports</h3>
                        </div>
                    </div>

                        <div class="card card-form">
                            <div class="row no-gutters">
                                <div class="col-lg-12 card-form__body">

                                    <div class="border-bottom" style="padding: 10px;">

                                      <div class="row">
                                            <div class="col-md-3">
                                                <label class="form-label">Start Date *</label>
                                                <input type="date" id="return-item-reports-start-date" value="<?php echo date('Y-m-d'); ?>" class="form-control" min="0" placeholder="Start Date" required>
                                            </div>
                                            <div class="col-md-3">
                                                <label class="form-label">End Date *</label>
                                                <input type="date" id="return-item-reports-end-date" value="<?php echo date('Y-m-d'); ?>" class="form-control" min="0" placeholder="Start Date" required>
                                            </div>
                                            <div class="col-md-3">
                                                <label class="form-label">Select Return Type</label>
                                                <select class="form-control" id="return-reports-type">
                                                  <option value="-1">All Return type</option>
                                                  <option value="0">Sales Return</option>
                                                  <option value="1">Damage Return</option>
                                                </select>
                                                
                                            </div>
                                            <div class="col-md-3">
                                                <label class="form-label">Select Distributor</label>
                                                <select class="form-control" id="return-item-reports-distributor-id" onchange="onDistributorChangeSalesRep()">
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
                                                <button type="submit" id="btn-get-return-item-reports" class="btn btn-primary">Get Return Items Reports</button>
                                            </div>
                                        </div>
                                           
                                        <hr>

                                        
                                        <h6>Return Items Report</h6>
                                    	<table class="table mb-0 thead-border-top-0" id="Return_Items_Table">
                                            <thead>
                                                <tr>
                                                    <th>Product Name</th>
                                                    <th>Product Code</th>
                                                    <th class="text-right">Quantity</th>
                                                    <th class="text-right">Cost</th>
                                                </tr>
                                            </thead>
                                            <tbody class="list" id="return-items-area">
                                               
                                                
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

    <script>
        // $(document).ready( function () {
        //     $('#ProductSaleTable').DataTable({
        //     	"order": [[ 2, "desc" ]],
		      //       dom: 'Bfrtip',
		      //       buttons: [
		      //           // 'copy', 'csv', 'excel', 'pdf', 'print'
		      //           'print', 'excel', 'pdf'
		      //       ]
        //     });
        // } );
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

                            var all_cheque_count = 0;
                            var all_cheque_collect_amount = 0.00;
                            var all_cheque_realized_amount = 0.00;


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

                                /////Cheque/////
                                if(json.all_cheque_count != 'null'){
                                    all_cheque_count = json.all_cheque_count;
                                }else{
                                    all_cheque_count = 0;
                                }
                                if(json.all_cheque_collect_amount != 'null'){
                                    all_cheque_collect_amount = json.all_cheque_collect_amount;
                                }else{
                                    all_cheque_collect_amount = 0;
                                }
                                if(json.all_cheque_realized_amount != 'null'){
                                    all_cheque_realized_amount = json.all_cheque_realized_amount;
                                }else{
                                    all_cheque_realized_amount = 0;
                                }
                                ///////////////

                                
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

                                
                                
                                ///////Cheque Summery Details Area//////////
                                $("#cheque-sumary-area").html(json.data_cheque);
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
                                $("#lbl-credit-income").html('Rs. '+parseFloat(credit_income).toFixed(2));
                                $("#lbl-grand-income").html('Rs. '+parseFloat(grand_income).toFixed(2));
                                

                                $("#lbl-all-bill-count").html(all_bill_count);

                                //////
                                $("#lbl-all-cheque-count").html(all_cheque_count);
                                $("#lbl-cheque-collect").html('Rs. '+parseFloat(all_cheque_collect_amount).toFixed(2));
                                $("#lbl-cheque-realized").html('Rs. '+parseFloat(all_cheque_realized_amount).toFixed(2));
                             

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

        <!------------Payment metod income--------------------->
        <script type="text/javascript">


          function onDistributorChangeSalesRep(){

            var val=$("#product-selling-distributor-id").val();   

            $.ajax({
                url:'controls/get_distributor_reps.php',
                type:'POST',
                data:{
                    distributor_id:val
                },
                success:function(data){
                   
                    var json = JSON.parse(data);
                    if(json.result){

                        $("#product-selling-sales-rep-id").html(json.data);
                    }

                },error:function(err){
                    console.log(err);
                }
            //
          });


        }



          var productssummerytbl = null;

            function downloadproductsellingsummery(){
            }

          $(document).ready(function(){
            downloadproductsellingsummery();

            // $('#myTable').DataTable();

            $("#btn-get-product-selling").click(function(event){

              if(productssummerytbl == null){
                        
                        
              }else{
                                                    
                productssummerytbl.clear().draw();
                productssummerytbl.destroy();
              }

              event.preventDefault();


              Swal.fire({
                  text: "Please wait...",
                  imageUrl:"assets/loader.gif",
                  showConfirmButton: false,
                  allowOutsideClick: false
                });


              

              $.ajax({
              url:'analytics/get_product_selling_summary.php',
              type:'POST',
              data:{
                    product_selling_start_date:$("#product-selling-start-date").val(),
                    product_selling_end_date:$("#product-selling-end-date").val(),
                    product_selling_distributor_id:$("#product-selling-distributor-id").val(),
                    product_selling_sales_rep_id:$("#product-selling-sales-rep-id").val()
              },
              success:function(data){
                console.log(data);


                var json=JSON.parse(data);
                
                if(json.result){

                  $("#product-sale-area").html(json.data);

                  productssummerytbl = $('#ProductSaleTable').DataTable({ 
                    "order": [[ 2, "desc" ]],
                    "destroy": true, //use for reinitialize datatable
                    dom: 'Bfrtip',
                      buttons: [
                                {
                                    extend: 'print',
                                    title: 'Product Selling Summery (Using This Software)',
                                    exportOptions: {
                                        columns: ':visible'
                                    }
                                },
                                {
                                    extend: 'copy',
                                    title: 'Product Selling Summery (Using This Software)',
                                    exportOptions: {
                                        columns: ':visible'
                                    }
                                },
                                {
                                    extend: 'excelHtml5',
                                    title: 'Product Selling Summery (Using This Software)',
                                    exportOptions: {
                                        columns: ':visible'
                                    }
                                },
                                {
                                    extend: 'pdfHtml5',
                                    title: 'Product Selling Summery (Using This Software)',
                                    exportOptions: {
                                        columns: ':visible'
                                    }
                                },
                                  'colvis'
                              ]
                  });

                }
                
                Swal.close();


              },
              error:function(err){
                console.log(err);
              }


            });








              
              
            });





          });

    </script>

    <!------------Debtor Report--------------------->
        <script type="text/javascript">

          var debtortbl = null;

            function downloaddebtorreport(){
            }

          $(document).ready(function(){
            downloaddebtorreport();

            // $('#myTable').DataTable();

            $("#btn-get-debtor-reports").click(function(event){

              if(debtortbl == null){
                        
                        
              }else{
                                                    
                debtortbl.clear().draw();
                debtortbl.destroy();
              }

              event.preventDefault();


              Swal.fire({
                  text: "Please wait...",
                  imageUrl:"assets/loader.gif",
                  showConfirmButton: false,
                  allowOutsideClick: false
                });


              

              $.ajax({
              url:'analytics/get_debtor_report.php',
              type:'POST',
              data:{
                    debtor_reports_start_date:$("#debtor-reports-start-date").val(),
                    debtor_reports_end_date:$("#debtor-reports-end-date").val(),
                    debtor_reports_distributor_id:$("#debtor-reports-distributor-id").val()
              },
              success:function(data){
                console.log(data);


                var json=JSON.parse(data);
                
                if(json.result){

                  $("#debtor-area").html(json.data);

                  debtortbl = $('#Debtor_Table').DataTable({ 
                    "order": [[ 1, "desc" ]],
                    "destroy": true, //use for reinitialize datatable
                    dom: 'Bfrtip',
                      buttons: [
                                {
                                    extend: 'print',
                                    title: 'Distributor Debtor Report (Using This Software)',
                                    exportOptions: {
                                        columns: ':visible'
                                    }
                                },
                                {
                                    extend: 'copy',
                                    title: 'Distributor Debtor Report (Using This Software)',
                                    exportOptions: {
                                        columns: ':visible'
                                    }
                                },
                                {
                                    extend: 'excelHtml5',
                                    title: 'Distributor Debtor Report (Using This Software)',
                                    exportOptions: {
                                        columns: ':visible'
                                    }
                                },
                                {
                                    extend: 'pdfHtml5',
                                    title: 'Distributor Debtor Report (Using This Software)',
                                    exportOptions: {
                                        columns: ':visible'
                                    }
                                },
                                  'colvis'
                              ]
                  });

                }
                
                Swal.close();


              },
              error:function(err){
                console.log(err);
              }


            });








              
              
            });





          });

    </script>
    


    <!------------Free Issue Report--------------------->
        <script type="text/javascript">

          var freeissuetbl = null;

            function downloadfreeissuereport(){
            }

          $(document).ready(function(){
            downloadfreeissuereport();

            // $('#myTable').DataTable();

            $("#btn-get-free-issue-reports").click(function(event){

              if(freeissuetbl == null){
                        
                        
              }else{
                                                    
                freeissuetbl.clear().draw();
                freeissuetbl.destroy();
              }

              event.preventDefault();


              Swal.fire({
                  text: "Please wait...",
                  imageUrl:"assets/loader.gif",
                  showConfirmButton: false,
                  allowOutsideClick: false
                });


              

              $.ajax({
              url:'analytics/get_free_issue_report.php',
              type:'POST',
              data:{
                    free_issue_start_date:$("#free-issue-reports-start-date").val(),
                    free_issue_end_date:$("#free-issue-reports-end-date").val(),
                    free_issue_distributor_id:$("#free-issue-reports-distributor-id").val()
              },
              success:function(data){
                console.log(data);


                var json=JSON.parse(data);
                
                if(json.result){

                  $("#free-issue-area").html(json.data);

                  freeissuetbl = $('#Free_Issue_Table').DataTable({ 
                    "order": [[ 1, "desc" ]],
                    "destroy": true, //use for reinitialize datatable
                    dom: 'Bfrtip',
                      buttons: [
                                {
                                    extend: 'print',
                                    title: 'Free Issue Report (Using This Software)',
                                    exportOptions: {
                                        columns: ':visible'
                                    }
                                },
                                {
                                    extend: 'copy',
                                    title: 'Free Issue Report (Using This Software)',
                                    exportOptions: {
                                        columns: ':visible'
                                    }
                                },
                                {
                                    extend: 'excelHtml5',
                                    title: 'Free Issue Report (Using This Software)',
                                    exportOptions: {
                                        columns: ':visible'
                                    }
                                },
                                {
                                    extend: 'pdfHtml5',
                                    title: 'Free Issue Report (Using This Software)',
                                    exportOptions: {
                                        columns: ':visible'
                                    }
                                },
                                  'colvis'
                              ]
                  });

                }
                
                Swal.close();


              },
              error:function(err){
                console.log(err);
              }


            });








              
              
            });





          });

    </script>
    
    
    





    <!------------Return Items Report--------------------->
        <script type="text/javascript">

          var returnitemtbl = null;

            function downloadreturnitemsreport(){
            }

          $(document).ready(function(){
            downloadreturnitemsreport();

            // $('#myTable').DataTable();

            $("#btn-get-return-item-reports").click(function(event){

              if(returnitemtbl == null){
                        
                        
              }else{
                                                    
                returnitemtbl.clear().draw();
                returnitemtbl.destroy();
              }

              event.preventDefault();


              Swal.fire({
                  text: "Please wait...",
                  imageUrl:"assets/loader.gif",
                  showConfirmButton: false,
                  allowOutsideClick: false
                });


              

              $.ajax({
              url:'analytics/get_return_items_report.php',
              type:'POST',
              data:{
                    return_item_start_date:$("#return-item-reports-start-date").val(),
                    return_item_end_date:$("#return-item-reports-end-date").val(),
                    return_item_distributor_id:$("#return-item-reports-distributor-id").val(),
                    return_report_type:$("#return-reports-type").val()
              },
              success:function(data){
                console.log(data);


                var json=JSON.parse(data);
                
                if(json.result){

                  $("#return-items-area").html(json.data);

                  returnitemtbl = $('#Return_Items_Table').DataTable({ 
                    "order": [[ 1, "desc" ]],
                    "destroy": true, //use for reinitialize datatable
                    dom: 'Bfrtip',
                      buttons: [
                                {
                                    extend: 'print',
                                    title: 'Return Item Report (Using This Software)',
                                    exportOptions: {
                                        columns: ':visible'
                                    }
                                },
                                {
                                    extend: 'copy',
                                    title: 'Return Item Report (Using This Software)',
                                    exportOptions: {
                                        columns: ':visible'
                                    }
                                },
                                {
                                    extend: 'excelHtml5',
                                    title: 'Return Item Report (Using This Software)',
                                    exportOptions: {
                                        columns: ':visible'
                                    }
                                },
                                {
                                    extend: 'pdfHtml5',
                                    title: 'Return Item Report (Using This Software)',
                                    exportOptions: {
                                        columns: ':visible'
                                    }
                                },
                                  'colvis'
                              ]
                  });

                }
                
                Swal.close();


              },
              error:function(err){
                console.log(err);
              }


            });








              
              
            });





          });

    </script>



    <!------------Shop Debtor Report--------------------->
        <script type="text/javascript">

          var shopdebtortbl = null;

            function downloadshopdebtorreport(){
            }

          $(document).ready(function(){
            downloadshopdebtorreport();

            // $('#myTable').DataTable();

            $("#btn-get-shop-debtor-reports").click(function(event){

              if(shopdebtortbl == null){
                        
                        
              }else{
                                                    
                shopdebtortbl.clear().draw();
                shopdebtortbl.destroy();
              }

              event.preventDefault();


              Swal.fire({
                  text: "Please wait...",
                  imageUrl:"assets/loader.gif",
                  showConfirmButton: false,
                  allowOutsideClick: false
                });


              

              $.ajax({
              url:'analytics/get_shop_debtor_report.php',
              type:'POST',
              data:{
                    shop_debtor_reports_start_date:$("#shop-debtor-reports-start-date").val(),
                    shop_debtor_reports_end_date:$("#shop-debtor-reports-end-date").val(),
                    shop_debtor_reports_route_id:$("#shop-debtor-reports-route-id").val()
              },
              success:function(data){
                console.log(data);


                var json=JSON.parse(data);
                
                if(json.result){

                  $("#shop-debtor-area").html(json.data);

                  shopdebtortbl = $('#Shop_Debtor_Table').DataTable({ 
                    "order": [[ 1, "desc" ]],
                    "destroy": true, //use for reinitialize datatable
                    dom: 'Bfrtip',
                      buttons: [
                                {
                                    extend: 'print',
                                    title: 'Shop Debtor Report (Using This Software)',
                                    exportOptions: {
                                        columns: ':visible'
                                    }
                                },
                                {
                                    extend: 'copy',
                                    title: 'Shop Debtor Report (Using This Software)',
                                    exportOptions: {
                                        columns: ':visible'
                                    }
                                },
                                {
                                    extend: 'excelHtml5',
                                    title: 'Shop Debtor Report (Using This Software)',
                                    exportOptions: {
                                        columns: ':visible'
                                    }
                                },
                                {
                                    extend: 'pdfHtml5',
                                    title: 'Shop Debtor Report (Using This Software)',
                                    exportOptions: {
                                        columns: ':visible'
                                    }
                                },
                                  'colvis'
                              ]
                  });

                }
                
                Swal.close();


              },
              error:function(err){
                console.log(err);
              }


            });








              
              
            });





          });

    </script>
    
    

</body>
</html>