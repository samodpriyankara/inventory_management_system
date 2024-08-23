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

if(isset($_POST['user_id']) && isset($_POST['app_type'])){

	$user_id=htmlspecialchars($_POST['user_id']);
	$app_type=htmlspecialchars($_POST['app_type']);

	if($conn->query("UPDATE tbl_user SET login_status = 0 WHERE id='$user_id'")){
		$output["result"]=true;
	}else{
		$output["result"]=false;
	}


	

	
	


}else{

	$output["result"]=false;
	
}
echo json_encode($output);







	