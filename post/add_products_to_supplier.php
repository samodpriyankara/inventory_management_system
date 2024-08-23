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
        $supplier_id = htmlspecialchars($_POST['supplier_id']);
        $item_detail_Id = htmlspecialchars($_POST['item_detail_Id']);


        $CheckAlreadyAddedItem = "SELECT COUNT(*) FROM tbl_supplier_has_products WHERE supplier_id='$supplier_id' AND item_detail_Id='$item_detail_Id'";
        $CAAI=$conn->query($CheckAlreadyAddedItem);
        if($CAAIrow =$CAAI->fetch_array())
        {
            $AlreadyAddedCount=$CAAIrow[0];


            if($AlreadyAddedCount=='0'){
        
                $sql = "INSERT INTO `tbl_supplier_has_products`(`supplier_has_products_id`, `supplier_id`, `item_detail_Id`) VALUES (null, '$supplier_id', '$item_detail_Id')";
                if($conn->query($sql) === TRUE){

                    $output['result']=true;
                    $output['msg']="Successfully set product to supplier.";

                }else{

                    $output['result']=false;
                    $output['msg']="Error seting product.";
                }


            }else{

                $output['result']=false;
                $output['msg']="Already added product.";

            }
        }


    }

    mysqli_close($conn);
    echo json_encode($output);

    ?>