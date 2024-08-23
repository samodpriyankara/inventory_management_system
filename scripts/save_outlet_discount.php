<?php
/////////////////////////////////
  session_start();
  require '../database/db.php';
  $db = new DB();
  $conn = $db->connect();
/////////////////////////////////

	
	$output=[];
	$session_id = session_id();
	


	if(isset($_POST['discount']) && isset($_POST['outlet'])){

		$discount = htmlspecialchars($_POST['discount']);
		$outlet = htmlspecialchars($_POST['outlet']);


		

		if($conn->query("UPDATE tbl_outlet SET outlet_discount = '$discount' WHERE outlet_id = '$outlet'")){

			$output['result']=true;
			$output['msg']="Discount updating success.";

		}else{
			$output['result']=false;
			$output['msg']="Updating discount failed, please try again.";
		}


		

				
		




		
		

	}else{

		$output['result']=false;
		$output['msg']="Required fields are not provided.";
	}




	echo json_encode($output);