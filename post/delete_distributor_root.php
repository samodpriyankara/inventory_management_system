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
        $distributor_id = htmlspecialchars($_POST['distributor_id']);
        $route_id = htmlspecialchars($_POST['route_id']);  
        
        $sql = "DELETE FROM `tbl_distributor_has_route` WHERE route_id='$route_id' AND distributor_id='$distributor_id'";
        if($conn->query($sql) === TRUE){

            $output['result']=true;
            $output['msg']="Successfully remove route.";

        }else{

            $output['result']=false;
            $output['msg']="Error removing route.";
        }




    }

    mysqli_close($conn);
    echo json_encode($output);

    ?>