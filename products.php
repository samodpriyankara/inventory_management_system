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
                                <h3>Create & Update Products</h3>
                            </div>
                            <!-- <div class="col-md-6">
                                <div class="pull-right" style="float: right;">
                                    <a href="create_invoice" class="btn btn-primary">Create Invoice</a>
                                </div>
                            </div> -->
                        </div>
                        <div class="card card-form">
                            <div class="row no-gutters">
                                
                                <form id="form-add-product" class="col-lg-4 card-form__body card-body">
                                    

                                    <div class="form-group">
                                        
                                        <label>Product Image 1 (Product Cover Image)</label>
                                        <div>
                                            <div class="avatar" style="width: 80px; height: 80px;">
                                                <img id="img_save_pre_1" class="please" src="assets/images/account-add-photo.svg" alt="your image"/><br><br>
                                            </div>
                                            <div class="media-body">
                                                <input type='file' class="btn btn-sm" accept= "image/jpeg, image/jpg, image/png" name="img_input_save_1" id="img_input_save_1" onchange="readURL1Save(this);" required>
                                            </div>
                                        </div>

                                        <!--<input type="hidden" name="img_save_1" id="img_save_1">-->

                                    </div>

                               <hr>

                                    <div class="form-group">
                                        <label>Product Image 2</label>
                                       
                                        <div>
                                           

                                            <div class="avatar" style="width: 80px; height: 80px;">
                                                <img id="img_save_pre_2" class="please" src="assets/images/account-add-photo.svg" alt="your image"/><br><br>
                                            </div>
                                            <div class="media-body">
                                                <input type='file' class="btn btn-sm" accept= "image/jpeg, image/jpg, image/png" name="img_input_save_2" id="img_input_save_2" onchange="readURL2Save(this);">
                                            </div>


                                        </div>

                                        <!--<input type="hidden" name="img_save_2" id="img_save_2">-->

                                    </div>

                                    <hr>


                                    <div class="form-group">
                                        <label>Product Image 3</label>
                                       
                                        <div>
                                           

                                            <div class="avatar" style="width: 80px; height: 80px;">
                                                <img id="img_save_pre_3" class="please" src="assets/images/account-add-photo.svg" alt="your image"/><br><br>
                                            </div>
                                            <div class="media-body">
                                                <input type='file' class="btn btn-sm" accept= "image/jpeg, image/jpg, image/png" name="img_input_save_3" id="img_input_save_3" onchange="readURL3Save(this);">
                                            </div>


                                        </div>

                                        <!--<input type="hidden" name="img_save_3" id="img_save_3">-->

                                    </div>

                                    <hr>


                                    <div class="form-group">
                                        <label>Product Image 4</label>
                                       
                                        <div>
                                           

                                            <div class="avatar" style="width: 80px; height: 80px;">
                                                <img id="img_save_pre_4" class="please" src="assets/images/account-add-photo.svg" alt="your image"/><br><br>
                                            </div>
                                            <div class="media-body">
                                                <input type='file' class="btn btn-sm" accept= "image/jpeg, image/jpg, image/png" name="img_input_save_4" id="img_input_save_4" onchange="readURL4Save(this);">
                                            </div>


                                        </div>

                                        <!--<input type="hidden" name="img_save_4" id="img_save_4">-->

                                    </div>

                                    <hr>


                                    <div class="form-group">
                                        <label>Product Image 5</label>
                                       
                                        <div>
                                           

                                            <div class="avatar" style="width: 80px; height: 80px;">
                                                <img id="img_save_pre_5" class="please" src="assets/images/account-add-photo.svg" alt="your image"/><br><br>
                                            </div>
                                            <div class="media-body">
                                                <input type='file' class="btn btn-sm" accept= "image/jpeg, image/jpg, image/png" name="img_input_save_5" id="img_input_save_5" onchange="readURL5Save(this);">
                                            </div>


                                        </div>

                                        <!--<input type="hidden" name="img_save_5" id="img_save_5">-->

                                    </div>

                                    <hr>










                                    <div class="form-group">
                                        <label>Product Code <span id="part-no" style="color: black; text-align: right !important; position: absolute; right: 15px;"></span></label>
                                        <input id="txt-p-code" name="p_code" type="text" class="form-control" placeholder="Ex : XXXXX" oninput="return product_code();" required>
                                    </div>
                                        
                                    <div class="form-group">
                                        <label>Product Name</label>
                                        <input id="txt-p-name" name="p_name" list="lst-product-names" type="text" class="form-control" placeholder="Ex : XXXXXX" required>
                                        <datalist id="lst-product-names">
                                                <?php
                                                    $get_product_names = $conn->query("SELECT DISTINCT itemDescription FROM tbl_item_details ORDER BY itemDescription ASC");
                                                    while ($pnrs = $get_product_names->fetch_array()) {
                                                        ?>
                                                        <option value="<?php echo $pnrs[0];?>"><?php echo $pnrs[0];?></option>

                                                <?php } ?>
                                            </datalist>
                                        
                                        
                                        
                                    </div>

                                    <div class="form-group">
                                        <label>Brand Name</label>
                                        <input id="txt-p-brand" name="p_brand" list="lst-brands" type="text" class="form-control" placeholder="Ex : XXXXXX" required>
                                        <datalist id="lst-brands">
                                                <?php
                                                    $get_brands = $conn->query("SELECT DISTINCT brand_name FROM tbl_item_details ORDER BY brand_name ASC");
                                                    while ($brs = $get_brands->fetch_array()) {
                                                        ?>
                                                        <option value="<?php echo $brs[0];?>"><?php echo $brs[0];?></option>

                                                <?php } ?>
                                            </datalist>
                                        
                                    </div>

                                    <div class="form-group">
                                        <label>Category</label>
                                        <input type="text" id="txt-p-category" name="p_category" class="form-control" placeholder="Ex : XXXXXX" list="lst-category" required>
                                            <datalist id="lst-category">
                                                <?php
                                                    $getCategories = $conn->query("SELECT DISTINCT name FROM tbl_category ORDER BY name ASC");
                                                    while ($cRs = $getCategories->fetch_array()) {
                                                        ?>
                                                        <option value="<?php echo $cRs[0];?>"><?php echo $cRs[0];?></option>

                                                <?php } ?>
                                            </datalist>
                                    </div>

                                    <!-- <div class="radio">
                                        <label><input type="radio" name="optradio" checked id="rad_with_dist"> With Distributor</label>
                                    </div>
                                    <div class="radio">
                                        <label><input type="radio" name="optradio" id="rad_without_dist"> Without Distributor</label>
                                    </div> -->

                                    <!-- <hr> -->

                                    <!-- <div class="form-group">
                                        <label>Distributor</label>
                                        <select id="txt-p-dis" class="custom-select" style="width: 100%;">
                                            <option selected disabled>Select Distributor</option>
                                            <?php
                                                //$getDisQuery=$conn->query("SELECT td.distributor_id,td.name FROM tbl_distributor td ORDER BY td.name ASC");
                                                //while ($dis=$getDisQuery->fetch_array()) {
                                            ?>
                                                <option value=<?php //echo $dis[0];?>><?php //echo $dis[1];?></option>
                                            <?php //} ?>
                                        </select>
                                    </div> -->

                                    <div class="form-group">
                                        <label>Supplier</label>
                                        <select id="txt-p-sup" name="p_sup" class="custom-select" style="width: 100%;">
                                            <option selected disabled>Select Supplier</option>
                                            <?php
                                                $getSup=$conn->query("SELECT td.supplier_id,td.name FROM tbl_supplier td ORDER BY td.name ASC");
                                                while ($dis=$getSup->fetch_array()) {
                                            ?>
                                                <option value="<?php echo $dis[0];?>"><?php echo $dis[1];?></option>
                                            <?php } ?>
                                        </select>
                                    </div>



                                    <div class="form-group">
                                        <label>Pack Size</label>
                                        <input id="txt-p-pack-size" name="pack_size" type="number" class="form-control" placeholder="Ex : 100" value="0" min="0">
                                    </div>





                                    <div class="text-right mb-5">
                                        <button type="submit" id="btn-submit" class="btn btn-primary">Save</button>
                                    </div>
                                </form>

                                <div class="col-lg-8 card-body">
                                    
                                    <div class="table-responsive border-bottom" style="padding: 10px;">

                                        <!-- <div class="search-form search-form--light m-3">
                                            <input type="text" class="form-control search" placeholder="Search">
                                            <button class="btn" type="button" role="button"><i class="material-icons">search</i></button>
                                        </div> -->

                                        <table class="table mb-0 thead-border-top-0" id="tbl_product_view">
                                            <thead>
                                                <tr>
                                                    <th>Code</th>
                                                    <th>Name</th>
                                                    <th>Brand</th>
                                                    <th></th>
                                                    <!--<th></th>-->
                                                </tr>
                                            </thead>
                                            <tbody class="list">
                                                <?php
                                                    $getProductsQuery=$conn->query("SELECT itemCode,itemDescription,brand_name,item_detail_Id,sequenceId,item_img,category_id FROM tbl_item_details ORDER BY itemCode ASC");
                                                    while ($product=$getProductsQuery->fetch_array()) {

                                                        $ItemCode = $product[0];
                                                        
                                                        $productId = $product[3];
                                                        $ItemImg = $product[5];
                                                        $current_pack_size= 0;
                                                        $catogory_id = $product[6];

                                                        //////get pack size/////

                                                        $get_pack_size =$conn->query("SELECT pack_size FROM tbl_other_item_details WHERE item_id = '$productId'");
                                                        if($pack_rs = $get_pack_size->fetch_array()){
                                                        	$current_pack_size = $pack_rs[0];
                                                        }

                                                        ////////////////////////


                                                    $find_discount_price = $conn->query("SELECT discount,status_code FROM tbl_free_issue_scheme WHERE item_id = '$productId'");
                                                    if ($drsp = $find_discount_price->fetch_array()) {
                                                        if ($drsp[1] == 1) {

                                                            $discount = $drsp[0];
                                                        } else {
                                                            $getCdetails = $conn->query("SELECT * FROM tbl_discount_for_each_category WHERE category_id ='$catogory_id'");
                                                            if ($fRs = $getCdetails->fetch_array()) {
                                                                $discount = $fRs[1];
                                                                
                                                            }
                                                        }
                                                    } else {


                                                        $getCdetails = $conn->query("SELECT * FROM tbl_discount_for_each_category WHERE category_id ='$catogory_id'");
                                                        if ($fRs = $getCdetails->fetch_array()) {
                                                            $discount = $fRs[1];
                                                            
                                                        }
                                                    }




                                                        $getFdetails = $conn->query("SELECT * FROM tbl_free_issue_scheme WHERE item_id='$productId'");
                                                        if($fRs = $getFdetails->fetch_array()){
                                                            $freeMargin = $fRs[1];
                                                            $freeQty = $fRs[2];
                                                            $freeMargin1 = $fRs[5];
                                                            $freeQty1 = $fRs[10];
                                                            $freeMargin2 = $fRs[6];
                                                            $freeQty2 = $fRs[11];
                                                            $freeMargin3 = $fRs[7];
                                                            $freeQty3 = $fRs[12];
                                                            $freeMargin4 = $fRs[8];
                                                            $freeQty4 = $fRs[13];
                                                            $freeMargin5 = $fRs[9];
                                                            $freeQty5 = $fRs[14];
                                                            $discount_margin = $fRs[17];
                                                        }else{
                                                            $freeMargin = 0;
                                                            $freeQty = 0;
                                                            $freeMargin1 = 0;
                                                            $freeQty1 = 0;
                                                            $freeMargin2 = 0;
                                                            $freeQty2 = 0;
                                                            $freeMargin3 = 0;
                                                            $freeQty3 = 0;
                                                            $freeMargin4 = 0;
                                                            $freeQty4 = 0;
                                                            $freeMargin5 = 0;
                                                            $freeQty5 = 0;
                                                            $discount_margin  = 0;
                                                          
                                                            
                                                        }



                                                        $activeStatus = $product[4];//sequence_id
                                             


                                                    if($activeStatus == 1){

                                                ?>

                                                <tr>
                                                    <td><?php echo $product[0]?></td>
                                                    <td><?php echo $product[1]?></td>
                                                    <td><?php echo $product[2]?></td>
                                                    <td>
                                                        <button class="btn btn-danger" onclick="viewEditProductsDialog('<?php echo $product[0]?>','<?php echo $product[1]?>','<?php echo $product[3]?>','<?php echo $activeStatus;?>','<?php echo $freeMargin;?>','<?php echo $freeQty;?>','<?php echo $current_pack_size;?>','<?php echo $ItemImg; ?>',
                                                                                                                       '<?php echo $freeMargin1;?>','<?php echo $freeQty1;?>','<?php echo $freeMargin2;?>','<?php echo $freeQty2;?>','<?php echo $freeMargin3;?>','<?php echo $freeQty3;?>',
                                                                                                                       '<?php echo $freeMargin4;?>','<?php echo $freeQty4;?>','<?php echo $freeMargin5;?>','<?php echo $freeQty5;?>','<?php echo $discount; ?>','<?php echo $discount_margin; ?>')">EDIT</button>
                                                    </td>
                                                    <!--<td>
                                                        <button class="btn btn-primary" data-toggle="modal" onclick="viewPBDialog('<?php //echo $product[0]?>')">PB View</button>
                                                    </td>-->
                                                </tr>

                                                <?php }else if($activeStatus == 0){ ?>

                                                <!--<tr style="background-color: rgb(237,149,149);">-->
                                                <!--    <td><?php echo $product[0]?></td>-->
                                                <!--    <td><?php echo $product[1]?></td>-->
                                                <!--    <td><?php echo $product[2]?></td>-->
                                                <!--    <td>-->
                                                <!--        <button class="btn btn-danger" onclick="viewEditProductsDialog('<?php echo $product[0]?>','<?php echo $product[1]?>','<?php echo $product[3]?>','<?php echo $activeStatus;?>','<?php echo $freeMargin;?>','<?php echo $freeQty;?>','<?php echo $current_pack_size;?>','<?php echo $ItemImg; ?>')">EDIT</button>-->
                                                <!--    </td>-->
                                                <!--</tr>-->


                                                <?php } ?>
                                                
                                                
                                                <?php } ?>
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
    </div>
    <!-- // END drawer-layout -->

    <?php include_once('controls/notification.php'); ?>

    <!-- Modal -->

