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
$outletArray = array();


if(isset($_POST['user_id']) && isset($_POST['shop_name'])){

	$shop_name=htmlspecialchars($_POST['shop_name']);
	$user_id=htmlspecialchars($_POST['user_id']);

	//fetch outlets according to routeId and add result to list and set it to routeList///
			 // $getOutlets=$conn->query("SELECT * FROM tbl_outlet  WHERE outlet_name LIKE '%$shop_name%'");
			 $getOutlets=$conn->query("SELECT * FROM tbl_outlet tout INNER JOIN tbl_route trou ON tout.route_id = trou.route_id WHERE outlet_name LIKE '%$shop_name%'");
			 


			 while ($row=$getOutlets->fetch_array()) {
					$outstandingTotal = 0;
			 		$outletId = $row[0];

					$outlet["outletId"]=$outletId;
					$outlet["outletName"]=$row[1];
					$outlet["contact"]=$row[3];
					$outlet["address"]=$row[4];
					
					$outlet["lat"]=$row[5];
					$outlet["lon"]=$row[6];
					$outlet["image"]=$row[7];
					
					
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

					
					$outlet["route_name"]=$row[20];



					array_push($outletArray,$outlet);


			}

			









	
				$output["result"] = true;
				$output["data"] = $outletArray;


}else{

	$output["result"]=false;
	$output["msg"]="Please provide all required data.";
	

}
echo json_encode($output);







	