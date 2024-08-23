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
        $f_name = htmlspecialchars($_POST['f_name']);
        $l_name = htmlspecialchars($_POST['l_name']);  
        $username = htmlspecialchars($_POST['username']);  
        $password = htmlspecialchars($_POST['password']);  
        $stat = htmlspecialchars($_POST['stat']);  

        $name = $f_name.' '.$l_name;
        // $hashed_password = password_hash($password, PASSWORD_DEFAULT);
        
        $sql = "INSERT INTO `tbl_web_console_user_account`(`user_id`, `username`, `password`, `created_date`, `active_status`, `stat`, `name`, `dist_id`) VALUES (null, '$username', '$password', '$currentDate', 1, '$stat', '$name', 0)";
        if($conn->query($sql) === TRUE){

            $output['result']=true;
            $output['msg']="Successfully created account.";

        }else{

            $output['result']=false;
            $output['msg']="Error creating account, Please try again!";
        }




    }

    mysqli_close($conn);
    echo json_encode($output);

    ?>