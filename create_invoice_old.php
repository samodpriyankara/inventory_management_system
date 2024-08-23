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
    
    
    <!-- Button to Open the Modal -->
<button type="button" class="btn btn-primary" data-toggle="modal" data-target="#myModal">
  Open modal
</button>

<!-- Return order selection modal -->
<div class="modal" id="modal_return_order_selection">
  <div class="modal-dialog">
    <div class="modal-content">

      <!-- Modal Header -->
      <div class="modal-header">
        <h4 class="modal-title">Pick a return order number</h4>
        <!--<button type="button" class="close" data-dismiss="modal">&times;</button>-->
      </div>

      <!-- Modal body -->
      <div class="modal-body">
        
        <div class="form-group">
            <label>Return Order Number</label>
            <select class="form-control" id="select-return-order-no">
                <option value = "0" selected disabled>SELECT RETURN ORDER NUMBER</option>
                
                <?php
                
                    $get_return_orders = $conn->query("SELECT id,order_id FROM tbl_return_order ORDER BY id DESC");
                    while($r_rs = $get_return_orders->fetch_array()){
                        
                        ?>
                        
                            <option value="<?php echo $r_rs[0];?>"><?php echo $r_rs[1];?></option>
                        
                        <?php
                        
                        
                    }
                 
                ?>
                
            </select>
        </div>
        
        <div class="form-group">
            <label>Note (optional)</label>
            <textarea class="form-control" id="txt-return-note"></textarea>
        </div>
        
        
      </div>

      <!-- Modal footer -->
      <div class="modal-footer">
        <span class="pull-left" id="lbl-return-no-err" style="color:red"></span>  
        <button type="button" id="btn-close-return-selection"  class="btn btn-danger">Close</button>
        <button type="button" class="btn btn-success" id="btn-done-return-order-no" style="color:white">Done</button>
      </div>

    </div>
  </div>
</div>





<!--MODAL PREVIEW-->
    
    <div class="modal" id="modal-preview-invoice">
  <div class="modal-dialog">
    <div class="modal-content">

      <!-- Modal Header -->
      <div class="modal-header">
        <h4 class="modal-title">Invoice Summary</h4>
        <!--<button type="button" class="close" data-dismiss="modal">&times;</button>-->
      </div>

      <!-- Modal body -->
      <div class="modal-body">
       
       
            
            <table class="table table-striped">
                <thead>
                    <th>Item Name</th>
                    <th>Quantity</th>
                    <th>Total Amount</th>
                </thead>
                
                <tbody id="invoice-preview-body">
                    
                </tbody>
                
            </table>
            
            
     
      
        
        
      </div>

      <!-- Modal footer -->
      <div class="modal-footer">
        <button type="button"  class="btn btn-danger" data-dismiss="modal">OK</button>
        
      </div>

    </div>
  </div>
</div>





