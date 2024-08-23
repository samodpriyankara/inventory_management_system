<?php
date_default_timezone_set('Asia/Colombo');
	//--------------------------------
require 'db/db_connection.php';
$db=new DB();
$conn=$db->connect();
//--------------------------------

$output=[];


if(isset($_POST['type_id']) && isset($_POST['amount']) && isset($_POST['remark']) && isset($_POST['user_id'])){


	$current_date = date('Y-m-d H:i:s');
	$type_id = htmlspecialchars($_POST['type_id']);
	$remark = preg_replace('/[^A-Za-z0-9\-]/', ' ', $_POST['remark']);
	$amount = htmlspecialchars($_POST['amount']);
	$user_id = htmlspecialchars($_POST['user_id']);
	



	if($conn->query("INSERT INTO tbl_sales_rep_expenses VALUES(null,'$type_id','$amount','$remark','$current_date','$user_id')")){

		$output['result'] = true;
		$output['msg'] = "Successfully Saved.";

	}else{
		$output['result'] = false;
		$output['msg'] = "Failed to save.";
	}
	



	
}else{
	$output["result"]=false;
	$output["msg"]="Something went wrong.Please try again";
}


echo json_encode($output);




