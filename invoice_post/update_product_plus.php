<?php
    require_once('../database/db.php');
    $db=new DB();
    $conn=$db->connect();
    session_start();

    $GrandTotal=0;

    $output=[];
    $dataArray = array();

    if($_POST)
    {
        $invoice_id = htmlspecialchars($_POST['invoice_id']);
        $product_id = htmlspecialchars($_POST['product_id']);
        $item_id = htmlspecialchars($_POST['item_id']);


            $checkQty = $conn->query("SELECT stock FROM tbl_item WHERE itemId='$item_id'");
            if($qRS = $checkQty->fetch_array()){
            $cqty = (int)$qRS[0];

            if($cqty >= 1){

                $ProductQuantityPlussql = "UPDATE tbl_order_item_details SET qty=qty+1 WHERE order_id='$invoice_id' AND id='$product_id'";

                if ($conn->query($ProductQuantityPlussql) === TRUE) 
                {
                    // echo 'Completed';

                    $ItemQuantitySql = "UPDATE tbl_item SET stock=stock-1 WHERE itemId='$item_id' ";
                    if ($conn->query($ItemQuantitySql) === TRUE) 
                    {


                        $getProductRecord=$conn->query("SELECT * FROM tbl_order_item_details toid INNER JOIN tbl_item tit ON toid.itemId=tit.itemId WHERE toid.order_id='$invoice_id'");
                        while($gprRs=$getProductRecord->fetch_array()){
                            $ProductOrderId=$gprRs[0];                       
                            $ProductItemId=$gprRs[1];                       
                            $ProductQty=$gprRs[3];                       
                            $ProductDiscountedPrice=$gprRs[4];                        
                            $ProductDiscountedValue=$gprRs[5];                       
                            $ProductPrice=$gprRs[6];     
                            $RPID=$gprRs[7]; 

                            //////////////////
                            $ItemId=$gprRs[8]; 
                            $ItemCode=$gprRs[9]; 
                            $ItemName=$gprRs[10]; 
                            $ItemPrice=$gprRs[11]; 


                            ////////Calculation//////////////
                            $DiscountedPrice = (double)$ItemPrice-(((double)$ItemPrice*(double)$ProductDiscountedValue)/100);
                            //With QTY
                            $ItemTotal = (double)$DiscountedPrice*(double)$ProductQty;

                            //Grand Total
                            $GrandTotal += $ItemTotal;
                            ////////Calculation//////////////


                            $row='
                                <tr>
                                    <td>'.$ItemCode.'</td>
                                    <td>'.$ItemName.'</td>
                                    <td><font class="pull-right">'.number_format($ItemPrice,2).'</font></td>
                                    <td>
                                        <font class="pull-right">
                                            '.$ProductDiscountedValue.'%'.'
                                            <button type="submit" id="btn-discount-model" class="btn btn-info btn-sm" data-toggle="modal" data-target="#DiscountModalCenter'.$ProductOrderId.'"><i class="fa fa-pencil"></i></button> 
                                        </font>
                                    </td>
                                    <td>
                                        <font class="pull-right">
                                            <div class="row" style="padding-right: 10px;">
                                                <div class="col-md-4">
                                                    <form id="Product-Minus">
                                                        <input type="hidden" value="'.$invoice_id.'" name="invoice_id">
                                                        <input type="hidden" value="'.$ProductOrderId.'" name="product_id">
                                                        <input type="hidden" value="'.$ItemId.'" name="item_id">
                                                        <button type="submit" id="btn-minus" class="btn btn-danger btn-sm">-</button> 
                                                    </form>
                                                </div>
                                                <div class="col-md-4">
                                                    '.$ProductQty.'
                                                </div>
                                                <div class="col-md-4">
                                                    <form id="Product-Plus">
                                                        <input type="hidden" value="'.$invoice_id.'" name="invoice_id">
                                                        <input type="hidden" value="'.$ProductOrderId.'" name="product_id">
                                                        <input type="hidden" value="'.$ItemId.'" name="item_id">
                                                        <button type="submit" id="btn-plus" class="btn btn-success btn-sm">+</button> 
                                                    </form>
                                                </div>
                                            </div>
                                        </font>
                                    </td>
                                    <td><font class="pull-right">'.number_format($ItemTotal,2).'</font></td>
                                </tr>
                                ';
                                            
                            array_push($dataArray,$row);
                                        
                                        
                        }
                                    
                        /////////////////
                                    
                                    
                        $output['result'] = true;
                        $output['msg'] = 'Successfully added product.';
                        $output['data'] = $dataArray;
                        $output['GrandTotal'] = number_format($GrandTotal,2);


                        //////////////////////Tbl Credid Order Update Start//////////////////////////

                        $CheckPaymentMethodCredit=$conn->query("SELECT * FROM tbl_order WHERE id='$invoice_id'");
                        if($cpmcRs=$CheckPaymentMethodCredit->fetch_array()){
                            $PaymentMethod=$cpmcRs[12];


                            if ($PaymentMethod=='2') {

                                $getCreditBalence=$conn->query("SELECT * FROM tbl_credit_orders WHERE order_id='$invoice_id'");
                                if($gcreditbRs=$getCreditBalence->fetch_array()){
                                    $FixedTotal=$gcreditbRs[2];
                                    $EditableTotal=$gcreditbRs[3];

                                    $FixedMinusEditeble=$FixedTotal-$EditableTotal;

                                    if ($FixedTotal==$EditableTotal) {
                                       
                                        $UpdateCreditTotalsql = "UPDATE tbl_credit_orders SET fixed_total='$GrandTotal', editable_total='$GrandTotal' WHERE order_id='$invoice_id' ";

                                            if ($conn->query($UpdateCreditTotalsql) === TRUE) 
                                            {
                                                // $output['result'] = true;
                                                // $output['msg'] = 'Yes 1';
                                                $output['Payment'] = number_format($GrandTotal,2);
                                            }else{
                                                $output['result']=false;
                                                $output['msg']="2222";
                                            }

                                    }else{

                                        $EditableUpdateTotal=$GrandTotal-$FixedMinusEditeble;

                                        $UpdateCreditTotalNotEqualsql = "UPDATE tbl_credit_orders SET fixed_total='$GrandTotal', editable_total='$EditableUpdateTotal' WHERE order_id='$invoice_id' ";

                                            if ($conn->query($UpdateCreditTotalNotEqualsql) === TRUE) 
                                            {  
                                                // $output['result'] = true;
                                                // $output['msg'] = 'Yes 2';
                                                $output['Payment'] = number_format($EditableUpdateTotal,2);
                                            }else{
                                                $output['result']=false;
                                                $output['msg']="1111";
                                            }

                                    }


                                }

                                
                            }else{

                                $output['result']=false;
                                $output['msg']="it's not credit invoice.";

                            }


                        }



                        //////////////////////Tbl Credid Order Update End//////////////////////////


                        




                    }else{

                        $output['result']=false;
                        $output['msg']="Error updating record 888.";

                    }



                }else{
                    $output['result']=false;
                    $output['msg']="Error updating record 999.";


                }




            }else{


                $output['result']=false;
                $output['msg']="Please add stock first.";



                }


        }



    }

    mysqli_close($conn);
    echo json_encode($output);

    ?>