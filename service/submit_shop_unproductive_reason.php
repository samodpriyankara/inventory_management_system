<?php
date_default_timezone_set('Asia/Colombo');
	//--------------------------------
require 'db/db_connection.php';
$db=new DB();
$conn=$db->connect();
//--------------------------------

$output=[];
$itemsList=array();


if(isset($_POST['reason_id']) && isset($_POST['remark']) && isset($_POST['outlet_id']) && isset($_POST['route_id']) && isset($_POST['date_time']) && isset($_POST['user_id']) && isset($_POST['lat']) && isset($_POST['lng']) && isset($_POST['image'])){


	$current_date = date('Y-m-d H:i:s');
	$reason_id = htmlspecialchars($_POST['reason_id']);

	$remark = preg_replace('/[^A-Za-z0-9\-]/', ' ', $_POST['remark']);



	$outlet_id = htmlspecialchars($_POST['outlet_id']);
	$route_id = htmlspecialchars($_POST['route_id']);
	$date_time = htmlspecialchars($_POST['date_time']);
	$user_id = htmlspecialchars($_POST['user_id']);
	
	$lat = htmlspecialchars($_POST['lat']);
	$lng = htmlspecialchars($_POST['lng']);
	$image = "data:image/png;base64,".$_POST['image'];



		if($image == ""){
			$uploaded_image_path = "N/A";
		}else{
			
			////compress image////

							$imgName = time().".jpg";
							$image_location = "../unproductive_shop_images/".$imgName;
							$exp = explode(",",$image);
							$quality = 25;
							// Content type
						    // header('Content-Type: image/jpeg');
							$data = base64_decode($exp[1]);
						    $im = imagecreatefromstring($data);
						    $width = imagesx($im);
						    $height = imagesy($im);
						    $newwidth = $width * $quality;
						    $newheight = $height * $quality;
							$thumb = imagecreatetruecolor($newwidth, $newheight);
							imagecopyresized($thumb, $im, 0, 0, 0, 0, $newwidth, $newheight, $width, $height);
							imagejpeg($thumb, $image_location);
							$uploaded_image_path = "unproductive_shop_images/".$imgName;

							


		}




		


	if($conn->query("INSERT INTO tbl_outlet_unproductive_remarks VALUES(null,'$reason_id','$remark','$outlet_id','$route_id','$user_id','$current_date','$date_time','$lat','$lng','$uploaded_image_path')")){

		$output["result"]=true;
		$output["msg"]="Successfully saved.";


	}else{
		$output["result"]=false;
		$output["msg"]="Failed to save unproductive reason.";
	}








	
}else{
	$output["result"]=false;
	$output["msg"]="Something went wrong.Please try again";
}


echo json_encode($output);




