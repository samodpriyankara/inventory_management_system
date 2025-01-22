<?php
session_start();
require 'database/db.php';
$db = new DB();
$conn = $db->connect();
date_default_timezone_set('Asia/Colombo');
$currentDate = date('Y-m-d');

$ItemId = base64_decode($_GET['g']);
$ItemCount = 0;
$net_total = 0;

$is_distributor = false;
$user_id = 0;

if (empty($_SESSION['user'])) {
    header('Location:index.php');
}
if (isset($_SESSION['DISTRIBUTOR'])) {
    $is_distributor = $_SESSION['DISTRIBUTOR'];
}
if (isset($_SESSION['ID'])) {
    $user_id = $_SESSION['ID'];
}
?>
<?php

$getProduct = $conn->query("SELECT * FROM tbl_item WHERE genaricName = '$ItemId'");
if ($GRNrs = $getProduct->fetch_array()) {

    $ProductId = $GRNrs[7];
    $ProductCode = $GRNrs[1];
    $ProductDescription = $GRNrs[2];
}

?>
<!DOCTYPE html>
<html lang="en" dir="ltr">

<?php include_once('controls/meta.php'); ?>

<body class="layout-default" onload="startTime()">

    <style>
        @media print {
            @page {
                size: auto;
                /* auto is the initial value */
                size: A4 portrait;
                margin: 0;
                /* this affects the margin in the printer settings */
                border: 1px solid red;
                /* set a border for all printed pages */
            }

            body {
                zoom: 80%;
                /*transform: scale(.6);*/
                /*margin-top: -320px;*/
                width: 100%;
                font-weight: 700;
                margin-top: -100px;
            }

            #print-page {
                margin-left: -320px;
                margin-top: 40px;
                background-color: #fff !important;
            }

            #supplier-details-print {
                margin-top: -80px !important;
            }

            #printPageButton {
                display: none;
            }

            .main-panel {
                width: 100% !important;
            }

            #topbar {
                display: none;
            }

            #footer {
                display: none;
            }

            #default-drawer {
                display: none;
            }

            #app-settings-dd__BV_toggle_ {
                display: none;
            }

            #sidebar {
                visibility: hidden;
            }
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

                        <div class="card">
                            <div class="card-header">
                                <div class="row">
                                    <div class="col-md-6">
                                        <h5 class="card-title">Invoices</h5>
                                    </div>
                                    <div class="col-md-6">

                                        <button type="button" id="printPageButton" onclick="window.print();" class="btn btn-primary pull-right"><i class="fa fa-print"></i> Print</button>

                                    </div>
                                </div>
                            </div>

                            <div class="card-body">

                                <div class="row">
                                    <div class="col-md-6">
                                        <p style="text-align: left;">
                                            <b>Product Code - <?php echo $ProductCode; ?></b><br>
                                            <b>Product Description - <?php echo $ProductDescription; ?></b><br>

                                        </p>
                                    </div>

                                </div>


                                <div class="example-content">
                                    <table class="table table-hover">
                                        <thead>

                                            <th scope="col">#</th>
                                            <th scope="col">GRN Number</th>
                                            <th scope="col">Invoice Number</th>
                                            <th scope="col">Supplier Name</th>
                                            <th scope="col">
                                                <font style="float: right;">Qty</font>
                                            </th>
                                            <th scope="col">
                                                <font style="float: right;">Cost Price</font>
                                            </th>
                                            <th scope="col">
                                                <font style="float: right;">Total(Rs)</font>
                                            </th>
                                            <th scope="col"></th>

                                        </thead>
                                        <tbody id="grn-item-area">
                                            <?php
                                            $getDataQuery = $conn->query("SELECT tgi.*, tgd.*, ts.* FROM tbl_grn_items tgi INNER JOIN tbl_grn_details tgd ON tgi.grn_detail_id = tgd.grn_detail_id INNER JOIN tbl_supplier ts ON tgd.supplier_id = ts.supplier_id WHERE tgi.item_detail_id = '$ProductId' ");
                                            while ($GRNrs = $getDataQuery->fetch_array()) {

                                                $ProductId = $GRNrs[1];
                                                $GrnId = $GRNrs[8];
                                                $GrnNumber = $GRNrs[11];
                                                $InvoiceNumber = $GRNrs[10];
                                                $Qty = $GRNrs[6];
                                                $Price = $GRNrs[4];
                                                $Suplier = $GRNrs[17];
                                                $total = $Qty * $Price;

                                                /////////////////////////////////
                                                $net_total =  $net_total + $total;




                                            ?>
                                                <tr onclick="location.href='grn_invoice_view?g=<?php echo (base64_encode($GrnId)); ?>'" style="cursor: pointer;">
                                                    <td><?php echo $ProductId; ?></td>
                                                    <td><?php echo $GrnNumber; ?></td>
                                                    <td><?php echo $InvoiceNumber; ?></td>
                                                    <td><?php echo $Suplier; ?></td>
                                                    <td><b style="float: right;"><?php echo $Qty; ?></b></td>
                                                    <td><b style="float: right;"><?php echo number_format($Price, 2); ?></b></td>
                                                    <td><b style="float: right;"><?php echo number_format($total, 2); ?></b></td>





                                                </tr>
                                            <?php } ?>
                                        </tbody>
                                        <tfoot>

                                            <th></th>
                                            <th></th>
                                            <th></th>
                                            <th><b style="float: right; color: #000; font-size: 20px; font-weight: 700;">Total = Rs. <?php echo number_format($net_total, 2); ?></b></th>
                                            <th></th>

                                        </tfoot>

                                    </table>
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
            if (i < 10) {
                i = "0" + i
            }; // add zero in front of numbers < 10
            return i;
        }
    </script>

    <script type="text/javascript">
        $(document).ready(function() {
            $('.js-example-basic-single').select2();
        });
    </script>




</body>

</html>