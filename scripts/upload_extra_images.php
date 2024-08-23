<?php
  require '../database/db.php';
  $db=new DB();
  $conn=$db->connect();
  date_default_timezone_set('Asia/Colombo');
/////////////////////////////////

	
	$output=[];




if(isset($_POST['lbl_product_id']) && !empty($_FILES['extra_images'])){

		$product_id = mysqli_real_escape_string($conn,$_POST['lbl_product_id']);
	
		
        if(isset($_FILES['extra_images']) && $_FILES['extra_images'] > 0){
            
             /////CLEAR OLD IMAGES////
                               
               
                   
                   $get_old_extra_images = $conn->query("SELECT extra_image_path FROM tbl_item_details_extra_images WHERE item_detail_id = '$product_id'");
                   while($oirs = $get_old_extra_images->fetch_array()){
                       unlink("../".$oirs[0]);
                   }
                   
                   $conn->query("DELETE FROM tbl_item_details_extra_images WHERE item_detail_id = '$product_id'");
                   
               
               
                               
            /////////////////////////
            
            foreach ($_FILES['extra_images']['tmp_name'] as $key => $value) {


                          $tmpName=$_FILES['extra_images']['tmp_name'][$key];
                          $fileName = $product_id."_extra_".rand(0000000000,9999999999).".jpg";
                          
                          
                          $fileNewLocation='../product_extra_images/'.$fileName;
                          $fileNewLocationToDb='product_extra_images/'.$fileName;

                          

                        //   $ext = pathinfo($fileName, PATHINFO_EXTENSION);


                          


                             if(move_uploaded_file($tmpName,$fileNewLocation)){

                               $uploaded_date_time = date('Y-m-d H:i:s');
                               $conn->query("INSERT INTO tbl_item_details_extra_images VALUES(null,'$fileNewLocationToDb','$uploaded_date_time','$product_id')");

                             }


                           


                    }

                
		           $output['result']=true;
                    

                    


                }else{
                    $output['result']=false;
		            $output['msg']="Please select images you want to upload.";
                }
		
		
		

	}else{

		$output['result']=false;
		$output['msg']="Required fields are not provided.";
	}




	echo json_encode($output);