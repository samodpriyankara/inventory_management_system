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
        $distributor_invoice_id = htmlspecialchars($_POST['distributor_invoice_id']);
        $item_id = htmlspecialchars($_POST['item_id']);

            $sql = "DELETE FROM `tbl_distributor_product_invoice_items` WHERE distributor_invoice_id='$distributor_invoice_id' AND item_id='$item_id'";
            if($conn->query($sql) === TRUE){

                $output['result']="ok_";
                $output['msg']='Successfully clear item.';

            }else{
                $output['result']=false;
                $output['msg']='Something went wrong (error code clear item)';
            }


    }

    mysqli_close($conn);
    echo json_encode($output);

    ?>