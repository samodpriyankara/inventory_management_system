<?php
/////////////////////////////////
  session_start();
  require '../database/db.php';
  $db = new DB();
  $conn = $db->connect();
/////////////////////////////////

	
	$output=[];
	
	$orderTotal = 0;
	
	if( isset($_SESSION['ID']) && $_SESSION['ID'] != "" && $_SESSION['ID'] != null){
	    
	    $session_id = session_id();
	    $userId = $_SESSION['ID'];
	    
	    
	    

	    
	    
if(isset($_POST['list']) && isset($_POST['payment_method_id']) && isset($_POST['outlet']) && isset($_POST['route']) && isset($_POST['distributor_id']) && isset($_POST['is_return']) && isset($_POST['return_type']) && isset($_POST['return_status']) && isset($_POST['return_order_no']) && isset($_POST['return_note']) ){

    if( $_POST['outlet']=="" || $_POST['outlet'] == NULL || $_POST['outlet'] == "undefined" || $_POST['outlet'] == null){
        
            $output['result']=false;
			$output['msg'] = "Shop verification failed. (code CHEAT_SHOP_VERIFY)";
        
    }else{
        
        
            ////////////get user details/////
                if(isset($_SERVER['HTTP_USER_AGENT'])){
                     $date_time = date('Y-m-d H:i:s');
                     $user_agent = "INVOICE_SAVE : ".htmlspecialchars($_SERVER['HTTP_USER_AGENT']);
                     $conn->query("INSERT INTO tbl_user_agent_details VALUES(null,'$user_agent','$date_time','$userId')");
                 }
            /////////////////////////////////
        
        
        
        
        
        $list = htmlspecialchars($_POST['list']);
		$payment_method_id = htmlspecialchars($_POST['payment_method_id']);
		$outlet = htmlspecialchars($_POST['outlet']);
		$route = htmlspecialchars($_POST['route']);
		$distributor_id = htmlspecialchars($_POST['distributor_id']);
        $invoice_note = htmlspecialchars($_POST['invoice_note']);

		$is_return = htmlspecialchars($_POST['is_return']);
		$return_type = htmlspecialchars($_POST['return_type']);
		
		/////return order details///
		$return_status = htmlspecialchars($_POST['return_status']);
		$return_order_no = htmlspecialchars($_POST['return_order_no']);
		$return_note = htmlspecialchars($_POST['return_note']);
		
		if($return_note == ""){
		    $return_note = "N/A";
		}
		
		
		/////

		$decodedList=json_decode($_POST['list'],false);

		$date = date('Y-m-d');
		$time = date('H:i:s');
		$timeStamp = time();


// 		$getOutletId = $conn -> query("SELECT outlet_id FROM tbl_outlet WHERE outlet_name='$outlet'");
// 		if($outRs = $getOutletId->fetch_array()){
// 			$outlet = $outRs[0];
// 		}


		$getRouteId = $conn -> query("SELECT route_id FROM tbl_route WHERE route_name = '$route'");
		if($routeRs = $getRouteId->fetch_array()){
			$route = $routeRs[0];
		}

		if($is_return == 1){


			//genarate return invoice id



		

		if($return_type == 0){
			$returnInvoiceId = "INV/SR/WEB/".$userId."/";
		}else{
			$returnInvoiceId = "INV/DR/WEB/".$userId."/";
		}


		






		$getLastWebrInvoiceId = $conn -> query("SELECT order_id FROM tbl_return_order WHERE order_id LIKE '%/web/%' ORDER BY id DESC LIMIT 1");
		if($ridRs = $getLastWebrInvoiceId->fetch_array()){

			$split = explode("/", $ridRs[0]);
			$newInvNo = str_pad(($split[4]+1), 6, '0', STR_PAD_LEFT);

			$returnInvoiceId.= $newInvNo;

		}else{
			$returnInvoiceId.= "000001";
		}


		

		////////////////

			 if($conn->query("INSERT INTO tbl_return_order VALUES(null,'$returnInvoiceId','$return_type','$date','$time',-1,-1,-1,'$timeStamp','web','$session_id','$outlet','$route','$distributor_id',0)")){

			 			$returnOrderKey = mysqli_insert_id($conn);



			 			for($i = 0;$i < count($decodedList);$i++){

										$obj = $decodedList[$i];




		                                    $itemId=$obj->item_id;
		                                    $price=$obj->price;
		                                    $discountedPrice=0;
		                                    $longEx=0;
		                                    $rpId=0;
		                                    $qty=$obj->item_qty;
		                                    
		                                    $discountedValue=$obj->discount;

		                                    $orderTotal += ($price*$qty);

                                                                




                             $conn->query("INSERT INTO tbl_return_order_item_details VALUES(null,'$itemId','$returnOrderKey','$qty','$discountedPrice','$discountedValue','$price','$rpId')");


                           if($return_type == 0){

                            // $conn->query("UPDATE tbl_item SET stock=stock + '$qty' WHERE itemId='$itemId'");
                            $conn->query("UPDATE tbl_distributor_has_products SET qty=qty + '$qty' WHERE item_id='$itemId' AND distributor_id = '$distributor_id'");

                           }
					   
              	
						}







					$output['result']=true;
					$output['msg']="Return invoice saved successfully.";
					$output['type']="ro_";
					$output['return_id'] = base64_encode($returnOrderKey);

			 }else{
					$output['result']=false;
					$output['msg']="Return invoice saving failed.";
			 }

















			



		}else{


			//genarate invoice id

		$invoiceId = "INV/SO/WEB/".$userId."/";

		$getLastWebInvoiceId = $conn -> query("SELECT order_id FROM tbl_order WHERE order_id LIKE '%/web/%' ORDER BY id DESC LIMIT 1");
		if($idRs = $getLastWebInvoiceId->fetch_array()){

			$split = explode("/", $idRs[0]);
			$newInvNo = str_pad(($split[4]+1), 6, '0', STR_PAD_LEFT);

			$invoiceId.= $newInvNo;

		}else{
			$invoiceId.= "000001";
		}


		

		////////////////


		 								if($payment_method_id == 0){//cash
                                        	$paymentStatus = 1;
                                        }else if($payment_method_id == 1){
                                        	$paymentStatus = 1;
                                        }else if($payment_method_id == 2){//credit
                                        	$paymentStatus = 0;
                                        }else if($payment_method_id == 3){
                                        	$paymentStatus = 1;
                                        }



		if($conn->query("INSERT INTO tbl_order VALUES(null,'$invoiceId','0','$date','$time',-1,-1,-1,'$timeStamp','$paymentStatus',0,0,'$payment_method_id','web','$session_id','$outlet','$route','$distributor_id',0)")){

            $orderKey = mysqli_insert_id($conn);
            $conn->query("INSERT INTO tbl_invoice_note VALUES(null,'$invoiceId','$timeStamp','$invoice_note')");



			for($i = 0;$i < count($decodedList);$i++){

				$obj = $decodedList[$i];




                                    $itemId=$obj->item_id;
                                    $price=$obj->price;
                                    $longEx=0;
                                    $rpId=0;
                                    $qty=$obj->item_qty;

                                    $discountedPrice=0;
                                    $discountedValue=$obj->discount;

                                    $orderTotal += ($price *((100 - $discountedValue)/100) *$qty);

                                                                
               if($conn->query("INSERT INTO tbl_order_item_details VALUES(null,'$itemId','$orderKey','$qty','$discountedPrice','$discountedValue','$price','$rpId')")){

               

               	$conn->query("UPDATE tbl_distributor_has_products SET qty=qty - '$qty' WHERE item_id='$itemId' AND distributor_id = '$distributor_id'");

               		// $conn->query("UPDATE tbl_item SET stock=stock-'$qty' WHERE itemId='$itemId'");
               		
               		
               		
               		
               		
               		/////////////////////check for free issues///////////////////////////////////////
               		
               	
               		
               		 
               		    $get_item_details = $conn->query("SELECT itemDescription,supplier_id,genaricName FROM tbl_item WHERE itemId = '$itemId'");
               		    
               		    if($id_rs = $get_item_details->fetch_array()){
               		        
               		         $item_name = $id_rs[0];
               		         $supplier_id = $id_rs[1];
               		         $genaric_name = $id_rs[2];
               		        
               		    }
               		    
               		  
               		$get_free_scheme = $conn->query("SELECT * FROM tbl_free_issue_scheme WHERE item_id = '$genaric_name' AND status = 1");
               		if($fc_rs = $get_free_scheme -> fetch_array()){
               		    
               		    $margin = $fc_rs[1];
               		    $free_qty = $fc_rs[2];
               		    
               		    $margin1 = $fc_rs[5];
               		    $free_qty1 = $fc_rs[10];
               		    
               		    $margin2 = $fc_rs[6];
               		    $free_qty2 = $fc_rs[11];
               		    
               		    $margin3 = $fc_rs[7];
               		    $free_qty3 = $fc_rs[12];
               		    
               		    $margin4 = $fc_rs[8];
               		    $free_qty4 = $fc_rs[13];
               		    
               		    $margin5 = $fc_rs[9];
               		    $free_qty5 = $fc_rs[14  ];
               		    
               		    
               		    

               		    ////CHECK IF MARGIN IS PROVIDED

               		    if($margin > 0){ 


               		    	if($qty >= $margin){
               		    	    if($margin1 > 0){
               		    	         if($qty >= $margin1){
               		    	             if($margin2 > 0){
               		    	                 if($qty >= $margin2){
               		    	                     if($margin3 > 0){
               		    	                         if($qty >= $margin3){
               		    	                             if($margin4 > 0){
               		    	                                if($qty >= $margin5){
               		    	                                    if($margin5 > 0){
               		    	                                         if($qty >= $margin5){
               		    	                                               $free =  (int)($qty / $margin5) * $free_qty5;
               		    	                                         }else{
               		    	                                             $free =  $free_qty4;
               		    	                                         }
               		    	                                    }else{
               		    	                                        $free =  (int)($qty / $margin4) * $free_qty4; 
               		    	                                    }
               		    	                                }else{
               		    	                                    $free =  $free_qty4; 
               		    	                                }    
               		    	                             }else{
               		    	                                   $free =  (int)($qty / $margin3) * $free_qty3;
               		    	                             }       
               		    	                             
               		    	                         }else{
               		    	                             $free = $free_qty2; 
               		    	                         }   
               		    	                     }else{
               		    	                           $free =  (int)($qty / $margin2) * $free_qty2;
               		    	                     }       
               		    	                 }else{
               		    	                     $free = $free_qty1; 
               		    	                 }            
               		    	             }else{
               		    	                $free =  (int)($qty / $margin1) * $free_qty1;  
               		    	             }                           
               		    	         }else{
               		    	            $free = $free_qty;  
               		    	         }
               		    	    }else{
               		    	         $free =  (int)($qty / $margin) * $free_qty;
               		    	    }
               		    	                        
               		    	   
               		        
               		        if($free > 0){
               		            
               		            
               		           $get_avail_qty = $conn->query("SELECT qty FROM tbl_distributor_has_products WHERE item_id = '$itemId'");
               		           if($aval_qty = $get_avail_qty->fetch_array()){
               		               $available_qty = $aval_qty[0];
               		               
               		               if($available_qty >= $free){
               		                   if($conn->query("INSERT INTO tbl_order_free_issues VALUES(null,'$itemId','$item_name','$price','$free','$orderKey','$supplier_id','$distributor_id')")){
               		                       
               		                       $conn->query("UPDATE tbl_distributor_has_products SET qty=qty - '$free' WHERE item_id='$itemId' AND distributor_id = '$distributor_id'");
               		                       
               		                   }
               		               }
               		               
               		           }
               		           
               		           
               		           
               		            
               		            
               		            
               		        }
               		         

                            // int free = ((int)(totalProductQty/od.getFreeIssueMargin()))*od.getFreeIssueQty();
                    
               		     }

               		    }

               		    
               		     
               		    
               		    
               		    
               		    
               		}
               		

                

               	
               		
               		
               		
               		
               		////////////////////////////////////////////////////////////////////////////////
               		
               		
               		

               }
			   
              	
			}


			if($paymentStatus == 0){

                $conn->query("INSERT INTO tbl_credit_orders VALUES(null,'$orderKey','$orderTotal','$orderTotal','$outlet','$route','$userId')");

           }


           $conn->query("INSERT INTO tbl_order_delivery VALUES(null,'$orderKey',0,'1999-01-01 12:00:00')");



            ////if order has returns save///
            
                if($return_status == 1){
                    
                    $current_date_time = date('Y-m-d H:i:s');
                    $conn->query("INSERT INTO tbl_invoices_for_returns VALUES(null,'$orderKey','$return_order_no','$return_note','$current_date_time')");
                    
                }
            
            
            ///////////////////////////////




			$output['result']=true;
			$output['order_id'] = base64_encode($orderKey);
			$output['type'] = "so_";
			
    //         } else {
				// 		$output['result'] = false;
				// 		$output['msg'] = "Invoice note saving failed, please try again.";
				// 	}

		}else{
			$output['result']=false;
			$output['msg'] = "Invoice saving failed, please try again.";
		}






		}
        
    }



	}else{

		$output['result']=false;
		$output['msg']="Required fields are not provided.";
	}
	    
	    
	    
	    
	}else{
	    $output['result']=false;
		$output['msg']="User verification failed. (code ERR_USR_VERIFY)";
	}
	
	
	
	




	




	echo json_encode($output);