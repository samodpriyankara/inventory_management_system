<?php
    require_once('../database/db.php');
    $db=new DB();
    $conn=$db->connect();
    session_start();
    date_default_timezone_set('Asia/Colombo');
    // $currentDate=date('Y-m-d');
    $currentDate=date('Y-m-d H:i:s');

    // $GrandTotal=0;

    $output=[];

    if(isset($_POST['stock_add_id']))
    {
        $stock_add_id = htmlspecialchars($_POST['stock_add_id']);


            $StockAddingToMainStockSql = "SELECT * FROM tbl_stock_add_items WHERE stock_add_id='$stock_add_id'";
            $SATMSrs=$conn->query($StockAddingToMainStockSql);
            while($SATMSrow=$SATMSrs->fetch_array())
            {  
                $StockAddItemId=$SATMSrow[0];
                $ItemId=$SATMSrow[2];
                $ProductQty=$SATMSrow[3];

                    $UpdateMainStockSql = "UPDATE tbl_item SET stock = stock + $ProductQty WHERE itemId='$ItemId'";
                    if($conn->query($UpdateMainStockSql) === TRUE){
                            
                        $output['result'] = true;
                        $output['msg'] = 'Product Added Complete To Main Stock.';
                        
                    }else{
                        $output['result']=false;
                        $output['msg']="Error Code Updating.";
                    }


            }


            $UpdateAddingStockStatusSql = "UPDATE tbl_stock_add_details SET stat = '1', stock_add_details_datetime='$currentDate' WHERE stock_add_id='$stock_add_id'";
            if($conn->query($UpdateAddingStockStatusSql) === TRUE){
                $output['result'] = true;
                $output['msg'] = 'Successfully saved.';
            }else{
                $output['result']=false;
                $output['msg']="Error saving, Please try again.";
            }

        

    }else{
        $output['result']=false;
        $output['msg']="No fields.";
    }

    mysqli_close($conn);
    echo json_encode($output);

    ?>