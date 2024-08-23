<?php
require '../database/db.php';
$db=new DB();
$conn=$db->connect();


$output=[];
$restrictions_list = array();


if(isset($_POST['user_id'])){


	$restriction_rs = $conn->query("SELECT * FROM tbl_app_restrictions");
	while ($rs = $restriction_rs->fetch_array()) {
			
			$rest['summary_view'] = $rs[0];
			$rest['expenses_adding'] = $rs[1];
			$rest['messages_view'] = $rs[2];
			$rest['pending_orders_view'] = $rs[3];
			$rest['invoice_history_view'] = $rs[4];
			

			array_push($restrictions_list,$rest);
	}

	$output["result"]=true;
	$output["data"]=$restrictions_list;
	

}else{

	$output["result"]=false;
	$output["msg"]="Something went wrong.Please try again.";
	
}
echo json_encode($output);







	