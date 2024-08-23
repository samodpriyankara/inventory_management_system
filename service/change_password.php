<?php
date_default_timezone_set('Asia/Colombo');
//--------------------------------
require '../database/db.php';
$db=new DB();
$conn=$db->connect();

session_start();
//--------------------------------

$output=[];


if(isset($_POST['user_id']) && isset($_POST['password']) && isset($_POST['current_password'])){

	$user_id=htmlspecialchars($_POST['user_id']);
	$password=htmlspecialchars($_POST['password']);
	$current_password=htmlspecialchars($_POST['current_password']);






	$checkPassword = $conn->query("SELECT password FROM tbl_user WHERE id = '$user_id'");
	if($pwdRs = $checkPassword->fetch_array()){
		$c_pass = $pwdRs[0];

		if($c_pass == $current_password){

			if($conn->query("UPDATE tbl_user SET password = '$password' WHERE id = '$user_id'")){
					$output["result"] = true;
					$output["msg"]="Successfully updated.";
			}else{
					$output["result"] = false;
					$output["msg"]="Something went wrong.Please try again.";
			}



		}else{
			$output["result"] = false;
			$output["msg"]="Current password is incorrect";
		}



	}else{
		$output["result"] = false;
		$output["msg"]="User verification failed.";
	}




	

}else{

	$output["result"]=false;
	$output["msg"]="Something went wrong.Please try again.";

}
echo json_encode($output);







	