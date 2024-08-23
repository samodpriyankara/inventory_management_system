<?php
 	require '../database/db.php';
    $db=new DB();
    $conn=$db->connect();
/////////////////////////////////
	
	$output=[];


          if(isset($_POST['user_id']) && isset($_POST['route_id']) && isset($_POST['route_date'])){

          		$userId=htmlspecialchars($_POST['user_id']);
          		$routeId=htmlspecialchars($_POST['route_id']);
          		$routeDate=htmlspecialchars($_POST['route_date']);

          		$checkRoute=$conn->query("SELECT * FROM tbl_user_has_routes tr WHERE tr.user_id='$userId' AND tr.date='$routeDate'");

          		if($crdata=$checkRoute->fetch_array()){

          				$output['result']=false;
          				$output['msg']="User already has a route for ".$routeDate.'.';

                              if($conn -> query("UPDATE tbl_user_has_routes tuhr SET tuhr.route_id = '$routeId' WHERE tuhr.date='$routeDate' AND tuhr.user_id='$userId'" )){

                                   $output['result']=true;
                              $output['msg']="Route plan has been updated successfully.";

                              }else{
                                     $output['result']=false;
                                   $output['msg']="Failed to update route plan, please try again";
                              }



          		}else{


          			$rs=$conn->query("INSERT INTO tbl_user_has_routes VALUES(null,'$userId','$routeId','$routeDate',1)");
          			if($rs){
          				$output['result']=true;
          				$output['msg']="Route plan registered successfully.";
          			}else{
          				$output['result']=false;
          				$output['msg']="Something went wrong please try again.";
          			}



          		}



		}else{


          		$output['result']=false;
          		$output['msg']="Required data not provided.";



          }






          echo json_encode($output);