<div id="modal-view-and-update-extra-images" class="modal fade" role="dialog">
  <div class="modal-dialog modal-lg">

    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        <!-- <button type="button" class="close" data-dismiss="modal">&times;</button> -->
        
        
        <div class="row" style="display:flex">
            <div class="col-md-6">
                <h4 class="modal-title">Extra Product Images)</h4>
                <p class="text-danger">( This will replace old images & If you need to clear all images, press upload now button without selecting any image. )</p>
            </div>
            
            
            <div class="col-md-6">
                <form id="form-upload-extra-images">
            
                <div class="form-group">
                    <input type="hidden" id="lbl-h-id-to-upload-extra-images" name="lbl_product_id">
                    <input type="file" class="form-control" name="extra_images[]" multiple accept="images/*"/>
                </div>
                <div class="form-group">
                     <button class="btn btn-primary">Upload Now</button>
                </div>
            
            </form>
            </div>
            
            
            
            
        </div>
        
        
        
        

      


      </div>
      <div class="modal-body">

       <input type="hidden" id="lbl-h-id-to-update-extra-images" disabled class="form-control">
       <div id="extra-image-preview-area"></div>

      </div>
     <!--  <div class="modal-footer">
        <button type="button" class="btn btn-dark" id="btn-update-product-extra-images">Update Images</button>
      </div> -->
    </div>

  </div>
