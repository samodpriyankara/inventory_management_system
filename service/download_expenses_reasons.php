<?php
require '../database/db.php';
$db=new DB();
$conn=$db->connect();


$output=[];
$expenses_types = array();


if(isset($_POST['user_id'])){


	$exp_types_rs = $conn->query("SELECT * FROM tbl_expenses_types WHERE status = 1 ORDER BY type ASC");
	while ($rs = $exp_types_rs->fetch_array()) {
			
			$type['id'] = $rs[0];
			$type['type'] = $rs[1];
			

			array_push($expenses_types,$type);
	}

	$output["result"]=true;
	$output["data"]=$expenses_types;
	

}else{

	$output["result"]=false;
	$output["msg"]="Something went wrong.Please try again.";
	
}
echo json_encode($output);







	