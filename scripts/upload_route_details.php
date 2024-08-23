<?php
  require '../database/db.php';
  $db=new DB();
  $conn=$db->connect();
/////////////////////////////////

	
	$output=[];


	if(isset($_POST['rname']) && isset($_POST['cname'])){

		$routName=htmlspecialchars($_POST['rname']);
		$cityName=htmlspecialchars($_POST['cname']);
		$currentDateTime=date('Y-m-d h:i:s');
		$routeId=0;

			$checkIfExists=$conn->query("SELECT * FROM tbl_route tr WHERE tr.route_name='$routName'");
			if($row=$checkIfExists->fetch_array()){

				$output['result']=false;
				$output['msg']="Route is already exists.";

			}else{


				if($conn->query("INSERT INTO tbl_route VALUES(null,'$routName','$currentDateTime',1)")){
				

				$getRouteIdQuery=$conn->query("SELECT tr.route_id FROM tbl_route tr WHERE tr.route_name='$routName'");
				if($route=$getRouteIdQuery->fetch_array()){
					$routeId=$route[0];
				}


				$checkRouteExistsQuery=$conn->query("SELECT * FROM tbl_city WHERE city_name='$cityName' AND route_id='$routeId'");
				if($check=$checkRouteExistsQuery->fetch_array()){

					//already mapped

				}else{

					$conn->query("INSERT INTO tbl_city VALUES(null,'$cityName','$routeId')");

				}











					$output['result']=true;
					$output['msg']="Successfully Saved.";

				}else{
					$output['result']=false;
					$output['msg']="Something went wrong please try again.";

				}

				

			}





			

	}else{

			$output['result']=false;
			$output['msg']="Required fields are not provided.";

	}

	echo json_encode($output);