<?php
	
  require '../database/db.php';
  $db=new DB();
  $conn=$db->connect();

  /////////////////////////////////

	
	$output=[];
	


if(isset($_POST['name']) && isset($_POST['address']) && isset($_POST['contact']) && isset($_POST['username']) && isset($_POST['password']) && isset($_POST['dis'])){

		$date=date('Y-m-d h:i:s');
		$name=htmlspecialchars($_POST['name']);
		$address=htmlspecialchars($_POST['address']);
		$contact=htmlspecialchars($_POST['contact']);
		$username=htmlspecialchars($_POST['username']);
		$password=htmlspecialchars($_POST['password']);
		$dis=htmlspecialchars($_POST['dis']);



		$checkUserName=$conn->query("SELECT * FROM tbl_user WHERE username='$username'");
		if(mysqli_num_rows($checkUserName) > 0){

				$output['result']=false;
				$output['msg']="Username already exists.";

		}else{
			$userId=0;
			if($conn->query("INSERT INTO tbl_user VALUES(null,'$name','$username','$password','$date','$address','$contact',1,0)")){

				
				//map user to distributor

				$getUserIdQuery=$conn->query("SELECT id FROM tbl_user WHERE username='$username'");
				if($user=$getUserIdQuery->fetch_array()){
						$userId=$user[0];
				}


				if($conn->query("INSERT INTO tbl_distributor_has_tbl_user VALUES(null,'$dis','$userId','$date',1)")){

						$output['result']=true;
						$output['msg']="User registration successful.";


				}else{

						$output['result']=false;
						$output['msg']="Mapping user to distributor error please try again.";
				}


					

				/////////////////////////






				

			}else{

				$output['result']=false;
				$output['msg']="Something went wrong please try again.";

			}

		}

	}else{

		$output['result']=false;
		$output['msg']='Required fields are not provided.';

	}



	echo json_encode($output);

