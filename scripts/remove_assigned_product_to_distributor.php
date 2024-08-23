<?php
/////////////////////////////////
  session_start();
  require '../database/db.php';
  $db = new DB();
  $conn = $db->connect();
  date_default_timezone_set('Asia/Colombo');
  $currentDate=date('Y-m-d H:i:s');
/////////////////////////////////

  	$is_distributor = false;
    if(isset($_SESSION['DISTRIBUTOR'])){
      $is_distributor = $_SESSION['DISTRIBUTOR'];
    }

    if(isset($_SESSION['ID'])){
      $user_id = $_SESSION['ID'];
    }

	$output=[];
	
	if(isset($_POST['assignment_id'])){

		$assignment_id = htmlspecialchars($_POST['assignment_id']);

		$get_data = $conn->query("SELECT * FROM tbl_distributor_has_products WHERE id = '$assignment_id'");
		if($gdrs = $get_data->fetch_array()){
			$item_id = $gdrs[1];
			$distributor_id = $gdrs[2];
			$available_qty = $gdrs[3];

			//REMOVE hISTORY ADD
			if($conn->query("INSERT INTO tbl_distributor_remove_products_details (id, item_id, distributor_id, removed_qty, remove_person_id, remove_datetime) VALUES (null, '$item_id', '$distributor_id', '$available_qty', '$user_id', '$currentDate')")){


				if($conn->query("DELETE FROM tbl_distributor_has_products WHERE id = '$assignment_id'")){

					/////////manage the stock ///
					if($conn->query("UPDATE tbl_item SET stock = (stock+$available_qty) WHERE itemId = '$item_id'")){

						$output['result']=true;
						$output['msg'] = "Successfully Removed.";

					}else{

						$output['result']=false;
						$output['msg'] = "Failed to adding main stock, please try again.";

					}
					////////////////////////////



				}else{
					$output['result']=false;
					$output['msg'] = "Failed to remove, please try again.";
				
				}



			}else{

				$output['result']=false;
				$output['msg'] = "Failed to adding remove history, please try again.";

			}

		}else{
			$output['result']=false;
			$output['msg'] = "Invalid assignment.";
		}




		

		

		


		
		

	}else{

		$output['result']=false;
		$output['msg']="Required fields are not provided.";
	}




	echo json_encode($output);