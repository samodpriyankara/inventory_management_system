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
				&& isset($_POST['order_date']) && isset($_POST['order_time'])){


 


										$decoded=json_decode($_POST['json'],false);
					
                                        $orderId=$decoded->invoice->orderId;
										$outletId=$decoded->invoice->outlet_id;
                                        $routeId=$decoded->invoice->route_id;
										$longitude=$decoded->invoice->lng;
                                        $latitude=$decoded->invoice->lat;
                                        $batteryLevel=$decoded->invoice->battery;
                                        $timestamp=$decoded->invoice->time_stamp;
                                        $distributorId=$decoded->invoice->distributor_id;
                                       
                                        

                                        $order_type=$decoded->invoice->order_type;//sales order or return order
                                        $order_return_type=$decoded->invoice->order_return_type;//sales return or damage return


                                       


                                        $userId=htmlspecialchars($_POST['user_id']);
                                        $appVersion=htmlspecialchars($_POST['app_version']);
                                        $sessionId=htmlspecialchars($_POST['session_id']);

                                        $orderDate=htmlspecialchars($_POST['order_date']);
                                        $orderTime=htmlspecialchars($_POST['order_time']);

                                        try {




   if($conn->query("INSERT INTO tbl_return_order VALUES(null,'$orderId','$order_return_type','$orderDate','$orderTime','$latitude','$longitude','$batteryLevel','$timestamp','$appVersion','$sessionId','$outletId','$routeId','$distributorId','$userId')")){



                                        				///////////////////

                                        		$orderKey = mysqli_insert_id($conn);



                                                
                                                        /*save order items*/


                                                       $invItems=$decoded->invoice->order_items;
                                                               
                                                               
                                                      


                         for ($i = 0; $i < count($invItems); $i++) {
                                                                            
                                    $itemId=$invItems[$i]->itemId;
                                    $price=$invItems[$i]->price;
                                    $discountedPrice=0;
                                    $longEx=0;
                                    $rpId=0;
                                    $qty=$invItems[$i]->qty;
                                    $discountedValue=0;

                                    $orderTotal += ($price*$qty);

                                                                




                             $conn->query("INSERT INTO tbl_return_order_item_details VALUES(null,'$itemId','$orderKey','$qty','$discountedPrice','$discountedValue','$price','$rpId')");


                           if($order_return_type == 0){
                            

                            // $conn->query("UPDATE tbl_item SET stock=stock + '$qty' WHERE itemId='$itemId'");

                            $conn->query("UPDATE tbl_distributor_has_products SET qty=qty + '$qty' WHERE item_id='$itemId' AND distributor_id = '$distributorId'");
                           


                           }



			   
                        

              



                                                                           
                             }













                                                          
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