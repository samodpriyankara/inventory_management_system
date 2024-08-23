<?php
session_start();
  require '../database/db.php';
  $db=new DB();
  $conn=$db->connect();
  date_default_timezone_set('Asia/Colombo');
  $currentDate=date('Y-m-d h:i:s');

    function get_ip_address()
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

	if(isset($_POST['auth-pwd']) && isset($_SESSION['ID'])){

		$pwd=mysqli_real_escape_string($conn,$_POST['auth-pwd']);
		$check_auth = $conn->query("SELECT * FROM tbl_editings_auth ORDER BY id DESC LIMIT 1");
		
		if($rs = $check_auth->fetch_array()){
		    $status = $rs[2];
		    
		    
		    if($status == 0){
		        
		        $output['result']=false;
		        $output['msg']="This action has been disabled, please contact the administrator.";
		        
		    }else{
		        
		        
		        $hash = $rs[1];
		        if( password_verify($pwd,$hash) ){
		            
		            $output['result'] = true;
		            $_SESSION['EDT_ENABLED'] = true;
		            
		            
		            
		        }else{
		            $output['result'] = false;
		            $output['msg'] = "Authentication failed, please try again.";
		        }
		        
		        
		        
		    }
		    
		    
		    
		}else{
		    $output['result']=false;
		    $output['msg']="Editing may have been disabled. Please contact the administrator.";
		}
		
		
		
		
		
		
		
		
    	



	}else{

		$output['result']=false;
		$output['msg']="Required fields are not provided.";


	}



	echo json_encode($output);