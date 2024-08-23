<?php
date_default_timezone_set('Asia/Colombo');
//--------------------------------
require '../database/db.php';
$db=new DB();
$conn=$db->connect();

session_start();
//--------------------------------

$output=[];
$ordersArray = array();

if(isset($_POST['outlet_id'])){

	$outlet_id=htmlspecialchars($_POST['outlet_id']);
	
	$getAllCorders = $conn->query("SELECT tor.id,tor.order_id,tor.invoice_date,tor.invoice_time,tco.fixed_total,tco.editable_total FROM tbl_order tor INNER JOIN tbl_credit_orders tco ON tor.id=tco.order_id  WHERE tco.editable_total > 0 AND tco.outlet_id = '$outlet_id'");
	while($oRs = $getAllCorders -> fetch_array()){

		$order['id'] = $oRs[0];
		$order['order_id'] = $oRs[1];
		$order['invoice_date'] = $oRs[2];
		$order['invoice_time'] = $oRs[3];
		$order['total'] = $oRs[4];
		$order['due'] = $oRs[5];


		array_push($ordersArray, $order);

	}

	$output["result"]=true;
	$output["data"] = $ordersArray;

	

	

}else{

	$output["result"]=false;
	$output["msg"]="Something went wrong.Please try again.";
	// $output["login_time_stamp"]=$login_time_stamp;

}
echo json_encode($output);







	