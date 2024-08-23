<?php
  require '../database/db.php';
  $db=new DB();
  $conn=$db->connect();
/////////////////////////////////

	
	$output=[];
	$shopsList = array();
	

	if(isset($_POST['route_name'])){
		
		$routeName = htmlspecialchars($_POST['route_name']);
		$getRouteId = $conn->query("SELECT route_id FROM tbl_route WHERE route_name = '$routeName'");

		if($rRs = $getRouteId->fetch_array()){

			$routeId = $rRs[0];

			$getShops = $conn->query("SELECT * FROM tbl_outlet WHERE route_id='$routeId'");
			while ($sRs = $getShops->fetch_array()) {
				
				
				$shopName = $sRs[1];
				$shop_id = $sRs[0];

				$obj = "<option value=".$shop_id.">".$shopName."</option>";

				array_push($shopsList,$obj);


			}




			$output['result']=true;
			$output['data']=$shopsList;


		}else{
			$output['result']=false;
			$output['msg']="No route details found.";
		}




		


	}else{
		$output['result']=false;
		$output['msg']="Required fields are not provided.";
	}


echo json_encode($output);