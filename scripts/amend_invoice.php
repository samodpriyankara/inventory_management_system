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

	if(isset($_POST['invoice']) && isset($_POST['value']) && isset($_POST['option'])){

		$order=mysqli_real_escape_string($conn,htmlspecialchars($_POST['invoice']));
		$value=mysqli_real_escape_string($conn,htmlspecialchars($_POST['value']));
		$option=mysqli_real_escape_string($conn,htmlspecialchars($_POST['option']));
		$user_id = $_SESSION['ID'];
		$ip_address = get_ip_address();
		
		
		$verify_order = $conn->query("SELECT * FROM tbl_order WHERE id = '$order'");
		if($vrs = $verify_order->fetch_array()){
		    
		    $increase_value = 0;
		    $decrease_value = 0;
		    $FixedTotal=0;
			$EditableTotal=0;


			$getCredit=$conn->query("SELECT * FROM tbl_credit_orders WHERE order_id='$order'");
			if($gcreditbRs=$getCredit->fetch_array()){
				$FixedTotal=$gcreditbRs[2];
				$EditableTotal=$gcreditbRs[3];

			}else{
				$output['result']=false;
				$output['msg']="Failed to verify the order, please check and try again.";
			}
		    
		    if($option == "increase_"){
		        $increase_value = $value;
		        $EditableUpdateTotal = $EditableTotal + $increase_value;

				$UpdateCreditTotalsql = "UPDATE tbl_credit_orders SET fixed_total='$FixedTotal', editable_total='$EditableUpdateTotal' WHERE order_id='$order' ";

					if ($conn->query($UpdateCreditTotalsql) === TRUE) 
					{
						$output['result'] = true;
						$output['msg'] = 'Yes 1';
						
					}else{
						$output['result']=false;
						$output['msg']="2222";
					}
		    }else{
		        $decrease_value = $value;
		        $EditableUpdateTotal = $EditableTotal - $decrease_value;

				$UpdateCreditTotalsql = "UPDATE tbl_credit_orders SET fixed_total='$FixedTotal', editable_total='$EditableUpdateTotal' WHERE order_id='$order' ";

					if ($conn->query($UpdateCreditTotalsql) === TRUE) 
					{
						$output['result'] = true;
						$output['msg'] = 'Yes 1';
						
					}else{
						$output['result']=false;
						$output['msg']="2222";
					}
		    }
		    
		    
		    $check_amendment = $conn->query("SELECT * FROM tbl_order_amendments WHERE order_id = '$order'");
		    if($ca = $check_amendment->fetch_array()){
		        /////UPDATE////
		        
		        if($conn->query("DELETE FROM tbl_order_amendments WHERE order_id = '$order'")){
		            if($conn->query("INSERT INTO tbl_order_amendments VALUES(null,'$increase_value','$decrease_value','$ip_address','$currentDate','$user_id','$order')")){
		                $output['result']=true;
		                $output['msg']="Successfully updated.";
        		    }else{
        		        $output['result']=false;
        		        $output['msg']="Failed to update amendment, please try again.";
        		    }
		        }else{
		            $output['result']=false;
        		    $output['msg']="Failed to clear the amendment, please try again.";
		        }
		        
		    }else{
		        ////INSERT////
		        
		        if($conn->query("INSERT INTO tbl_order_amendments VALUES(null,'$increase_value','$decrease_value','$ip_address','$currentDate','$user_id','$order')")){
		            $output['result']=true;
		            $output['msg']="Successfully amended.";
    		    }else{
    		        $output['result']=false;
    		        $output['msg']="Failed to amend, please try again.";
    		    }
		        
		        
		    }
		    
		    
		    
		}else{
		    $output['result']=false;
		    $output['msg']="Failed to verify the order, please check and try again.";
		}
	
    	
        


	}else{

		$output['result']=false;
		$output['msg']="Required fields are not provided.";


	}



	echo json_encode($output);