<?php
date_default_timezone_set('Asia/Colombo');
//--------------------------------
require 'db/db_connection.php';
$db=new DB();
$conn=$db->connect();
//--------------------------------

	$output=[];
	$array_amount_wise_target = array();
	$array_qty_wise_target = array();
	

	if(isset($_POST['user_id'])){

		$user_id=htmlspecialchars($_POST['user_id']);
		$total_amount = 0;
		$output['result']=true;

		$get_amount_wise_criteria = $conn->query("SELECT * FROM tbl_rep_target_amount_wise ORDER BY id DESC LIMIT 1");
		if($awcrs = $get_amount_wise_criteria->fetch_array()){
			$target_amount = $awcrs[1];
			$valid_from = $awcrs[2];
			$valid_to = $awcrs[3];





			/////search data for target criteria///

			$get_base_orders = $conn->query("SELECT id FROM tbl_order WHERE user_id = '$user_id' AND order_type = 0 AND invoice_date BETWEEN '$valid_from' AND '$valid_to'");


			while($bors = $get_base_orders->fetch_array()){

				$order_id = $bors[0];
				$order_total = 0;


				$get_order_details = $conn->query("SELECT qty,price FROM tbl_order_item_details WHERE order_id = '$order_id'");
				while($odrs = $get_order_details->fetch_array()){
					$qty = $odrs[0];
					$price = $odrs[1];

					$order_total += ($qty*$price);
				}


				$total_amount += $order_total;
			

			}

			$output['target_amount'] = $target_amount;
			$output['achieved_amount'] = $total_amount;


			

		}else{

			$output['target_amount'] = 0;
			$output['achieved_amount'] = 0;

		}







		////////////////download qty wise target data/////////////////


		$get_qty_wise_criteria = $conn->query("SELECT * FROM tbl_rep_target_qty_wise WHERE status = 1 ORDER BY id ASC");
		while ($qwcrs = $get_qty_wise_criteria->fetch_array()) {

			$item_id = $qwcrs[1];
			$qty = $qwcrs[2];
			$valid_from = $qwcrs[3];
			$valid_to = $qwcrs[4];

			$total_item_qty = 0;
			$item_name = "N/A";
			$item_code = "N/A";

			//////////get item name/////
			$get_item_name = $conn->query("SELECT itemDescription,itemCode FROM tbl_item WHERE itemId = '$item_id'");
			if($inrs = $get_item_name->fetch_array()){
				$item_name = $inrs[0];
				$item_code = $inrs[1];
			}
			///////////////////////////



			$get_base_orders = $conn->query("SELECT id FROM tbl_order WHERE user_id = '$user_id' AND order_type = 0 AND invoice_date BETWEEN '$valid_from' AND '$valid_to'");


			while($bors = $get_base_orders->fetch_array()){
				$order_id = $bors[0];

				$get_order_details = $conn->query("SELECT SUM(qty) FROM tbl_order_item_details WHERE order_id = '$order_id' AND itemId = '$item_id'");
				if($odrs = $get_order_details->fetch_array()){
					

					$total_item_qty += $odrs[0];
					

					
				}




			}





			$item_detail['item_id'] = $item_id;
			$item_detail['item_code'] = $item_code;
			$item_detail['item_name'] = $item_name;
			$item_detail['item_qty'] = $total_item_qty;
			$item_detail['target_qty'] = $qty;


			array_push($array_qty_wise_target, $item_detail);



		}
		
		

		$output['qty_wise_target_data'] = $array_qty_wise_target;




		
		
	
	}else{
		$output['result']=false;
		$output['msg']='Required data not provided.';

	}



	echo json_encode($output);






