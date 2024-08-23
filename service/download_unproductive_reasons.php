<?php
date_default_timezone_set('Asia/Colombo');
	//--------------------------------
require 'db/db_connection.php';
$db=new DB();
$conn=$db->connect();
//--------------------------------

$output=[];
$reasons_list=array();


	if(isset($_POST['user_id'])){

	
		$get_reasons = $conn->query("SELECT * FROM tbl_unproductive_reasons WHERE status = 1");
		while($r_rs = $get_reasons->fetch_array()){
			$reason['id'] = $r_rs[0];
			$reason['reason'] = $r_rs[1];


			array_push($reasons_list, $reason);
		}


		$output["result"]=true;
		$output["data"] = $reasons_list;
	


	}else{
		$output["result"]=false;
		$output["msg"]="Something went wrong.Please try again";
	}


			echo json_encode($output);




