<?php
  require '../database/db.php';
  $db=new DB();
  $conn=$db->connect();

  /////////////////////////////////

	
	$output=[];

	if(isset($_POST['year']) && isset($_POST['month']) && isset($_POST['count']) && isset($_POST['starting_from'])){
		$year=htmlspecialchars($_POST['year']);
		$month=htmlspecialchars($_POST['month']);
		$count=htmlspecialchars($_POST['count']);
		$starting=htmlspecialchars($_POST['starting_from']);


		$checkData=$conn->query("SELECT * FROM tbl_working_days WHERE year='$year' AND month='$month'");

		if($row=$checkData->fetch_array()){

			$output['result']=false;
			$output['msg']="Year and month already exists.";

		}else{

			if($conn->query("INSERT INTO tbl_working_days VALUES(null,'$year','$month','$count','$starting')")){
				$output['result']=true;
			}else{
				$output['result']=false;
				// $output['msg']="Something went wrong please try again. ".mysqli_error($conn);
				$output['msg']="Something went wrong please try again. ";
			}


		}




 
	}else{
		$output['result']=false;
		$output['msg']="Required data not provided.";
	}



	echo json_encode($output);
