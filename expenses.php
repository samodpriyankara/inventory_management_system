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
                                <h3>Add Expense</h3>
                            </div>
                            
                        </div>
                        <div class="card card-form">
                            <div class="row no-gutters">
                                
                                <form id="form-add-expense" class="col-lg-4 card-form__body card-body">
                                    


                                    <div class="form-group">
                                        <label>Description</label>
                                        <input id="txt-description" name="description" type="text" class="form-control" placeholder="Expense Description" required>
                                    </div>
                                    
                                     <div class="form-group">
                                        <label>Debit Amount</label>
                                        <input id="txt-debit-amount" name="debit_amount" type="number" class="form-control" placeholder="Debit Amount" min="0" required>
                                    </div>
                                    
                                    <div class="form-group">
                                        <label>Date</label>
                                        <input id="txt-debit-date" name="debit_date" type="date" class="form-control" placeholder="Debit Date" required>
                                    </div>





                                    <div class="text-right mb-5">
                                        <button type="submit" id="btn-submit" class="btn btn-primary">Add Expense</button>
                                    </div>
                                </form>

                                <div class="col-lg-8 card-body">
                                    
                                    <div class="table-responsive border-bottom" style="padding: 10px;">

                                        

                                        <table class="table mb-0 thead-border-top-0" id="tbl_product_view">
                                            <thead>
                                                <tr>
                                                    <th>Expense Date</th>
                                                    <th>Description</th>
                                                    <th>Debit Amount</th>
                                                    
                                                </tr>
                                            </thead>
                                            <tbody class="list">
                                               
                                               <?php
                                                $total_expenses = 0;
                                                $get_all_expenses = $conn->query("SELECT * FROM tbl_petty_cash_and_expenses WHERE debit != 0");
                                                while($ers = $get_all_expenses->fetch_array()){
                                                    $total_expenses += $ers[4];
                                                    ?>
                                                    
                                                    <tr>
                                                         <td><?php echo $ers[1]?></td>
                                                         <td><?php echo $ers[2]?></td>
                                                         <td><?php echo number_format($ers[4],2)?></td>
                                                    </tr>
                                                    
                                                    <?php
                                                    
                                                    
                                                }
                                               
                                               
                                               ?>
                                               
                                            </tbody>
                                            
                                            <tfoot>
                                                <tr style = "background-color:#FF9999">
                                                    <td></td>
                                                    <td style = "font-weight:bold">Total Expenses</td>
                                                    <td style = "font-weight:bold"><?php echo number_format($total_expenses,2)?></td>
                                                </tr>
                                            </tfoot>
                                            
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
    </div>
    <!-- // END drawer-layout -->

    <?php include_once('controls/notification.php'); ?>

    <!-- Modal -->





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

    <script src="https://cdn.datatables.net/buttons/2.1.0/js/dataTables.buttons.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/pdfmake.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.1.0/js/buttons.html5.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.1.0/js/buttons.print.min.js"></script>

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
    $(document).ready( function () {
        $('#tbl_product_view').DataTable({
                "order": [[ 0, "desc" ]],
                    dom: 'Bfrtip',
                    buttons: [
                        // 'copy', 'csv', 'excel', 'pdf', 'print'
                        'print', 'excel', 'pdf'
                    ]
            });

    

    } );
  </script>


<script>





function validate(evt) {
  var theEvent = evt || window.event;

  // Handle paste
  if (theEvent.type === 'paste') {
      key = event.clipboardData.getData('text/plain');
  } else {
  // Handle key press
      var key = theEvent.keyCode || theEvent.which;
      key = String.fromCharCode(key);
  }
  var regex = /[0-9]|\./;
  if( !regex.test(key) ) {
    theEvent.returnValue = false;
    if(theEvent.preventDefault) theEvent.preventDefault();
  }
}




    



    $(document).ready(function() {
        

    $("#form-add-expense").submit(function(e){
        e.preventDefault();
        
         var formData = new FormData($(this)[0]);
            
            
            	$.ajax({
                        url: "scripts/add_expenses.php",
                        type: 'POST',
                        data: formData,
                        //async: false,
                        cache: false,
                        contentType: false,
                        processData: false,

                          beforeSend : function() {

                              Swal.fire({
                                title:'',
                                icon: 'info',
                                text:'Please wait...',
                                showConfirmButton:false,
                                showCancelButton:false,
                                allowOutsideClick: false,
                              });

                          },


                        success: function (data) {

                            
                            var json=JSON.parse(data);
                            if(json.result){

                                Swal.fire({
                                    title: 'Success',
                                    text: 'Expense registration success.',
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
                        error: function (jqXHR, textStatus, errorThrown) {
                            console.log(errorThrown+"");
                        }

                    });
            
            
            
        
        
    });



    });

</script>

  
</body>
</html>