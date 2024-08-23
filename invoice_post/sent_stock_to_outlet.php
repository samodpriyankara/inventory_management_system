<?php
    require_once('../database/db.php');
    $db=new DB();
    $conn=$db->connect();
    session_start();
    date_default_timezone_set('Asia/Colombo');
    // $currentDate=date('Y-m-d');
    $currentDate=date('Y-m-d H:i:s');

    $output=[];

    if(isset($_POST['invo_id']))
    {
        $invo_id = htmlspecialchars($_POST['invo_id']);

        $sql = "UPDATE tbl_order_delivery SET delivery_status='1' WHERE order_id='$invo_id'";

        if ($conn->query($sql) === TRUE) {
        
            $output['result'] = true;
            $output['msg'] = 'Stock sent to outlet.';


        } else {

            $output['result']=false;
            $output['msg']="Error Stock sent to outlet.";
        }




    }else{
        $output['result']=false;
            $output['msg']="No fields.";
    }

    mysqli_close($conn);
    echo json_encode($output);

    ?>