<!----------------->



    
    
    
    

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
                                    <p><strong class="headings-color">Create Invoice</strong></p>
                                    <p class="text-muted">Edit your account details and settings.</p>
                                </div>
                                <div class="col-lg-8 card-form__body card-body">
                                    <div class="row">
                                        <div class="col">
                                            <div class="form-group">
                                                <label>Order Type</label>
                                                <select id="txt_order_type" class="custom-select" style="width: 100%;">
                                                    <option value="0">Sales Order</option>
                                                    <option value="1">Return Order</option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col">
                                            <div class="form-group" style="display: none" id="area_return_type">
                                                <label>Return Type</label>
                                                <select id="txt_return_type" class="custom-select" style="width: 100%;">
                                                    <option value="0">Sales Return</option>
                                                    <option value="1">Damage Return</option>
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col">
                                            <div class="form-group">
                                                <label>Select Route</label>
                                                <select id="route_name" class="js-example-basic-single custom-select" style="width: 100%;">
                                                    <option selected disabled value="">Select Route</option>
                                                    <?php
                                                        
                                                        if($is_distributor){
                                                            $RouteQuery=$conn->query("SELECT * FROM tbl_route tr INNER JOIN tbl_distributor_has_route tdhr ON tr.route_id = tdhr.route_id WHERE tdhr.distributor_id = '$user_id' AND tr.status = 1");
                                                        }else{
                                                            $RouteQuery=$conn->query("SELECT * FROM tbl_route WHERE status = 1");
                                                        }
                                                        
                                                        while ($Rrow=$RouteQuery->fetch_array()) {
                                                    ?>
                                                        <option value="<?php echo $Rrow[1];?>"><?php echo $Rrow[1];?></option>
                                                    <?php } ?>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col">
                                            <div class="form-group" id="area_payment_type">
                                                <label>Payment Type</label>
                                                <select id="txt_payment_type" class="custom-select" style="width: 100%;">
                                                    <option value="0">Cash</option>
                                                    <!--<option value="1">Cheque</option>-->
                                                    <option value="2">Credit</option>
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col">
                                            <div class="form-group">
                                                <label>Outlet</label>
                                                
                                                
                                                
                                                
                                                <select id="txt_outlet" class="js-example-basic-single custom-select" style="width: 100%;" required>
                                                </select>
                                                
                                                
                                                
                                                <!--<input type="text" id="txt_outlet" list="list_outlets" class="custom-select" placeholder="Outlet" required>-->
                                                <!--    <datalist id="list_outlets">-->
                                    
                                                <!--    </datalist>-->
                                            </div>
                                        </div>
                                        <div class="col">
                                            <div class="form-group">
                                                <label>Distributor</label>
                                                    <select type="text" id ="txt_dist" class="js-example-basic-single custom-select">

                                                        <!--<option value="0" selected disabled>SELECT DISTRIBUTOR</option>-->

                                                        <?php
                                                            if($is_distributor){

                                                                $getDist = $conn->query("SELECT * FROM tbl_distributor WHERE distributor_id='$user_id'");
                                                                if($distRs = $getDist->fetch_array()){

                                                        ?>
                                                                <option value="<?php echo $distRs[0];?>" selected><?php echo $distRs[1].' ('.$distRs[2].')';?></option>
                                                        
                                                        <?php } }else{ ?>

                                                          

                                                            <?php
                                                                $getDist = $conn->query("SELECT * FROM tbl_distributor");
                                                                while($distRs = $getDist->fetch_array()){

                                                            ?>
                                                                <option value="<?php echo $distRs[0];?>" selected><?php echo $distRs[1].' ('.$distRs[2].')';?></option>

                                                            <?php } ?>


                                                        <?php } ?>

                                                  </select>
                                            </div>
                                        </div>
                                        
                                        
                                    
                                        
                                        
                                        
                                        
                                    </div>
                                    
                                    <div class="row" id="area_return_status">
                                        <div class="col">
                                            <div class="form-group">
                                                <label>Order Return Status</label>
                                                
                                                
                                                
                                                    
                                                    
                                                  <div class="container">
                                                      
                                                      <div class="row">
                                                        <div class="form-check" style = "margin-right:25px;">
                                                          <input type="radio" class="form-check-input" id="rad-with-returns" name="rad_return_status" value="1">With Returns
                                                          <label class="form-check-label" for="rad-with-returns"></label>
                                                        </div>
                                                        <div class="form-check">
                                                          <input type="radio" class="form-check-input" id="rad-without-returns" name="rad_return_status" value="0" checked>Without Returns
                                                          <label class="form-check-label" for="rad-without-returns"></label>
                                                        </div>
                                                        
                                                        </div>
                                                        
                                                  </div>
                                                    
                                                    
                                                

                                                
                                                <!--<select id="txt_order_type" class="custom-select" style="width: 100%;">-->
                                                <!--    <option value="0">Sales Order</option>-->
                                                <!--    <option value="1">Return Order</option>-->
                                                <!--</select>-->
                                            </div>
                                        </div>
                                       
                                    </div>

                                    

                                    <div class="text-right mb-5">
                                        <button class="btn btn-danger" onclick="location.reload();" id="btn_cancel_invoice" style="display: none;">Cancel</button>
                                        <button type="submit" id="btn_create_invoice" class="btn btn-dark">Create</button>
                                    </div>

                                </div>

                                
                            </div>

                        </div>

                        
                    </div>





                    <div style="width: 100%;padding: 80px;display: none;" id="area_progress">
                        <center><img src="progress.gif" width="100"></center>
                    </div>



                    <div class="container-fluid page__container">
                        <div class="card card-form">
                            <div class="row no-gutters">
                                

                                <div class="col-md-12" id="area_products" style="display: none;">
                               
                                      
                                    

                                      <table class="table table-hover" id="tbl-products">
                                        <thead>
                                              <th>#</th>
                                            <th>Product image</th>
                                            <th>Product name</th>
                                            <th>Available qty</th>
                                            <th>Unit price</th>
                                            <th></th>
                                            <th></th>
                                            
                                        </thead>

                                        <tbody id="body_products">
                                            
                                        </tbody>


                                      </table>
                                       



                                       <button class="btn btn-primary" id="btn-save-invoice" style="padding: 10px;">SAVE INVOICE</button>
                                       <button class="btn btn-danger" id="btn-preview-invoice" style="padding: 10px;">PREVIEW ITEMS</button>
                                       <h4 class="pull-right" id="lbl_invoice_total" style="padding: 10px; font-weight: 600;font-size: 30px;color: red;margin-right: 50px; float: right;">LKR 0.00</h4><br>
                                        
                                       
                                        
                                     
                                      


                                 
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
      
      <script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>
      
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

    var invoiceItemsDetails = [];

    function addToCart(input,label,item_id,price,stockQty,des){
        
       

      var qty = $("#"+input);
      var lbl = $("#"+label);
      
      
      

      if(parseInt(qty.val()) > parseInt(stockQty)){
          
           

                    Swal.fire({
                      icon: 'warning',
                      title: 'Oops...',
                      text: 'Not enough quantity availbale to add.'
                    });

      }else if(qty.val() !== ""){



      var obj = {item_id:item_id,item_qty:qty.val(),price:price,name:des};
      // obj['item_id'] = item_id;
      // obj['item_qty'] = qty.val();



      if(invoiceItemsDetails.length == 0){
        invoiceItemsDetails.push(obj);
        console.log("added");
      }else{

             for(var i = 0;i<invoiceItemsDetails.length;i++){

                  var po = invoiceItemsDetails[i];

                  if(po.item_id == obj.item_id){

                      var index = invoiceItemsDetails.indexOf(po);

                    if(obj.item_qty === 0 && index !== -1){
                        invoiceItemsDetails.splice(index, 1);
                         console.log("removed");
                    }else{
                       if (index !== -1) {
                        invoiceItemsDetails.splice(index, 1);
                        console.log("removed");
                      }
                    }

                    
                   




                    
                  }


            }

            if(obj.item_qty>0){
              invoiceItemsDetails.push(obj);
              console.log("added");
            }
            


      }

      



     


      




      $("#"+label).val(parseInt(qty.val()));
      qty.val("");


      }else{

                    Swal.fire({
                      icon: 'warning',
                      title: 'Oops...',
                      text: 'Please enter valid quantity.'
                    });

      }






      /////view the total of invoice///////

            var invoice_total = 0.00;

             for(var i = 0;i<invoiceItemsDetails.length;i++){

                  var po = invoiceItemsDetails[i];
                  var qty = po.item_qty;
                  var price = po.price;


                  invoice_total += (parseFloat(qty) * parseFloat(price));



              }


                $("#lbl_invoice_total").html("LKR "+invoice_total.toFixed(2));


      ///////////////////////////////////////




    }


    function check_key_up(e,input_id,lbl_id,item_id,price,stock_qty,des){


        if(e.keyCode == 13){
            addToCart(input_id,lbl_id,item_id,price,stock_qty,des);
        }
    }




    $(document).ready( function () {
        
         $('.js-example-basic-single').select2();
        $("#btn_cancel_invoice").hide();
        $("#area_progress").hide();
        
        
        
        $("#btn-preview-invoice").click(function(){
            $("#invoice-preview-body").html("");
            
            
            if(invoiceItemsDetails.length != 0){
                
                
                
                
                for(var i = 0;i<invoiceItemsDetails.length;i++){

                  var po = invoiceItemsDetails[i];
                  console.log(po);
                  
                  var name = po.name;
                  var qty = po.item_qty;
                  var total = parseFloat(po.price) * parseFloat(qty);
                  
                  var row = '<tr><td>'+name+'</td><td>'+qty+'</td><td>'+total.toFixed(2)+'</td><tr>';
                  
                  $("#invoice-preview-body").append(row);
                 
                
                }
                
                
                
                
              
                $("#modal-preview-invoice").modal(); 
            }else{
                alert("No items has been added.");
            }
            
             
            
            
        });
        
        
        
        
        
        
        
        


        $("#txt_order_type").change(function(){

            $("#rad-without-returns").prop("checked", true);
            

            if($("#txt_order_type").val() === "0"){
                
                $("#rad-without-returns").prop("checked", true);
                $("#area_return_status").show();
                $("#area_return_type").hide();
                $("#area_payment_type").show();
            }else{
                $("#area_return_status").hide();
                $("#area_return_type").show();
                $("#area_payment_type").hide();
            }


        });
        
        $("#btn-done-return-order-no").click(function(){
            
            
            
            if($("#select-return-order-no").val() == null){
                
                $("#lbl-return-no-err").html("PLEASE PICK A RETURN ORDER NO");
                
                setTimeout(function() {
                    $("#lbl-return-no-err").html("");
                }, 2000);
                
                
            }else{
                //hide modal
                $("#modal_return_order_selection"). modal('hide');
            }
        });
        
        $("#btn-close-return-selection").click(function(){
            $("#rad-without-returns").prop("checked", true);
            $("#modal_return_order_selection"). modal('hide');
        });


        $("#btn-save-invoice").click(function(){


          if(invoiceItemsDetails.length > 0){


            

            $("#btn-save-invoice").attr("disabled",true);
            
            
            $("#area_progress").show();



            var list = JSON.stringify(invoiceItemsDetails);
            var return_status = $('input[name=rad_return_status]:checked').val();

        
           
        

          $.ajax({
            url:'scripts/save_order_details.php',
            type:'POST',
        
            data:{
                list:list,
                payment_method_id:$("#txt_payment_type").val(),
                outlet:$("#txt_outlet").val(),
                route:$("#route_name").val(),
                distributor_id:$("#txt_dist").val(),
                is_return:$("#txt_order_type").val(),
                return_type:$("#txt_return_type").val(),
                
                return_status: return_status,
                return_order_no: $("#select-return-order-no").val(),
                return_note: $("#txt-return-note").val()
                

            },
            success:function(data){


                
                var json = JSON.parse(data);
                if(json.result){


                    if(json.type === 'so_'){
                        window.open('invoice?i='+json.order_id);
                        location.reload();
                        // location.href = 'invoice?i='+json.order_id;
                    }else{
                        window.open('return_invoice?i='+json.return_id);
                        location.reload();
                        //location.href = 'return_invoice?i='+json.return_id;
                    }




                    


                }else{
                    Swal.fire({
                      icon: 'warning',
                      title: 'Oops...',
                      text: 'Something went wrong. please try again.'
                    });
                }


                $("#area_progress").hide();

            },
            error:function(err){
                
                 $("#btn-save-invoice").attr("disabled",false);
                
                console.log(err);
            }

          });






          }else{
              alert("No items has been added.");
          }



         
            



        });



        $("#route_name").change(function(e){
            
            $.ajax({
                url:'scripts/download_shops_for_route.php',
                type:'POST',
                data:{
                    route_name:$("#route_name").val()
                },
                success:function(data){

                    
                    
                    var json = JSON.parse(data);
                    if(json.result){

                        $("#txt_outlet").html(json.data);

                    }


                },
                error:function(err){
                    console.log(err);
                }


            });

        });
        
        $("#rad-with-returns").change(function(){
            
            if( $("#rad-with-returns").prop("checked") ){
                
                $('#modal_return_order_selection').modal({
                    backdrop: 'static',
                    keyboard: false
                })
                
               
            }
            
            
        });

       

        $("#btn_create_invoice").click(function(){
            
            
            
            
            var shop = $("#txt_outlet").val();
            var route = $("#route_name").val();
            var dist = $("#txt_dist").val();
            var paymentMode = $("#txt_payment_type").val();


            if(route == "" || route == null){
                alert("Please select a route.");
            }else if(shop == "" || shop == null){
                alert("Please select a shop/outlet.");
            }else if(dist == null || dist == ""){
                alert("Please select a distributor.");
            }else{

                $("#area_progress").show();

                $("#txt_outlet").attr("disabled",true);
                $("#route_name").attr("disabled",true);
                $("#txt_dist").attr("disabled",true);
                $("#txt_payment_type").attr("disabled",true);
                
                $("#txt_order_type").attr("disabled",true);
                $("#txt_return_type").attr("disabled",true);
                
                $("#rad-without-returns").attr("disabled",true);
                $("#rad-with-returns").attr("disabled",true);


                ////load products for distributor/////

                $.ajax({
                    url:'scripts/download_products_for_each_distributor.php',
                    type:'POST',
                    data:{
                        dist_id:$("#txt_dist").val()
                    },
                    success:function(data){
                        

                        var json = JSON.parse(data);
                        if(json.result){
                            $("#btn_create_invoice").hide();
                            $("#btn_cancel_invoice").show();
                            $("#body_products").html(json.data);
                            $('#tbl-products').DataTable();
                            $("#area_products").slideDown(400);

                        }


                        $("#area_progress").hide();

                    },
                    error:function(err){
                        console.log(err);
                    }

                });




                //////////////////////////////////////





            }



        });




    } );
  </script>

  

</body>
</html>