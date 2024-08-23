<?php
  require '../database/db.php';
  $db=new DB();
  $conn=$db->connect();
/////////////////////////////////

	
	$output=[];



	if(isset($_POST['dis_id']) && isset($_POST['sup_name'])){

		$disId=htmlspecialchars($_POST['dis_id']);
		$supName=htmlspecialchars($_POST['sup_name']);


		if($conn->query("INSERT INTO tbl_supplier VALUES(null,'$supName','$disId')")){
			$output['result']=true;
			$output['msg']="Successfully Registered.";
		}else{
			$output['result']=false;
			$output['msg']="Something went wrong please try again.";

		}


		
	}else{

		$output['result']=false;
		$output['msg']="Required fields are not provided.";

	}



	echo json_encode($output);