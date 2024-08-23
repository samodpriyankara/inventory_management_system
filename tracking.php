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
                            <div class="col-md-6">
                                <h3>Sales-Rep Live Tracking</h3>
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
                                    <p><strong class="headings-color">Create Goods Received Note (GRN)</strong></p>
                                    <p class="text-muted">Edit your account details and settings.</p>
                                </div> -->
                                <div class="col-lg-12 card-form__body card-body">
                                    
                                    <div class="row">
                                        <div class="form-group" style="align-self: center;margin-left: 20px">
                                          <!-- <LABEL style="font-size:18px">Sales Person</LABEL> -->
                                          <select class="form-control" id="txt-user" style="height: 43px">
                                            
                                            <?php 
                                                if($is_distributor){
                                                    $rs=$conn->query("SELECT * FROM tbl_user tu INNER JOIN tbl_distributor_has_tbl_user tdhu ON tu.id = tdhu.user_id WHERE tdhu.distributor_id = '$user_id' ORDER BY tu.id DESC");
                                                }else{
                                                    $rs=$conn->query("SELECT * FROM tbl_user");
                                                }
                                                    while ($row=$rs->fetch_array()) {
                                            ?>

                                                <option value=<?php echo $row[0];?>><?php echo $row[1];?></option>
                                                  
                                            <?php } ?>

                                          </select>
                                        </div>

                                        <div class="form-group" style="margin-left: 20px;align-self: center;">
                                          <input type="Date" class="form-control" id="txt-date">
                                        </div>


                                        <div class="form-group" style="margin-left: 20px;align-self: center;">
                                           <input type="text" class="form-control" list="lst-time-frames" id="txt-time-frame" placeholder="Time frame">
                                           <datalist id="lst-time-frames">
                                           </datalist>
                                        </div>


                                        <div class="form-group" style="margin-left: 20px;margin-right: 20px;align-self: center;">
                                            <span id="lbl_battery_level">0 %</span>
                                        </div>

                                        <div class="form-group" style="margin-left: 20px;margin-right: 20px;align-self: center;">
                                            <button class="btn btn-primary" style="height: 41px;" id="btn-search">Search</button>
                                        </div>

                                    </div>


                                    <div id="map-layer"  style="background-color:gray;width:100%;height:500px;border-bottom-right-radius: 10px;border-bottom-left-radius: 10px"></div>




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

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

     <script 
            src="https://maps.googleapis.com/maps/api/js?key=AIzaSyC7FUnTem9bn1mO2KE1P5bitnPUbxhl8EM&sensor=false">
    </script>


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
        var map;
        var polyline;
        var lineCoordinates = [];
        var markerType = "ALL";
        var info = new google.maps.InfoWindow();
        var marker_circle = null;




    $(document).ready(function() {
    		

    		google.maps.visualRefresh = true;
 			var mapOptions = {
                center: new google.maps.LatLng(7.1667,80.1233),
                zoom: 7,
                mapTypeId: google.maps.MapTypeId.ROADMAP
            };

			var mapElement = document.getElementById('map-layer');
 			map = new google.maps.Map(mapElement, mapOptions);

            var trafficLayer = new google.maps.TrafficLayer();
            trafficLayer.setMap(map);



      // Javascript method's body can be found in assets/assets-for-demo/js/demo.js
      //demo.initChartsPages();
      $("#txt-time-frame").change(function(){
        var timeFrame = $("#txt-time-frame").val();
        var user = $("#txt-user").val();
        var search_date = $("#txt-date").val();


        


        if(user !== "" && search_date !==""){

        

            if(timeFrame === ""){
                
                //load all locations
                markerType = "ALL";
                loadTrackingData("");
                


                

            }else{

                //load location for time frame
                markerType = "TIME";
                loadTrackingData(timeFrame);
                
                
            }

            
        }





      });


        function setMapOnAll(map) {
              for (let i = 0; i < lineCoordinates.length; i++) {
                lineCoordinates[i].setMap(map);
              }
        }



      function loadTrackingData(time_frame){


          $.ajax({
                        url: "scripts/download_user_tracking_data.php",
                        type: 'POST',
                        data: {
                            user_id:$("#txt-user").val(),
                            search_date:$("#txt-date").val(),
                            time_frame:time_frame
                        },
                        success: function (data) {

                            
                           var json=JSON.parse(data);
                           

                            


                           if(json.result){

                             lineCoordinates = [];
                             outletCoordinates = [];

                            if(map != null){
                                setMapOnAll(map);
                            }

                            




                               // var lineCoordinates = [
                               //      [6.9000,79.8000],[7.2000,80.6000]
                               // ];
                            




                              var dataList = json.locations;
                              var time_frames = json.time_frames;
                              var outlets = json.outlets;

                              var base_outlet_data = [];






                              $("#lst-time-frames").html(time_frames);


                              var lastLat = 0;
                              var lastLn = 0;
                              var last_battery_level = 0;


                          for (i = 0; i < dataList.length; i++) {
                              
                              var lat=dataList[i].lat;
                              var lon=dataList[i].lon;
                              var battery_level=dataList[i].battery_level;
							  lineCoordinates.push(new google.maps.LatLng(lat, lon));
							  lastLat = lat;
                              lastLn = lon;
                              last_battery_level = battery_level;

                          
                          }




                          for (i = 0; i < outlets.length; i++) {

                              var lat = outlets[i].lat;
                              var lon = outlets[i].lng;
                              var outlet_name = outlets[i].name;
                              
                              var owner = outlets[i].owner;
                              var outlet_id = outlets[i].outlet_id;
                              var contact = outlets[i].contact;
                              var image = outlets[i].image;

                              	
                              

                              var outlet_obj = [];


                              outlet_obj['lat_lng'] = new google.maps.LatLng(lat, lon);
                              outlet_obj['name'] = outlet_name;
                              outlet_obj['owner'] = owner;
                              outlet_obj['outlet_id'] = outlet_id;
                              outlet_obj['contact'] = contact;
                              outlet_obj['image'] = image;

							  outletCoordinates.push(outlet_obj);

						  }




                          initMap(lineCoordinates,lastLat,lastLn,outletCoordinates,last_battery_level);
                        
                          // google.maps.event.addDomListener(window, 'load', initialize);

                           }else{

                              Swal.fire({
                                    title: 'Warning !',
                                    text: json.msg,
                                    icon: 'warning',
                                    allowOutsideClick:false,
                                    showCancelButton: true,
                                    showConfirmButton:false,
                                    cancelButtonColor: '#d33',
                                    cancelButtonText: 'OK'
                                  });




                           }
                          



                        },
                        error: function (jqXHR, textStatus, errorThrown) {
                            console.log(errorThrown);
                        }

                    });









      }




      $("#tracking-nav-tab").attr("class","active");


      $("#btn-search").click(function(){

        var userId=$("#txt-user").val();

        loadTrackingData("");
                      

      });



    });

		 
 
        function initMap(coordinates,lastLat,lastLn,outletCoordinates,last_battery_level) {

        	if(last_battery_level != undefined && last_battery_level != ''){
        		$("#lbl_battery_level").html(last_battery_level+" %");
        	}else{
        		$("#lbl_battery_level").html("0 %");
        	}

        	google.maps.visualRefresh = true;
 
            var mapOptions = {
                center: new google.maps.LatLng(lastLat,lastLn),
                zoom: 9,
                mapTypeId: google.maps.MapTypeId.ROADMAP
            };


        	var mapElement = document.getElementById('map-layer');
 			map = new google.maps.Map(mapElement, mapOptions);
			var trafficLayer = new google.maps.TrafficLayer();
            trafficLayer.setMap(map);


           


  

/////////////////////////////////////sales rep////////////////////////////
          for(var i= 0;i<coordinates.length;i++){

            var c = coordinates[i];
            var marker_icon = "";

            if(markerType ==="ALL"){
                marker_icon = 'dot.png';
            }else{
                marker_icon = 'assets/img/sales-rep-small.png';
            }

	        new google.maps.Marker({
	          position: c,
	          // animation:google.maps.Animation.BOUNCE,
	          map: map,
	          title: 'Rep Location',
	          icon:marker_icon
	        });

          }
////////////////////////////////////////////////////////////////////////////////
          


          

          for(var i= 0;i<outletCoordinates.length;i++){

          


            var position = outletCoordinates[i]['lat_lng'];
           	var outlet_name = outletCoordinates[i]['name'];
           	
           	var id = outletCoordinates[i]['outlet_id'];
           	var owner = outletCoordinates[i]['owner'];
           	var contact = outletCoordinates[i]['contact'];
           	var image = outletCoordinates[i]['image'];



            var marker_icon = "assets/img/shop_small.png";

            
           	// var img_src = "data:image/png;base64,"+image;
           

           	
            	
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
                                              '</tr>'+
                                              '<tr>'+
                                                '<td>Waiting Time (Approx)</td>'+
                                                '<td class="text-danger" style="font-size:18px"><span id="lbl-wait-time">0 hr 0 min 0sec</span></td>'+
                                              '</tr>'+
                                              '</tbody>'+
                                          '</table>'+
                                          '</div>'+
                                          '</div>';

			var marker = new google.maps.Marker({
	          position: position,
	          title: outlet_name,
	          icon:marker_icon
	        });
	      
	       marker.setMap(map);

			google.maps.event.addListener(marker, 'click',handleMarkerClick(marker,markerContent,id));

          }




 
            // addAnimatedPolyline(coordinates);
        }
 
     	function handleMarkerClick(marker,content,outlet_id) {
		  return function() {

        var lat = marker.getPosition().lat();
        var lng = marker.getPosition().lng();


        get_approx_waiting_time(lat,lng,$("#txt-user").val(),$("#txt-date").val());

        if(marker_circle != null){
          marker_circle.setMap(null);
        }

        marker_circle = marker.Circle = new google.maps.Circle({
        center:marker.getPosition(),
        radius: 15,//meters
        map: map,
        fillColor: '#61AF60'
        });

        marker.Circle.bindTo('center', marker, 'position');

			  info.setContent(content);
		    info.open(map, marker);
		  };
		}


    function get_approx_waiting_time(lat,lng,user_id,date){
      $.ajax({
        url:'scripts/download_approx_waiting_time.php',
        method:'POST',
        data:{
          lat:lat,
          lng:lng,
          user_id:user_id,
          date:date
        },success:function(data){
          var json = JSON.parse(data);
          if(json.result){
            $("#lbl-wait-time").html(json.wait_duration);
          }


        },error:function(err){
          console.log(err);
        }

      });
    }

</script>

   



  
</body>
</html>

