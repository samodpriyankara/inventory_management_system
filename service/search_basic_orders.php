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

if(isset($_POST['from']) && isset($_POST['to']) && isset($_POST['user_id'])){

	$from=htmlspecialchars($_POST['from']);
	$to=htmlspecialchars($_POST['to']);
	$user_id=htmlspecialchars($_POST['user_id']);
	
	
	$getOrders = $conn->query("SELECT * FROM tbl_order tblorder INNER JOIN tbl_outlet tblout ON tblorder.outlet_id=tblout.outlet_id WHERE tblorder.invoice_date BETWEEN '$from' AND '$to' AND tblorder.user_id = '$user_id'");
	while($ordersRs = $getOrders->fetch_array()){

		$order['id'] = $ordersRs[0];
		$order['order_id'] = $ordersRs[1];
		$order['date'] = $ordersRs[3];
		$order['time'] = $ordersRs[4];
		$order['payment_status'] = $ordersRs[9];
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







	