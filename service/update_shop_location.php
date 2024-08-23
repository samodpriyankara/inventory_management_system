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
$sessionId=session_id();

if(isset($_POST['lat']) && isset($_POST['lng']) && isset($_POST['outlet'])){

	$lat = mysqli_real_escape_string($conn,$_POST['lat']);
	$lng = mysqli_real_escape_string($conn,$_POST['lng']);
	$outlet_id = mysqli_real_escape_string($conn,$_POST['outlet']);
	
	if($conn->query("UPDATE tbl_outlet SET lat='$lat',lon='$lng' WHERE outlet_id='$outlet_id'")){
	    $output["result"] = true;
	    $output["msg"] = "Location has been updated successfully.";
	}else{
	    $output["result"] = false;
	    $output["msg"] = "Failed to update the location.";
	}
	
	

    


}else{

	$output["result"]=false;
	$output["msg"]="Something went wrong.Please try again.";
	// $output["login_time_stamp"]=$login_time_stamp;

}
echo json_encode($output);







	