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

	if(isset($_POST['invoice_id']) && isset($_SESSION['ID'])){

		$invoice_id = mysqli_real_escape_string($conn,$_POST['invoice_id']);
		$user_id = $_SESSION['ID'];
	    $ip_address = get_ip_address();
	    $current_date_time = date('Y-m-d H:i:s');
	    
	    
	    
	    $get_invo_details = $conn->query("SELECT * FROM tbl_distributor_product_invoice WHERE distributor_invoice_id = '$invoice_id'");
	    if($irs = $get_invo_details->fetch_array()){
	        
	        $distributor_invoice_id = $irs[0];
	        $dist_id = $irs[1];
	        $admin_id = $irs[2];
	        $note = $irs[3];
	        $stat = $irs[4];
	        $pay = $irs[5];
	        $grand_total = $irs[6];
	        $distributor_product_invoice_datetime = $irs[7];
	        
	        
	        
	        
	        
	        $get_invo_items = $conn->query("SELECT * FROM tbl_distributor_product_invoice_items WHERE distributor_invoice_id = '$invoice_id'");
	        while($item_detail_rs = $get_invo_items->fetch_array()){
	            $id = $item_detail_rs[0];
	            $dist_invoice_id = $item_detail_rs[1];
	            $item_id = $item_detail_rs[2];
	            $qty = $item_detail_rs[3];
	            $cost = $item_detail_rs[4];
	            
	            
	            
	            ////restore qty/////
	            
	                if($conn->query("UPDATE tbl_item SET stock = stock + '$qty' WHERE itemId = '$item_id'")){
	                    
	                    
	                    if($conn->query("INSERT INTO tbl_removed_distributor_product_invoice_items VALUES(null,'$id','$dist_invoice_id','$item_id','$qty','$cost','$current_date_time','$ip_address')")){
	                        $conn->query("DELETE FROM tbl_distributor_product_invoice_items WHERE distributor_product_invoice_items_id = '$id'");
	                    }
	                    
	                     
	                }
	            
	            ///////////////////
	            
	            
	            
	            
	            
	            
	            
	            
	        }
	        
	        
	        if($conn->query("INSERT INTO tbl_removed_distributor_product_invoice VALUES(null,'$distributor_invoice_id','$dist_id','$admin_id','$note','$stat','$pay','$grand_total','$distributor_product_invoice_datetime','$ip_address','$current_date_time')")){
	            
	            
	            if($conn->query("DELETE FROM tbl_distributor_product_invoice WHERE distributor_invoice_id = '$invoice_id'")){
    	            
    	            ////CLEAR SESSION
	                unset($_SESSION['EDT_ENABLED']);
    	            $output['result']=true;
    		        $output['msg']="Distributor invoice has been removed.";
		        
		        
		        
    	        }else{
    	            $output['result']=false;
    		        $output['msg']="Failed to remove distributor invoice. please try again.";
    	        }
	        
	            
	            
	            
	            
	        }else{
	            $output['result']=false;
		        $output['msg']="Something went wrong while deleting, please try again or contact adminostrator.";
	        }
	        
	        
	        
	        
	        
	        
	        
	    }else{
	        $output['result']=false;
		    $output['msg']="Distributor invoice could not be verified.";
	    }
	    
	   
	    
	    
		
    	



	}else{

		$output['result']=false;
		$output['msg']="Required fields are not provided.";


	}



	echo json_encode($output);