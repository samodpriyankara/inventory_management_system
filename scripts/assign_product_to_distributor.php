<?php
/////////////////////////////////
  session_start();
  require '../database/db.php';
  $db = new DB();
  $conn = $db->connect();
/////////////////////////////////

	
	$output=[];
	$session_id = session_id();

	
	


	if(isset($_POST['ass_product_id']) && isset($_POST['ass_dist_id']) && isset($_POST['ass_product_qty']) && isset($_POST['ass_product_cost'])){

		$product_id = htmlspecialchars($_POST['ass_product_id']);
		$dist_id = htmlspecialchars($_POST['ass_dist_id']);
		$product_qty = htmlspecialchars($_POST['ass_product_qty']);
		$product_cost = htmlspecialchars($_POST['ass_product_cost']);
		$current_date = date("Y-m-d H:i:s");


		///check stock availability////


			$check_base_stock = $conn->query("SELECT stock FROM tbl_item WHERE itemId = '$product_id'");
			if($cbsrs = $check_base_stock->fetch_array()){

				$real_stock = $cbsrs[0];


				if($product_qty > $real_stock){
					$output['result']=false;
					$output['msg']="Not enough stock available.";
				}else{

					$check_if_assigned = $conn->query("SELECT * FROM tbl_distributor_has_products WHERE distributor_id = '$dist_id' AND item_id='$product_id'");

		if($crs = $check_if_assigned->fetch_array()){

			//check if cost prices are equal or not. ///

			$already_assigned_cost = $crs[4];

			if($already_assigned_cost == $product_cost){

				if($conn->query("UPDATE tbl_distributor_has_products SET qty = (qty+'$product_qty') WHERE distributor_id = '$dist_id' AND item_id='$product_id'")){

				

				
				if($conn->query("UPDATE tbl_item SET stock = (stock-$product_qty) WHERE itemId = $product_id")){


					//////add to history////

					$conn->query("INSERT INTO tbl_distributor_product_history VALUES(null,'$product_id','$product_qty','$product_cost','$current_date','$dist_id')");

					////////////////////////




					$output['result']=true;
					$output['msg']="Successfully Updated.";
				}else{
					$output['result']=false;
					$output['msg']="Failed to manage base stock, please contact administrator.";
				}




			}else{
				$output['result']=false;
				$output['msg']="Failed to update the item, please try again.";
			}


			}else{


				$output['result']=false;
				$output['msg']="This product has already been assigned to the distributor.";

			// 	if($conn->query("INSERT INTO tbl_distributor_has_products VALUES(null,'$product_id','$dist_id','$product_qty','$product_cost','$current_date')")){



			// 	if($conn->query("UPDATE tbl_item SET stock = (stock-$product_qty) WHERE itemId = $product_id")){
			// 		$output['result']=true;
			// 		$output['msg']="Assigned successfuly.";
			// 	}else{
			// 		$output['result']=false;
			// 		$output['msg']="Failed to manage base stock, please contact administrator.";
			// 	}

				

				






			// }else{
			// 	$output['result']=true;
			// 	$output['msg']="Failed to assign, please try again.";

			// }



			}



			///////////////////////



			


		}else{


			if($conn->query("INSERT INTO tbl_distributor_has_products VALUES(null,'$product_id','$dist_id','$product_qty','$product_cost','$current_date')")){



				if($conn->query("UPDATE tbl_item SET stock = (stock-$product_qty) WHERE itemId = $product_id")){

					//////add to history////

					$conn->query("INSERT INTO tbl_distributor_product_history VALUES(null,'$product_id','$product_qty','$product_cost','$current_date','$dist_id')");

					////////////////////////


					
					$output['result']=true;
					$output['msg']="Assigned successfuly.";
				}else{
					$output['result']=false;
					$output['msg']="Failed to manage base stock, please contact administrator.";
				}

				

				






			}else{
				$output['result']=false;
				$output['msg']="Failed to assign, please try again.";

			}


		}



				}



			}else{
				$output['result']=false;
				$output['msg']="Invalid assignment, please try again.";
			}



		//////////////////////////////



		




		


		


		
		

	}else{

		$output['result']=false;
		$output['msg']="Required fields are not provided.";
	}




	echo json_encode($output);