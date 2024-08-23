<?php
 	require '../database/db.php';
    $db=new DB();
    $conn=$db->connect();
/////////////////////////////////
	
	$output=[];


          if(isset($_POST['status']) && isset($_POST['user_id'])){

          		$userId=htmlspecialchars($_POST['user_id']);
          		$status=htmlspecialchars($_POST['status']);
          		


                    if($conn->query("UPDATE tbl_user SET login_status = 0 WHERE id = '$userId'")){

                          $output['result']=true;
                         $output['msg']="Success";
                    }else{

                         $output['result']=false;
                         $output['msg']="Update failed, Please try again.";

                    }






		}else{


          		$output['result']=false;
          		$output['msg']="Required data not provided.";



          }






          echo json_encode($output);

