<?php
session_start();
  require '../database/db.php';
  $db=new DB();
  $conn=$db->connect();
  $output=[];

	if(isset($_POST['pid']) && isset($_SESSION['ID']) && $_POST['pid'] != "" && $_SESSION['ID'] != ""){
		$pid=htmlspecialchars($_POST['pid']);
		$image_array = array();
		$get_images = $conn->query("SELECT * FROM tbl_item_details_extra_images WHERE item_detail_id = '$pid'");

		if(mysqli_num_rows($get_images) > 0){

			while($irs = $get_images->fetch_array()){


			$image_preview = "<img src='$irs[1]' class='img img-responsive' width='200' style='margin-right:20px;margin-bottom:20px;'/>";

			

			array_push($image_array, $image_preview);

		}

		$output['result']=true;
		$output['data']=$image_array;


		}else{
			$output['result']=true;
			$output['data']="<h3 style='color:darkred;'>No extra images found...</h3>";
		}

		

	}else{

		$output['result']=false;
		$output['msg']="Required fields are not provided.";


	}



	echo json_encode($output);