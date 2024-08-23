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
        $admin_id = htmlspecialchars($_POST['admin_id']);
        $supplier_id = htmlspecialchars($_POST['supplier_id']);
        $note = htmlspecialchars($_POST['note']);

        $stat = 0;

        $sql = "INSERT INTO `tbl_stock_add_details`(`admin_id`, `supplier_id`, `note`, `stat`, `stock_add_details_datetime`) VALUES (?,?,?,?,?)";
        $stmt = mysqli_prepare($conn, $sql);
        mysqli_stmt_bind_param($stmt, "iisis", $admin_id, $supplier_id, $note, $stat, $currentDate);
        $result = mysqli_stmt_execute($stmt);
        if($result)
        {
            // echo 'Completed';

            $lastId=0;
            $getLast=$conn->query("SELECT stock_add_id FROM tbl_stock_add_details ORDER BY stock_add_id DESC LIMIT 1");
            if($lRs=$getLast->fetch_array()){
              $lastId=$lRs[0];
              
              // $sendId=base64_encode($lastId);
            }

                $output['result']="ok_";
                $output['msg']='Successfully created stock adding form.';
                $output['j_id']=$lastId;       

        }else{
            // echo 'Error';   

            $output['result']=false;
            $output['msg']='Something went wrong (error code SNAKE)';
        }


    }

    mysqli_close($conn);

    echo json_encode($output);

    ?>