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
        $user_id = htmlspecialchars($_POST['user_id']);
        $subject = htmlspecialchars($_POST['subject']);
        $msg = htmlspecialchars($_POST['msg']);    
        
        $status =1;


        $sql = "INSERT INTO `tbl_office_msgs`(`msg_id`, `subject`, `msg`, `added_date_time`, `status`, `user_id`) VALUES (null, '$subject', '$msg', '$currentDate', '$status', '$user_id')";

       

        if ($conn->query($sql) === TRUE) {
          // echo "New record created ";

            $output['result']=true;
            $output['msg']="Successfully sent message.";

        } else {
          // echo "Error: " . $sql . "<br>" . $conn->error;

            $output['result']=false;
            $output['msg']="Error Message Sending.";
        }




    }

    mysqli_close($conn);
    echo json_encode($output);

    ?>