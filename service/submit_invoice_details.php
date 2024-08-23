<?php
date_default_timezone_set('Asia/Colombo');
//--------------------------------
require 'db/db_connection.php';
$db=new DB();
$conn=$db->connect();
//--------------------------------

 			$output=[];
            $orderPrimaryKey=0;
            $paymentStatus = 0;//order_source_id
            $orderTotal = 0;
			


			if(isset($_POST['json']) && isset($_POST['user_id']) && isset($_POST['app_version']) && isset($_POST['session_id']) && isset($_POST['system_date'])
				&& isset($_POST['order_date']) && isset($_POST['order_time']) && isset($_POST['app_type'])){


 


										$decoded=json_decode($_POST['json'],false);
					
                                        $orderId=$decoded->invoice->orderId;
										$outletId=$decoded->invoice->outlet_id;
                                        $routeId=$decoded->invoice->route_id;
										$longitude=$decoded->invoice->lng;
                                        $latitude=$decoded->invoice->lat;
                                        $batteryLevel=$decoded->invoice->battery;
                                        $timestamp=$decoded->invoice->time_stamp;
                                        $distributorId=$decoded->invoice->distributor_id;
                                        $paymentMethod=$decoded->invoice->payment_method;


                                        $deliver_status=$decoded->invoice->deliver_status;
                                        $deliver_date=$decoded->invoice->deliver_date;
 
 										$discount_percentage=$decoded->invoice->discount_percentage;
                                        $discount_amount=$decoded->invoice->discount_amount;


                                        ///payment methods////
										//0 - cash
                                        //1 - cheque
                                        //2 - credit(loan)
                                        //3 - visa/master
                                        ////////////////////////

                                        if($paymentMethod == 0){
                                        	$paymentStatus = 1;
                                        }else if($paymentMethod == 1){
                                        	$paymentStatus = 1;
                                        }else if($paymentMethod == 2){
                                        	$paymentStatus = 0;
                                        }else if($paymentMethod == 3){
                                        	$paymentStatus = 1;
                                        }


                                        $userId=htmlspecialchars($_POST['user_id']);
                                        $appVersion=htmlspecialchars($_POST['app_version']);
                                        $sessionId=htmlspecialchars($_POST['session_id']);

                                        $orderDate=htmlspecialchars($_POST['order_date']);
                                        $orderTime=htmlspecialchars($_POST['order_time']);


                                        $appType=htmlspecialchars($_POST['app_type']);

                                        try {



                                        	if($conn->query("INSERT INTO tbl_order VALUES(null,'$orderId',0,'$orderDate','$orderTime','$longitude','$latitude','$batteryLevel','$timestamp','$paymentStatus','$discount_amount','$discount_percentage','$paymentMethod','$appVersion','$sessionId','$outletId','$routeId','$distributorId','$userId')")){



                                        				///////////////////

                                        		$orderKey = mysqli_insert_id($conn);



                                                
                                                        /*save order items*/


                                                       $invItems=$decoded->invoice->order_items;
                                                       $freeItems=$decoded->invoice->free_items;
                                                               
                                                               
                                                      


                         for ($i = 0; $i < count($invItems); $i++) {
                                                                            
                                    $itemId=$invItems[$i]->itemId;
                                    $price=$invItems[$i]->price;
                                    $discountedPrice=0;
                                    $longEx=0;
                                    $rpId=0;
                                    $qty=$invItems[$i]->qty;

                                    
                                    $units_per_pack=$invItems[$i]->unit_per_pack;
                                    



                                    $discountedValue=0;

                                    $orderTotal += ($price*$qty);

                                                                
               $conn->query("INSERT INTO tbl_order_item_details VALUES(null,'$itemId','$orderKey','$qty','$discountedPrice','$discountedValue','$price','$rpId')");

               $last_order_detail_id = mysqli_insert_id($conn);
			   





               if($appType == "normal"){
                    // $conn->query("UPDATE tbl_item SET stock=stock-'$qty' WHERE itemId='$itemId'");


                    $conn->query("UPDATE tbl_distributor_has_products SET qty=qty-'$qty' WHERE item_id='$itemId' AND distributor_id='$distributorId'");




                   ////save pack record/////

                    $conn->query("INSERT INTO tbl_order_detail_packs VALUES(null,'$units_per_pack','$last_order_detail_id')");

                    ////////////////////////



               }

               
                        

              



                                                                           
                             }




                             // INSERT INTO `tbl_order_free_issues`(`id`, `item_id`, `item_name`, `item_price`, `free_qty`, `order_id`)


                              for ($i = 0; $i < count($freeItems); $i++) {
                              		  $itemId=$freeItems[$i]->product_id;
                              		  $free_qty=$freeItems[$i]->free_qty;
                              		  $item_name=$freeItems[$i]->item_name;
                              		  $item_price=$freeItems[$i]->item_price;
                              		  

                              		  $supplier_id=$freeItems[$i]->supplier_id;
                              		  $distributor_id=$freeItems[$i]->distributor_id;

                              		  if($conn->query("INSERT INTO tbl_order_free_issues VALUES(null,'$itemId','$item_name','$item_price','$free_qty','$orderKey','$supplier_id','$distributor_id')")){


                              		  	// $conn->query("UPDATE tbl_item SET stock = stock-$free_qty WHERE itemId = '$itemId' AND distributor_id='$distributor_id' AND supplier_id = '$supplier_id'");


                                      

                                      $conn->query("UPDATE tbl_distributor_has_products SET qty = qty-$free_qty WHERE item_id = '$itemId' AND distributor_id='$distributor_id'");


                              		  }



                              }















                             	if($paymentStatus == 0){

                             		$conn->query("INSERT INTO tbl_credit_orders VALUES(null,'$orderKey','$orderTotal','$orderTotal','$outletId','$routeId','$userId')");

                             	}



                                ///set data on delivery

                                // $conn->query("INSERT INTO tbl_order_delivery VALUES(null,'$orderKey',0,'1999-01-01 12:00:00')");

                                    if($appType == "normal"){
                                        $conn->query("INSERT INTO tbl_order_delivery VALUES(null,'$orderKey',0,'1999-01-01 12:00:00')");
                                    }else{
                                        $conn->query("INSERT INTO tbl_order_delivery VALUES(null,'$orderKey','$deliver_status','$deliver_date')");
                                    }

                                //////////////////////












                                                          
                                                $output["result"]=true;
                                                $output["msg"]="Order placed successfully";









                                        		////////////////////////













                                        	}else{

                                        		$output["result"]=false;
                                                $output["msg"]="Failed to place order.";

                                        	}



                                             
                                           




                                        } catch (Exception $e) {
                                                $output["result"]=false;
                                                $output["msg"]="Something went wrong.Please try again.";
                                        }

                                }else{

                                        $output["result"]=false;
                                        $output["msg"]="No required data provided.";

                                }
			


echo json_encode($output);