</div>








<div id="modal-update" class="modal fade" role="dialog">
  <div class="modal-dialog modal-lg">

    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        <!-- <button type="button" class="close" data-dismiss="modal">&times;</button> -->
        <h4 class="modal-title">Update item details</h4>

        <button class="btn btn-success" id="btn-enable-product" style="display: none;">Enable Product</button>
        <button class="btn btn-danger" id="btn-disable-product" style="display: none;">Disable Product</button>



      </div>
      <div class="modal-body">

      	<div class="row">
      	    
      	    <form id="form-update-product-data">
      	    
      	    
      		<div class="col-md-8">
      			
      		

            <input type="hidden" id="lbl-h-id" name="lbl_h_id" class="form-control">

            <div class="form-group">
                <label>Product Code</label>
                <input type="text" id="lbl-code" name="lbl-code" disabled class="form-control">
            </div>

            <div class="form-group">
                <label>Product Name</label>
                <input type="text" id="lbl-name" name="lbl_name" class="form-control">
            </div>


        <!--    <div class="form-group">
                <label>Buying Price</label>
                <input type="text" id="lbl-b-price" class="form-control" onkeypress='validate(event)'>
            </div>


            <div class="form-group">
                <label>Selling Price</label>
                <input type="text" id="lbl-s-price" class="form-control" onkeypress='validate(event)'>
            </div>
 -->
 
            
            <h4 class="modal-title">Discount (%)</h4>
            <hr>


            <div class="form-group">

            <input type="text" id="tbl_discount" name="tbl_discount" class="form-control" placeholder="discount">

            </div>
            
             <h5 class="modal-title">Discount Upper Margin</h5>
             <hr>


            <div class="form-group">
             <input type="text" id="tbl_discount_margin" name="tbl_discount_margin" class="form-control" placeholder="discount upper discount">
            </div>

            

             <h4 class="modal-title">Free issue scheme</h4>
             <hr>


             <div class="form-group">
                <label>Free issue margin</label><span style="color:red"> ( product quantity to enable free issues. )</span>
                <input type="text" id="lbl-free-margin" name="lbl_free_margin" class="form-control" placeholder="margin 1">
                <div id="error-message" style="color:red; display:none;">Free issue margins must be greater than the discount margin.</div>
                <input type="text" id="lbl-free-margin1" name="lbl_free_margin1" class="form-control" placeholder="enter value greater than margin 1">
                <input type="text" id="lbl-free-margin2" name="lbl_free_margin2" class="form-control" placeholder="enter value greater than margin 2">
                <input type="text" id="lbl-free-margin3" name="lbl_free_margin3" class="form-control" placeholder="enter value greater than margin 3">
                <input type="text" id="lbl-free-margin4" name="lbl_free_margin4" class="form-control" placeholder="enter value greater than margin 4">
                <input type="text" id="lbl-free-margin5" name="lbl_free_margin5" class="form-control" placeholder="enter value greater than margin 5">
            </div>
            
                      

             <div class="form-group">
                <label>Free issue quantity</label><span style="color:red"> ( items quantity which will be issued free when above quntity is bought. )</span>
                <input type="text" id="lbl-free-qty" name="lbl_free_qty" class="form-control" placeholder="1">
                 <input type="text" id="lbl-free-qty1" name="lbl_free_qty1" class="form-control" placeholder="1">
                 <input type="text" id="lbl-free-qty2" name="lbl_free_qty2" class="form-control" placeholder="1">
                 <input type="text" id="lbl-free-qty3" name="lbl_free_qty3" class="form-control" placeholder="1">
                 <input type="text" id="lbl-free-qty4" name="lbl_free_qty4" class="form-control" placeholder="1">
                 <input type="text" id="lbl-free-qty5" name="lbl_free_qty5" class="form-control" placeholder="1">
            </div>



            <h4 class="modal-title">Pack Size</h4>
             <hr>

              <div class="form-group">
                <label>Pack Size</label>
                <input type="number" id="lbl-pack-size" name="lbl_pack_size" class="form-control" placeholder="15">
            </div>



            </div>
      		<div class="col-md-4">
      			<div class="form-group">
                    <label>Product Image</label>
                    <div class="media align-items-center" data-toggle="dropzone" data-dropzone-url="http://" data-dropzone-clickable=".dz-clickable" data-dropzone-files='["assets/images/account-add-photo.svg"]'>
                        <div class="avatar" style="width: 80px; height: 80px;">


                            <img id="img3" class="please" src="assets/images/account-add-photo.svg" alt="your image"/><br><br>

                        </div>
                        <div class="media-body">
                            <input type='file' class="btn btn-sm" name="img2" id="img2" onchange="readURL2(this);">
                        </div>
                    </div>

                    <!--<input type="hidden" name="img_save2" id="img_save2">-->

                </div>

                <br><br>

                <!-- EXTRA IMAGES -->


                <a href="javascript:void(0);"class="btn btn-primary pull-right" onclick="show_extra_image_dialog()">View Extra Images</a>



                <!-- ------------ -->






      		</div>
      		
      		<div class="container">
      		    
      		        <button type="submit" class="btn btn-success float-right" id="btn-update-product-data">Update</button>
      		    
      		</div>
      		
      		
      		</form>
      		
      	</div>


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
        
        document.addEventListener('DOMContentLoaded', function() {
            const discountInput = document.getElementById('tbl_discount_margin');
            const freeMarginInputs = [
                document.getElementById('lbl-free-margin') 
            ];
            const errorMessage = document.getElementById('error-message');

            function validateMargins() {
                const discountValue = parseFloat(discountInput.value);
                let isValid = true;

                if (isNaN(discountValue)) {
                    errorMessage.style.display = 'none';
                    return;
                }

                freeMarginInputs.forEach(input => {
                    const marginValue = parseFloat(input.value);
                    if (marginValue !== 0 && !isNaN(marginValue) && marginValue <= discountValue) {
                        isValid = false;
                    }
                });

                if (isValid) {
                    errorMessage.style.display = 'none';
                } else {
                    errorMessage.style.display = 'block';
                }
            }

            discountInput.addEventListener('input', validateMargins);
            freeMarginInputs.forEach(input => {
                input.addEventListener('input', validateMargins);
            });
        });
        
    </script>

    <script>

        function show_extra_image_dialog(){

            $("#lbl-h-id-to-update-extra-images").val($("#lbl-h-id").val());
            $("#lbl-h-id-to-upload-extra-images").val($("#lbl-h-id").val());
            $("#modal-update").modal('hide');

            $.ajax({

                type:"POST",
                url: "scripts/download_extra_images.php",
                data:{
                    pid:$("#lbl-h-id-to-update-extra-images").val()
                },
                cache: false,

                success: function(data) {



                    var json = JSON.parse(data);
                    if(json.result){

                       $("#extra-image-preview-area").html(json.data);

                       

                    }else{
                        alert(json.msg);
                    }

                 
                },
                error:function(err){
                    console.log("EXTRA_FAILED_"+err);
                }

            });









            $("#modal-view-and-update-extra-images").modal();
        }


        function product_code()
        {
            var product_code=document.getElementById('txt-p-code').value;
            var dataString='product_code='+  product_code;
            $.ajax({

                type:"post",
                url: "controls/check_product_code.php",
                data:dataString,
                cache: false,

                success: function(html) {

                    $('#part-no').html(html);
                    return d = true;
                }

            });

            return false;
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

        $('#rad_with_dist').change(function(){

            $('#txt-p-dis').attr("disabled",false);
            
        });

        $('#rad_without_dist').change(function(){

            $("#txt-p-dis").prop("selectedIndex", 0);;
            $('#txt-p-dis').attr("disabled",true);
            
        });

    } );
  </script>


