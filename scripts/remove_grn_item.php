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

	if(isset($_POST['grn_item_id']) && isset($_SESSION['ID'])){

		$grn_item_id = mysqli_real_escape_string($conn,$_POST['grn_item_id']);
		$user_id = $_SESSION['ID'];
	    $ip_address = get_ip_address();
	    $current_date_time = date('Y-m-d H:i:s');
	    
	    
	    
	    /////Verify grn item data//
	    
	    
	    $check_data = $conn->query("SELECT * FROM tbl_grn_items WHERE grn_items_id = '$grn_item_id'");
	    if($rs = $check_data->fetch_array()){
	        
	        
	        $grn_items_id = $rs[0];
	        $grn_detail_id = $rs[1];
	        $item_detail_id = $rs[2];
	        $cost_price = $rs[4];
	        $selling_price = $rs[5];
	        $stat = $rs[7];
	        
	        $price_batch = $rs[3];
	        $qty = $rs[6];
	        
	        
	        
	        /////REDUCE QTY////
	        
	            if( $conn->query("UPDATE tbl_item SET stock = (stock - '$qty') WHERE itemId = '$price_batch'") ){
	                
	                //REMOVE GRN ITEM///
	                if($conn->query("DELETE FROM tbl_grn_items WHERE grn_items_id = '$grn_item_id'")){
	                    
	                    ////SAVE REMOVAL HISTORY
	                    
	                    $query = "INSERT INTO tbl_grn_item_removal_history VALUES(null,'$grn_items_id','$grn_detail_id','$item_detail_id','$price_batch','$cost_price','$selling_price','$qty','$stat','$ip_address','$user_id','$current_date_time')";
	                    if($conn->query($query)){
	                        $output['result'] = true;
	                        
	                        ////CLEAR SESSION
	                        unset($_SESSION['EDT_ENABLED']);
		                    
	                    }else{
	                        $output['result'] = false;
		                    $output['msg'] = "Operation success but something went wrong, please contact administrator ( CODE_HIST_ERR )";
	                    }
	                    
	                    
	                    ///////////////////////
	                    
	                    
	                }else{
	                    $output['result'] = false;
		                $output['msg'] = "Failed to remove item from grn items list, please contact administrator.";
	                }
	                
	                ////////////////////
	                
	                
	            }else{
	              $output['result'] = false;
		          $output['msg'] = "Failed to reduce item quantity, please try again.";
	            }
	        
	        
	        ///////////////////
	        
	        
	        
	        
	        
	        
		    
		    
		    
		    
	    }else{
	        $output['result'] = false;
		    $output['msg'] = "Invalid GRN item information, please refresh the page and try again.";
	    }
	    
	    
	    
	    
	    
	    
	    
	    
	    
	    
		
    	



	}else{

		$output['result']=false;
		$output['msg']="Required fields are not provided.";


	}



	echo json_encode($output);