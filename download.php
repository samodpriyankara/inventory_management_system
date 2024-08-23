<?php
    session_start();
    require 'database/db.php';
    $db=new DB();
    $conn=$db->connect();
    date_default_timezone_set('Asia/Colombo');
    $currentDate=date('Y-m-d');
?>
<!DOCTYPE html>
<html lang="en" dir="ltr">

<?php include_once('controls/meta.php'); ?>

<body class="layout-default">

    <div class="preloader"></div>

    <div class="mdk-drawer-layout js-mdk-drawer-layout" data-push data-responsive-width="992px" data-fullbleed>
        <div class="mdk-drawer-layout__content">

            <!-- Header Layout -->
            <div class="mdk-header-layout js-mdk-header-layout" data-has-scrolling-region>

                <!-- Header -->

                <!-- Header Layout Content -->
                <div class="mdk-header-layout__content mdk-header-layout__content--fullbleed mdk-header-layout__content--scrollable page">


                    <center><h1>Sales-Rep App</h1></center>

                    <div class="container-fluid page__container">
                        <div class="mb-3"><strong class="text-dark-gray">App Download</strong></div>
                        <div class="stories-cards mb-4">

                            <?php
                                $getAllData = $conn->query("SELECT * FROM tbl_app_update ORDER BY id DESC LIMIT 1");       
                                while($rs = $getAllData->fetch_array()){

                                    $AppId = $rs[0];
                                    $AppVersion = $rs[1];
                                    $AppLocation = $rs[2];
                                    $AppUploadDateTime = $rs[3];
                                    $AppStatus = $rs[4];

                                    $AppUploadDateTimeSet = date('d M, Y h:i a', strtotime($AppUploadDateTime));

                            ?>

                            <div class="card stories-card">
                                <div class="stories-card__content d-flex align-items-center flex-wrap">
                                    <div class="avatar avatar-lg mr-3">
                                        <a href="app/smartsalesman.apk" download>
                                            <img src="assets/img/logo-only.png" alt="avatar" class="avatar-img rounded">
                                        </a>
                                    </div>
                                    <div class="stories-card__title flex">
                                        <h5 class="card-title m-0"><a href="app/smartsalesman.apk" class="headings-color" download>Smart Salesman</a></h5>
                                        <small class="text-dark-gray"><?php echo $AppVersion; ?> Version</small>
                                    </div>
                                    <div class="d-flex align-items-center flex-column flex-sm-row stories-card__meta">
                                        <div class="mr-3 text-dark-gray stories-card__date">
                                            <small><?php echo $AppUploadDateTimeSet; ?></small>
                                        </div>
                                    </div>
                                    <div class="dropdown ml-auto">
                                        <a href="app/smartsalesman.apk" style="margin-left: -50px !important;" download><i class="material-icons">cloud_download</i></a>
                                    </div>
                                </div>
                            </div>


                            <?php } ?>


                            

                        </div>



                        <div class="my-3"><strong class="text-dark-gray">App Screen Shots</strong></div>
                        <div class="row">

                            <div class="col-sm-6 col-md-4">
                                <div class="card stories-card-popular">
                                    <img src="assets/1.png" style="height: 100% !important;" alt="smartsalesman1" class="card-img">
                                    <div class="stories-card-popular__content">
                                        <div class="stories-card-popular__title card-body">
                                            <!-- <small class="text-muted text-uppercase">blog</small>
                                            <h4 class="card-title m-0"><a href="">Tremblant In Canada</a></h4> -->
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="col-sm-6 col-md-4">
                                <div class="card stories-card-popular">
                                    <img src="assets/2.png" style="height: 100% !important;" alt="smartsalesman2" class="card-img">
                                    <div class="stories-card-popular__content">
                                        <div class="stories-card-popular__title card-body">
                                            <!-- <small class="text-muted text-uppercase">blog</small>
                                            <h4 class="card-title m-0"><a href="">Tremblant In Canada</a></h4> -->
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="col-sm-6 col-md-4">
                                <div class="card stories-card-popular">
                                    <img src="assets/3.png" style="height: 100% !important;" alt="smartsalesman3" class="card-img">
                                    <div class="stories-card-popular__content">
                                        <div class="stories-card-popular__title card-body">
                                            <!-- <small class="text-muted text-uppercase">blog</small>
                                            <h4 class="card-title m-0"><a href="">Tremblant In Canada</a></h4> -->
                                        </div>
                                    </div>
                                </div>
                            </div>

                        </div>


                        <div class="mb-3"><strong class="text-dark-gray">App Download</strong></div>
                        <div class="stories-cards mb-4">

                            <?php
                                $getAllData = $conn->query("SELECT * FROM tbl_app_update ORDER BY id DESC LIMIT 1");       
                                while($rs = $getAllData->fetch_array()){

                                    $AppId = $rs[0];
                                    $AppVersion = $rs[1];
                                    $AppLocation = $rs[2];
                                    $AppUploadDateTime = $rs[3];
                                    $AppStatus = $rs[4];

                                    $AppUploadDateTimeSet = date('d M, Y h:i a', strtotime($AppUploadDateTime));

                            ?>

                            <div class="card stories-card">
                                <div class="stories-card__content d-flex align-items-center flex-wrap">
                                    <div class="avatar avatar-lg mr-3">
                                        <a href="app/smartsalesman.apk" download>
                                            <img src="assets/img/logo-only.png" alt="avatar" class="avatar-img rounded">
                                        </a>
                                    </div>
                                    <div class="stories-card__title flex">
                                        <h5 class="card-title m-0"><a href="app/smartsalesman.apk" class="headings-color" download>Smart Salesman</a></h5>
                                        <small class="text-dark-gray"><?php echo $AppVersion; ?> Version</small>
                                    </div>
                                    <div class="d-flex align-items-center flex-column flex-sm-row stories-card__meta">
                                        <div class="mr-3 text-dark-gray stories-card__date">
                                            <small><?php echo $AppUploadDateTimeSet; ?></small>
                                        </div>
                                    </div>
                                    <div class="dropdown ml-auto">
                                        <a href="app/smartsalesman.apk" style="margin-left: -50px !important;" download><i class="material-icons">cloud_download</i></a>
                                    </div>
                                </div>
                            </div>


                            <?php } ?>


                            

                        </div>




                    </div>


                </div>
                <!-- // END header-layout__content -->

            </div>
            <!-- // END header-layout -->

        </div>
        <!-- // END drawer-layout__content -->

        
    </div>
    <!-- // END drawer-layout -->



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





</body>

</html>