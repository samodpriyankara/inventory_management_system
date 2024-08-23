<?php
session_start();
  require '../database/db.php';
  $db=new DB();
  $conn=$db->connect();
  date_default_timezone_set('Asia/Colombo');
  $currentDate=date('Y-m-d h:i:s');

    function getIp()
	{
	    $ip = $_SERVER['REMOTE_ADDR'];

	    if (empty($ip) && !empty($_SERVER['HTTP_CLIENT_IP'])) {
	        $ip = $_SERVER['HTTP_CLIENT_IP'];
	    } elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
	        // omit private IP addresses which a proxy forwarded
	        $tmpIp = $_SERVER['HTTP_X_FORWARDED_FOR'];
	        $tmpIp = filter_var(
	            $tmpIp,
	            FILTER_VALIDATE_IP,
	            FILTER_FLAG_IPV4 | FILTER_FLAG_NO_PRIV_RANGE | FILTER_FLAG_NO_RES_RANGE
	        );
	        if ($tmpIp != false) {
	            $ip = $tmpIp;
	        }
	    }
	    return $ip;
	}

	
/////////////////////////////////

	
	$output=[];

	if(isset($_POST['un']) && isset($_POST['pw']) && isset($_POST['login_type'])){

		$username=htmlspecialchars($_POST['un']);
		$password=htmlspecialchars($_POST['pw']);
		$LoggedIp = getIp();

		$login_type=htmlspecialchars($_POST['login_type']);//0 = admin | 1 = distributor

		if($login_type == 0){


			$checkUserQuery=$conn->query("SELECT * FROM tbl_web_console_user_account WHERE username='$username' AND password='$password'");
		if($user=$checkUserQuery->fetch_array()){

				$activeStatus=$user[4];
				
				if($activeStatus==0){

						$output['result']=false;
						$output['msg']="Your account has been De-activated please contact system administrator.";

				}else{
						$_SESSION['user']= $user['username'];
						$_SESSION['ID']=$user[0];
						$_SESSION['ACCOUNT_TYPE']=$user[7]; //0=super admin 1=distributor 2=accountant etc 
						
						$output['result']=true;
						$output['msg']="";

						$AdminId=$user[0];
						$LoginDataAdminSql = "INSERT INTO tbl_web_console_user_account_login_history (id, admin_id, distributor_id, loged_ip, logged_datetime) VALUES (null, '$AdminId', 0, '$LoggedIp', '$currentDate')";
						if($conn->query($LoginDataAdminSql) === TRUE){
						  
						}else{
						  $output['msg']="Login Data not collected.";
						}



				}









		}else{

			$output['result']=false;
			$output['msg']="Administrator Username or password is incorrect.";

		}



		}else if($login_type == 1){




		$checkUserQuery=$conn->query("SELECT * FROM tbl_distributor_account WHERE username='$username'");
		if($user=$checkUserQuery->fetch_array()){

			$hashed_password = $user[2];

			if(password_verify($password, $hashed_password)){

				$activeStatus=$user[4];
				
				if($activeStatus==0){

						$output['result']=false;
						$output['msg']="Your account has been De-activated please contact system administrator.";

				}else{
						$_SESSION['user']= $user['username'];
						$_SESSION['ID']=$user[5];

						$_SESSION['DISTRIBUTOR'] = true;
						$_SESSION['ACCOUNT_TYPE']=1;
						
						$output['result']=true;
						$output['msg']="";

						$DistributorId=$user[5];
						$LoginDataAdminSql = "INSERT INTO tbl_web_console_user_account_login_history (id, admin_id, distributor_id, loged_ip, logged_datetime) VALUES (null, 0, $DistributorId, '$LoggedIp', '$currentDate')";
						if($conn->query($LoginDataAdminSql) === TRUE){
						  
						}else{
						  $output['msg']="Login Data not colected.";
						}


				}



			}else{
				$output['result']=false;
				$output['msg']="Invalid distributor password, please try again.";
			}



				








		}else{

			$output['result']=false;
			$output['msg']="Invalid distributor account, please try again.";

		}





		}else{
			$output['result']=false;
			$output['msg']="Invalid login settings, please try again.";
		}





		






	}else{

		$output['result']=false;
		$output['msg']="Required fields are not provided.";


	}



	echo json_encode($output);