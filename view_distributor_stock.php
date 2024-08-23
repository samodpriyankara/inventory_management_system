<?php
    session_start();
    require 'database/db.php';
    $db=new DB();
    $conn=$db->connect();
    date_default_timezone_set('Asia/Colombo');
    $currentDate=date('Y-m-d');

    $dist_id = base64_decode($_GET['distributor']);

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
                            <h3>View Distributor Stock</h3>
                        </div>
                        <!-- <div class="col-md-6">
                            <div class="pull-right" style="float: right;">
                                <a href="create_invoice" class="btn btn-primary">Create Invoice</a>
                            </div>
                        </div> -->
                    </div>

                    <?php

                        if($is_distributor){ ?>
                            <a href="view_distributor_stock_history?distributor=<?php echo base64_encode($user_id);?>" class="btn btn-danger pull-right">View Stock History</a>
                        <?php } ?>

                        <div class="card card-form">
                            <div class="row no-gutters">
                                <!-- <div class="col-lg-4 card-body">
                                    <p><strong class="headings-color">Search</strong></p>
                                    <p class="text-muted">Add search functionality to your tables with List.js. Please read the <a href="http://listjs.com/" target="_blank">official plugin documentation</a> for a full list of options.</p>
                                </div> -->
                                <div class="col-lg-12 card-form__body">

                                    <div class="table-responsive border-bottom" style="padding: 10px;">

                                        <!-- <div class="search-form search-form--light m-3">
                                            <input type="text" class="form-control search" placeholder="Search">
                                            <button class="btn" type="button" role="button"><i class="material-icons">search</i></button>
                                        </div> -->

                                        <table class="table mb-0 thead-border-top-0" id="StockTable">
                                            <thead>
                                                <tr>
                                                    <th>Code</th>
                                                    <th>Name</th>
                                                    <th>Brand</th>
                                                    <th>Cost Price</th>
                                                    <th>Selling Price</th>
                                                    <th>Stock</th>
                                                    <th></th>
                                                </tr>
                                            </thead>
                                            <tbody class="list">
                                                <?php

                                                    $getProductsQuery=$conn->query("SELECT ti.itemCode,ti.itemDescription,ti.brand_name,ti.price,ti.stock,ti.itemId,ti.re_price,tdhp.qty,tdhp.cost_price,tdhp.id FROM tbl_item ti INNER JOIN tbl_distributor_has_products tdhp ON ti.itemId = tdhp.item_id WHERE tdhp.distributor_id = '$dist_id' ORDER BY itemCode ASC");
                                                    while ($product=$getProductsQuery->fetch_array()) {
                                                        $item_id = $product[5];
                                                        $item_description = $product[1];
                                                        $item_cost = $product[8];
                                                        $item_selling_price = $product[3];
                                                        $available_qty = $product[7];
                                                        $assignment_id = $product[9];
                                                        
                                                        $total_cost += $item_cost * $available_qty;
                                                        $total_selling += $item_selling_price * $available_qty;
                                                        
                                                ?>
                                                <tr>
                                                    <td><?php echo $product[0]; ?></td>
                                                    <td><?php echo $product[1]; ?></td>
                                                    <td><?php echo $product[2]; ?></td>
                                                    <td><font class="pull-right" style="font-weight: 600;"><?php echo number_format($item_cost,2); ?></font></td>
                                                    <td><font class="pull-right" style="font-weight: 600;"><?php echo number_format($item_selling_price,2); ?></font></td>
                                                    <td><font class="pull-right"><?php echo $available_qty; ?></font></td>
                                                    <td><a href="#" onclick="remove_assignment('<?php echo $assignment_id;?>')" style="color: red">Remove</a></td>
                                                </tr>
                                                <?php } ?>
                                                
                                                <tfoot>
                                                    <?php 
                                                        $FullCost= $total_cost;
                                                        $FullSelling= $total_selling;
                                                        
                                                        $FullIncome= $FullSelling - $FullCost;
                                                    ?>
                                                    <th></th>
                                                    <th></th>
                                                    <th></th>
                                                    <th></th>
                                                    <th style="color: #080080;"><b style="float: right;">Selling Value<br><?php echo number_format($FullSelling,2); ?></b></th>
                                                    <th style="color: #FF0000;"><b style="float: right;">Stock Value<br><?php echo number_format($FullCost,2); ?></b></th>
                                                    <th style="color: #008000;"><b style="float: right;">GROSS PROFIT<br><?php echo number_format($FullIncome,2); ?></b></th>
                                                </tfoot>
                                                
                                                
                                                
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
        //     $('#StockTable').DataTable({
        //         "order": [[ 0, "desc" ]],
        //             dom: 'Bfrtip',
        //             buttons: [
        //                 // 'copy', 'csv', 'excel', 'pdf', 'print'
        //                 'print', 'excel', 'pdf'
        //             ]
        //     });
        // } );
        
        
        
                        $(document).ready(function () {
                    $('#StockTable').DataTable({
                        "order": [[0, "desc"]],
                        dom: 'Bfrtip',
                        buttons: [
                            {
                                extend: 'print',
                                customize: function (win) {
                                    // Add custom content to the printed document
                                    $(win.document.body).append('<div style="margin-top:20px;">' +
                                        '<p style="font-weight:bold;">Selling Value: <?php echo number_format($FullSelling,2); ?></p>' +
                                        '<p style="font-weight:bold;">Stock Value: <?php echo number_format($FullCost,2); ?></p>' +
                                        '<p style="font-weight:bold;">GROSS PROFIT: <?php echo number_format($FullIncome,2); ?></p>' +
                                        '</div>');
                                }
                            },
                            'excel', 'pdf'
                        ]
                    });
                });
                
                
              


    </script>
    
    

    <script>




    function remove_assignment(assignment_id){

        
        Swal.fire({
          title: 'Are you sure?',
          text: "Do you want to remove this assignment",
          icon: 'warning',
          showCancelButton: true,
          confirmButtonColor: '#3085d6',
          cancelButtonColor: '#d33',
          confirmButtonText: 'Yes'
        }).then((result) => {
          if (result.isConfirmed) {
              


            $.ajax({
                    
                    
                        beforeSend : function() {
                            // $("#progress_alert").addClass('show'); 

                        },

                        url:"scripts/remove_assigned_product_to_distributor.php",
                        type: 'POST',
                        data: {
                          assignment_id:assignment_id
                        },
                        //async: false,
                      
                        success: function (data) {

                          
                            
                            var json=JSON.parse(data);
                            
                            if(json.result){


                                Swal.fire({
                                    title: 'Success',
                                    text: json.msg,
                                    icon: 'success',
                                    allowOutsideClick:false,
                                    showCancelButton: false,
                                    showConfirmButton:false,
                                    cancelButtonColor: '#d33',
                                    cancelButtonText: 'OK'
                                  });


                               
                               setTimeout(function(){
                                // location.reload();
                                
                               }, 500);

                                
                            }else{

                              Swal.fire({
                                    title: 'Warning !',
                                    text: json.msg,
                                    icon: 'warning',
                                    allowOutsideClick:true,
                                    showCancelButton: true,
                                    showConfirmButton:false,
                                    cancelButtonColor: '#d33',
                                    cancelButtonText: 'OK'
                                  });
                                
                            }
                            
                        }

                    });






          }
        })




    }



    $(document).ready( function () {
        // $('#StockTable').DataTable();

        $("#form_assign_produc_to_dist").submit(function(e){
          e.preventDefault();


                    var formData = new FormData($(this)[0]);
                    $.ajax({
                    
                    
                        beforeSend : function() {
                            // $("#progress_alert").addClass('show'); 

                        },

                        url:"scripts/assign_product_to_distributor.php",
                        type: 'POST',
                        data: formData,
                        //async: false,
                        cache: false,
                        contentType: false,
                        processData: false,

                        success: function (data) {

                          
                            
                            var json=JSON.parse(data);
                            
                            if(json.result){



                               // $("#success_msg").html(json.msg);
                               // $("#success_alert").addClass('show'); 

                               Swal.fire({
                                    title: 'Success',
                                    text: json.msg,
                                    icon: 'success',
                                    allowOutsideClick:false,
                                    showCancelButton: false,
                                    showConfirmButton:false,
                                    cancelButtonColor: '#d33',
                                    cancelButtonText: 'OK'
                                  });


                               
                               setTimeout(function(){
                                location.reload();
                                // $("#success_alert").removeClass('show');  
                               }, 500);

                               // window.location.href = "dashboard";
                                
                            }else{

                              Swal.fire({
                                    title: 'Warning !',
                                    text: json.msg,
                                    icon: 'warning',
                                    allowOutsideClick:true,
                                    showCancelButton: true,
                                    showConfirmButton:false,
                                    cancelButtonColor: '#d33',
                                    cancelButtonText: 'OK'
                                  });
                                // $("#danger_alert").addClass('show');
                                // setTimeout(function(){ $("#danger_alert").removeClass('show'); }, 1000);
                                // $("#mail-create-btn").attr("disabled",false);
                            }
                            
                        }

                    });


        });



    } );
  </script>

</body>
</html>