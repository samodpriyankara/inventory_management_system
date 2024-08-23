<?php
date_default_timezone_set('Asia/Colombo');
	//--------------------------------
require 'db/db_connection.php';
$db=new DB();
$conn=$db->connect();
//--------------------------------


$output=[];


if(isset($_POST['jsonString']) && isset($_POST['userId']) && isset($_POST['session'])){


$decoded=json_decode($_POST['jsonString']);

		$dateTime=htmlspecialchars($decoded->attended_time);
		$lon=htmlspecialchars($decoded->longitude);
		$lat=htmlspecialchars($decoded->latitude);
		$locType=htmlspecialchars($decoded->location_type);
		$status=htmlspecialchars($decoded->attendance_status);
		$millage=htmlspecialchars($decoded->mileage);

		
		$userId=htmlspecialchars($_POST['userId']);
		$session=htmlspecialchars($_POST['session']);

	

        try {
        	
    			if($conn->query("INSERT INTO tbl_attendance VALUES(null,'$dateTime','$lat','$lon','$locType','$status','$millage','$userId','$session')")){


    				if($status==1){
						if($conn->query("UPDATE tbl_user SET login_status=0 WHERE id='$userId'")){
						}
					}


    					$output['result']=true;


        		}else{

        				$output['result']=false;
        				$output['msg']="Couldn't mark attendance successfully.Please try again.";

        		}
        		







        } catch (Exception $e) {
        	
        	$output['result']=false;
        	$output['msg']="Something went wrong.Please try again.";
        	
        }




}else{

			$output['result']=false;
        	$output['msg']="Required data not provided.";
}

echo json_encode($output);