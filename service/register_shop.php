<?php
date_default_timezone_set('Asia/Colombo');
//--------------------------------
require 'db/db_connection.php';
$db=new DB();
$conn=$db->connect();
//--------------------------------


$output=[];




if(isset($_POST['shop_name']) && isset($_POST['owner_name']) && isset($_POST['address']) && isset($_POST['contact']) && isset($_POST['seq']) && isset($_POST['lon']) && isset($_POST['lat']) && isset($_POST['category']) && isset($_POST['route']) && isset($_POST['grade']) && isset($_POST['image']) && isset($_POST['temp_id'])){

	


	$date=date('Y-m-d h:i:s');

	$image = 'data:image/jpeg;base64,'.$_POST['image'];
	$shopName=htmlspecialchars($_POST['shop_name']);
	$ownerName=htmlspecialchars($_POST['owner_name']);
	$address=htmlspecialchars($_POST['address']);
	$routeId=htmlspecialchars($_POST['route']);

	$contact=htmlspecialchars($_POST['contact']);

	$grade=htmlspecialchars($_POST['grade']);
	$lon=htmlspecialchars($_POST['lon']);
	$categoryId=htmlspecialchars($_POST['category']);
	$sequence=htmlspecialchars($_POST['seq']);
	$lat=htmlspecialchars($_POST['lat']);
	$tempId=htmlspecialchars($_POST['temp_id']);









	
	$check_contact = $conn->query("SELECT * FROM tbl_outlet WHERE contact = '$contact'");
	if(mysqli_num_rows($check_contact) > 0){
			$output['result']=true;
			$output['msg']="This contact no already has a shop assigned, please check shop name again.";
			$output['temp_id']=$tempId;
	}else{

		//$file_name = time().".jpeg";
		$file_name = rand(111111,999999).".jpeg";
	
		$path_for_db = 'outlet_images/'.$file_name;
		file_put_contents("../outlet_images/".$file_name,file_get_contents($image));



		if($conn->query("INSERT INTO tbl_outlet VALUES(null,'$shopName','$ownerName','$contact','$address','$lat','$lon','$path_for_db',0,0,0,0,0,0,'$categoryId','$sequence','$grade','$date','$routeId')")){

		$output['result']=true;
		$output['msg']="Successfully Registered.";
		$output['temp_id']=$tempId;


		

	}else{

			$output['result']=false;
			$output['msg']="Registering failed.";
	}
	}



	


	



}else{

	$output['result']=false;
	$output['msg']="Required data not provided.";


}


echo json_encode($output);