<script>




    $("#btn-disable-product").click(function(){

      $.ajax({
                        url: "scripts/change_product_status.php",
                        type: 'POST',
                        data: {
                          
                            p_id:$("#lbl-h-id").val(),
                            status:0,
                            
                          },
                        success: function (data) {

                          

                            
                            var json=JSON.parse(data);
                            if(json.result){


                              location.reload();

                             

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
                            alert(errorThrown+"");
                        }

                    });




    });

     $("#btn-enable-product").click(function(){

       $.ajax({
                        url: "scripts/change_product_status.php",
                        type: 'POST',
                        data: {
                          
                            p_id:$("#lbl-h-id").val(),
                            status:1,
                            
                          },
                        success: function (data) {

                          

                            
                            var json=JSON.parse(data);
                            if(json.result){


                              location.reload();

                             

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
                            alert(errorThrown+"");
                        }

                    });



      
    });


      function readURL2(input) {
            if (input.files && input.files[0]) {
                var reader = new FileReader();

                reader.onload = function (e) {
                  $("#img_save2").val(e.target.result);
                    $('#img3')
                        .attr('src', e.target.result)
                        .width(80)
                        .height(80);
                };

                reader.readAsDataURL(input.files[0]);
            }
        }



$("#form-update-product-data").submit(function(e){
    e.preventDefault();
    
     var formData = new FormData($(this)[0]);
            
           
 
    
    
     $.ajax({
                        url: "scripts/update_product_prices.php",
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
                                text:'Uploading...',
                                showConfirmButton:false,
                                showCancelButton:false,
                                allowOutsideClick: false,
                              });

                          },
                        
                        
                        success: function (data) {

                          

                            
                            var json=JSON.parse(data);
                            if(json.result){


                              location.reload();


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
                            alert(errorThrown+"");
                        }

                    });
    
    
    
    
    
    
    
});


