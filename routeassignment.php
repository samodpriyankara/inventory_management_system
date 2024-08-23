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

                <?php if(!$is_distributor){ ?>

                    <div class="container-fluid page__container">
                        <div class="row">
                            <div class="col-md-6">
                                <h3>Working Days</h3>
                            </div>
                        </div>

                        <div class="card card-form">
                            <div class="row no-gutters">

                                <div class="col-lg-12 card-form__body">

                                    <div class="border-bottom" style="padding: 10px;">

                                        
                                        <div class="row">
                                        <!-- load years -->
                                          <?php
                                            $yearList="";
                                            $ly=$conn->query("SELECT DISTINCT twd.year FROM tbl_working_days twd");
                                            while ($lyr=$ly->fetch_array()) {
                                                $yearList.="<option>".$lyr[0]."</option>";
                                            }

                                          ?>
                                        <!-- ------------ -->

                                            <div class="col-sm-2 col-md-2 col-lg-2">
                                                <div class="form-group">
                                                    <label>Year</label>
                                                    <input type="text" class="form-control" id="txt-wd-year" style="height: 43px" list="y-list-wd">
                                                    <datalist id="y-list-wd">
                                                      <?php echo $yearList;?>
                                                    </datalist>

                                                </div>
                                            </div>

                                            <div class="col-sm-2 col-md-2 col-lg-2">
                                                <div class="form-group">
                                                    <label>Month</label>
                                                    <input type="text" class="form-control" id="txt-wd-month" list="month-list-wd" style="height: 43px">
                                                        <datalist id="month-list-wd">
                                                            <option>January</option>
                                                            <option>February</option>
                                                            <option>March</option>
                                                            <option>April</option>
                                                            <option>May</option>
                                                            <option>June</option>
                                                            <option>July</option>
                                                            <option>August</option>
                                                            <option>September</option>
                                                            <option>October</option>
                                                            <option>November</option>
                                                            <option>December</option>
                                                        </datalist>
                                                </div>
                                            </div>

                                            <div class="col-sm-2 col-md-2 col-lg-2">
                                                <div class="form-group">
                                                    <label>Days Count</label>
                                                    <input type="number" class="form-control" id="txt-wd-count" style="height: 43px">
                                                </div>
                                            </div>

                                          <div class="col-sm-3 col-md-3 col-lg-3">

                                            <div class="form-group">
                                                <label>Starting From</label>
                                                <input type="date" class="form-control" id="txt-wd-starting">
                                            </div>
                                            
                                          </div>

                                           <div class="col-sm-3 col-md-3 col-lg-3">
                                                <button class="btn btn-dark" id="btn-wd-submit" style="margin-top: 25px;">Submit</button>
                                            </div>


                                       </div>

                                        

                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                <?php } ?>


                    <div class="container-fluid page__container">
                        <div class="row">
                            <div class="col-md-6">
                                <h3>Route Plan</h3>
                            </div>
                        </div>

                        <div class="card card-form">
                            <div class="row no-gutters">

                                <div class="col-lg-12 card-form__body">

                                    <div class="border-bottom" style="padding: 10px;">

                                        
                                       




                                        <div class="row">
                 

                                            <div class="col-sm-3 col-md-3 col-lg-3">
                                                <div class="form-group">
                                                    <label>Representative</label>
                                                    <select class="form-control" id="txt-rp-rep">
                                                        <option value=0 selected disabled>SELECT USER</option>
                                                        <?php
                                                          if($is_distributor){
                                                            $getUsers=$conn->query("SELECT tu.id,tu.name FROM tbl_user tu INNER JOIN tbl_distributor_has_tbl_user tdhu ON tu.id = tdhu.user_id WHERE tdhu.distributor_id = '$user_id'");
                                                          }else{
                                                            $getUsers=$conn->query("SELECT id,name FROM tbl_user");
                                                          }
                                                            while ($data=$getUsers->fetch_array()) {
                                                                $userId=$data[0];
                                                                $name=$data[1];
                                                        ?>
                                                            <option value=<?php echo $userId;?>> <?php echo $name;?></option>

                                                        <?php } ?>
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="col-sm-3 col-md-3 col-lg-3">
                                                <div class="form-group">
                                                    <label>Year</label>
                                                    <input type="text" class="form-control" id="txt-rp-year" list="y-list-rp">
                                                        <datalist id="y-list-rp">
                                                          <?php echo $yearList;?>
                                                        </datalist>
                                                </div>
                                            </div>
                                            <div class="col-sm-3 col-md-3 col-lg-3">
                                                <div class="form-group">
                                                    <label>Month</label>
                                                    <input type="text" class="form-control" id="txt-rp-month" list="month-list-rp">
                                                        <datalist id="month-list-rp">
                                                            <option>January</option>
                                                            <option>February</option>
                                                            <option>March</option>
                                                            <option>April</option>
                                                            <option>May</option>
                                                            <option>June</option>
                                                            <option>July</option>
                                                            <option>August</option>
                                                            <option>September</option>
                                                            <option>October</option>
                                                            <option>November</option>
                                                            <option>December</option>
                                                        </datalist>
                                                </div>
                                            </div>
                                              
                                            <div class="col-sm-3 col-md-3 col-lg-3">
                                                <button class="btn btn-dark" id="btn-rp-search" style="margin-top: 25px">Search</button>
                                            </div>


                                        </div>

                                        <hr/>

                                        <div class="col-sm-12 col-md-12 col-lg-12">
                                            <div class="table-responsive table-hover" style="overflow: auto;">
                                            
                                                <table class="table">
                                                    <thead class=" text-primary">
                                                        <th>Date</th>
                                                        <th>Route</th>
                                                        <th></th>
                                              
                                                    </thead>
                                                    <tbody id="route-plan-table">


                                                
                                                    </tbody>
                                                </table>

                                            </div>
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

    <!-- Update Route Modal -->
    <div id="update-route-modal" class="modal fade" role="dialog">
      <div class="modal-dialog modal-dialog-centered">
        <!-- Modal content-->
        <div class="modal-content">
          <div class="modal-header">
            <h4>Update route plan</h4>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
            <!-- <button type="button" class="close" data-dismiss="modal">&times;</button>
            <h4 class="modal-title">Modal Header</h4> -->
          </div>
          <div class="modal-body">
            <input type="hidden" id ="txt-route-update-user-id" value="">
            <div class="form-group">
                <label>Date</label>
                <input type="text" class="form-control" id ="txt-route-update-date" disabled="" value="">
            </div>
            <select id="txt-route" class="form-control">
                <option value="0">SELECT A ROUTE</option>
                <?php
                    
                    if($is_distributor){
                    	$getRoutes=$conn->query("SELECT tr.route_id,tr.route_name FROM tbl_route tr INNER JOIN tbl_distributor_has_route tdhr ON tr.route_id = tdhr.route_id WHERE tdhr.distributor_id = '$user_id'");
                    }else{
                    	$getRoutes=$conn->query("SELECT route_id,route_name FROM tbl_route");
                    }



                    while ($gr=$getRoutes->fetch_array()) {
                ?>
                    <option value="<?php echo $gr[0];?>"><?php echo $gr[1];?></option>
                <?php } ?>
            </select>
          </div>
          <div class="modal-footer">
            <button type="button" id="btn-update-route-plan" class="btn btn-dark">UPDATE NOW</button>
          </div>
        </div>

      </div>
    </div>

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

    <script>


    function enableChangingRoute(date,userId){


        $("#txt-route-update-date").val(date);
        $("#txt-route-update-user-id").val(userId);
        $("#update-route-modal").modal('show');


    }


    $(document).ready(function() {


        

        $("#btn-update-route-plan").click(function(){
                 var userId=$("#txt-route-update-user-id").val();
                  var routeId=$("#txt-route").val();
                  var routeDate=$("#txt-route-update-date").val();



                  if(userId =="" || routeId == 0 || routeDate == ""){


                  }else{

                     $.ajax({

        url:'scripts/change_user_route_plan.php',
        type:'POST',
        data :{

          user_id:userId,
          route_id:routeId,
          route_date:routeDate
        
        },
        success:function(data){
            
          var json=JSON.parse(data);
          if(json.result){

            Swal.fire({
                                    title: 'Success',
                                    text: 'Route updated Successfully',
                                    icon: 'success',
                                    allowOutsideClick:false,
                                    showCancelButton: false,
                                    showConfirmButton:true,
                                    cancelButtonColor: '#d33',
                                    confirmButtonText: 'OK'
                                  }).then((result) => {
                                    if (result.value) {

                                      location.reload();
                                  
                                    }
                                  });





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
        error : function(err) {
            alert(err);
        }




      });


                  }



        });



      $("#btn-rp-search").click(function(){

          var userId=$("#txt-rp-rep").val();
          var year=$("#txt-rp-year").val();
          var month=$("#txt-rp-month").val();

          $.ajax({

              url:"scripts/download_route_plan_data.php",
              type:"POST",
              data:{

                user_id:userId,
                year : year,
                month:month

              },
              success:function(data){
                
                var result=JSON.parse(data);

                if(result.result){


                  $("#route-plan-table").html(result.msg);


                }else{

                 alert('No data found.');

                }



              },
              error:function(jqXHR, textStatus, errorThrown){

                  console.log(errorThrown+"");

              }


          });


      });


      $("#btn-wd-submit").click(function(){

        var year=$("#txt-wd-year").val();
        var month=$("#txt-wd-month").val();
        var count=$("#txt-wd-count").val();
        var startingFrom=$("#txt-wd-starting").val();



        $.ajax({
                        url: "scripts/upload_working_days_details.php",
                        type: 'POST',
                        data: {
                            year:year,
                            month:month,
                            count:count,
                            starting_from:startingFrom
                      
                        },
                        success: function (data) {


                            var response=JSON.parse(data);
                            if(response.result){

                                Swal.fire({
                                    title: 'Success',
                                    text: 'Successfully Saved.',
                                    icon: 'success',
                                    allowOutsideClick:false,
                                    showCancelButton: false,
                                    showConfirmButton:true,
                                    cancelButtonColor: '#d33',
                                    confirmButtonText: 'OK'
                                  }).then((result) => {
                                    if (result.value) {

                                      location.reload();
                                  
                                    }
                                  });

                                
                            

                            }else{

                                Swal.fire({
                                    title: 'Warning !',
                                    text: response.msg,
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
                            alert(errorThrown+"");
                        }

                    });






      });

      // demo.initGoogleMaps();
    });



    function changeUserRoute(routeId,routeDate){
        
        var user_id=$("#txt-rp-rep").val();
        

  
      $.ajax({

        url:'scripts/change_user_route_plan.php',
        type:'POST',
        data :{

          user_id:user_id,
          route_id:routeId,
          route_date:routeDate
        
        },
        success:function(data){
            
          var json=JSON.parse(data);
          if(json.result){

            Swal.fire({
                                    title: 'Success',
                                    text: 'Route assigned Successfully',
                                    icon: 'success',
                                    allowOutsideClick:false,
                                    showCancelButton: false,
                                    showConfirmButton:true,
                                    cancelButtonColor: '#d33',
                                    confirmButtonText: 'OK'
                                  }).then((result) => {
                                    if (result.value) {

                                      
                                  
                                    }
                                  });





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
        error : function(err) {
            alert(err);
        }




      });



    }


  </script>


</body>
</html>