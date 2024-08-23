<?php
date_default_timezone_set('Asia/Colombo');
//--------------------------------
require 'db/db_connection.php';
$db=new DB();
$conn=$db->connect();
//--------------------------------

$output=[];



	if(isset($_POST['userId']) && isset($_POST['location'])){

				try {
		

						$userId=htmlspecialchars($_POST['userId']);
						$data=json_decode($_POST['location']);


						for($i=0;$i<count($data);$i++){

								$lon=$data[$i]->lon;
								$lat=$data[$i]->lat;
								$speed=$data[$i]->speed;
								$accuracy=$data[$i]->accuracy;
								$provider=$data[$i]->provider;
								$date=$data[$i]->date;
								$time=$data[$i]->time;
								$bearing=$data[$i]->bearing;
								$batteryLevel=$data[$i]->bat_level;

						
								$conn->query("INSERT INTO tbl_gps_track VALUES(null,'$lon','$lat','$speed','$accuracy','$provider','$date','$time','$bearing','$batteryLevel','$userId')");

								


						}
						



						$output['result']=1;
						$output['msg']='Success';






				} catch (Exception $e) {

					$output['result']=0;
					$output['msg']='User tracking failed';
					
				}

	}else{

					$output['result']=0;
					$output['msg']='Required data not provided.';

	}






echo json_encode($output);