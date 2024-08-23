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
        $distributor_id = htmlspecialchars($_POST['distributor_id']);
        $note = htmlspecialchars($_POST['note']);
        $stat = 0;
        $pay = 0;
        $grand_total = '';

        $sql = "INSERT INTO `tbl_distributor_product_invoice`(`distributor_id`, `admin_id`, `note`, `stat`, `pay`, `grand_total`, `distributor_product_invoice_datetime`) VALUES (?,?,?,?,?,?,?)";
        $stmt = mysqli_prepare($conn, $sql);
        mysqli_stmt_bind_param($stmt, "sssssss", $distributor_id, $admin_id, $note, $stat, $pay, $grand_total, $currentDate);
        $result = mysqli_stmt_execute($stmt);
        if($result)
        {
            // echo 'Completed';

            $lastId=0;
            $getLast=$conn->query("SELECT distributor_invoice_id FROM tbl_distributor_product_invoice ORDER BY distributor_invoice_id DESC LIMIT 1");
            if($lRs=$getLast->fetch_array()){
              $lastId=$lRs[0];
              
              // $sendId=base64_encode($lastId);
            }

                $output['result']="ok_";
                $output['msg']='Successfully created Distributor invoice.';
                $output['j_id']=$lastId;       

        }else{
            // echo 'Error';   

            $output['result']=false;
            $output['msg']='Something went wrong (error code create invoice)';
        }


    }

    mysqli_close($conn);

    echo json_encode($output);

    ?>