<?php
session_start();
  require '../database/db.php';
  $db=new DB();
  $conn=$db->connect();
/////////////////////////////////

	
	$output=[];

  



	//if(isset($_POST['p_id']) && isset($_POST['f_margin']) && isset($_POST['f_qty']) && isset($_POST['pack_size']) && isset($_POST['p_name_update'])){
	    
	if(isset($_POST['lbl_h_id']) && isset($_POST['lbl_free_margin']) && isset($_POST['lbl_free_qty']) && isset($_POST['lbl_pack_size']) && isset($_POST['lbl_name']) && 
	   isset($_POST['lbl_free_margin1']) && isset($_POST['lbl_free_qty1']) &&  isset($_POST['lbl_free_margin2']) && isset($_POST['lbl_free_qty2']) &&
	   isset($_POST['lbl_free_margin3']) && isset($_POST['lbl_free_qty3']) &&  isset($_POST['lbl_free_margin4']) && isset($_POST['lbl_free_qty4']) &&
	   isset($_POST['lbl_free_margin5']) && isset($_POST['lbl_free_qty5']) && isset($_POST['tbl_discount']) && isset($_POST['tbl_discount_margin'])){

		$p_id=mysqli_real_escape_string($conn,$_POST['lbl_h_id']);
		
		$p_name=mysqli_real_escape_string($conn,htmlspecialchars(str_replace('"', ' Inch', $_POST['lbl_name'])));
		

		$f_margin=mysqli_real_escape_string($conn,$_POST['lbl_free_margin']); 
		$f_qty=mysqli_real_escape_string($conn,$_POST['lbl_free_qty']);
		$f_margin1=mysqli_real_escape_string($conn,$_POST['lbl_free_margin1']); 
		$f_qty1=mysqli_real_escape_string($conn,$_POST['lbl_free_qty1']);
		$f_margin2=mysqli_real_escape_string($conn,$_POST['lbl_free_margin2']); 
		$f_qty2=mysqli_real_escape_string($conn,$_POST['lbl_free_qty2']);
		$f_margin3=mysqli_real_escape_string($conn,$_POST['lbl_free_margin3']); 
		$f_qty3=mysqli_real_escape_string($conn,$_POST['lbl_free_qty3']);
		$f_margin4=mysqli_real_escape_string($conn,$_POST['lbl_free_margin4']); 
		$f_qty4=mysqli_real_escape_string($conn,$_POST['lbl_free_qty4']);
		$f_margin5=mysqli_real_escape_string($conn,$_POST['lbl_free_margin5']); 
		$f_qty5=mysqli_real_escape_string($conn,$_POST['lbl_free_qty5']);
		
		$tbl_discount=mysqli_real_escape_string($conn,$_POST['tbl_discount']);
		
		$tbl_discount_margin=mysqli_real_escape_string($conn,$_POST['tbl_discount_margin']);


// 		if(isset($_POST['img_update'])){
// 			$img_update = htmlspecialchars($_POST['img_update']);
// 		}else{
// 			$img_update = "";
// 		}
		



// 		if($img_update != "" && $img_update != null){

// 			////compress image////

// 			$img_update=htmlspecialchars($_POST['img_update']);

// 			$imgName = time().".jpg";
// 			$image_location = "../product_images/".$imgName;
// 			$exp = explode(",",$img_update);
// 			$quality = 0.2;
// 			// Content type
// 			// header('Content-Type: image/jpeg');
// 			$data = base64_decode($exp[1]);
// 			$im = imagecreatefromstring($data);
// 			$width = imagesx($im);
// 			$height = imagesy($im);
// 			$newwidth = $width * $quality;
// 			$newheight = $height * $quality;
// 			$thumb = imagecreatetruecolor($newwidth, $newheight);
// 			imagecopyresized($thumb, $im, 0, 0, 0, 0, $newwidth, $newheight, $width, $height);
// 			imagejpeg($thumb, $image_location);
// 			$uploaded_image_path = "product_images/".$imgName;

			

// 			if($conn->query("UPDATE tbl_item_details SET item_img='$uploaded_image_path' WHERE item_detail_Id = '$p_id'")){

// 				$output['result']=true;
// 				$output['msg']="Successfully updated.";


// 			}else{
// 				$output['result']=false;
// 				$output['msg']="Product Image update failed, please try again.(code 4785)";
// 			}

// 			/////////////////////


// 		}
		
		if(isset($_FILES['img2']) && $_FILES['img2'] > 0){
		    $tmpName=$_FILES['img2']['tmp_name'];
            $fileName = time().".jpg";;
            
            $fileNewLocation='../product_images/'.$fileName;
            $fileNewLocationToDb='product_images/'.$fileName;
            
                if(move_uploaded_file($tmpName,$fileNewLocation)){

                    $conn->query("UPDATE tbl_item_details SET item_img='$fileNewLocationToDb' WHERE item_detail_Id = '$p_id'");

                }
		    
		    
		}




		$pack_size=htmlspecialchars($_POST['lbl_pack_size']);

		if($pack_size <= 0 || $pack_size == ''){
			$pack_size = 1;
		}



		/////update pack size///

			$conn->query("UPDATE tbl_other_item_details SET pack_size = '$pack_size' WHERE item_id = '$p_id'");


		////////////////////////
		
		////UPDATE PRODUCT NAME///
		
		$conn->query("UPDATE tbl_item_details SET itemDescription = '$p_name' WHERE item_detail_Id = '$p_id'");
		
		//////////////////////////


			$checkFree = $conn->query("SELECT * FROM tbl_free_issue_scheme WHERE item_id = '$p_id'");
			if($cRs = $checkFree->fetch_array()){


					if($conn->query("UPDATE tbl_free_issue_scheme SET margin='$f_margin',free_qty='$f_qty',margin1='$f_margin1',free_qty1='$f_qty1',
					                                                  margin2='$f_margin2',free_qty2='$f_qty2',margin3='$f_margin3',free_qty3='$f_qty3',
					                                                  margin4='$f_margin4',free_qty4='$f_qty4',margin5='$f_margin5',free_qty5='$f_qty5',
					                                                  discount='$tbl_discount',status_code = 1,discount_margin = '$tbl_discount_margin'
					                                                  WHERE item_id = '$p_id'")){

						$output['result']=true;
						$output['msg']="Successfully updated.";


					}else{
						$output['result']=false;
						$output['msg']="Free issue margin update failed, please try again.(code 4785)";
					}








			}else{

					
					if($conn->query("INSERT INTO tbl_free_issue_scheme VALUES(null,'$f_margin','$f_qty','$p_id',1,'$f_margin1','$f_margin2','$f_margin3','$f_margin4','$f_margin5',
					                                                               '$f_qty1','$f_qty2','$f_qty3','$f_qty4','$f_qty5',,'$tbl_discount',1,'$tbl_discount_margin')")){

						$output['result']=true;
						$output['msg']="Successfully updated.";


					}else{
						$output['result']=false;
						$output['msg']="Free issue margin update failed, please try again.(code 5698)";
					}






			}




	

		


	}else{

		$output['result']=false;
		$output['msg']="Required fields are not provided.";


	}



	echo json_encode($output);