<?php
    require_once('../database/db.php');
    $db=new DB();
    $conn=$db->connect();
    
    date_default_timezone_set('Asia/Colombo');
    
    $currentDate=date('Y-m-d H:i:s');

    $GrandTotal=0;

    $output=[];
    




    if(isset($_POST['method']) && isset($_POST['amount']) && isset($_POST['orderKey']) && isset($_POST['user_id']))
    {
        $user_id = 0;
        $invoice_id = htmlspecialchars($_POST['orderKey']);
        $payment_amount = htmlspecialchars($_POST['amount']);
        $payment_method = htmlspecialchars($_POST['method']);

        $sales_user= htmlspecialchars($_POST['user_id']);;
        // $sql = "UPDATE tbl_order_item_details SET discounted_value='$discounted_value' WHERE id='$order_id'";

        // if ($conn->query($sql) === TRUE) {
          // echo "Record updated successfully";


                $CreditPaymentSQL=$conn->query("SELECT * FROM tbl_credit_orders WHERE order_id='$invoice_id'");
                if($CPSrow=$CreditPaymentSQL->fetch_array()){
                    $CreditOrderId=$CPSrow[0];
                    $FixedTotal=$CPSrow[2];
                    $EditableTotal=$CPSrow[3];
                    $OutletID=$CPSrow[4];
                    $RouteId=$CPSrow[5];
                    $UserId=$CPSrow[6];   



                    if ($EditableTotal<$payment_amount) {

                        $output['result']=false;
                        $output['msg']="This amount is bigger than actual amount. please add correct amount and try again";

                    }else{

                        
                        $AddPaymentMethodsql = "INSERT INTO `tbl_outstanding_payments`(`id`, `order_id`, `amount`, `date_time`, `sales_user`, `admin_user`, `payment_method`) VALUES (null, '$invoice_id', '$payment_amount', '$currentDate', '$sales_user', '$user_id', '$payment_method')";


                        $output['q'] = $AddPaymentMethodsql;



                        if ($conn->query($AddPaymentMethodsql) === TRUE) {

                            $MinusAmountsql = "UPDATE tbl_credit_orders SET editable_total=editable_total-'$payment_amount' WHERE order_id='$invoice_id'";
                            if ($conn->query($MinusAmountsql) === TRUE) {

                                $CheckPaymentZeroSQL=$conn->query("SELECT * FROM tbl_credit_orders WHERE order_id='$invoice_id'");
                                if($CPZrow=$CheckPaymentZeroSQL->fetch_array()){
                                    
                                    $EditableTotalZero=$CPZrow[3]; 

                                    if ($EditableTotalZero=='0') {

                                        $PaymentStatusChange = "UPDATE tbl_order SET payment_status='1' WHERE id='$invoice_id'";
                                        if ($conn->query($PaymentStatusChange) === TRUE) {

                                            $output['result'] = true;
                                            $output['msg'] = 'Payment successful';

                                        }else{

                                            $output['result'] = false;
                                            $output['msg'] = 'Payment not done.';

                                        }


                                        
                                    }

                                    $output['result'] = true;
                                    $output['msg'] = 'Payment successful.';


                                }




                            }else{

                                $output['result']=false;
                                $output['msg']="Payment Error.";

                            }

                        
                              


                        }else {


                            $output['result']=false;
                            $output['msg']="Payment Method Saving Error.";

                             
                        }






                }
                            
                /////////////////

                
        }



    }

    mysqli_close($conn);
    echo json_encode($output);

    ?>