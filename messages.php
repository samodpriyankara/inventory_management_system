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
                        <div class="row">
                            <!-- <div class="col-md-6">
                                <h3>CREATE AND UPDATE PRODUCTS</h3>
                            </div> -->
                            <!-- <div class="col-md-6">
                                <div class="pull-right" style="float: right;">
                                    <a href="create_invoice" class="btn btn-primary">Create Invoice</a>
                                </div>
                            </div> -->
                        </div>
                        <div class="card card-form">
                            <div class="row no-gutters">
                                
                                <form id="Add-Messages" class="col-lg-4 card-form__body card-body">
                                    <h5>Write Message</h5>
                                    <input type="hidden" class="form-control" name="user_id" value="<?php echo $user_id; ?>" required>
                                    <div class="form-group">
                                        <label>Subject</label>
                                        <input type="text" class="form-control" name="subject" placeholder="Subject" required>
                                    </div>
                                        
                                    <div class="form-group">
                                        <label>Message</label>
                                        <textarea class="form-control" name="msg" placeholder="Type your Message...." cols="30" rows="5" style="padding: 8px; resize: auto; max-height: 100% !important;" required></textarea>
                                    </div>

                                    <div class="text-right mb-5">
                                        <button type="submit" id="btn-submit" class="btn btn-primary">Send</button>
                                    </div>
                                </form>

                                <div class="col-lg-8 card-body">
                                    
                                    <div class="table-responsive border-bottom" style="padding: 10px; height: 700px;">
                                        <h5>Previous Messages</h5><hr>

                                        <?php
                                            $sql = "SELECT * FROM tbl_office_msgs ORDER BY msg_id DESC";
                                            $rs=$conn->query($sql);
                                            while($row=$rs->fetch_array())
                                            {
                                                $MsgId=$row[0];                       
                                                $Subject=$row[1];                       
                                                $Message=$row[2];                       
                                                $MsgDateTime=$row[3];
                                                $Status=$row[4];
                                                $MsgUserId=$row[5];
                                        ?>

                                        <div class="alert alert-soft-primary d-flex align-items-center" role="alert">
                                            <i class="material-icons mr-3">flag</i>
                                            <div class="text-body">
                                                <div class="row">
                                                    <div class="col-md-6">
                                                        <strong><?php echo $Subject; ?></strong>
                                                    </div>
                                                    <div class="col-md-6" style="float: right;">
                                                        <span style="color: #000; float: right;"><?php echo $MsgDateTime; ?></span>
                                                    </div>
                                                </div>
                                                
                                                
                                                <br>
                                                <?php echo nl2br($Message); ?>.
                                            </div>
                                        </div>

                                        <?php } ?>

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

    <!-- Dropzone -->
    <script src="assets/vendor/dropzone.min.js"></script>
    <script src="assets/js/dropzone.js"></script>

    <script src="https://cdn.datatables.net/1.11.3/js/jquery.dataTables.min.js"></script>

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

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
        
        $(document).on('submit', '#Add-Messages', function(e){
        e.preventDefault(); //stop default form submission

        $("#btn-write-message").attr("disabled",true);

        var formData = new FormData($(this)[0]);

        $.ajax({
            
            
                beforeSend : function() {
                    $("#progress_alert").addClass('show');
                },

                url:"post/add_messages.php",
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
                       $("#btn-write-message").attr("disabled",false);
                       location.reload();
                        
                    }else{
                        $("#danger_alert").addClass('show');
                        setTimeout(function(){ $("#danger_alert").removeClass('show'); }, 1000);
                        $("#btn-write-message").attr("disabled",false);
                    }
                    
                }

            });

        return false;
        });
    </script>

  
</body>
</html>