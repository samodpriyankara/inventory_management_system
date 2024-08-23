<?php
    require_once('../database/db.php');
    $db=new DB();
    $conn=$db->connect();
    session_start();
    date_default_timezone_set('Asia/Colombo');
    $currentDate=date('Y-m-d H:i:s');


    $output=[];

    if(isset($_POST['dist_product_invo_id']))
    {
        $dist_product_id = mysqli_real_escape_string($conn,htmlspecialchars($_POST['dist_product_invo_id']));
        
        ///////////////////GET DIST DETAILS///////////////////
         
         $GDPDRs=$conn->query("SELECT * FROM tbl_distributor_product_invoice WHERE distributor_invoice_id='$dist_product_id'");
         if($GDPDrow =$GDPDRs->fetch_array())
         {
            $DistributorId=$GDPDrow[1];
            $items_count = 0;
            
            
            /////
            
            
            
            $get_all_products = $conn->query("SELECT itemId,stock FROM tbl_item WHERE stock > 0");
            while($irs = $get_all_products->fetch_array()){
                
                $item_id = $irs[0];
                $stock = $irs[1];
                $items_count += 1;
                $already_added_qty = 0;
                
                
                
                ///CHECK STOCK AVAILABILITY
                
                $get_added_qty = $conn->query("SELECT qty FROM  tbl_distributor_product_invoice_items WHERE distributor_invoice_id = '$dist_product_id' AND item_id = '$item_id'");
                if($ers = $get_added_qty->fetch_array()){
                    $already_added_qty = $ers[0];
                }
                
                $total_qty_to_add = $already_added_qty + $stock;
                ///////////////////////////
                
                ////
                if($stock >= $total_qty_to_add){
                    
                    $GIOPRs = $conn->query("SELECT * FROM tbl_item_other_prices WHERE item_id='$item_id'");
                 if($GIOProw =$GIOPRs->fetch_array())
                 {
                      $cost = (double)$GIOProw[2];
                 }
                
                
                
                
                $check_product = $conn->query("SELECT * FROM tbl_distributor_product_invoice_items WHERE distributor_invoice_id = '$dist_product_id' AND item_id = '$item_id'");
                if($cprs = $check_product->fetch_array()){
                    
                    ////UPDATE PRODUCT///
                    
                        if($conn->query("UPDATE tbl_distributor_product_invoice_items SET qty = qty + $stock WHERE distributor_invoice_id='$dist_product_id' AND item_id='$item_id'")){
                            $items_count -= 1;
                        }
                    
                    
                }else{
                    ////SAVE PRODUCT////
                    
                     
                     
                     if($conn->query("INSERT INTO tbl_distributor_product_invoice_items VALUES(null,'$dist_product_id','$item_id','$stock','$cost')")){
                         $items_count -= 1;
                     }
                     
                     
                     
                    
                    
                }
                    
                }else{
                    $items_count = -1;
                }
                 
                
                
                
            }
            
            
            
            
            
            
            
            
            
            
            
            if($items_count == 0){
                $output['result'] = true;
                $output['msg'] = "Stock has been shared successfully.";
            }else{
                $output['result'] = false;
                if($items_count == -1){
                    $items_count = 0;
                }
                $output['msg'] = "$items_count items affected.";
            }
            
            
            
            
            
            
            
            
                
         }else{
              $output['result'] = false;
              $output['msg'] = "Distributor verification failed, please try again.";
         }    
        
        
        
        
        
        
        
        
        
        
       

    }else{
        $output['result'] = false;
        $output['msg'] = "Something went wrong, please try again.";
    }

    mysqli_close($conn);
    echo json_encode($output);

    ?>