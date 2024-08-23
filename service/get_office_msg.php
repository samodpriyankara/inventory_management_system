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
$msgList = array();

if(isset($_POST['user_id'])){


	$getMsgs = $conn->query("SELECT * FROM tbl_office_msgs WHERE status = 1 ORDER BY msg_id DESC LIMIT 5");
	while ($rs = $getMsgs->fetch_array()) {
			
			$msg['id'] = $rs[0];
			$msg['sub'] = $rs[1];
			$msg['msg'] = $rs[2];
			$msg['date'] = $rs[3];

			array_push($msgList,$msg);
	}

	$output["result"]=true;
	$output["data"]=$msgList;
	

}else{

	$output["result"]=false;
	$output["msg"]="Something went wrong.Please try again.";
	// $output["login_time_stamp"]=$login_time_stamp;

}
echo json_encode($output);







	