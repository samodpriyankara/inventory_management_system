<?php
date_default_timezone_set('Asia/Colombo');
//--------------------------------
require 'db/db_connection.php';
$db=new DB();
$conn=$db->connect();
//--------------------------------

$output=[];
$routeId=0;

if(isset($_POST['userId'])){

	try {
		
			$userId=htmlspecialchars($_POST['userId']);
			$getRid=$conn->query("SELECT route_id FROM tbl_user_has_routes WHERE user_id='$userId'");
	
	if($row=$getRid->fetch_array()){
		$routeId=$row[0];
	}

	$getCity=$conn->query("SELECT * FROM tbl_city WHERE route_id='$routeId'");
	if($row=$getCity->fetch_array()){



		$output['result']=true;
		$output['cid']=$row[0];
		$output['rid']=$routeId;
		$output['name']=$row[1];
		$output['avg']=0;

	}else{

		$output['result']=false;

	}

	} catch (Exception $e) {
			$output['result']=false;
			$output['msg']="Error";
	}






}else{

	$output['result']=false;
	$output['msg']="Required values are not provided.";

}


echo json_encode($output);