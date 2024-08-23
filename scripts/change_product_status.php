<?php
session_start();
  require '../database/db.php';
  $db=new DB();
  $conn=$db->connect();
/////////////////////////////////

	
	$output=[];


	if(isset($_POST['p_id']) && isset($_POST['status'])){

		$p_id=htmlspecialchars($_POST['p_id']);
		$status=htmlspecialchars($_POST['status']);
		


		if($conn -> query("UPDATE tbl_item_details SET sequenceId  = '$status' WHERE item_detail_Id  = '$p_id'")){

			$output['result']=true;
			$output['msg']="Successfully updated.";

		}else{

			$output['result']=false;
			$output['msg']="Update failed, please try again.";

		}

		


	}else{

		$output['result']=false;
		$output['msg']="Required fields are not provided.";


	}



	echo json_encode($output);