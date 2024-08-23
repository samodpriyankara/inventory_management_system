<?php
  require '../database/db.php';
  $db=new DB();
  $conn=$db->connect();
  date_default_timezone_set('Asia/Colombo');
/////////////////////////////////

	
	$output=[];


	// if(isset($_POST['p_code']) && isset($_POST['p_name']) && isset($_POST['p_category']) && isset($_POST['p_stock']) && isset($_POST['p_price']) && isset($_POST['p_dis']) && isset($_POST['p_sup']) && isset($_POST['p_brand']) && isset($_POST['p_image']) && isset($_POST['b_price'])){


function upload_extra_images($image_source,$product_id,$conn){
    
    if(isset($_FILES[$image_source])){


                    


                          $tmpName=$_FILES[$image_source]['tmp_name'];
                          //$fileName = rand(1111111111,9999999999)."_".$_FILES[$image_source]['name'];
                        //   $fileName = rand(1111111111,9999999999).".jpg";
                          
                          $fileName = $product_id."_extra_".rand(0000000000,9999999999).".jpg";
                          

                          $fileNewLocation='../product_extra_images/'.$fileName;
                          $fileNewLocationToDb='product_extra_images/'.$fileName;

                          //$ext = pathinfo($fileName, PATHINFO_EXTENSION);


                           if($_FILES['img_input_save_1'] > 0){

                                if(move_uploaded_file($tmpName,$fileNewLocation)){

                                    $uploaded_image_path = $fileNewLocationToDb;
                                    $uploaded_date_time = date('Y-m-d H:i:s');
                                    $conn->query("INSERT INTO tbl_item_details_extra_images VALUES(null,'$uploaded_image_path','$uploaded_date_time','$product_id')");
                                    

                                }
                             


                           }


                    


                }
    
    
    
    
    
    
    
}

// function upload_image($image,$product_id,$conn){

// ////compress image////

// 							$imgName = rand(111111,999999)."_".time().".jpg";
// 							$image_location = "../product_extra_images/".$imgName;
// 							$exp = explode(",",$image);
// 							$quality = 0.2;
// 							// Content type
// 						    // header('Content-Type: image/jpeg');
// 							$data = base64_decode($exp[1]);
// 						    $im = imagecreatefromstring($data);
// 						    $width = imagesx($im);
// 						    $height = imagesy($im);
// 						    $newwidth = $width * $quality;
// 						    $newheight = $height * $quality;
// 							$thumb = imagecreatetruecolor($newwidth, $newheight);
// 							imagecopyresized($thumb, $im, 0, 0, 0, 0, $newwidth, $newheight, $width, $height);
// 							imagejpeg($thumb, $image_location);
// 							$uploaded_image_path = "product_extra_images/".$imgName;
// 							$uploaded_date_time = date('Y-m-d H:i:s');


// 							$conn->query("INSERT INTO tbl_item_details_extra_images VALUES(null,'$uploaded_image_path','$uploaded_date_time','$product_id')");

// 					/////////////////////



// }



