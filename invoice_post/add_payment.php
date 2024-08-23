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
    $dataArray = array();

    if($_POST)
    {
        $user_id = htmlspecialchars($_POST['user_id']);
        $invoice_id = htmlspecialchars($_POST['invoice_id']);
        $payment_amount = htmlspecialchars($_POST['payment_amount']);
        
        
        $payment_method = htmlspecialchars($_POST['payment_method']);
        
        $cheque_no = htmlspecialchars($_POST['cheque_no']);
        $bank_name = htmlspecialchars($_POST['bank_name']);
        $cash_date = htmlspecialchars($_POST['date_to_cash']);
        
        

        $sales_user=0;
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
                        $output['msg']="This amount bigger than actual amount. please add correct amount and try again";

                    }else{

                        
                        $AddPaymentMethodsql = "INSERT INTO `tbl_outstanding_payments`(`id`, `order_id`, `amount`, `date_time`, `sales_user`, `admin_user`, `payment_method`) VALUES (null, '$invoice_id', '$payment_amount', '$currentDate', '$sales_user', '$user_id', '$payment_method')";
                        if ($conn->query($AddPaymentMethodsql) === TRUE) {
                            
                            $payment_history_id = mysqli_insert_id($conn);
                            
                            $NowhavetoPay = $EditableTotal - $payment_amount;
                            
                            $MinusAmountsql = "UPDATE tbl_credit_orders SET editable_total = '$NowhavetoPay' WHERE order_id='$invoice_id'";
                            if ($conn->query($MinusAmountsql) === TRUE) {

                                $CheckPaymentZeroSQL=$conn->query("SELECT * FROM tbl_credit_orders WHERE order_id='$invoice_id'");
                                if($CPZrow=$CheckPaymentZeroSQL->fetch_array()){
                                    
                                    $EditableTotalZero=$CPZrow[3]; 

                                    if ($EditableTotalZero=='0') {

                                        $PaymentStatusChange = "UPDATE tbl_order SET payment_status='1' WHERE id='$invoice_id'";
                                        
                                        if ($conn->query($PaymentStatusChange) === TRUE) {
                                            $output['result'] = true;
                                            $output['msg'] = 'Payment successfully. Full paid';

                                        }else{

                                            $output['result'] = false;
                                            $output['msg'] = 'Payment not done.';

                                        }


                                        
                                    }

                                    $output['result'] = true;
                                    $output['msg'] = 'Payment successfully. Full paid';


                                }




                            }else{

                                $output['result']=false;
                                $output['msg']="Payment Error.";

                            }

                        /////////save cheque details////////
                        
                        if($payment_method == 'Cheque'){
                            
                            
       
                            
                            $conn->query("INSERT INTO tbl_order_cheque_payment_details VALUES(null,'$cheque_no','$bank_name','$cash_date','$payment_amount',0,'$currentDate','$invoice_id','$user_id','$payment_history_id')");
                            
                            
                        }
                        
                        /////////////////////////////////////
                              


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