<?php
date_default_timezone_set('Asia/Colombo');
//--------------------------------
require 'db/db_connection.php';
$db=new DB();
$conn=$db->connect();
//--------------------------------

 			$output=[];
            $orderPrimaryKey=0;
            $paymentStatus = 0;
			
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
                                        $paymentMethod=$decoded->invoice->payment_method;


                                        ///payment methods////order_source_id
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

                                        try {



                                        	if($conn->query("INSERT INTO tbl_order VALUES(null,'$orderId',0,'$orderDate','$orderTime','$longitude','$latitude','$batteryLevel','$timestamp','$paymentStatus',0,0,'$paymentMethod','$appVersion','$sessionId','$outletId','$routeId','$distributorId','$userId')")){



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

                                                                
               $conn->query("INSERT INTO tbl_order_item_details VALUES('null','$itemId','$orderKey','$qty','$discountedPrice','$discountedValue','$price','$rpId')");


                // --------------------------------------reduce qty from stock--------------------------


                        $conn->query("UPDATE tbl_item SET stock=stock-'$qty' WHERE itemId='$itemId'");
                        

                // ------------------------------------------------------------------------------------- 



                                                                           
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