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
        $order_id = htmlspecialchars($_POST['order_id']);
        $amount = htmlspecialchars($_POST['amount']);
        $user_id = htmlspecialchars($_POST['user_id']);
        
        $outstanding_payment_id = htmlspecialchars($_POST['outstanding_payment_id']);
        $payment_method = htmlspecialchars($_POST['payment_method']);
        
        $password = htmlspecialchars($_POST['password']);
        $sales_rep_id = htmlspecialchars($_POST['sales_rep_id']);
        $admin_id = htmlspecialchars($_POST['admin_id']);



        //Check Login Sucess
        $checkUserQuery=$conn->query("SELECT * FROM tbl_web_console_user_account WHERE user_id='$user_id' AND password='$password'");
        if($user=$checkUserQuery->fetch_array()){

            //Delete Outstanding History
            $DeleteOutstandingHistorySql = "DELETE FROM tbl_outstanding_payments WHERE id='$outstanding_payment_id'";
            if ($conn->query($DeleteOutstandingHistorySql) === TRUE) {
              
                //Update Need To Pay Now
                $UpdateNeedToPaySql = "UPDATE tbl_credit_orders SET editable_total=editable_total+'$amount' WHERE order_id='$order_id'";
                if ($conn->query($UpdateNeedToPaySql) === TRUE) {
                    
                    //Adding Removed Data for History
                    $AddRemovedDataHistorySql = "INSERT INTO `tbl_outstanding_payments_history_remove_data`(`id`, `web_user_id`, `order_id`, `sales_rep_id`, `admin_id`, `amount`, `remove_datetime`) VALUES (null, '$user_id', '$order_id', '$sales_rep_id', '$admin_id', '$amount', '$currentDate')";
                    if($conn->query($AddRemovedDataHistorySql) === TRUE){


                        ///////if cheque payment remove from tbl_order_cheque_payments////
                            
                        if($payment_method == "Cheque"){
                            
                            $conn->query("DELETE FROM tbl_order_cheque_payment_details WHERE payment_history_id = '$outstanding_payment_id'");
                            
                        }
                        
                        
                       
                        
                        ////////////////



                        $output['result']=true;
                        $output['msg']="Successfully Removed.";

                    }else{
                        $output['result']=false;
                        $output['msg']="Something went wrong, Please try again. (CODE COKE)";
                    }



                }else{
                    $output['result']=false;
                    $output['msg']="Something went wrong, Please try again. (CODE SPRITE)";
                }


            }else{
                $output['result']=false;
                $output['msg']="Something went wrong, Please try again. (CODE SNAKE)";
            }

            

       

        }else{
            $output['result']=false;
            $output['msg']="Invalid login settings, please try again.";
        }




    }

    mysqli_close($conn);
    echo json_encode($output);

    ?>