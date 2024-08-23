<?php
    require_once('../database/db.php');
    $db=new DB();
    $conn=$db->connect();
    session_start();
    date_default_timezone_set('Asia/Colombo');
    // $currentDate=date('Y-m-d');
    $currentDate=date('Y-m-d H:i:s');

    $output=[];

    if(isset($_POST['invo_id_del']))
    {
        $invo_id_del = htmlspecialchars($_POST['invo_id_del']);

        $sql = "UPDATE tbl_order_delivery SET delivery_status='2' WHERE order_id='$invo_id_del'";

        if ($conn->query($sql) === TRUE) {
        
            $output['result'] = true;
            $output['msg'] = 'Stock delivered.';


        } else {

            $output['result']=false;
            $output['msg']="Error Stock delivered to outlet.";
        }


    }else{
        $output['result']=false;
        $output['msg']="No fields delivered.";
    }

    mysqli_close($conn);
    echo json_encode($output);

    ?>