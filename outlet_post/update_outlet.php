<?php
    require_once('../database/db.php');
    $db=new DB();
    $conn=$db->connect();
    session_start();
    date_default_timezone_set('Asia/Colombo');
    $currentDate=date('Y-m-d H:i:s');


    $output=[];
    
    if(isset($_POST['update-outlet-id']) && $_POST['update-outlet-id'] != "" && isset($_POST['update-shop-name']) && $_POST['update-shop-name'] != ""){
        
        $outlet_id = base64_decode(mysqli_real_escape_string($conn,$_POST['update-outlet-id']));
        $outlet_name = mysqli_real_escape_string($conn,$_POST['update-shop-name']);
        
        
        if($conn->query("UPDATE tbl_outlet SET outlet_name = '$outlet_name' WHERE outlet_id = '$outlet_id'")){
            $output['result'] = true;
        }else{
            $output['result'] = false;
            $output['msg'] = "Failed to update outlet name, please try again.";
        }
        
        
        
        
    }else{
        $output['result'] = false;
        $output['msg'] = "Please fill all the required fields and try again.";
    }
    


    mysqli_close($conn);
    echo json_encode($output);

    ?>
