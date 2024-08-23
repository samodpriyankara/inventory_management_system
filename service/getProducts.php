<?php
date_default_timezone_set('Asia/Colombo');
	//--------------------------------
require 'db/db_connection.php';
$db=new DB();
$conn=$db->connect();
//--------------------------------

$output=[];
$itemsList=array();


if(isset($_POST['userId'])){

	try {
		$userId=htmlspecialchars($_POST['userId']);
	$disId=0;

	$rs=$conn->query("SELECT distributor_id FROM tbl_distributor_has_tbl_user thu WHERE thu.user_id='$userId'");
	
	if($gdi=$rs->fetch_array()){

		$disId=$gdi[0];

	}
////////////////////////////////////////////////


// $rs_item=$conn->query("SELECT * FROM tbl_item ti WHERE ti.distributor_id='$disId' AND stock > 0");
// $rs_item=$conn->query("SELECT * FROM tbl_item ti WHERE ti.distributor_id='$disId' AND stock > 0 AND sequenceId = 1");






// $rs_item=$conn->query("SELECT * FROM tbl_item ti WHERE (ti.distributor_id='$disId' OR ti.distributor_id = 0) AND stock > 0 AND sequenceId = 1");



	$get_dist_products = $conn->query("SELECT * FROM tbl_distributor_has_products WHERE distributor_id = '$disId'");
	

	while($dprs = $get_dist_products->fetch_array()){

		$product_id = $dprs[1];

		$assigned_qty = $dprs[3];

		
		$assigned_cost = $dprs[4];


		// $rs_item=$conn->query("SELECT * FROM tbl_item ti WHERE  ti.itemId = '$product_id' AND ti.stock > 0 AND ti.sequenceId = 1");
		// $rs_item=$conn->query("SELECT * FROM tbl_item ti WHERE  ti.itemId = '$product_id' AND ti.stock > 0");
		$rs_item=$conn->query("SELECT * FROM tbl_item ti WHERE  ti.itemId = '$product_id'");

		//////////////////

		if ($gp=$rs_item->fetch_array()) {


			$itemId=$gp[0];
			$itemDetailId = $gp[7];
			$pack_size = 0;

			// $distributor_price = 0.0;
			$return_price = 0.0;


			///get free scheme///

			$getFree = $conn->query("SELECT * FROM tbl_free_issue_scheme WHERE item_id = '$itemDetailId'");
			if($fRs = $getFree->fetch_array()){
				$item["free_margin"] = $fRs[1];
				$item["free_qty"] = $fRs[2];
			}else{
				$item["free_margin"] = 0;
				$item["free_qty"] = 0;
			}

			



			////////////////////



			/////get pack size///
				$get_other_details = $conn->query("SELECT * FROM tbl_other_item_details WHERE item_id = '$itemDetailId'");
				if($other_rs = $get_other_details->fetch_array()){
					$pack_size = $other_rs[1];
				}
			////////////////////


			/////get other prices///
				$get_other_prices = $conn->query("SELECT * FROM tbl_item_other_prices WHERE item_id = '$itemId'");
				if($other_rs = $get_other_prices->fetch_array()){
					$return_price = $other_rs[1];
					// $distributor_price = $other_rs[2];
				}
			////////////////////





			$item["id"]=$itemId;
			$item["code"]=$gp[1];
			$item["description"] = htmlspecialchars_decode($gp[2]);
			$item["price"]=$gp[3];
			// $item["p_size"]=$gp[4];
			$item["p_size"]=$pack_size;
			

			// $item["stock"]=$gp[5];
			$item["stock"] = $assigned_qty;
			$item["rp_id"]=$gp[6];
			$item["genaric_name"]=$gp[7];
			// $item["genaric_name"] = "";
			// $item["re_price"] = $gp[8];
			$item["re_price"] = $assigned_cost;
			$item["min_qty"]=$gp[9];
			$item["weight"]=$gp[10];
			$item["sequence"]=$gp[11];
			$item["max_qty"]=$gp[12];
			$item["brand"]=$gp[13];
			$item["cat_id"]=$gp[14];
			// $item["dis_id"]=$gp[15];
			$item["dis_id"] = $disId;
			$item["sup_id"]=$gp[16];
			
			$item["return_price"] = $return_price;



			


			$image = $gp[17];


			if($image == null || $image == ""){
				$item["image"]=null;
			}else{
				// $image = explode(",",$image);
				// $item["image"]=$image[1];


				//product_images/1644905533.jpg




				$path = '../'.$image;
				$type = pathinfo($path, PATHINFO_EXTENSION);
				$data = file_get_contents($path);
				// $base64 = 'data:image/' . $type . ';base64,' . base64_encode($data);
				// $base64 = ;

				$item["image"] = base64_encode($data);









			}
			



					$priceBatchList=array();

					$getPriceBatches=$conn->query("SELECT * FROM tbl_product_batch WHERE tbl_product_batch.itemId='$itemId'");
					while ($row=$getPriceBatches->fetch_array()) {


						$pb["id"]=$row[0];
						$pb["itemId"]=$row[6];
						$pb["name"]=$row[1];
						$pb["selling_price"]=$row[2];
						$pb["buying_price"]=$row[3];
						$pb["stock"]=$row[4];
						$pb["pack_size"]=$row[5];
						$pb["status"]=$row[8];
						array_push($priceBatchList,$pb);


					}

					


			/////////////////////




			$item["batches"]=$priceBatchList;
			
			// if($assigned_qty > 0){
			// 	array_push($itemsList,$item);
			// }

			array_push($itemsList,$item);


			



	}




		//////////////////





	}






$output["result"]=true;
	
	




$output["items_list"]=$itemsList;

	} catch (Exception $e) {
		$output["result"]=false;
		$output["msg"]="Something went wrong.Please try again";
	}





////////////////////////////////////////////////
	}else{
		$output["result"]=false;
		$output["msg"]="Something went wrong.Please try again";
	}


			echo json_encode($output);




