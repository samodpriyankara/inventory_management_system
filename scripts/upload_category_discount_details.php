<?php
  require '../database/db.php';
  $db=new DB();
  $conn=$db->connect();
/////////////////////////////////

	
	$output=[];


	if(isset($_POST['category']) && isset($_POST['discount'])){

		$category=mysqli_real_escape_string($conn,htmlspecialchars($_POST['category']));
		$discount=mysqli_real_escape_string($conn,htmlspecialchars($_POST['discount']));
		$currentDateTime=date('Y-m-d h:i:s');
		
		$check = $conn->query("SELECT * FROM tbl_discount_for_each_category WHERE category_id = '$category'");
		if($crs = $check->fetch_array()){
		    //UPDATE
		    if($conn->query("UPDATE tbl_discount_for_each_category SET discount_percentage = '$discount',added_date = '$currentDateTime' WHERE category_id = '$category'")){
		        $output['result']=true;
			    $output['msg']="Discount has been updated successfully..";
		    }else{
		        $output['result']=false;
			    $output['msg']="Failed to update discounts, please try again.";
		    }
		    
		}else{
		    //INSERT 
		    if($conn->query("INSERT INTO tbl_discount_for_each_category VALUES(null,'$discount','$category','$currentDateTime')")){
		        $output['result']=true;
			    $output['msg']="Discount has been added successfully..";
		    }else{
		        $output['result']=false;
			    $output['msg']="Failed to add discounts, please try again.";
		    }
		}
		
        


			

	}else{

			$output['result']=false;
			$output['msg']="Required fields are not provided.";

	}

	echo json_encode($output);