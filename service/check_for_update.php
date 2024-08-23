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


if(isset($_POST['app_version'])){

	$appVersion=htmlspecialchars($_POST['app_version']);

	$check = $conn->query("SELECT * FROM tbl_app_update ORDER BY id DESC LIMIT 1");
	if($cRs = $check->fetch_array()){

		$newVersion = $cRs[1];


		if($appVersion === $newVersion){

			$output["result"]=false;
			$output["msg"]="Your app is up-to-date";
			

		}else{
			$output["result"]=true;
			$output["msg"]="New update available.";
		}




	}else{
		$output["result"]=false;
		$output["msg"]="No update found.";
	}


	


}else{

	$output["result"]=false;
	$output["msg"]="Please provide all required data.";
	

}
echo json_encode($output);







	