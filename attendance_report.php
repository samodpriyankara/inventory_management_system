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
                            <h3>Sales-Rep Attendance</h3>
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
                                    <p><strong class="headings-color">Search</strong></p>
                                    <p class="text-muted">Add search functionality to your tables with List.js. Please read the <a href="http://listjs.com/" target="_blank">official plugin documentation</a> for a full list of options.</p>
                                </div> -->

                                <div class="col-sm-12 col-md-12 col-lg-12">
                                    <div class="col-sm-6 col-md-6 col-lg-6 " style="float: right;"> 
                                        <br>
                                        <input type="date" class="form-control" id="att-sum-search-date" />
                                        <br>
                                    </div>
                                </div>



                                <div class="col-lg-12 card-form__body">


                                    <div class="table-responsive border-bottom" style="padding: 10px;">

                                        <!-- <div class="search-form search-form--light m-3">
                                            <input type="text" class="form-control search" placeholder="Search">
                                            <button class="btn" type="button" role="button"><i class="material-icons">search</i></button>
                                        </div> -->

                                        <table class="table mb-0 thead-border-top-0" id="attendanceTable">
                                            <thead>
                                                <tr>
                                                    <th>In Date</th>
                                                    <th>In Time</th>
                                                    <th>Rep's Name</th>
                                                    <th>In Location</th>
                                                    <th>Out Date</th>
                                                    <th>Out Time</th>
                                                    <th>Out Location</th>
                                                </tr>
                                            </thead>
                                            <tbody class="list" id="tbl-att-data">
                                               
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


    function showImage(inId,outId){

        Swal.fire({
            title:'',
            // text: json.make+" "+json.color,
            //icon: 'assets/img/ajaxloader.gif',
            imageUrl: 'assets/ajaxloader.gif',
            html:'Loading...',
            allowOutsideClick:false,
            showCancelButton: false,
            showConfirmButton:false,
            showCloseButton: false,
            cancelButtonColor: '#d33'
                                              
        }); 
    }



    function loadAttendanceSummary(selectedDate){
        
        Swal.fire({
            title:'',
            // text: json.make+" "+json.color,
            //icon: 'assets/img/ajaxloader.gif',
            imageUrl: 'assets/ajaxloader.gif',
            html:'Loading...',
            allowOutsideClick:false,
            showCancelButton: false,
            showConfirmButton:false,
            showCloseButton: false,
            cancelButtonColor: '#d33'
                                      
        });
                                  
                                  
                                    
    $.ajax({
                                    
        url:'controls/download_emp_attendance_summary_for_date.php',
        type:'POST',
        data:{
            selected_date:selectedDate
        },
        success:function(data){
              
        // console.log(data);

              
              var json=JSON.parse(data);
           

              if(json.result){
                  
                   $("#tbl-att-data").html("");
                  
                
                var attList=json.emp;
                
                for(var index=0;index<attList.length;index++){
                    
                    
                    var rowData=attList[index];
                    
                    var markup="";


                    if(rowData.in_absent){


                    }else{
                        markup="<tr><td>"+rowData.inDate+"</td>"+"<td>"+rowData.inTime+"</td>"+"<td>"+rowData.emp_name+"</td>"+"<td>"+rowData.in_loc+"</td>"+"<td>"+rowData.outDate+"</td><td>"+rowData.outTime+"</td>"+"<td>"+rowData.out_loc+"</td>"+"</tr>";
                    }
                    
                       if(rowData.in_absent && rowData.out_absent){
                           
                           markup="<tr style=background-color:#FFCCCC><td>"+rowData.inDate+"</td>"+"<td>"+rowData.inTime+"</td>"+"<td>"+rowData.emp_name+"</td>"+"<td>"+rowData.in_loc+"</td>"+"<td>"+rowData.outDate+"</td><td>"+rowData.outTime+"</td>"+"<td>"+rowData.out_loc+"</td>"+"</tr>";  
     
                       }
                       
           
                    $("#tbl-att-data").append(markup);
                   
                }
                
                  
            }
              
              
              
        Swal.close();
              
        },
        error:function(err){
            console.log(err);
              
        }
                                    
                                    
                                    
                                    
    });
                                
      
        
    }



    $(document).ready(function (){
        
        loadAttendanceSummary("");
        
        
        $("#att-sum-search-date").change(function(){
            
            var selectedDate=$("#att-sum-search-date").val();
            loadAttendanceSummary(selectedDate);
             
        });
        
      

    });



    
  </script>

    <script>
        function viewLocation(lat,lng){
            window.open("https://www.google.com/maps/search/"+lat+"+"+lng+"?sa=X&ved=2ahUKEwia_uS42r_0AhWUlNgFHTOOB1YQ8gF6BAgCEAE");
        }
  </script>

</body>
</html>