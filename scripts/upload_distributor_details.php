<?php
  require '../database/db.php';
  $db=new DB();
  $conn=$db->connect();
/////////////////////////////////

	
	$output=[];


	if(isset($_POST['dis_name']) && isset($_POST['dis_address']) && isset($_POST['dis_contact']) && isset($_POST['dis_username']) && isset($_POST['dis_password'])){



		$disName=htmlspecialchars($_POST['dis_name']);
		$disAddress=htmlspecialchars($_POST['dis_address']);
		$disContact=htmlspecialchars($_POST['dis_contact']);
		$current_date_time =date('Y-m-d H:i:s');

		

		$dis_username = preg_replace('/[^A-Za-z0-9\-]/', '', str_replace(' ', '', htmlspecialchars($_POST['dis_username'])));
		$dis_password = htmlspecialchars($_POST['dis_password']);


		if($conn->query("INSERT INTO tbl_distributor VALUES(null,'$disName','$disAddress','$disContact')")){


			$last_id = $conn->insert_id;
			$password_hash = password_hash($dis_password, PASSWORD_DEFAULT);

			if($conn->query("INSERT INTO tbl_distributor_account VALUES(null,'$dis_username','$password_hash','$current_date_time',1,'$last_id')")){

				$output['result']=true;
				$output['msg']="Successfully Registered.";


			}else{
				$output['result']=false;
				$output['msg']="Registration failed (error code 6349).";
			}




			
		}else{

			$output['result']=false;
			$output['msg']="Something went wrong please try again.";
		}



		
	


	}else{


		$output['result']=false;
		$output['msg']="Required fields are not provided.";
	}



echo json_encode($output);
