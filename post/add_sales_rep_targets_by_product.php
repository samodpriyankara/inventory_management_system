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
        $item_id = htmlspecialchars($_POST['item_id']);
        $qty = htmlspecialchars($_POST['qty']);  
        $valid_from = htmlspecialchars($_POST['valid_from']);  
        $valid_to = htmlspecialchars($_POST['valid_to']);  
        $status = 1;  
        
        $sql = "INSERT INTO `tbl_rep_target_qty_wise`(`id`, `item_id`, `qty`, `valid_from`, `valid_to`, `status`) VALUES (null, '$item_id', '$qty', '$valid_from', '$valid_to', '$status')";
        if($conn->query($sql) === TRUE){

            $output['result']=true;
            $output['msg']="Successfully added target.";

        }else{

            $output['result']=false;
            $output['msg']="Error adding target.";
        }




    }

    mysqli_close($conn);
    echo json_encode($output);

    ?>