<?php
session_start();
  require '../database/db.php';
  $db=new DB();
  $conn=$db->connect();
/////////////////////////////////

	
	$output=[];

	

	if(isset($_POST['lat']) && isset($_POST['lng']) && isset($_POST['user_id']) && isset($_POST['date'])){

		$lat=htmlspecialchars($_POST['lat']);
		$lng=htmlspecialchars($_POST['lng']);
		$user_id=htmlspecialchars($_POST['user_id']);
		$date=htmlspecialchars($_POST['date']);



		$exploded_lat = explode(".",$lat);
		$exploded_lng = explode(".",$lng);
		

		$n_lat =  $exploded_lat[0].".".substr($exploded_lat[1], 0, 3);
		$n_lng =  $exploded_lng[0].".".substr($exploded_lng[1], 0, 3);
		$first_date = "";
		$last_date = "";

		$difference = "0 hr 0 min 0 sec";

		$get_first = $conn->query("SELECT tgt.date,tgt.time FROM tbl_gps_track tgt WHERE tgt.user_id='$user_id' AND tgt.date='$date' AND tgt.lat LIKE '$n_lat%' AND tgt.lon LIKE '$n_lng%' ORDER BY tgt.time ASC LIMIT 1");
		


		if($gf_rs = $get_first->fetch_array()){
			$first_date = $gf_rs[0].' '.$gf_rs[1];
		}


		$get_last = $conn->query("SELECT tgt.date,tgt.time FROM tbl_gps_track tgt WHERE tgt.user_id='$user_id' AND tgt.date='$date' AND tgt.lat LIKE '$n_lat%' AND tgt.lon LIKE '$n_lng%' ORDER BY tgt.time DESC LIMIT 1");

		if($gl_rs = $get_last->fetch_array()){
			$last_date = $gl_rs[0].' '.$gl_rs[1];
		}


		if($first_date != "" || $last_date != ""){



			$start_datetime = new DateTime($first_date); 
			$diff = $start_datetime->diff(new DateTime($last_date)); 
			$difference = $diff->h."hr ".$diff->i."min ".$diff->s."sec";
			 
		


		}




		$output['result'] = true;
		$output['wait_duration'] = $difference;
		
		
		


	}else{

		$output['result']=false;
		$output['msg']="Something went wrong, please try again.";


	}



	echo json_encode($output);