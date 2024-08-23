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


		/////get amount wise target////////

			$get_amount_wise_target = $conn->query("SELECT * FROM tbl_rep_target_amount_wise ORDER BY id DESC LIMIT 1");
			if($awrs = $get_amount_wise_target->fetch_array()){
				$awt['id'] = $awrs[0];
				$awt['amount'] = $awrs[1];
				$awt['valid_from'] = $awrs[2];
				$awt['valid_to'] = $awrs[3];

				array_push($array_amount_wise_target, $awt);
			}


		//////////////////////////////////




		/////get qty wise target////////

			$get_qty_wise_target = $conn->query("SELECT * FROM tbl_rep_target_qty_wise ORDER BY id ASC");
			if($qwrp = $get_qty_wise_target->fetch_array()){
				$qwt['id'] = $qwrp[0];
				$qwt['item_id'] = $qwrp[1];
				$qwt['qty'] = $qwrp[2];
				$qwt['valid_from'] = $qwrp[3];
				$qwt['valid_to'] = $qwrp[4];

				array_push($array_qty_wise_target, $qwt);
			}


		//////////////////////////////////




		$output['result']=true;
		$output['amount_wise_target'] = $array_amount_wise_target;
		$output['qty_wise_target'] = $array_qty_wise_target;

	
	}else{
		$output['result']=false;
		$output['msg']='Required data not provided.';

	}



	echo json_encode($output);






