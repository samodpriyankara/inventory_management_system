<?php
date_default_timezone_set('Asia/Colombo');
//--------------------------------
require '../database/db.php';
$db=new DB();
$conn=$db->connect();

session_start();
//--------------------------------

$output=[];


if(isset($_POST['user_id']) && isset($_POST['order_id']) && isset($_POST['deliver_date'])){
	
	$user_id = htmlspecialchars($_POST['user_id']);
	$order_id = htmlspecialchars($_POST['order_id']);
	$deliver_date = htmlspecialchars($_POST['deliver_date']);


	$getOrderId = $conn->query("SELECT id FROM tbl_order WHERE order_id = '$order_id' AND user_id = '$user_id'");
	if($oidRs = $getOrderId->fetch_array()){

		$id = $oidRs[0];
		if($conn->query("UPDATE tbl_order_delivery SET delivery_status = 2,delivered_datetime = '$deliver_date' WHERE order_id = '$id'")){

			$output["result"]=true;
			$output["msg"] = "Successfully updated.";

		}else{
			$output["result"]=false;
			$output["msg"] = "Updating failed, please try again.";
		}



	}else{
		$output["result"]=false;
		$output["msg"] = "Order has not been saved. please save the order first and try again";
	}



	



	



	

}else{

	$output["result"]=false;
	$output["msg"]="Something went wrong.Please try again.";
	// $output["login_time_stamp"]=$login_time_stamp;

}
echo json_encode($output);