// $("#btn-update-product-data").click(function(){

    
      

//          $.ajax({
//                         url: "scripts/update_product_prices.php",
//                         type: 'POST',
//                         data: {
//                             p_id:$("#lbl-h-id").val(),
//                             p_name_update:$("#lbl-name").val(),

//                             f_margin:$("#lbl-free-margin").val(),
//                             f_qty:$("#lbl-free-qty").val(),
//                             pack_size:$("#lbl-pack-size").val(),
//                             img_update:$("#img_save2").val()
                            
//                           },
//                         success: function (data) {

                          

                            
//                             var json=JSON.parse(data);
//                             if(json.result){


//                               location.reload();

//                                 // Swal.fire({
//                                 //     title: 'Success',
//                                 //     text: 'Successfully Registered.',
//                                 //     icon: 'success',
//                                 //     allowOutsideClick:false,
//                                 //     showCancelButton: false,
//                                 //     showConfirmButton:true,
//                                 //     cancelButtonColor: '#d33',
//                                 //     confirmButtonText: 'OK'
//                                 //   }).then((result) => {
//                                 //     if (result.value) {

//                                 //       location.reload();
                                  
//                                 //     }
//                                 //   });

//                             }else{

//                                 Swal.fire({
//                                     title: 'Warning !',
//                                     text: json.msg,
//                                     icon: 'warning',
//                                     allowOutsideClick:false,
//                                     showCancelButton: true,
//                                     showConfirmButton:false,
//                                     cancelButtonColor: '#d33',
//                                     cancelButtonText: 'OK'
//                                   });

