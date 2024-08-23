<?php
date_default_timezone_set('Asia/Colombo');
//--------------------------------
require '../database/db.php';
$db=new DB();
$conn=$db->connect();

session_start();
//--------------------------------

		$output=[];
		$date=date('Y-m-d');
		$sessionId=session_id();
		$ordersList = array();

if(isset($_POST['outlet_id']) && isset($_POST['user_id'])){

	$outlet_id=htmlspecialchars($_POST['outlet_id']);
	$user_id=htmlspecialchars($_POST['user_id']);
	
	
	$getOrders = $conn->query("SELECT * FROM tbl_order tblorder INNER JOIN tbl_outlet tblout ON tblorder.outlet_id=tblout.outlet_id INNER JOIN tbl_order_delivery tod ON tblorder.id = tod.order_id WHERE (tod.delivery_status = 0 OR tod.delivery_status = 1) AND tblout.outlet_id = '$outlet_id'");
	
	while($ordersRs = $getOrders->fetch_array()){

		$order['id'] = $ordersRs[0];
		$order['order_id'] = $ordersRs[1];
		$order['date'] = $ordersRs[3];
		$order['time'] = $ordersRs[4];

		$delivery_status = $ordersRs[40];
		
		if($delivery_status == 0){
				$order['delivery_status'] = "Still in stores.";
		}else if($delivery_status == 1){
				$order['delivery_status'] = "Dispatched from stores.";
		}

		

		$order['outlet'] = $ordersRs[20];


		$order['time_stamp'] = $ordersRs[8];


		array_push($ordersList,$order);
	}




	$output["result"]=true;
	$output["data"]=$ordersList;
	

}else{

	$output["result"]=false;
	$output["msg"]="Something went wrong.Please try again.";
	// $output["login_time_stamp"]=$login_time_stamp;

}
echo json_encode($output);







	