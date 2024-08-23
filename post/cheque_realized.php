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
        $cheque_id = htmlspecialchars($_POST['cheque_id']);
        $cheque_realized_date = htmlspecialchars($_POST['cheque_realized_date']);
        $status = 1;  
        
        $AddCheckRealizedDataSql = "INSERT INTO `tbl_order_cheque_payment_realized_date`(`id`, `cheque_id`, `cheque_realized_date`, `cheque_date`) VALUES (null, '$cheque_id', '$cheque_realized_date', '$currentDate')";
        if($conn->query($AddCheckRealizedDataSql) === TRUE){


            $UpdateStatusSql = "UPDATE tbl_order_cheque_payment_details SET is_cleared='$status' WHERE id='$cheque_id' ";

            if($conn->query($UpdateStatusSql) === TRUE){

                $output['result']=true;
                $output['msg']="Successfully cheque realized.";

            }else{

                $output['result']=false;
                $output['msg']="Error realizing cheque.";

            }


        }else{

            $output['result']=false;
            $output['msg']="Error realizing cheque.";
        }




    }

    mysqli_close($conn);
    echo json_encode($output);

    ?>