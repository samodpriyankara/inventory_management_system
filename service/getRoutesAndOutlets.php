<?php
date_default_timezone_set('Asia/Colombo');
//--------------------------------
require 'db/db_connection.php';
$db=new DB();
$conn=$db->connect();
//--------------------------------


$array = array();
$routeList=[];
//$outlet=[];
$outletArray=array();


if(isset($_POST['routeId'])){

		$userId=htmlspecialchars($_POST['userId']);
		////////////////////////////////////////////
		
		
		$routeId=htmlspecialchars($_POST['routeId']);
		
		// $getRoutes=$conn->query("SELECT * FROM tbl_user_has_routes tuhr INNER JOIN tbl_route tr ON tuhr.route_id=tr.route_id WHERE tuhr.user_id='$userId'");
		// $getRoutes=$conn->query("SELECT DISTINCT tr.route_id,tr.route_name FROM tbl_user_has_routes tuhr INNER JOIN tbl_route tr ON tuhr.route_id=tr.route_id WHERE tuhr.user_id='$userId'");


		$getRoutes=$conn->query("SELECT DISTINCT tr.route_id,tr.route_name FROM tbl_user_has_routes tuhr INNER JOIN tbl_route tr ON tuhr.route_id=tr.route_id WHERE tuhr.user_id='$userId' AND tr.route_id = '$routeId'");
		
		
		if($gr_rs=$getRoutes->fetch_array()){
			
			$rId=$gr_rs[0];
			
			$routeList["result"]=true;
			$routeList["routeId"]=$rId;
			$routeList["routeName"]=$gr_rs[1];
			
			
			//fetch outlets according to routeId and add result to list and set it to routeList///
			 $getOutlets=$conn->query("SELECT * FROM tbl_outlet  WHERE route_id='$rId'");
			 while ($row=$getOutlets->fetch_array()) {
				$outstandingTotal = 0;
			 	$outletId = $row[0];

			 	$image_path = '../'.$row[7];

				//////check if outstanding available/////


			 	$getOutstanding = $conn->query("SELECT editable_total FROM tbl_credit_orders WHERE outlet_id = '$outletId'");
			 	while($osRs = $getOutstanding->fetch_array()){
					$outstandingTotal += $osRs[0];
				}

			 	////////////////////////////////////////




					$outlet["outletId"]=$outletId;
					$outlet["outletName"]=$row[1];
					$outlet["contact"]=$row[3];
					$outlet["address"]=$row[4];
					
					$outlet["lat"]=$row[5];
					$outlet["lon"]=$row[6];


					



					// $outlet["image"]=$row[7];
					$outlet["image"] = base64_encode(file_get_contents($image_path));










					
					
					$outlet["type"]=$row[8];
					$outlet["discount"]=$row[9];
					$outlet["last_order_value"]=$row[10];
					
					$outlet["current_month_purchase"]=$row[11];
					$outlet["avarage_purchase"]=$row[12];
					

					// $outlet["outstanding"]=$row[13];
					$outlet["outstanding"]=$outstandingTotal;
					

					$outlet["category"]=$row[14];
					$outlet["sequence"]=$row[0];
					$outlet["grade"]=$row[16];
					$outlet["created_date"]=$row[17];
					$outlet["route_id"]=$row[18];



					array_push($outletArray,$outlet);


}

$routeList["outlets"]=$outletArray;



			///////////////////////////////////////////////////////////////////////////////////////
			
			
				
			
			
			
			
			
			
				array_push($array,$routeList);		
			
			
			
			}
		
		
		
		
		/*$rs=$conn->query("SELECT * FROM tbl_outlet  WHERE route_id='$routeId'");
				
				while ($row=$rs->fetch_array()) {

					$outlet["outletId"]=$row[0];
					$outlet["outletName"]=$row[1];
					$outlet["contact"]=$row[2];
					$outlet["address"]=$row[3];
					
					$outlet["lat"]=$row[4];
					$outlet["lon"]=$row[5];
					$outlet["image"]=$row[6];
					
					$outlet["type"]=$row[7];
					$outlet["discount"]=$row[8];
					$outlet["last_order_value"]=$row[9];
					
					$outlet["current_month_purchase"]=$row[10];
					$outlet["avarage_purchase"]=$row[11];
					$outlet["outstanding"]=$row[12];
					$outlet["category"]=$row[13];
					$outlet["sequence"]=$row[14];
					$outlet["grade"]=$row[15];
					$outlet["created_date"]=$row[16];
					$outlet["route_id"]=$row[17];







					array_push($array,$outlet);
				}*/











}


echo json_encode($array);
