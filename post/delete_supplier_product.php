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
        $supplier_has_products_id = htmlspecialchars($_POST['supplier_has_products_id']);
        $item_detail_Id = htmlspecialchars($_POST['item_detail_Id']);  
        
        $sql = "DELETE FROM `tbl_supplier_has_products` WHERE item_detail_Id='$item_detail_Id' AND supplier_has_products_id='$supplier_has_products_id'";
        if($conn->query($sql) === TRUE){

            $output['result']=true;
            $output['msg']="Successfully remove product.";

        }else{

            $output['result']=false;
            $output['msg']="Error removing product.";
        }




    }

    mysqli_close($conn);
    echo json_encode($output);

    ?>