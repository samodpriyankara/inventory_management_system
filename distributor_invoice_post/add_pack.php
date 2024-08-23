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
        $distributor_id = htmlspecialchars($_POST['distributor_id']);
        $cost_price = htmlspecialchars($_POST['cost_price']);
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


            //Check Available Count
            $CheckAvailableStock=$conn->query("SELECT stock FROM tbl_item WHERE itemId='$item_id'");
            if ($CASRs=$CheckAvailableStock->fetch_array()) {
                $CASRow=$CASRs[0];

                if($CASRow >= $qty){

                    //Distributor Invoice Item Count
                    $getDistributorInvoiceItemQuery=$conn->query("SELECT COUNT(*) FROM tbl_distributor_product_invoice_items WHERE distributor_invoice_id='$distributor_invoice_id' AND item_id='$item_id'");
                    if ($GgdiiQ=$getDistributorInvoiceItemQuery->fetch_array()) {
                        $DistributorInvoiceItemCount=$GgdiiQ[0];
                
                        if($DistributorInvoiceItemCount=='0'){ 

                            $sql = "INSERT INTO `tbl_distributor_product_invoice_items`(`distributor_invoice_id`, `item_id`, `qty`, `cost_price`) VALUES (?,?,?,?)";
                            $stmt = mysqli_prepare($conn, $sql);
                            mysqli_stmt_bind_param($stmt, "ssss", $distributor_invoice_id, $item_id, $qty, $cost_price);
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

                            $sql = "UPDATE tbl_distributor_product_invoice_items SET qty = qty + $qty WHERE distributor_invoice_id='$distributor_invoice_id' AND item_id='$item_id'";
                            if($conn->query($sql) === TRUE){

                                $output['result']="ok_";
                                $output['msg']='Successfully added item.';

                            }else{
                                $output['result']=false;
                                $output['msg']='Something went wrong (error code adding item)';
                            }

                        }

                    }

                }else{

                    $output['result']=false;
                    $output['msg']='Please add stock first.';

                }

                
            }






        }




    }

    mysqli_close($conn);
    echo json_encode($output);

    ?>