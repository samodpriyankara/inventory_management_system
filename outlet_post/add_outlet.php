<?php
    require_once('../database/db.php');
    $db=new DB();
    $conn=$db->connect();
    session_start();
    date_default_timezone_set('Asia/Colombo');
    // $currentDate=date('Y-m-d');
    $currentDate=date('Y-m-d H:i:s');


    $output=[];

    if($_POST)
    {
        $outlet_name = htmlspecialchars($_POST['outlet_name']);
        $owner_name = htmlspecialchars($_POST['owner_name']);
        $contact_number = htmlspecialchars($_POST['contact_number']);    
        $address = htmlspecialchars($_POST['address']);    
        $category = htmlspecialchars($_POST['category']);
        $route_id = htmlspecialchars($_POST['route_id']);
        $latitude = htmlspecialchars($_POST['latitude']);
        $longitude = htmlspecialchars($_POST['longitude']);
        // $img_save = htmlspecialchars($_POST['img_save']);
        
        $outlet_type =0;
        $outlet_discount = 0;
        $last_order_value = 0;
        $current_month_purchase = 0;
        $avarage_purchases = 0;
        $outstanding = 0;
        $sequence = 0;
        $grade = 0;




        // if(isset($_POST['img_save'])){
        //     $img_save = htmlspecialchars($_POST['img_save']);
        //     //Explode
        //     $Imgcontents = explode(',',$img_save);
        //     $ImgcontentsDB = $Imgcontents[1];
        //     //Explode
        // }else{
        //     $ImgcontentsDB = '';
        // }

        $outlet_image_path = "";
        if(isset($_FILES['outlet_image'])){


                    


                          $tmpName=$_FILES['outlet_image']['tmp_name'];
                          $fileName = rand(111111,999999)."_".$_FILES['outlet_image']['name'];

                          $fileNewLocation='../outlet_images/'.$fileName;
                          $fileNewLocationToDb='outlet_images/'.$fileName;

                          $ext = pathinfo($fileName, PATHINFO_EXTENSION);


                           if($_FILES['outlet_image'] > 0){

                                if(move_uploaded_file($tmpName,$fileNewLocation)){

                                    $outlet_image_path = $fileNewLocationToDb;

                                }else{

                                    $outlet_image_path = "";

                                }
                             


                           }


                    


                }








   $result = $conn->query("INSERT INTO `tbl_outlet`(outlet_id,`outlet_name`, `owner_name`, `contact`, `address`, `lat`, `lon`, `image`, `outlet_type`, `outlet_discount`, `last_order_value`, `current_month_purchase`, `avarage_purchases`, `outstanding`, `category`, `sequence`, `grade`, `created_date`, `route_id`) VALUES (null,'$outlet_name', '$owner_name', '$contact_number', '$address', '$latitude', '$longitude', '$outlet_image_path', '$outlet_type', '$outlet_discount', '$last_order_value', '$current_month_purchase', '$avarage_purchases', '$outstanding', '$category', '$sequence', '$grade', '$currentDate', '$route_id')");



	//$output['oq']= "INSERT INTO `tbl_outlet`(outlet_id,`outlet_name`, `owner_name`, `contact`, `address`, `lat`, `lon`, `image`, `outlet_type`, `outlet_discount`, `last_order_value`, `current_month_purchase`, `avarage_purchases`, `outstanding`, `category`, `sequence`, `grade`, `created_date`, `route_id`) VALUES (null,'$outlet_name', '$owner_name', '$contact_number', '$address', '$latitude', '$longitude','$ImgcontentsDB', '$outlet_type', '$outlet_discount', '$last_order_value', '$current_month_purchase', '$avarage_purchases', '$outstanding', '$category', '$sequence', '$grade', '$currentDate', '$route_id')";

        if($result)
        {
            // echo 'Completed';

            $lastId=0;
        
            $getLast=$conn->query("SELECT outlet_id FROM tbl_outlet ORDER BY outlet_id DESC LIMIT 1");
            if($lRs=$getLast->fetch_array()){

              $lastId=$lRs[0];
              $encodelastId=base64_encode($lastId);


              ///////////UPLOAD DOCUMENTS IF AVAILABLE////////////


                if(isset($_FILES['docs'])){


                    foreach ($_FILES['docs']['tmp_name'] as $key => $value) {


                          $tmpName=$_FILES['docs']['tmp_name'][$key];
                          $fileName = $lastId."_".$_FILES['docs']['name'][$key];

                          $fileNewLocation='../outlet_documents/'.$fileName;
                          $fileNewLocationToDb='outlet_documents/'.$fileName;

                          $ext = pathinfo($fileName, PATHINFO_EXTENSION);


                           if($_FILES['docs'] > 0){


                             if(move_uploaded_file($tmpName,$fileNewLocation)){

                                $conn->query("INSERT INTO tbl_outlet_documents VALUES(null,'$fileNewLocationToDb','$currentDate',1,'$lastId')");

                             }


                           }


                    }


                }



            ////////////////////////////////////////////////////






              $output['result']=true;
              $output['msg']="Outlet add successfully.";

              $output['lastId']=$encodelastId;

          
            }else{

                $output['result']=true;
                $output['msg']="Outlet adding successfully Please Go to outlet List.";

            }


            









        }else{  
            // echo 'Error';   
            $output['result']=false;
            $output['msg']="Error adding outlet.";
        }


    }

    mysqli_close($conn);
    echo json_encode($output);

    ?>
