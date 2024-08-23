<?php
    require_once('../database/db.php');
    $db=new DB();
    $conn=$db->connect();
    session_start();
    date_default_timezone_set('Asia/Colombo');

    $vanLoadingArray = array();
    $freeIssueArray = array();
    $returnArray = array();
    $creditArray = array();
    $otherOrderArray = array();


    $output=[];

    if(isset($_POST['search_date_start']) && isset($_POST['search_date_end']) && isset($_POST['rep_id']))
    {
        $searchDateStart = htmlspecialchars($_POST['search_date_start']);
        $searchDateEnd = htmlspecialchars($_POST['search_date_end']);
        $repId = htmlspecialchars($_POST['rep_id']);


        // $getBasicLoading = $conn->query("SELECT ti.itemId,ti.itemDescription,tvlh.qty FROM tbl_item ti INNER JOIN tbl_van_loading_history tvlh ON ti.itemId = tvlh.item_id WHERE user_id = '$repId' AND DATE(tvlh.date_time) BETWEEN '$searchDateStart' AND '$searchDateEnd' ");



        //////////////van loading summary data///////////////////////

         $getBasicLoading = $conn->query("SELECT ti.itemId,ti.itemDescription,SUM(tvlh.qty) FROM tbl_item ti INNER JOIN tbl_van_loading_history tvlh ON ti.itemId = tvlh.item_id WHERE user_id = '$repId' AND DATE(tvlh.date_time) BETWEEN '$searchDateStart' AND '$searchDateEnd' GROUP BY tvlh.item_id
        ");
        $index = 1;


        while ($bRs = $getBasicLoading->fetch_array()) {
            
            $itemId = $bRs[0];
            $itemName = $bRs[1];
            $qty = $bRs[2];
            $soldQty = 0;




            $getOrders = $conn->query("SELECT id FROM tbl_order WHERE user_id='$repId' AND invoice_date BETWEEN '$searchDateStart' AND '$searchDateEnd'");
            while($goRs = $getOrders->fetch_array()){
                $orderId = $goRs[0];
               


                $getSoldQty = $conn->query("SELECT SUM(qty) FROM tbl_order_item_details WHERE order_id = '$orderId' AND itemId = '$itemId'");
                if($gsoRs = $getSoldQty->fetch_array()){
                    $soldQty += $gsoRs[0];
                }else{
                    $soldQty = 0;
                }

                



            }


            $availableQty = $qty-$soldQty;




            $row = "<tr><td>".$index++."</td><td>".$itemName."</td><td>".$qty."</td><td>".$availableQty."</td><td>".$soldQty."</td></tr>";
                array_push($vanLoadingArray, $row);




                
            


        }


        /////////////////////////////////////////////////////////////






        //////////////////////////////free issue summary/////////////////

        $searchQuery = "0";

         $getOrders = $conn->query("SELECT id FROM tbl_order WHERE user_id='$repId' AND invoice_date BETWEEN '$searchDateStart' AND '$searchDateEnd'");
         

         $freeId = 1;   
            while($goRs = $getOrders->fetch_array()){
                $orderId = $goRs[0];
                $searchQuery .= $orderId." OR 0";
            


               $getFree = $conn->query("SELECT tofi.item_name,SUM(tofi.free_qty) FROM tbl_order_free_issues tofi WHERE tofi.order_id = '$orderId' GROUP BY tofi.item_id");

               // $output['qq'] = $searchQuery." "."SELECT tofi.item_name,SUM(tofi.free_qty) FROM tbl_order_free_issues tofi WHERE tofi.order_id = $searchQuery GROUP BY tofi.item_id";

               $rows_count = mysqli_num_rows($getFree);

                if($rows_count > 0){
                while ($gfRs = $getFree->fetch_array()) {
                    


                        $row = "<tr><td>".$freeId++."</td><td>". $gfRs[0]."</td><td>".$gfRs[1]."</td></tr>";
                        array_push($freeIssueArray, $row);


                    }
                }
            }

          ////////////////////////////////////////////////////////////////



         //////////////////////////////return summary/////////////////


               $getReturns = $conn->query("SELECT tro.id,tro.order_id,tro.return_type,tro.app_version,tr.route_name,tro.invoice_date FROM tbl_return_order tro INNER JOIN tbl_route tr ON tro.route_id = tr.route_id WHERE user_id = '$repId' AND invoice_date BETWEEN '$searchDateStart' AND '$searchDateEnd'");
               $rindex = 1;
               while($rRs = $getReturns->fetch_array()){

                $orderTotal = 0;
                $orderId = $rRs[0];
                $returnType = $rRs[2];

                if($returnType == 0){
                    $returnType = "Sales Return";
                }else{
                    $returnType = "Damage Return";
                }






                $getOrderDetails = $conn->query("SELECT troid.qty,troid.price FROM tbl_return_order_item_details troid WHERE troid.order_id = '$orderId'");
                while($odRs = $getOrderDetails->fetch_array()){
                    $qty = $odRs[0];
                    $price = $odRs[1];

                    $orderTotal += ($qty*$price);


                }//http://124.43.5.226/smartsalesman/return_invoice?i=MQ==






                 $row = "<tr><td>".$rindex++."</td><td>".$rRs[1]."</td><td>".$returnType."</td><td>".$rRs[3]."</td><td>".$rRs[4]."</td><td>".$rRs[5]."</td><td class='text-right'><b>".number_format($orderTotal,2)."</b></td><td><a href=return_invoice?i=".base64_encode($orderId)." class='btn btn-secondary btn-sm' target='_blank'>VIEW</a></td></tr>";
                 array_push($returnArray, $row);

               }








         /////////////////////////////////////////////////////////////


               //////credit order total//////////
                $completeOrderTotalCredit = 0;
               $getCreditOrders = $conn->query("SELECT tro.id,tro.optional_discount_amount FROM tbl_order tro INNER JOIN tbl_route tr ON tro.route_id = tr.route_id WHERE tro.payment_method = 2 AND user_id = '$repId' AND invoice_date BETWEEN '$searchDateStart' AND '$searchDateEnd'");



             while($rRs = $getCreditOrders->fetch_array()){
             	 $orderId = $rRs[0];
                $discount = $rRs[1];


                $getOrderDetails = $conn->query("SELECT troid.qty,troid.price FROM tbl_order_item_details troid WHERE troid.order_id = '$orderId'");

                while($odRs = $getOrderDetails->fetch_array()){
                    $qty = $odRs[0];
                    $price = $odRs[1];



                    $completeOrderTotalCredit += ($qty*$price);
				}

				$completeOrderTotalCredit = $completeOrderTotalCredit-$discount;



             }




               //////////////////////////////////




               //////////////////////////////credit order summary/////////////////


               $getCreditOrders = $conn->query("SELECT tro.id,tro.order_id,tro.payment_method,tro.app_version,tr.route_name,tro.invoice_date,tro.optional_discount_amount FROM tbl_order tro INNER JOIN tbl_route tr ON tro.route_id = tr.route_id WHERE tro.payment_method = 2 AND user_id = '$repId' AND invoice_date BETWEEN '$searchDateStart' AND '$searchDateEnd'");
               $cindex = 1;
               while($rRs = $getCreditOrders->fetch_array()){

                $orderTotal = 0;
                $orderId = $rRs[0];
                $discount = $rRs[6];
              




                $getOrderDetails = $conn->query("SELECT troid.qty,troid.price FROM tbl_order_item_details troid WHERE troid.order_id = '$orderId'");
                while($odRs = $getOrderDetails->fetch_array()){
                    $qty = $odRs[0];
                    $price = $odRs[1];

                    $orderTotal += ($qty*$price);


                }






                 $row = "<tr><td>".$cindex++."</td><td>".$rRs[1]."</td><td>Credit</td><td>".$rRs[3]."</td><td>".$rRs[4]."</td><td>".$rRs[5]."</td><td class='text-right'><b>".number_format(($orderTotal-$discount),2)."</b></td><td><a href=invoice?i=".base64_encode($orderId)." class='btn btn-secondary btn-sm' target='_blank'>VIEW</a></td></tr>";
                 array_push($creditArray, $row);

               }








         /////////////////////////////////////////////////////////////







                 //////////////////////////////cash order summary/////////////////


               //////////////get complete total///////

               	$completeOrderTotal = 0;
               	$getOtherOrders = $conn->query("SELECT tro.id,tro.optional_discount_amount FROM tbl_order tro INNER JOIN tbl_route tr ON tro.route_id = tr.route_id WHERE tro.payment_method != 2 AND user_id = '$repId' AND invoice_date BETWEEN '$searchDateStart' AND '$searchDateEnd'");
               	while($rRs = $getOtherOrders->fetch_array()){
               			$orderId = $rRs[0];
               			$discount_amount = $rRs[1];


				$getOrderDetails = $conn->query("SELECT troid.qty,troid.price FROM tbl_order_item_details troid WHERE troid.order_id = '$orderId'");
                while($odRs = $getOrderDetails->fetch_array()){
                    $qty = $odRs[0];
                    $price = $odRs[1];

                    $completeOrderTotal += ($qty*$price);
				}

				$completeOrderTotal = $completeOrderTotal-$discount_amount;


               	}	



               /////////////////////////////////////











               $getOtherOrders = $conn->query("SELECT tro.id,tro.order_id,tro.payment_method,tro.app_version,tr.route_name,tro.invoice_date,tro.optional_discount_amount FROM tbl_order tro INNER JOIN tbl_route tr ON tro.route_id = tr.route_id WHERE tro.payment_method != 2 AND user_id = '$repId' AND invoice_date BETWEEN '$searchDateStart' AND '$searchDateEnd'");
               $oindex = 1;
               


               while($rRs = $getOtherOrders->fetch_array()){

                $orderTotal = 0;
                $orderId = $rRs[0];
                $discount = $rRs[6];
                $paymentType = $rRs[2];
              

              if($paymentType == 0){
                $paymentType = "Cash";
              }else if($paymentType == 1){
                $paymentType = "Cheque";
              }




                $getOrderDetails = $conn->query("SELECT troid.qty,troid.price FROM tbl_order_item_details troid WHERE troid.order_id = '$orderId'");
                while($odRs = $getOrderDetails->fetch_array()){
                    $qty = $odRs[0];
                    $price = $odRs[1];

                    $orderTotal += ($qty*$price);



                }






                 $row = "<tr><td>".$oindex++."</td><td>".$rRs[1]."</td><td>".$paymentType."</td><td>".$rRs[3]."</td><td>".$rRs[4]."</td><td>".$rRs[5]."</td><td class='text-right'><b>".number_format(($orderTotal-$discount),2)."</b></td><td><a href=invoice?i=".base64_encode($orderId)." class='btn btn-secondary btn-sm' target='_blank'>VIEW</a></td></tr>";
                 array_push($otherOrderArray, $row);

               }








         /////////////////////////////////////////////////////////////










































         $output['result'] = true;
         $output['van_loading_data'] = $vanLoadingArray;
         $output['free_issue_data'] = $freeIssueArray;
         $output['return_data'] = $returnArray;
         $output['credit_data'] = $creditArray;
         $output['other_order_data'] = $otherOrderArray;

         $output['cash_income_total'] = number_format($completeOrderTotal,2);
         $output['credit_income_total'] = number_format($completeOrderTotalCredit,2);
         
        
       

    }else{
        $output['result'] = false;
        $output['msg'] = "Something went wrong, please try again.";
    }   

    mysqli_close($conn);
    echo json_encode($output);
