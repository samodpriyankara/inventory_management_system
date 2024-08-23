<?php
date_default_timezone_set('Asia/Colombo');
	//--------------------------------
require 'db/db_connection.php';
$db=new DB();
$conn=$db->connect();
//--------------------------------

$output=[];
$supplierList=array();

if(isset($_POST['userId'])){

	$userId=htmlspecialchars($_POST['userId']);

	$rs=$conn->query("SELECT * FROM tbl_supplier ts INNER JOIN tbl_distributor_has_tbl_user tdhtu ON ts.distributor_id=tdhtu.distributor_id WHERE tdhtu.user_id='$userId'");


		$output["result"]=true;

	while($row=$rs->fetch_array()){

		$supplier["id"]=$row[0];
		$supplier["name"]=$row[1];
		$supplier["disId"]=$row[2];
		
		array_push($supplierList,$supplier);

	}
		$output["suppliers"]=$supplierList;






	}else{
		
		$output["result"]=false;
		$output["msg"]="Something went wrong.Please try again.";

	}


		echo json_encode($output);