//                             }

                            





//                         },
//                         error: function (jqXHR, textStatus, errorThrown) {
//                             alert(errorThrown+"");
//                         }

//                     });







//     });
    




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











    function viewEditProductsDialog(code,name,id,status,freeMargin,freeQty,currentPackSize,ItemImg,freeMargin1,freeQty1,freeMargin2,freeQty2,freeMargin3,freeQty3,freeMargin4,freeQty4,freeMargin5,freeQty5,discount,discount_margin){



    if(status == 1){
      $("#btn-enable-product").hide();
      $("#btn-disable-product").show();
      $("#btn-update-product-data").show();
    }else{

      $("#btn-update-product-data").hide();
      $("#btn-disable-product").hide();
      $("#btn-enable-product").show();
    }




    if(ItemImg == '' || ItemImg == null){
    	$("#img3"). attr("src",'assets/images/account-add-photo.svg');
    }else{
    	$("#img3"). attr("src",ItemImg);
    	
    }

    $("#img3").css('height','80');
	$("#img3").css('width','80');



        $("#lbl-code").val(code);
        $("#lbl-name").val(name);
        
    $("#lbl-h-id").val(id);
  //   $("#lbl-b-price").val(bp);
        // $("#lbl-s-price").val(sp);


    $("#lbl-free-margin").val(freeMargin);
    $("#lbl-free-margin1").val(freeMargin1);
    $("#lbl-free-margin2").val(freeMargin2);
    $("#lbl-free-margin3").val(freeMargin3);
    $("#lbl-free-margin4").val(freeMargin4);
    $("#lbl-free-margin5").val(freeMargin5);
    $("#lbl-free-qty").val(freeQty);
    $("#lbl-free-qty1").val(freeQty1);
    $("#lbl-free-qty2").val(freeQty2);
    $("#lbl-free-qty3").val(freeQty3);
    $("#lbl-free-qty4").val(freeQty4);
    $("#lbl-free-qty5").val(freeQty5);
    
    $("#tbl_discount").val(discount);
    
    $("#tbl_discount_margin").val(discount_margin);
    
    $("#lbl-pack-size").val(currentPackSize);

        


        $("#modal-update").modal("show");







    }
    



 function readURL1Save(input) {
            if (input.files && input.files[0]) {
                var reader = new FileReader();

                reader.onload = function (e) {
                  $("#img_save_1").val(e.target.result);
                    $('#img_save_pre_1')
                        .attr('src', e.target.result)
                        .width(80)
                        .height(80);
                };

                reader.readAsDataURL(input.files[0]);
            }
        }


  function readURL2Save(input) {
            if (input.files && input.files[0]) {
                var reader = new FileReader();

                reader.onload = function (e) {
                  $("#img_save_2").val(e.target.result);
                    $('#img_save_pre_2')
                        .attr('src', e.target.result)
                        .width(80)
                        .height(80);
                };

                reader.readAsDataURL(input.files[0]);
            }
        }      

         function readURL3Save(input) {
            if (input.files && input.files[0]) {
                var reader = new FileReader();

                reader.onload = function (e) {
                  $("#img_save_3").val(e.target.result);
                    $('#img_save_pre_3')
                        .attr('src', e.target.result)
                        .width(80)
                        .height(80);
                };

                reader.readAsDataURL(input.files[0]);
            }
        } 


         function readURL4Save(input) {
            if (input.files && input.files[0]) {
                var reader = new FileReader();

                reader.onload = function (e) {
                  $("#img_save_4").val(e.target.result);
                    $('#img_save_pre_4')
                        .attr('src', e.target.result)
                        .width(80)
                        .height(80);
                };

                reader.readAsDataURL(input.files[0]);
            }
        } 


         function readURL5Save(input) {
            if (input.files && input.files[0]) {
                var reader = new FileReader();

                reader.onload = function (e) {
                  $("#img_save_5").val(e.target.result);
                    $('#img_save_pre_5')
                        .attr('src', e.target.result)
                        .width(80)
                        .height(80);
                };

                reader.readAsDataURL(input.files[0]);
            }
        }




    $(document).ready(function() {
        
    $("#form-upload-extra-images").submit(function(e){
        e.preventDefault();
        
     
        
         var formData = new FormData($(this)[0]);
            
            
            	$.ajax({
                        url: "scripts/upload_extra_images.php",
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
                                text:'Uploading...',
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
                                    text: 'Successfully Uploaded.',
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
        

    $("#form-add-product").submit(function(e){
        e.preventDefault();
        
        
        var pack_size=$("#txt-p-pack-size").val();



        if(pack_size == "" || pack_size < 0){
        	alert("Invalid pack size, please check again.");
        }else{
            
            var formData = new FormData($(this)[0]);
            
            
            	$.ajax({
                        url: "scripts/upload_product_data.php",
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
                                    text: 'Successfully Registered.',
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
            
            
            
            
            
        } 
       
       
       
       
        
        
        
    });


    //   $("#btn-submit").click(function(){





    //   	var pCode=$("#txt-p-code").val();
    //     var pName=$("#txt-p-name").val();
    //     var pCategory=$("#txt-p-category").val();
        
    //     // var pStock=$("#txt-p-stock").val();
    //     // var pPrice=$("#txt-p-price").val();
    //     // var bPrice=$("#txt-b-price").val();
        

    //     // var pDis=$("#txt-p-dis").val();
    //     var pDis = 0;
        

    //     var pSup=$("#txt-p-sup").val();
    //     var pBrand=$("#txt-p-brand").val();

    //     // var pImg=$("#img_save_1").val();
    //     // var pImg2=$("#img_save_2").val();
    //     // var pImg3=$("#img_save_3").val();
    //     // var pImg4=$("#img_save_4").val();
    //     // var pImg5=$("#img_save_5").val();

        


    //     var pack_size=$("#txt-p-pack-size").val();



    //     if(pack_size == "" || pack_size < 0){
    //     	alert("Invalid pack size, please check again.");
    //     }else{

    //     	$.ajax({
    //                     url: "scripts/upload_product_data.php",
    //                     type: 'POST',
    //                     data: {
    //                         p_code:pCode,
    //                         p_name:pName,
    //                         p_category:pCategory,
    //                         // p_stock:pStock,
    //                         // p_price:pPrice,
    //                         p_dis:pDis,
    //                         p_sup:pSup,
    //                         p_brand:pBrand,

    //                         // p_image:pImg,
    //                         // p_image_2:pImg2,
    //                         // p_image_3:pImg3,
    //                         // p_image_4:pImg4,
    //                         // p_image_5:pImg5,

    //                         pack_size:pack_size

    //                         // b_price:bPrice
    //                       },

    //                       beforeSend : function() {

    //                           Swal.fire({
    //                             title:'',
    //                             icon: 'info',
    //                             text:'Please wait...',
    //                             showConfirmButton:false,
    //                             showCancelButton:false,
    //                             allowOutsideClick: false,
    //                           });

    //                       },


    //                     success: function (data) {

                            
    //                         var json=JSON.parse(data);
    //                         if(json.result){

    //                             Swal.fire({
    //                                 title: 'Success',
    //                                 text: 'Successfully Registered.',
    //                                 icon: 'success',
    //                                 allowOutsideClick:false,
    //                                 showCancelButton: false,
    //                                 showConfirmButton:true,
    //                                 cancelButtonColor: '#d33',
    //                                 confirmButtonText: 'OK'
    //                               }).then((result) => {
    //                                 if (result.value) {

    //                                   location.reload();
                                  
    //                                 }
    //                               });

    //                         }else{

    //                             Swal.fire({
    //                                 title: 'Warning !',
    //                                 text: json.msg,
    //                                 icon: 'warning',
    //                                 allowOutsideClick:false,
    //                                 showCancelButton: true,
    //                                 showConfirmButton:false,
    //                                 cancelButtonColor: '#d33',
    //                                 cancelButtonText: 'OK'
    //                               });

    //                         }

                            





    //                     },
    //                     error: function (jqXHR, textStatus, errorThrown) {
    //                         console.log(errorThrown+"");
    //                     }

    //                 });





    //     }






         

















    //     // if( $('#rad_with_dist').is(':checked') && $('#txt-p-dis').val() == "0" ){
    //     //                       Swal.fire({
    //     //                         title:'Warning !',
    //     //                         icon: 'warning',
    //     //                         text:'Please select a distributor',
    //     //                         showConfirmButton:true,
    //     //                         showCancelButton:false,
    //     //                         allowOutsideClick: true,
    //     //                       });

    //     // }else{



    //     // }


        






    //   });



        $("#txt-p-dis").change(function (){


           $.ajax({
                        url: "scripts/download_supplier_data.php",
                        type: 'POST',
                        data: {
                            dis_id:$("#txt-p-dis").val()
                        },
                        success: function (data) {

                            
                            var json=JSON.parse(data);
                            if(json.result){


                                  $("#txt-p-sup").html(json.list);

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