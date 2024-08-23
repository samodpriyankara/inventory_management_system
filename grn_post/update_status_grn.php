<?php
    require_once('../database/db.php');
    $db=new DB();
    $conn=$db->connect();
    session_start();
    date_default_timezone_set('Asia/Colombo');
    $currentDate=date('Y-m-d H:i:s');


    $output=[];

    if($_POST)
    {
        $grn_detail_id = htmlspecialchars($_POST['grn_detail_id']);
        $stat = 1;

        $sql = "UPDATE tbl_grn_details SET stat='$stat' WHERE grn_detail_id='$grn_detail_id' ";

        if ($conn->query($sql) === TRUE) {
            
            $output['result']="ok_";
            $output['msg']='Successfully Finalize GRN.';

        } else {
            $output['result']=false;
            $output['msg']='Something went wrong (error code Finalize)';
        }
           


    }

    mysqli_close($conn);

    echo json_encode($output);

    ?>