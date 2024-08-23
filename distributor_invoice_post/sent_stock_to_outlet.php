<?php
    require_once('../database/db.php');
    $db=new DB();
    $conn=$db->connect();
    session_start();
    date_default_timezone_set('Asia/Colombo');
    // $currentDate=date('Y-m-d');
    $currentDate=date('Y-m-d H:i:s');

    $GrandTotal=0;

    $output=[];

    if(isset($_POST['dis_invo_id']))
    {
        $dis_invo_id = htmlspecialchars($_POST['dis_invo_id']);


        $getDistributorProductInvoiceDetails = "SELECT * FROM tbl_distributor_product_invoice WHERE distributor_invoice_id='$dis_invo_id'";
        $GDPDRs=$conn->query($getDistributorProductInvoiceDetails);
        if($GDPDrow =$GDPDRs->fetch_array())
        {
            $DistributorId=$GDPDrow[1];

            $DistributorInvoiceProductsql = "SELECT * FROM tbl_distributor_product_invoice_items WHERE distributor_invoice_id='$dis_invo_id'";
            $DisInvoiceProductrs=$conn->query($DistributorInvoiceProductsql);
            while($DIProw=$DisInvoiceProductrs->fetch_array())
            {  
                $DistributorProductId=$DIProw[0];
                $ItemId=$DIProw[2];
                $ProductQty=$DIProw[3];
                $CostPrice=$DIProw[4];

                $ItemTotal = (double)$CostPrice*(double)$ProductQty;
                $GrandTotal += $ItemTotal;


                $CheckAlreadyAddedItem = "SELECT COUNT(*) FROM tbl_distributor_has_products WHERE distributor_id='$DistributorId' AND item_id='$ItemId'";
                $CAAI=$conn->query($CheckAlreadyAddedItem);
                if($CAAIrow =$CAAI->fetch_array())
                {
                    $AlreadyAddedCount=$CAAIrow[0];


                    if($AlreadyAddedCount=='0'){

                        $AddDistributorToItemSql = "INSERT INTO `tbl_distributor_has_products`(`id`, `item_id`, `distributor_id`, `qty`, `cost_price`, `assigned_date`) VALUES (null, '$ItemId', '$DistributorId', '$ProductQty', '$CostPrice', '$currentDate')";
                        if($conn->query($AddDistributorToItemSql) === TRUE){
                            


                            $UpdateMainStockSql = "UPDATE tbl_item SET stock = stock - $ProductQty WHERE itemId='$ItemId'";
                            if($conn->query($UpdateMainStockSql) === TRUE){
                                $output['result'] = true;
                                $output['msg'] = 'Product Update Complete And Minus From Main Stock.';
                            }else{
                                $output['result']=false;
                                $output['msg']="Error Code Updating main stock.";
                            }



                        }else{
                            $output['result']=false;
                            $output['msg']="Error Code Adding.";
                        }


                    }else{

                        $UpdateDistributorStockSql = "UPDATE tbl_distributor_has_products SET qty = qty + $ProductQty WHERE distributor_id='$DistributorId' AND item_id='$ItemId'";
                        if($conn->query($UpdateDistributorStockSql) === TRUE){
                            

                            $UpdateMainStockSql = "UPDATE tbl_item SET stock = stock - $ProductQty WHERE itemId='$ItemId'";
                            if($conn->query($UpdateMainStockSql) === TRUE){
                                $output['result'] = true;
                                $output['msg'] = 'Product Update Complete 2 And Minus From Main Stock.';
                            }else{
                                $output['result']=false;
                                $output['msg']="Error Code Updating main stock 3.";
                            }



                        }else{
                            $output['result']=false;
                            $output['msg']="Error Code Updating.";
                        }

                    }



                    


                }




            }

            ////////Grand Total//////////////
                
            /////////////////////////////////


            $UpdateDistributorInvoiceStatusSql = "UPDATE tbl_distributor_product_invoice SET stat = '1', grand_total='$GrandTotal' WHERE distributor_invoice_id='$dis_invo_id'";
            if($conn->query($UpdateDistributorInvoiceStatusSql) === TRUE){
                $output['result'] = true;
                $output['msg'] = 'Successfully saved.';
            }else{
                $output['result']=false;
                $output['msg']="Error saving, Please try again.";
            }

        }

    }else{
        $output['result']=false;
        $output['msg']="No fields.";
    }

    mysqli_close($conn);
    echo json_encode($output);

    ?>