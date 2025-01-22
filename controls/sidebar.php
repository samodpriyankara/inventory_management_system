<?php
    // session_start();
    $path = $_SERVER['PHP_SELF'];
    $page = basename($path);
    $page = basename($path, '.php');
?> 
<style>
    
    .app-fab--absolute {
        
        top: 0.5rem;
        right: 15rem;
        
    }
    
</style>

        <div class="mdk-drawer  js-mdk-drawer" id="default-drawer" data-align="start">
            <div class="mdk-drawer__content">
                <div class="sidebar sidebar-dark sidebar-left simplebar" data-simplebar>
                    <div class="d-flex align-items-center sidebar-p-a border-bottom sidebar-account flex-shrink-0">
                        <a href="dashboard" class="flex d-flex align-items-center text-underline-0 text-body">
                            <span class="mr-3">
                                <img src="assets/img/logo-only.png" width="43" height="43" alt="avatar">
                            </span>
                            <span class="flex d-flex flex-column">
                                <strong style="font-size: 1.125rem;">Smart Salesman</strong>
                                <small class="text-muted text-uppercase" style="color: rgba(255,255,255,.54)">
                                    <?php
                                        if($is_distributor){
                                            echo "Distributor";
                                        }else{
                                            echo "Administrator";
                                        }
                                    ?>
                                </small>
                            </span>
                        </a>
                        <div class="dropdown ml-auto">
                            <a href="#" data-toggle="dropdown" data-caret="false" class="text-muted"><i class="material-icons">keyboard_arrow_down</i></a>
                            <div class="dropdown-menu dropdown-menu-right">
                                <div class="dropdown-item-text dropdown-item-text--lh">
                                    
                                    
                                        <?php if($is_distributor){ ?>
                                            <?php
                                                $DistributorNameSql = "SELECT * FROM tbl_distributor WHERE distributor_id='$user_id'";
                                                $DistributorNameRs=$conn->query($DistributorNameSql);
                                                if($DNrow=$DistributorNameRs->fetch_array())
                                                {  
                                                    $DistributorName=$DNrow[1];
                                                }
                                            ?>
                                            <div><strong><?php echo $DistributorName; ?></strong></div>
                                            <div>@Distributor</div>
                                        <?php }else{ ?>
                                            <?php
                                                $WebUserNameSql = "SELECT * FROM tbl_web_console_user_account WHERE user_id='$user_id'";
                                                $WebUserNameRs=$conn->query($WebUserNameSql);
                                                if($WUNrow=$WebUserNameRs->fetch_array())
                                                {  
                                                    $AdminName=$WUNrow[6];
                                                    $AdminStat=$WUNrow[5];
                                                }
                                            ?>
                                            <div><strong><?php echo $AdminName; ?></strong></div>
                                            <div>
                                                <?php if($AdminStat=='1') {
                                                   echo '@SuperAdmin';
                                                }elseif($AdminStat=='2'){
                                                    echo '@Stock';
                                                }else{
                                                    echo '@Accounts';
                                                } ?>
                                                
                                            </div>
                                        <?php } ?>
                                </div>
                                <div class="dropdown-divider"></div>
                                <a class="dropdown-item active" href="dashboard">Dashboard</a>
                                <a class="dropdown-item" href="shop">All Shops</a>
                                <?php  if($is_distributor){ ?>
                                    <a class="dropdown-item" href="view_distributor_profile?d=<?php echo base64_encode($user_id); ?>">My Profile</a>
                                <?php }else{ ?>
                                    <a class="dropdown-item" href="sales_report">Sales Report</a>
                                <?php } ?>

                                <div class="dropdown-divider"></div>
                                <a class="dropdown-item" href="scripts/signout_from_web_system">Logout</a>
                            </div>
                        </div>
                    </div>
                    <div class="sidebar-stats row no-gutters align-items-center text-center border-bottom flex-shrink-0">
                        <div class="sidebar-stats__col col">
                            <div class="sidebar-stats__title">Shops</div>
                            
                            <?php
                                if($is_distributor){
                                    $OutletCountSql = "SELECT COUNT(*) FROM tbl_outlet";
                                }else{
                                    $OutletCountSql = "SELECT COUNT(*) FROM tbl_outlet";
                                }
                                $OutletCountResult = mysqli_query($conn, $OutletCountSql);
                                $OutletCount = mysqli_fetch_assoc($OutletCountResult)['COUNT(*)'];
                                // echo $RouteCount;
                            ?>

                            <div class="sidebar-stats__value"><?php echo $OutletCount; ?></div>
                        </div>
                        <div class="sidebar-stats__col col border-left">
                            <div class="sidebar-stats__title">Sales-Reps</div>

                            <?php
                                if($is_distributor){
                                    $SalesRepCountSql = "SELECT COUNT(*) FROM tbl_user tu INNER JOIN tbl_distributor_has_tbl_user tdhtu ON tu.id=tdhtu.user_id WHERE distributor_id='$user_id'";
                                }else{
                                    $SalesRepCountSql = "SELECT COUNT(*) FROM tbl_user";
                                }
                                $SalesRepCountResult = mysqli_query($conn, $SalesRepCountSql);
                                $SalesRepCount = mysqli_fetch_assoc($SalesRepCountResult)['COUNT(*)'];
                                // echo $RouteCount;
                            ?>

                            <div class="sidebar-stats__value"><?php echo $SalesRepCount; ?></div>
                        </div>
                    </div>

                    <div class="py-4 text-center flex-shrink-0">
                        <a style="min-width: 157px;" href="create_invoice" class="btn btn-primary">Create Invoice <i class="material-icons ml-1">add</i></a>
                    </div>

                    <ul class="nav nav-tabs sidebar-tabs flex-shrink-0" role="tablist">
                        <li class="nav-item"><a class="nav-link active show" id="sm-menu-tab" href="#sm-menu" data-toggle="tab" role="tab" aria-controls="sm-menu" aria-selected="true">Menu</a></li>
                        <!-- <li class="nav-item"><a class="nav-link" href="#sm-account" data-toggle="tab">Account</a></li> -->
                        <!-- <li class="nav-item"><a class="nav-link" href="#sm-settings" data-toggle="tab">Settings</a></li> -->
                    </ul>

                    <?php if($is_distributor){  ?>

                        <!------------------------------------Start Distributor Area----------------------------------------->
                        <div class="tab-content">
                            <div id="sm-menu" class="tab-pane show active" role="tabpanel" aria-labelledby="sm-menu-tab">
                                <ul class="sidebar-menu flex">
                                    <li class="sidebar-menu-item <?php if ($page == 'dashboard') echo 'active';?>">
                                        <a class="sidebar-menu-button" href="dashboard">
                                            <i class="sidebar-menu-icon sidebar-menu-icon--left material-icons">dvr</i>
                                            <span class="sidebar-menu-text">Dashboard</span>
                                        </a>
                                    </li>

                                    <li class="sidebar-menu-item <?php if ($page == 'view_distributor_profile') echo 'active';?>">
                                        <a class="sidebar-menu-button" href="view_distributor_profile?d=<?php echo base64_encode($user_id); ?>">
                                            <i class="sidebar-menu-icon sidebar-menu-icon--left material-icons">account_circle</i>
                                            <span class="sidebar-menu-text">My Profile</span>
                                        </a>
                                    </li>

                                    <li class="sidebar-menu-item <?php if ($page == 'distributor_invoice_history') echo 'active';?>">
                                        <a class="sidebar-menu-button" href="distributor_invoice_history">
                                            <i class="sidebar-menu-icon sidebar-menu-icon--left material-icons">library_books</i>
                                            <span class="sidebar-menu-text">My Invoice History</span>
                                        </a>
                                    </li>

                                    <li class="sidebar-menu-item <?php if ($page == 'shop' || $page == 'shop_route' || $page == 'register_outlet' || $page == 'unproductive_shop') echo 'active open';?>">
                                        <a class="sidebar-menu-button" data-toggle="collapse" href="#pages_menu">
                                            <i class="sidebar-menu-icon sidebar-menu-icon--left material-icons">business</i>
                                            <span class="sidebar-menu-text">Shops</span>
                                            <!-- <span class="badge badge-warning rounded-circle badge-notifications ml-auto" style="padding: .1875rem .375rem;">8</span>
                                            <span class="sidebar-menu-toggle-icon"></span> -->
                                            <span class="ml-auto sidebar-menu-toggle-icon"></span>
                                        </a>
                                        <ul class="sidebar-submenu collapse" id="pages_menu">
                                            <li class="sidebar-menu-item <?php if ($page == 'shop') echo 'active';?>">
                                                <a class="sidebar-menu-button" href="shop">
                                                    <span class="sidebar-menu-text">All Shops</span>
                                                </a>
                                            </li>
                                            <li class="sidebar-menu-item <?php if ($page == 'shop_route') echo 'active';?>">
                                                <a class="sidebar-menu-button" href="shop_route">
                                                    <span class="sidebar-menu-text">Shop Route</span>
                                                </a>
                                            </li>
                                            <li class="sidebar-menu-item <?php if ($page == 'register_outlet') echo 'active';?>">
                                                <a class="sidebar-menu-button" href="register_outlet">
                                                    <span class="sidebar-menu-text">Register Shop</span>
                                                </a>
                                            </li>
                                            <li class="sidebar-menu-item <?php if ($page == 'unproductive_shop') echo 'active';?>">
                                                <a class="sidebar-menu-button" href="unproductive_shop">
                                                    <span class="sidebar-menu-text">Unproductive Shop</span>
                                                </a>
                                            </li>
                                        </ul>
                                    </li>

                                    <li class="sidebar-menu-item <?php if ($page == 'create_invoice' || $page == 'invoice_history' || $page == 'credit_invoice_history') echo 'active open';?>">
                                        <a class="sidebar-menu-button" data-toggle="collapse" href="#components_menu">
                                            <i class="sidebar-menu-icon sidebar-menu-icon--left material-icons">account_balance_wallet</i>
                                            <span class="sidebar-menu-text">Invoices</span>
                                            <span class="ml-auto sidebar-menu-toggle-icon"></span>
                                        </a>
                                        <ul class="sidebar-submenu collapse" id="components_menu">
                                            <li class="sidebar-menu-item <?php if ($page == 'create_invoice') echo 'active';?>">
                                                <a class="sidebar-menu-button" href="create_invoice">
                                                    <span class="sidebar-menu-text">Create Invoice</span>
                                                </a>
                                            </li>
                                            <li class="sidebar-menu-item <?php if ($page == 'invoice_history') echo 'active';?>">
                                                <a class="sidebar-menu-button" href="invoice_history">
                                                    <span class="sidebar-menu-text">Invoice History</span>
                                                </a>
                                            </li>
                                            <li class="sidebar-menu-item <?php if ($page == 'credit_invoice_history') echo 'active';?>">
                                                <a class="sidebar-menu-button" href="credit_invoice_history">
                                                    <span class="sidebar-menu-text">Credit Invoice History</span>
                                                </a>
                                            </li>
                                        </ul>
                                    </li>

                                    <li class="sidebar-menu-item <?php if ($page == 'orders_to_delivery') echo 'active';?>">
                                        <a class="sidebar-menu-button" href="orders_to_delivery">
                                            <i class="sidebar-menu-icon sidebar-menu-icon--left material-icons">time_to_leave</i>
                                            <span class="sidebar-menu-text">Orders To Delivery</span>
                                        </a>
                                    </li>

                                    <li class="sidebar-menu-item <?php if ($page == 'return_history') echo 'active';?>">
                                        <a class="sidebar-menu-button" href="return_history">
                                            <i class="sidebar-menu-icon sidebar-menu-icon--left material-icons">history</i>
                                            <span class="sidebar-menu-text">Return History</span>
                                        </a>
                                    </li>

                                    <li class="sidebar-menu-item <?php if ($page == 'routeassignment') echo 'active';?>">
                                        <a class="sidebar-menu-button" href="routeassignment">
                                            <i class="sidebar-menu-icon sidebar-menu-icon--left material-icons">map</i>
                                            <span class="sidebar-menu-text">Set Route to Rep</span>
                                        </a>
                                    </li>

                                    <li class="sidebar-menu-item <?php if ($page == 'stock') echo 'active';?>">
                                        <a class="sidebar-menu-button" href="stock">
                                            <i class="sidebar-menu-icon sidebar-menu-icon--left material-icons">gradient</i>
                                            <span class="sidebar-menu-text">View Stock</span>
                                        </a>
                                    </li>

                                    <li class="sidebar-menu-item <?php if ($page == 'user' || $page == 'attendance_report' || $page == 'sales_rep' || $page == 'tracking') echo 'active open';?>">
                                        <a class="sidebar-menu-button" data-toggle="collapse" href="#sales_rep_details_menu">
                                            <i class="sidebar-menu-icon sidebar-menu-icon--left material-icons">person_pin</i>
                                            <span class="sidebar-menu-text">Sales-Rep Details</span>
                                            <span class="ml-auto sidebar-menu-toggle-icon"></span>
                                        </a>
                                        <ul class="sidebar-submenu collapse" id="sales_rep_details_menu">
                                            <li class="sidebar-menu-item <?php if ($page == 'user') echo 'active';?>">
                                                <a class="sidebar-menu-button" href="user">
                                                    <span class="sidebar-menu-text">Create Sales-Rep Account</span>
                                                </a>
                                            </li>
                                            <li class="sidebar-menu-item <?php if ($page == 'attendance_report') echo 'active';?>">
                                                <a class="sidebar-menu-button" href="attendance_report">
                                                    <span class="sidebar-menu-text">Sales-Rep Attendence</span>
                                                </a>
                                            </li>
                                            <li class="sidebar-menu-item <?php if ($page == 'sales_rep') echo 'active';?>">
                                                <a class="sidebar-menu-button" href="sales_rep">
                                                    <span class="sidebar-menu-text">Sales-Rep Performence</span>
                                                </a>
                                            </li>
                                            <li class="sidebar-menu-item <?php if ($page == 'tracking') echo 'active';?>">
                                                <a class="sidebar-menu-button" href="tracking">
                                                    <span class="sidebar-menu-text">Sales-Rep Live Tracking</span>
                                                </a>
                                            </li>
                                        </ul>
                                    </li>

                                    <li class="sidebar-menu-item <?php if ($page == 'download') echo 'active';?>">
                                        <a class="sidebar-menu-button" href="download">
                                            <i class="sidebar-menu-icon sidebar-menu-icon--left material-icons">touch_app</i>
                                            <span class="sidebar-menu-text">Download Sales-Rep App</span>
                                        </a>
                                    </li>
                                </ul>
                            </div>
                            <div id="sm-account" class="tab-pane">
                                <ul class="sidebar-menu">
                                    <li class="sidebar-menu-item">
                                        <a class="sidebar-menu-button" href="edit-account.html">
                                            <span class="sidebar-menu-text">Edit Information</span>
                                        </a>
                                    </li>
                                    <li class="sidebar-menu-item">
                                        <a class="sidebar-menu-button" href="edit-account.html">
                                            <span class="sidebar-menu-text">Payments</span>
                                        </a>
                                    </li>
                                    <li class="sidebar-menu-item">
                                        <a class="sidebar-menu-button" href="edit-account.html">
                                            <span class="sidebar-menu-text">Billing</span>
                                        </a>
                                    </li>
                                    <li class="sidebar-menu-item">
                                        <a class="sidebar-menu-button" href="edit-account.html">
                                            <span class="sidebar-menu-text">Change Password</span>
                                        </a>
                                    </li>
                                    <li class="sidebar-menu-item">
                                        <a class="sidebar-menu-button" href="#!">
                                            <span class="sidebar-menu-text">Logout</span>
                                        </a>
                                    </li>
                                </ul>
                            </div>
                            
                        </div>


                        <!------------------------------------End Distributor Area----------------------------------------->



                    <?php }elseif($_SESSION['user'] == 'user'){ ?>
                        <!------------------------------------Start Admin Area----------------------------------------->
                        <div class="tab-content">
                            <div id="sm-menu" class="tab-pane show active" role="tabpanel" aria-labelledby="sm-menu-tab">
                                <ul class="sidebar-menu flex">
                                    <li class="sidebar-menu-item <?php if ($page == 'dashboard') echo 'active';?>">
                                        <a class="sidebar-menu-button" href="dashboard.php">
                                            <i class="sidebar-menu-icon sidebar-menu-icon--left material-icons">dvr</i>
                                            <span class="sidebar-menu-text">Dashboard</span>
                                        </a>
                                    </li>

                                    <li class="sidebar-menu-item <?php if ($page == 'shop' || $page == 'shop_route' || $page == 'register_outlet' || $page == 'unproductive_shop' || $page == 'routes' || $page == 'routeassignment') echo 'active open';?>">
                                        <a class="sidebar-menu-button" data-toggle="collapse" href="#pages_menu">
                                            <i class="sidebar-menu-icon sidebar-menu-icon--left material-icons">business</i>
                                            <span class="sidebar-menu-text">Shops</span>
                                            <!-- <span class="badge badge-warning rounded-circle badge-notifications ml-auto" style="padding: .1875rem .375rem;">8</span>
                                            <span class="sidebar-menu-toggle-icon"></span> -->
                                            <span class="ml-auto sidebar-menu-toggle-icon"></span>
                                        </a>
                                        <ul class="sidebar-submenu collapse" id="pages_menu">
                                            <li class="sidebar-menu-item <?php if ($page == 'shop') echo 'active';?>">
                                                <a class="sidebar-menu-button" href="shop.php">
                                                    <span class="sidebar-menu-text">All Shops</span>
                                                </a>
                                            </li>
                                            
                                            <li class="sidebar-menu-item <?php if ($page == 'routes') echo 'active';?>">
                                                <a class="sidebar-menu-button" href="routes.php">
                                                    <span class="sidebar-menu-text">Routes</span>
                                                </a>
                                            </li>
                                            <li class="sidebar-menu-item <?php if ($page == 'routeassignment') echo 'active';?>">
                                                <a class="sidebar-menu-button" href="routeassignment.php">
                                                    <span class="sidebar-menu-text">Set Route to Rep</span>
                                                </a>
                                            </li>
                                            
                                            
                                            <li class="sidebar-menu-item <?php if ($page == 'shop_route') echo 'active';?>">
                                                <a class="sidebar-menu-button" href="shop_route.php">
                                                    <span class="sidebar-menu-text">Shop Route</span>
                                                </a>
                                            </li>
                                            <li class="sidebar-menu-item <?php if ($page == 'register_outlet') echo 'active';?>">
                                                <a class="sidebar-menu-button" href="register_outlet.php">
                                                    <span class="sidebar-menu-text">Register Shop</span>
                                                </a>
                                            </li>
                                            <li class="sidebar-menu-item <?php if ($page == 'unproductive_shop') echo 'active';?>">
                                                <a class="sidebar-menu-button" href="unproductive_shop.php">
                                                    <span class="sidebar-menu-text">Unproductive Shop</span>
                                                </a>
                                            </li>
                                        </ul>
                                    </li>

                                    <li class="sidebar-menu-item <?php if ($page == 'create_invoice' || $page == 'invoice_history' || $page == 'credit_invoice_history' || $page == 'cheque_history') echo 'active open';?>">
                                        <a class="sidebar-menu-button" data-toggle="collapse" href="#components_menu">
                                            <i class="sidebar-menu-icon sidebar-menu-icon--left material-icons">account_balance_wallet</i>
                                            <span class="sidebar-menu-text">Invoices</span>
                                            <span class="ml-auto sidebar-menu-toggle-icon"></span>
                                        </a>
                                        <ul class="sidebar-submenu collapse" id="components_menu">
                                            <li class="sidebar-menu-item <?php if ($page == 'create_invoice') echo 'active';?>">
                                                <a class="sidebar-menu-button" href="create_invoice.php">
                                                    <span class="sidebar-menu-text">Create Invoice</span>
                                                </a>
                                            </li>
                                            <li class="sidebar-menu-item <?php if ($page == 'invoice_history') echo 'active';?>">
                                                <a class="sidebar-menu-button" href="invoice_history.php">
                                                    <span class="sidebar-menu-text">Invoice History</span>
                                                </a>
                                            </li>
                                            <li class="sidebar-menu-item <?php if ($page == 'credit_invoice_history') echo 'active';?>">
                                                <a class="sidebar-menu-button" href="credit_invoice_history.php">
                                                    <span class="sidebar-menu-text">Credit Invoice History</span>
                                                </a>
                                            </li>
                                            <li class="sidebar-menu-item <?php if ($page == 'cheque_history') echo 'active';?>">
                                                <a class="sidebar-menu-button" href="cheque_history.php">
                                                    <span class="sidebar-menu-text">Cheque History</span>
                                                </a>
                                            </li>
                                        </ul>
                                    </li>

                                    <li class="sidebar-menu-item <?php if ($page == 'return_history') echo 'active';?>">
                                        <a class="sidebar-menu-button" href="return_history">
                                            <i class="sidebar-menu-icon sidebar-menu-icon--left material-icons">history</i>
                                            <span class="sidebar-menu-text">Return History</span>
                                        </a>
                                    </li>

                                    <li class="sidebar-menu-item <?php if ($page == 'products' || $page == 'create_grn' || $page == 'view_grn_list' || $page == 'add_product_price_batch' || $page == 'category_discounts') echo 'active open';?>">
                                        <a class="sidebar-menu-button" data-toggle="collapse" href="#products_menu">
                                            <i class="sidebar-menu-icon sidebar-menu-icon--left material-icons">photo_filter</i>
                                            <span class="sidebar-menu-text">Products</span>
                                            <span class="ml-auto sidebar-menu-toggle-icon"></span>
                                        </a>
                                        <ul class="sidebar-submenu collapse" id="products_menu">
                                            <li class="sidebar-menu-item <?php if ($page == 'products') echo 'active';?>">
                                                <a class="sidebar-menu-button" href="products.php">
                                                    <span class="sidebar-menu-text">Add Products</span>
                                                </a>
                                            </li>
                                            <li class="sidebar-menu-item <?php if ($page == 'add_product_price_batch') echo 'active';?>">
                                                <a class="sidebar-menu-button" href="add_product_price_batch.php">
                                                    <span class="sidebar-menu-text">Add Product Price Batch</span>
                                                </a>
                                            </li>
                                            <li class="sidebar-menu-item <?php if ($page == 'create_grn') echo 'active';?>">
                                                <a class="sidebar-menu-button" href="create_grn.php">
                                                    <span class="sidebar-menu-text">Create GRN</span>
                                                </a>
                                            </li>
                                            <li class="sidebar-menu-item <?php if ($page == 'view_grn_list') echo 'active';?>">
                                                <a class="sidebar-menu-button" href="view_grn_list.php">
                                                    <span class="sidebar-menu-text">View GRN List</span>
                                                </a>
                                            </li>
                                            
                                            <li class="sidebar-menu-item <?php if ($page == 'category_discounts') echo 'active';?>">
                                                <a class="sidebar-menu-button" href="category_discounts.php">
                                                    <span class="sidebar-menu-text">Add Discounts For Category</span>
                                                </a>
                                            </li>
                                            
                                            
                                        </ul>
                                    </li>

                                    <li class="sidebar-menu-item <?php if ($page == 'stock' || $page == 'add_stock_invoice' || $page == 'stock_invoice_history') echo 'active open';?>">
                                        <a class="sidebar-menu-button" data-toggle="collapse" href="#stock_menu">
                                            <i class="sidebar-menu-icon sidebar-menu-icon--left material-icons">gradient</i>
                                            <span class="sidebar-menu-text">Stock</span>
                                            <span class="ml-auto sidebar-menu-toggle-icon"></span>
                                        </a>
                                        <ul class="sidebar-submenu collapse" id="stock_menu">
                                            <li class="sidebar-menu-item <?php if ($page == 'stock') echo 'active';?>">
                                                <!--<a class="sidebar-menu-button" href="stock">-->
                                                <!--    <span class="sidebar-menu-text">View Stock</span>-->
                                                <!--</a>-->
                                                
                                                 <a class="sidebar-menu-button" href="view_distributor_stock.php?distributor=Mg==">
                                                    <span class="sidebar-menu-text">View Stock</span>
                                                </a>
                                                
                                                
                                                
                                                
                                            </li>
                                            <li class="sidebar-menu-item <?php if ($page == 'add_stock_invoice') echo 'active';?>">
                                                <a class="sidebar-menu-button" href="add_stock_invoice.php">
                                                    <span class="sidebar-menu-text">Add Stock</span>
                                                </a>
                                            </li>
                                            <li class="sidebar-menu-item <?php if ($page == 'stock_invoice_history') echo 'active';?>">
                                                <a class="sidebar-menu-button" href="stock_invoice_history.php">
                                                    <span class="sidebar-menu-text">View Stock Adding History</span>
                                                </a>
                                            </li>
                                        </ul>
                                    </li>

                                    <li class="sidebar-menu-item <?php if ($page == 'accounts_manage') echo 'active';?>">
                                        <a class="sidebar-menu-button" href="accounts.php">
                                            <i class="sidebar-menu-icon sidebar-menu-icon--left material-icons">account_balance</i>
                                            <span class="sidebar-menu-text">Accounts</span>
                                        </a>
                                    </li>

                                   
                                   
                                </ul>
                            </div>
                            <div id="sm-account" class="tab-pane">
                                <ul class="sidebar-menu">
                                    <li class="sidebar-menu-item">
                                        <a class="sidebar-menu-button" href="edit-account.html">
                                            <span class="sidebar-menu-text">Edit Information</span>
                                        </a>
                                    </li>
                                    <li class="sidebar-menu-item">
                                        <a class="sidebar-menu-button" href="edit-account.html">
                                            <span class="sidebar-menu-text">Payments</span>
                                        </a>
                                    </li>
                                    <li class="sidebar-menu-item">
                                        <a class="sidebar-menu-button" href="edit-account.html">
                                            <span class="sidebar-menu-text">Billing</span>
                                        </a>
                                    </li>
                                    <li class="sidebar-menu-item">
                                        <a class="sidebar-menu-button" href="edit-account.html">
                                            <span class="sidebar-menu-text">Change Password</span>
                                        </a>
                                    </li>
                                    <li class="sidebar-menu-item">
                                        <a class="sidebar-menu-button" href="#!">
                                            <span class="sidebar-menu-text">Logout</span>
                                        </a>
                                    </li>
                                </ul>
                            </div>
                            
                        </div>

                        <!------------------------------------End Admin Area----------------------------------------->
                <?php }else{ ?>

                     <!------------------------------------Start Admin Area----------------------------------------->
                     <div class="tab-content">
                            <div id="sm-menu" class="tab-pane show active" role="tabpanel" aria-labelledby="sm-menu-tab">
                                <ul class="sidebar-menu flex">
                                    <li class="sidebar-menu-item <?php if ($page == 'dashboard') echo 'active';?>">
                                        <a class="sidebar-menu-button" href="dashboard.php">
                                            <i class="sidebar-menu-icon sidebar-menu-icon--left material-icons">dvr</i>
                                            <span class="sidebar-menu-text">Dashboard</span>
                                        </a>
                                    </li>

                                    <li class="sidebar-menu-item <?php if ($page == 'shop' || $page == 'shop_route' || $page == 'register_outlet' || $page == 'unproductive_shop' || $page == 'routes' || $page == 'routeassignment') echo 'active open';?>">
                                        <a class="sidebar-menu-button" data-toggle="collapse" href="#pages_menu">
                                            <i class="sidebar-menu-icon sidebar-menu-icon--left material-icons">business</i>
                                            <span class="sidebar-menu-text">Shops</span>
                                            <!-- <span class="badge badge-warning rounded-circle badge-notifications ml-auto" style="padding: .1875rem .375rem;">8</span>
                                            <span class="sidebar-menu-toggle-icon"></span> -->
                                            <span class="ml-auto sidebar-menu-toggle-icon"></span>
                                        </a>
                                        <ul class="sidebar-submenu collapse" id="pages_menu">
                                            <li class="sidebar-menu-item <?php if ($page == 'shop') echo 'active';?>">
                                                <a class="sidebar-menu-button" href="shop.php">
                                                    <span class="sidebar-menu-text">All Shops</span>
                                                </a>
                                            </li>
                                            
                                            <li class="sidebar-menu-item <?php if ($page == 'routes') echo 'active';?>">
                                                <a class="sidebar-menu-button" href="routes.php">
                                                    <span class="sidebar-menu-text">Routes</span>
                                                </a>
                                            </li>
                                            <li class="sidebar-menu-item <?php if ($page == 'routeassignment') echo 'active';?>">
                                                <a class="sidebar-menu-button" href="routeassignment.php">
                                                    <span class="sidebar-menu-text">Set Route to Rep</span>
                                                </a>
                                            </li>
                                            
                                            
                                            <li class="sidebar-menu-item <?php if ($page == 'shop_route') echo 'active';?>">
                                                <a class="sidebar-menu-button" href="shop_route.php">
                                                    <span class="sidebar-menu-text">Shop Route</span>
                                                </a>
                                            </li>
                                            <li class="sidebar-menu-item <?php if ($page == 'register_outlet') echo 'active';?>">
                                                <a class="sidebar-menu-button" href="register_outlet.php">
                                                    <span class="sidebar-menu-text">Register Shop</span>
                                                </a>
                                            </li>
                                            <li class="sidebar-menu-item <?php if ($page == 'unproductive_shop') echo 'active';?>">
                                                <a class="sidebar-menu-button" href="unproductive_shop.php">
                                                    <span class="sidebar-menu-text">Unproductive Shop</span>
                                                </a>
                                            </li>
                                        </ul>
                                    </li>

                                    <li class="sidebar-menu-item <?php if ($page == 'create_invoice' || $page == 'invoice_history' || $page == 'credit_invoice_history' || $page == 'cheque_history') echo 'active open';?>">
                                        <a class="sidebar-menu-button" data-toggle="collapse" href="#components_menu">
                                            <i class="sidebar-menu-icon sidebar-menu-icon--left material-icons">account_balance_wallet</i>
                                            <span class="sidebar-menu-text">Invoices</span>
                                            <span class="ml-auto sidebar-menu-toggle-icon"></span>
                                        </a>
                                        <ul class="sidebar-submenu collapse" id="components_menu">
                                            <li class="sidebar-menu-item <?php if ($page == 'create_invoice') echo 'active';?>">
                                                <a class="sidebar-menu-button" href="create_invoice.php">
                                                    <span class="sidebar-menu-text">Create Invoice</span>
                                                </a>
                                            </li>
                                            <li class="sidebar-menu-item <?php if ($page == 'invoice_history') echo 'active';?>">
                                                <a class="sidebar-menu-button" href="invoice_history.php">
                                                    <span class="sidebar-menu-text">Invoice History</span>
                                                </a>
                                            </li>
                                            <li class="sidebar-menu-item <?php if ($page == 'credit_invoice_history') echo 'active';?>">
                                                <a class="sidebar-menu-button" href="credit_invoice_history.php">
                                                    <span class="sidebar-menu-text">Credit Invoice History</span>
                                                </a>
                                            </li>
                                            <li class="sidebar-menu-item <?php if ($page == 'cheque_history') echo 'active';?>">
                                                <a class="sidebar-menu-button" href="cheque_history.php">
                                                    <span class="sidebar-menu-text">Cheque History</span>
                                                </a>
                                            </li>
                                        </ul>
                                    </li>

                                    <li class="sidebar-menu-item <?php if ($page == 'orders_to_delivery') echo 'active';?>">
                                        <a class="sidebar-menu-button" href="orders_to_delivery.php">
                                            <i class="sidebar-menu-icon sidebar-menu-icon--left material-icons">time_to_leave</i>
                                            <span class="sidebar-menu-text">Orders To Delivery</span>
                                        </a>
                                    </li>

                                    <li class="sidebar-menu-item <?php if ($page == 'return_history') echo 'active';?>">
                                        <a class="sidebar-menu-button" href="return_history.php">
                                            <i class="sidebar-menu-icon sidebar-menu-icon--left material-icons">history</i>
                                            <span class="sidebar-menu-text">Return History</span>
                                        </a>
                                    </li>

                                    <!--<li class="sidebar-menu-item <?php if ($page == 'routes' || $page == 'routeassignment') echo 'active open';?>">-->
                                    <!--    <a class="sidebar-menu-button" data-toggle="collapse" href="#route_menu">-->
                                    <!--        <i class="sidebar-menu-icon sidebar-menu-icon--left material-icons">map</i>-->
                                    <!--        <span class="sidebar-menu-text">Manage Routes</span>-->
                                    <!--        <span class="ml-auto sidebar-menu-toggle-icon"></span>-->
                                    <!--    </a>-->
                                    <!--    <ul class="sidebar-submenu collapse" id="route_menu">-->
                                    <!--        <li class="sidebar-menu-item <?php if ($page == 'routes') echo 'active';?>">-->
                                    <!--            <a class="sidebar-menu-button" href="routes">-->
                                    <!--                <span class="sidebar-menu-text">Routes</span>-->
                                    <!--            </a>-->
                                    <!--        </li>-->
                                    <!--        <li class="sidebar-menu-item <?php if ($page == 'routeassignment') echo 'active';?>">-->
                                    <!--            <a class="sidebar-menu-button" href="routeassignment">-->
                                    <!--                <span class="sidebar-menu-text">Set Route to Rep</span>-->
                                    <!--            </a>-->
                                    <!--        </li>-->
                                    <!--    </ul>-->
                                    <!--</li>-->

                                    <li class="sidebar-menu-item <?php if ($page == 'products' || $page == 'create_grn' || $page == 'view_grn_list' || $page == 'add_product_price_batch' || $page == 'category_discounts') echo 'active open';?>">
                                        <a class="sidebar-menu-button" data-toggle="collapse" href="#products_menu">
                                            <i class="sidebar-menu-icon sidebar-menu-icon--left material-icons">photo_filter</i>
                                            <span class="sidebar-menu-text">Products</span>
                                            <span class="ml-auto sidebar-menu-toggle-icon"></span>
                                        </a>
                                        <ul class="sidebar-submenu collapse" id="products_menu">
                                            <li class="sidebar-menu-item <?php if ($page == 'products') echo 'active';?>">
                                                <a class="sidebar-menu-button" href="products.php">
                                                    <span class="sidebar-menu-text">Add Products</span>
                                                </a>
                                            </li>
                                            <li class="sidebar-menu-item <?php if ($page == 'add_product_price_batch') echo 'active';?>">
                                                <a class="sidebar-menu-button" href="add_product_price_batch.php">
                                                    <span class="sidebar-menu-text">Add Product Price Batch</span>
                                                </a>
                                            </li>
                                            <li class="sidebar-menu-item <?php if ($page == 'create_grn') echo 'active';?>">
                                                <a class="sidebar-menu-button" href="create_grn.php">
                                                    <span class="sidebar-menu-text">Create GRN</span>
                                                </a>
                                            </li>
                                            <li class="sidebar-menu-item <?php if ($page == 'view_grn_list') echo 'active';?>">
                                                <a class="sidebar-menu-button" href="view_grn_list.php">
                                                    <span class="sidebar-menu-text">View GRN List</span>
                                                </a>
                                            </li>
                                            
                                            <li class="sidebar-menu-item <?php if ($page == 'product_selling_list') echo 'active';?>">
                                                <a class="sidebar-menu-button" href="product_selling_list.php">
                                                    <span class="sidebar-menu-text">Products Bin</span>
                                                </a>
                                            </li> 
                                            
                                            <li class="sidebar-menu-item <?php if ($page == 'category_discounts') echo 'active';?>">
                                                <a class="sidebar-menu-button" href="category_discounts.php">
                                                    <span class="sidebar-menu-text">Add Discounts For Category</span>
                                                </a>
                                            </li>
                                            
                                            
                                        </ul>
                                    </li>

                                    <li class="sidebar-menu-item <?php if ($page == 'stock' || $page == 'add_stock_invoice' || $page == 'stock_invoice_history') echo 'active open';?>">
                                        <a class="sidebar-menu-button" data-toggle="collapse" href="#stock_menu">
                                            <i class="sidebar-menu-icon sidebar-menu-icon--left material-icons">gradient</i>
                                            <span class="sidebar-menu-text">Stock</span>
                                            <span class="ml-auto sidebar-menu-toggle-icon"></span>
                                        </a>
                                        <ul class="sidebar-submenu collapse" id="stock_menu">
                                            <li class="sidebar-menu-item <?php if ($page == 'stock') echo 'active';?>">
                                                <!--<a class="sidebar-menu-button" href="stock">-->
                                                <!--    <span class="sidebar-menu-text">View Stock</span>-->
                                                <!--</a>-->
                                                
                                                 <a class="sidebar-menu-button" href="view_distributor_stock.php?distributor=Mg==">
                                                    <span class="sidebar-menu-text">View Stock</span>
                                                </a>
                                                
                                                
                                                
                                                
                                            </li>
                                            <li class="sidebar-menu-item <?php if ($page == 'add_stock_invoice') echo 'active';?>">
                                                <a class="sidebar-menu-button" href="add_stock_invoice.php">
                                                    <span class="sidebar-menu-text">Add Stock</span>
                                                </a>
                                            </li>
                                            <li class="sidebar-menu-item <?php if ($page == 'stock_invoice_history') echo 'active';?>">
                                                <a class="sidebar-menu-button" href="stock_invoice_history.php">
                                                    <span class="sidebar-menu-text">View Stock Adding History</span>
                                                </a>
                                            </li>
                                        </ul>
                                    </li>

                                    <li class="sidebar-menu-item <?php if ($page == 'distributor' || $page == 'create_distributor_invoice' || $page == 'distributor_invoice_history' || $page == 'distributors_performence' || $page == 'set_distributor_targets') echo 'active open';?>">
                                        <a class="sidebar-menu-button" data-toggle="collapse" href="#users_and_settings_menu">
                                            <i class="sidebar-menu-icon sidebar-menu-icon--left material-icons">supervisor_account</i>
                                            <span class="sidebar-menu-text">Distributors</span>
                                            <span class="ml-auto sidebar-menu-toggle-icon"></span>
                                        </a>
                                        <ul class="sidebar-submenu collapse" id="users_and_settings_menu">
                                            <li class="sidebar-menu-item <?php if ($page == 'distributor') echo 'active';?>">
                                                <a class="sidebar-menu-button" href="distributor.php">
                                                    <span class="sidebar-menu-text">Distributors</span>
                                                </a>
                                            </li>
                                            <li class="sidebar-menu-item <?php if ($page == 'create_distributor_invoice') echo 'active';?>">
                                                <a class="sidebar-menu-button" href="create_distributor_invoice.php">
                                                    <span class="sidebar-menu-text">Create Distributor Invoice</span>
                                                </a>
                                            </li>
                                            <li class="sidebar-menu-item <?php if ($page == 'distributor_invoice_history') echo 'active';?>">
                                                <a class="sidebar-menu-button" href="distributor_invoice_history.php">
                                                    <span class="sidebar-menu-text">Distributor Invoice History</span>
                                                </a>
                                            </li>
                                            <li class="sidebar-menu-item <?php if ($page == 'set_distributor_targets') echo 'active';?>">
                                                <a class="sidebar-menu-button" href="#!">
                                                    <span class="sidebar-menu-text">Set Distributor Targets</span>
                                                </a>
                                            </li>
                                            <li class="sidebar-menu-item <?php if ($page == 'distributors_performence') echo 'active';?>">
                                                <a class="sidebar-menu-button" href="distributors_performence.php">
                                                    <span class="sidebar-menu-text">Distributors Performence</span>
                                                </a>
                                            </li>
                                        </ul>
                                    </li>

                                    <li class="sidebar-menu-item <?php if ($page == 'supplier' || $page == 'add_new_supplier') echo 'active open';?>">
                                        <a class="sidebar-menu-button" data-toggle="collapse" href="#supplier_menu">
                                            <i class="sidebar-menu-icon sidebar-menu-icon--left material-icons">store</i>
                                            <span class="sidebar-menu-text">Suppliers</span>
                                            <span class="ml-auto sidebar-menu-toggle-icon"></span>
                                        </a>
                                        <ul class="sidebar-submenu collapse" id="supplier_menu">
                                            <li class="sidebar-menu-item <?php if ($page == 'add_new_supplier') echo 'active';?>">
                                                <a class="sidebar-menu-button" href="add_new_supplier.php">
                                                    <span class="sidebar-menu-text">Add New Suppliers</span>
                                                </a>
                                            </li>
                                            <li class="sidebar-menu-item <?php if ($page == 'supplier') echo 'active';?>">
                                                <a class="sidebar-menu-button" href="supplier.php">
                                                    <span class="sidebar-menu-text">Suppliers</span>
                                                </a>
                                            </li>
                                        </ul>
                                    </li>

                                    <li class="sidebar-menu-item <?php if ($page == 'user' || $page == 'attendance_report' || $page == 'sales_rep' || $page == 'tracking' || $page == 'set_sales_rep_targets' || $page == 'get_sales_rep_targets') echo 'active open';?>">
                                        <a class="sidebar-menu-button" data-toggle="collapse" href="#sales_rep_details_menu">
                                            <i class="sidebar-menu-icon sidebar-menu-icon--left material-icons">person_pin</i>
                                            <span class="sidebar-menu-text">Sales-Rep Details</span>
                                            <span class="ml-auto sidebar-menu-toggle-icon"></span>
                                        </a>
                                        <ul class="sidebar-submenu collapse" id="sales_rep_details_menu">
                                            <li class="sidebar-menu-item <?php if ($page == 'user') echo 'active';?>">
                                                <a class="sidebar-menu-button" href="user.php">
                                                    <span class="sidebar-menu-text">Create Sales-Rep Account</span>
                                                </a>
                                            </li>
                                            <li class="sidebar-menu-item <?php if ($page == 'attendance_report') echo 'active';?>">
                                                <a class="sidebar-menu-button" href="attendance_report.php">
                                                    <span class="sidebar-menu-text">Sales-Rep Attendence</span>
                                                </a>
                                            </li>
                                            <li class="sidebar-menu-item <?php if ($page == 'set_sales_rep_targets') echo 'active';?>">
                                                <a class="sidebar-menu-button" href="set_sales_rep_targets.php">
                                                    <span class="sidebar-menu-text">Set Sales-Rep Targets</span>
                                                </a>
                                            </li>
                                            <li class="sidebar-menu-item <?php if ($page == 'get_sales_rep_targets') echo 'active';?>">
                                                <a class="sidebar-menu-button" href="get_sales_rep_targets.php">
                                                    <span class="sidebar-menu-text">Sales-Rep Targets</span>
                                                </a>
                                            </li>
                                            <li class="sidebar-menu-item <?php if ($page == 'sales_rep') echo 'active';?>">
                                                <a class="sidebar-menu-button" href="sales_rep.php">
                                                    <span class="sidebar-menu-text">Sales-Rep Performence</span>
                                                </a>
                                            </li>
                                            <li class="sidebar-menu-item <?php if ($page == 'tracking') echo 'active';?>">
                                                <a class="sidebar-menu-button" href="tracking.php">
                                                    <span class="sidebar-menu-text">Sales-Rep Live Tracking</span>
                                                </a>
                                            </li>
                                        </ul>
                                    </li>
                                    
                                    <li class="sidebar-menu-item <?php if ($page == 'hr_manage') echo 'active';?>">
                                        <a class="sidebar-menu-button" href="hr_manage.php">
                                            <i class="sidebar-menu-icon sidebar-menu-icon--left material-icons">wc</i>
                                            <span class="sidebar-menu-text">HR</span>
                                        </a>
                                    </li>
                                    
                                    <li class="sidebar-menu-item <?php if ($page == 'accounts_manage') echo 'active';?>">
                                        <a class="sidebar-menu-button" href="accounts.php">
                                            <i class="sidebar-menu-icon sidebar-menu-icon--left material-icons">account_balance</i>
                                            <span class="sidebar-menu-text">Accounts</span>
                                        </a>
                                    </li>

                                    <li class="sidebar-menu-item <?php if ($page == 'messages') echo 'active';?>">
                                        <a class="sidebar-menu-button" href="messages.php">
                                            <i class="sidebar-menu-icon sidebar-menu-icon--left material-icons">message</i>
                                            <span class="sidebar-menu-text">Messages to Sales-Rep</span>
                                        </a>
                                    </li>
                                    <li class="sidebar-menu-item <?php if ($page == 'sales_report') echo 'active';?>">
                                        <a class="sidebar-menu-button" href="sales_report.php">
                                            <i class="sidebar-menu-icon sidebar-menu-icon--left material-icons">pie_chart</i>
                                            <span class="sidebar-menu-text">Sales Report</span>
                                        </a>
                                    </li>
                                    <li class="sidebar-menu-item <?php if ($page == 'collection_check') echo 'active';?>">
                                        <a class="sidebar-menu-button" href="collection_check.php">
                                            <i class="sidebar-menu-icon sidebar-menu-icon--left material-icons">monetization_on</i>
                                            <span class="sidebar-menu-text">Payment Collection</span>
                                        </a>
                                    </li>
                                    <li class="sidebar-menu-item <?php if ($page == 'download') echo 'active';?>">
                                        <a class="sidebar-menu-button" href="download.php">
                                            <i class="sidebar-menu-icon sidebar-menu-icon--left material-icons">touch_app</i>
                                            <span class="sidebar-menu-text">Download Sales-Rep App</span>
                                        </a>
                                    </li>
                                    <li class="sidebar-menu-item">
                                        <a class="sidebar-menu-button" href="tel:076 44 15555;">
                                            <i class="sidebar-menu-icon sidebar-menu-icon--left material-icons">call</i>
                                            <span class="sidebar-menu-text">076 44 15555 (Support)</span>
                                        </a>
                                    </li>
                                </ul>
                            </div>
                            <div id="sm-account" class="tab-pane">
                                <ul class="sidebar-menu">
                                    <li class="sidebar-menu-item">
                                        <a class="sidebar-menu-button" href="edit-account.html">
                                            <span class="sidebar-menu-text">Edit Information</span>
                                        </a>
                                    </li>
                                    <li class="sidebar-menu-item">
                                        <a class="sidebar-menu-button" href="edit-account.html">
                                            <span class="sidebar-menu-text">Payments</span>
                                        </a>
                                    </li>
                                    <li class="sidebar-menu-item">
                                        <a class="sidebar-menu-button" href="edit-account.html">
                                            <span class="sidebar-menu-text">Billing</span>
                                        </a>
                                    </li>
                                    <li class="sidebar-menu-item">
                                        <a class="sidebar-menu-button" href="edit-account.html">
                                            <span class="sidebar-menu-text">Change Password</span>
                                        </a>
                                    </li>
                                    <li class="sidebar-menu-item">
                                        <a class="sidebar-menu-button" href="#!">
                                            <span class="sidebar-menu-text">Logout</span>
                                        </a>
                                    </li>
                                </ul>
                            </div>
                            
                        </div>

                        <!------------------------------------End Admin Area----------------------------------------->

               <?php } ?>


                <!--------------------------Distributor Footer Area---------------------------------------->
                <?php if($is_distributor){  ?>

                    <div class="mt-auto sidebar-p-a sidebar-b-t d-flex flex-column flex-shrink-0">
                        <!-- <a class="sidebar-link mb-2" href="edit-account.html">Change Password</a> -->
                        <!-- <a class="sidebar-link mb-2" href="edit-account.html">Settings</a> -->
                        <a class="sidebar-link" href="scripts/signout_from_web_system" id="btn-signout">
                            Logout
                            <i class="sidebar-menu-icon ml-2 material-icons icon-16pt">exit_to_app</i>
                        </a>
                    </div>


                <?php }else{ ?>

                	<!--------------------------Admin Footer Area---------------------------------------->
                	<div class="mt-auto sidebar-p-a sidebar-b-t d-flex flex-column flex-shrink-0">
                        <!-- <a class="sidebar-link mb-2" href="edit-account.html">Change Password</a> -->
                        <a class="sidebar-link mb-2" href="settings">Settings</a>
                        <a class="sidebar-link" href="scripts/signout_from_web_system" id="btn-signout">
                            Logout
                            <i class="sidebar-menu-icon ml-2 material-icons icon-16pt">exit_to_app</i>
                        </a>
                    </div>

                <?php } ?>

                </div>
            </div>
        </div>
