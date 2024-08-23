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
        $amount = htmlspecialchars($_POST['amount']); 
        $valid_from = htmlspecialchars($_POST['a_valid_from']);  
        $valid_to = htmlspecialchars($_POST['a_valid_to']);
        
        $sql = "INSERT INTO `tbl_rep_target_amount_wise`(`id`, `amount`, `valid_from`, `valid_to`) VALUES (null, '$amount', '$valid_from', '$valid_to')";
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