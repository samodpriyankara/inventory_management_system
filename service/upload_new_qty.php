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

if(isset($_POST['json'])){
	

	$json = $_POST['json'];
	$decoded_json = json_decode($json,false);


		$stock_data = $decoded_json->stock_data;
		$dist_id = $stock_data->dist_id;
		$user_id = $stock_data->user_id;
		$data = $stock_data->data;

		$currentDate = date('Y-m-d H:i:s');



			for ($i = 0; $i < count($data); $i++) {
                                                                            
              $product_id=$data[$i]->product_id;
              $qty=$data[$i]->qty;
              $supplier_id=$data[$i]->supplier_id;
              $distributor_id=$data[$i]->distributor_id;



              // if($conn->query("UPDATE tbl_item SET stock = stock - '$qty' WHERE distributor_id = '$distributor_id' AND supplier_id = '$supplier_id' AND itemId = '$product_id'")){

              // 	if($qty > 0){
              // 		$conn->query("INSERT INTO tbl_van_loading_history VALUES(null,'$product_id','$qty','$supplier_id','$distributor_id','$currentDate','$user_id')");
              // 	}

              


              // }


               if($conn->query("UPDATE tbl_distributor_has_products SET qty = qty - '$qty' WHERE distributor_id = '$distributor_id' AND item_id = '$product_id'")){

              	if($qty > 0){
              		$conn->query("INSERT INTO tbl_van_loading_history VALUES(null,'$product_id','$qty','$supplier_id','$distributor_id','$currentDate','$user_id')");
              	}

              


              }






              
              // INSERT INTO ``(`id`, `item_id`, `qty`, `supplier_id`, `dist_id`, `date_time`, `user_id`)









            }










	$output["result"]=true;
	$output["msg"] = "Successfully Updated.";



	

}else{

	$output["result"]=false;
	$output["msg"]="Something went wrong.Please try again.";
	// $output["login_time_stamp"]=$login_time_stamp;

}
echo json_encode($output);