if(isset($_POST['p_code']) && isset($_POST['p_name']) && isset($_POST['p_category']) && isset($_POST['p_sup']) && isset($_POST['p_brand']) && isset($_POST['pack_size'])){



		$pCode=mysqli_real_escape_string($conn, $_POST['p_code']);
		$pName=mysqli_real_escape_string($conn, str_replace('"', ' Inch', $_POST['p_name']));
		$pCategory=mysqli_real_escape_string($conn, $_POST['p_category']);
		

		// $pStock=htmlspecialchars($_POST['p_stock']);
		// $pPrice=htmlspecialchars($_POST['p_price']);
		// $bPrice=htmlspecialchars($_POST['b_price']);
		


//  		$pDis=htmlspecialchars($_POST['p_dis']);
		$pSup=mysqli_real_escape_string($conn, $_POST['p_sup']);
		$pBrand=mysqli_real_escape_string($conn, $_POST['p_brand']);

		$pack_size=mysqli_real_escape_string($conn, $_POST['pack_size']);
		if($pack_size < 0 || $pack_size == ''){
			$pack_size = 0;
		}


// 		$pImg=htmlspecialchars($_POST['p_image']);
// 		$pImg2=htmlspecialchars($_POST['p_image_2']);
// 		$pImg3=htmlspecialchars($_POST['p_image_3']);
// 		$pImg4=htmlspecialchars($_POST['p_image_4']);
// 		$pImg5=htmlspecialchars($_POST['p_image_5']);
		



// 		$uploaded_image_path = "";
//         if($pImg == ""){
// 			$uploaded_image_path = null;
// 		}else{
			
// 			////compress image////

// 							$imgName = time().".jpg";
// 							$image_location = "../product_images/".$imgName;
// 							$exp = explode(",",$pImg);
// 							$quality = 0.2;
// 							// Content type
// 						    // header('Content-Type: image/jpeg');
// 							$data = base64_decode($exp[1]);
// 						    $im = imagecreatefromstring($data);
// 						    $width = imagesx($im);
// 						    $height = imagesy($im);
// 						    $newwidth = $width * $quality;
// 						    $newheight = $height * $quality;
// 							$thumb = imagecreatetruecolor($newwidth, $newheight);
// 							imagecopyresized($thumb, $im, 0, 0, 0, 0, $newwidth, $newheight, $width, $height);
// 							imagejpeg($thumb, $image_location);
// 							$uploaded_image_path = "product_images/".$imgName;

// 			/////////////////////
// 		}
		
		
		//////////UPLOAD COVER IMAGE/////////
		
		
		
		$cover_image_path = "";
        if(isset($_FILES['img_input_save_1'])){


                    


                          $tmpName=$_FILES['img_input_save_1']['tmp_name'];
                          //$fileName = rand(1111111111,9999999999)."_".$_FILES['img_input_save_1']['name'];
                          $fileName = rand(1111111111,9999999999).".jpg";

                          $fileNewLocation='../product_images/'.$fileName;
                          $fileNewLocationToDb='product_images/'.$fileName;

                          //$ext = pathinfo($fileName, PATHINFO_EXTENSION);


                           if($_FILES['img_input_save_1'] > 0){

                                if(move_uploaded_file($tmpName,$fileNewLocation)){

                                    $cover_image_path = $fileNewLocationToDb;

                                }else{

                                    $cover_image_path = "";

                                }
                             


                           }


                    


                }
		
		
		////////////////////////////////////
		
		
		
		
		


		$genaricName=$pCode.'-'.$pName;
		$categoryId=0;

		//get category data if present get the id if not save category and get id

			$checkCategoryExists=$conn->query("SELECT tc.category_id FROM tbl_category tc WHERE tc.name='$pCategory'");
			if($cat=$checkCategoryExists->fetch_array()){
				$categoryId=$cat[0];
			}else{

				if($conn->query("INSERT INTO tbl_category VALUES(null,'$pCategory')")){
					$getCategoryIdQuery=$conn->query("SELECT tc.category_id FROM tbl_category tc WHERE tc.name='$pCategory'");
					if($cat=$getCategoryIdQuery->fetch_array()){
						$categoryId=$cat[0];
					}
				}

			}


		/////////////
// $saveProduct=$conn->query("INSERT INTO tbl_item VALUES(null,'$pCode','$pName','$pPrice',0,'$pStock',0,'$genaricName','$bPrice',1,0,1,1000,'$pBrand','$categoryId','$pDis','$pSup','$pImg')");

// $saveProduct=$conn->query("INSERT INTO tbl_item_details VALUES(null,'$pCode','$pName',0,0,'$genaricName',1,0,1,1000,'$pBrand','$categoryId','$pDis','$pSup','$cover_image_path')");
$saveProduct=$conn->query("INSERT INTO tbl_item_details VALUES(null,'$pCode','$pName',0,0,'$genaricName',1,0,1,1000,'$pBrand','$categoryId',0,'$pSup','$cover_image_path')");


			if($saveProduct){


				$last_product_id = mysqli_insert_id($conn);


				/////SAVE EXTRA IMAGES/////
				
				upload_extra_images('img_input_save_2',$last_product_id,$conn);
				upload_extra_images('img_input_save_3',$last_product_id,$conn);
				upload_extra_images('img_input_save_4',$last_product_id,$conn);
				upload_extra_images('img_input_save_5',$last_product_id,$conn);

				// if($pImg2 != ""){
				// 	upload_image($pImg2,$last_product_id,$conn);
				// }


				// if($pImg3 != ""){
				// 	upload_image($pImg3,$last_product_id,$conn);
				// }

				// if($pImg4 != ""){
				// 	upload_image($pImg4,$last_product_id,$conn);
				// }

				// if($pImg5 != ""){
				// 	upload_image($pImg5,$last_product_id,$conn);
				// }





				///////////////////////////






					$checkIfCategoryAndSupplierMapped=$conn->query("SELECT * FROM supplier_has_category WHERE category_id='$categoryId' AND supplier_id='$pSup'");

					if($check=$checkIfCategoryAndSupplierMapped->fetch_array()){

						//already mapped

					}else{

						if($conn->query("INSERT INTO supplier_has_category VALUES(null,'$categoryId','$pSup')")){
								//mapped
						}

					}



					$conn->query("INSERT INTO tbl_free_issue_scheme VALUES(null,0,0,'$last_product_id',1)");


					////pack size////
					$conn->query("INSERT INTO tbl_other_item_details VALUES(null,'$pack_size','$last_product_id')");








				$output['result']=true;
				$output['msg']="Product registration successful";
			}else{

				$output['result']=false;
				$output['msg']="Something went wrong please try again.";
			}





		


	}else{

		$output['result']=false;
		$output['msg']="Required fields are not provided.";
	}




	echo json_encode($output);