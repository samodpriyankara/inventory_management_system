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
        $stock_add_id = htmlspecialchars($_POST['stock_add_id']);
        $item_id = htmlspecialchars($_POST['item_id']);
        $pack = htmlspecialchars($_POST['pack']);
        $item_detail_id = htmlspecialchars($_POST['item_detail_id']);



        //Get Pack Data
        $getItemsPackSize = "SELECT * FROM tbl_other_item_details WHERE item_id='$item_detail_id'";
        $GIPSRs=$conn->query($getItemsPackSize);
        if($GIPSrow =$GIPSRs->fetch_array())
        {
            $PackSize=(double)$GIPSrow[1];

            //Get Quantity 
            $qty = $pack * $PackSize;


                //Stock Item Count
                $getStockItemQuery=$conn->query("SELECT COUNT(*) FROM tbl_stock_add_items WHERE stock_add_id='$stock_add_id' AND item_id='$item_id'");
                if ($GSIQ=$getStockItemQuery->fetch_array()) {
                    $StockItemCount=$GSIQ[0];
                
                    if($StockItemCount=='0'){ 

                        $sql = "INSERT INTO `tbl_stock_add_items`(`stock_add_id`, `item_id`, `qty`) VALUES (?,?,?)";
                        $stmt = mysqli_prepare($conn, $sql);
                        mysqli_stmt_bind_param($stmt, "sss", $stock_add_id, $item_id, $qty);
                        $result = mysqli_stmt_execute($stmt);
                        if($result)
                        {
                            $output['result']="ok_";
                            $output['msg']='Successfully added items.';

                        }else{

                            $output['result']=false;
                            $output['msg']='Something went wrong (error code adding items)';
                        }

                    }else{

                        $sql = "UPDATE tbl_stock_add_items SET qty = qty + $qty WHERE stock_add_id='$stock_add_id' AND item_id='$item_id'";
                        if($conn->query($sql) === TRUE){

                            $output['result']="ok_";
                            $output['msg']='Successfully added item.';

                        }else{
                            $output['result']=false;
                            $output['msg']='Something went wrong (error code adding item)';
                        }

                    }

                }

                

                
            }


    }



    mysqli_close($conn);
    echo json_encode($output);

    ?>