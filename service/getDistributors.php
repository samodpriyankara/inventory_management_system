<?php
date_default_timezone_set('Asia/Colombo');
	//--------------------------------
require 'db/db_connection.php';
$db=new DB();
$conn=$db->connect();
//--------------------------------

$distributor=[];

if(isset($_POST['userId'])){

	$userId=htmlspecialchars($_POST['userId']);

	$rs=$conn->query("SELECT * FROM tbl_distributor_has_tbl_user tdhtu INNER JOIN tbl_distributor td ON tdhtu.distributor_id=td.distributor_id WHERE tdhtu.user_id='$userId' AND tdhtu.status=1");


	if($row=$rs->fetch_array()){

		$distributor["result"]=true;
		$distributor["id"]=$row[5];
		$distributor["name"]=$row[6];
		$distributor["address"]=$row[7];
		$distributor["contact"]=$row[8];


	}else{
		$distributor["result"]=false;
		$distributor["msg"]="No valid distributor found.";
	}





}else{
		
		$distributor["result"]=false;
		$distributor["msg"]="Something went wrong.Please try again.";

}


echo json_encode($distributor);


