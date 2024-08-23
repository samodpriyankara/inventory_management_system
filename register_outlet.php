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
                                <div class="col-lg-4 card-body">
                                    <p><strong class="headings-color">Register Outlet</strong></p>
                                    <p class="text-muted">Edit your account details and settings.</p>
                                </div>
                                <form id="Register-Outlet" class="col-lg-8 card-form__body card-body">
                                    
                                    <div class="row">
                                        

                                    <div class="form-group">
                                        <label>Outlet Image</label>
                                        <input type="file" name="outlet_image" class="form-control" accept="image/*">

                                        <!-- <div class="dz-clickable media align-items-center" data-toggle="dropzone" data-dropzone-url="http://" data-dropzone-clickable=".dz-clickable" data-dropzone-files='["assets/images/account-add-photo.svg"]'>
                                            <div class="dz-preview dz-file-preview dz-clickable mr-3">
                                                <div class="avatar" style="width: 80px; height: 80px;">
                                                    <img src="assets/images/account-add-photo.svg" class="avatar-img rounded" alt="..." name="img" id="img" data-dz-thumbnail>
                                                </div>
                                            </div>
                                            <div class="media-body">
                                                <button class="btn btn-sm btn-primary dz-clickable">Choose photo</button>
                                            </div>
                                        </div> -->
                                    </div>


                                    <div class="form-group">
                                        
                                        <label>Uploading Documents</label>
                                        <input type="file" name="docs[]" multiple class="form-control" accept=".xlsx,.xls,.doc, .docx,.ppt, .pptx,.txt,.pdf">

                                    </div>

                                    


                                    </div>













                                    <div class="row">
                                        <div class="col">
                                            <div class="form-group">
                                                <label>Outlet Name</label>
                                                <input name="outlet_name" type="text" class="form-control" placeholder="Outlet Name" required>
                                            </div>
                                        </div>
                                        <div class="col">
                                            <div class="form-group">
                                                <label>Owner Name</label>
                                                <input name="owner_name" type="text" class="form-control" placeholder="Owner Name" required>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col">
                                            <div class="form-group">
                                                <label>Contact Number</label>
                                                <input name="contact_number" type="text" class="form-control" placeholder="07X XXXXXXX" required>
                                            </div>
                                        </div>
                                        <div class="col">
                                            <div class="form-group">
                                                <label>Outlet Category</label>
                                                <select name="category" class="custom-select" style="width: 100%;">
                                                    <option selected disabled>Select Outlet Category</option>
                                                    <?php
                                                        $OutletCategoryQuery=$conn->query("SELECT DISTINCT category_id,category_name FROM tbl_shop_category ORDER BY category_name ASC");
                                                        while ($OCrow=$OutletCategoryQuery->fetch_array()) {
                                                    ?>
                                                        <option value="<?php echo $OCrow[0];?>"><?php echo $OCrow[1];?></option>
                                                    <?php } ?>
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label>Select Route</label>
                                        <select name="route_id" class="custom-select" style="width: 100%;">
                                            <option selected disabled>Select Route</option>
                                            <?php

                                                if($is_distributor){
                                                    $RouteQuery=$conn->query("SELECT DISTINCT tr.route_id,tr.route_name FROM tbl_route tr INNER JOIN tbl_distributor_has_route tdhr ON tr.route_id=tdhr.route_id WHERE tdhr.distributor_id='$user_id'");
                                                }else{
                                                    $RouteQuery=$conn->query("SELECT DISTINCT route_id,route_name FROM tbl_route");
                                                }

                                                
                                                while ($Rrow=$RouteQuery->fetch_array()) {

                                            ?>
                                                <option value="<?php echo $Rrow[0];?>"><?php echo $Rrow[1];?></option>
                                            <?php } ?>
                                        </select>
                                    </div>
                                    <div class="form-group">
                                        <label>Address</label>
                                        <textarea name="address" rows="4" class="form-control" placeholder="Address ..." required></textarea>
                                    </div>

                                    <!-----------Google Map------------------------>
                                    <div class="col-md-6 pr-1">
                                      <div class="form-group">
                                        <label>Outlet Location</label>
                                        <div id="map-layer" style="width: 100%; height: 300px;"></div>
                                        <div class="row">
                                          <div class="col-md-6">
                                              <div class="form-group">
                                                  <label for="latitude" class="control-label">Latitude</label>
                                                  <input type="text" class="form-control" id="latitude" name="latitude" placeholder="Latitude" required readonly>
                                              </div>
                                          </div>
                                          <div class="col-md-6">
                                              <div class="form-group">
                                                  <label for="longitude" class="control-label">Longitude</label>
                                                  <input type="text" class="form-control" id="longitude" name="longitude" placeholder="Longitude" required readonly>
                                              </div>
                                          </div>
                                        </div>
                                      </div>
                                    </div>
                                    <!-----------Google Map------------------------>

                                    <div class="text-right mb-5">
                                        <button type="submit" id="btn-reg-outlet" class="btn btn-dark">Save</button>
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

    <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyC7FUnTem9bn1mO2KE1P5bitnPUbxhl8EM&sensor=false"></script>
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

  <script>
  
        $(document).ready(function(){
            $('.custom-select').select2();
        });
  
  
        
        $(document).on('submit', '#Register-Outlet', function(e){
        e.preventDefault(); //stop default form submission

        $("#btn-reg-outlet").attr("disabled",true);

        var formData = new FormData($(this)[0]);

        $.ajax({
            
            
                beforeSend : function() {

                $("#progress_alert").addClass('show'); 

                },

                url:"outlet_post/add_outlet.php",
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
                       // $("#outlet-record-area").html(json.data);
                        
                       $("#success_msg").html(json.msg);
                       $("#success_alert").addClass('show'); 
                       
                       setTimeout(function(){$("#success_alert").removeClass('show');  }, 1000);
                       $("#btn-reg-outlet").attr("disabled",false);
                       // document.getElementById('record-textarea').value = '';
                       var id = json.lastId;
                       // alert(id);
                       window.location.href = "shop_single?s="+id;
                        
                    }else{
                        $("#danger_alert").addClass('show');
                        setTimeout(function(){ $("#danger_alert").removeClass('show'); }, 1000);
                        $("#btn-reg-outlet").attr("disabled",false);
                    }
                    
                }

            });

        return false;
        });
    </script>

    <!-------------------------------Map Select--------------------------------------->
    <script>

        var map;
        var marker=false;

        
 
        function initMap() {

            
          var centerPosition = new google.maps.LatLng(7.9850308,79.6509649);

           google.maps.visualRefresh = true;
 
            var mapOptions = {
                center: centerPosition,
                zoom:7,
                mapTypeId: google.maps.MapTypeId.ROADMAP
            };

          var mapElement = document.getElementById('map-layer');
          map = new google.maps.Map(mapElement, mapOptions);

        
    google.maps.event.addListener(map, 'click', function(event) {                
       
        var clickedLocation = event.latLng;
       
        if(marker === false){
            
            marker = new google.maps.Marker({
                position: clickedLocation,
                map: map,
                draggable: false
            });
           
            google.maps.event.addListener(marker, 'dragend', function(event){
                fetchGeoLocation(marker);
            });
        } else{
             marker.setPosition(clickedLocation);
        }
       
        fetchGeoLocation(marker);
    });

}

function fetchGeoLocation(marker){

  var pickedLocation = marker.getPosition();
  var lat = pickedLocation.lat;
  var lng = pickedLocation.lng;

  $("#latitude").val(lat);
  $("#longitude").val(lng);
   
}

initMap();
 
    </script>

</body>
</html>