  <?php
    require_once('../database/db.php');
    $db=new DB();
    $conn=$db->connect();
    session_start();
    $output=[];
    date_default_timezone_set('Asia/Colombo');
    $currentDate=date('Y-m-d');
    // $currentDate=date('Y-m-d H:i:s');


    $output=[]; 

    $cashInvoiceCount = array();
    $creditInvoiceCount = array();

    if(isset($_POST['payment_summery_outlet_id']))
    {
        $payment_summery_outlet_id = htmlspecialchars($_POST['payment_summery_outlet_id']);

        //Cash Invoice
        $query="SELECT COUNT(*) FROM tbl_order WHERE outlet_id='$payment_summery_outlet_id' AND payment_method='0'";
        $GetCashInvoicesql=$conn->query($query);
        while ($GCIrow=$GetCashInvoicesql->fetch_array()) {

          $CashInvoice=$GCIrow[0];

          array_push($cashInvoiceCount,$CashInvoice);

        }

        //Credit Invoice
        $query="SELECT COUNT(*) FROM tbl_order WHERE outlet_id='$payment_summery_outlet_id' AND payment_method='1'";
        $GetCreditInvoicesql=$conn->query($query);
        while ($GCRIrow=$GetCreditInvoicesql->fetch_array()) {

          $CreditInvoice=$GCRIrow[0];

          array_push($creditInvoiceCount,$CreditInvoice);


        }


      
    }


    $output['result']=true;
    // $output['data']=$datalist;
    

    $output['cashInvoiceCount'] = $cashInvoiceCount;
    $output['creditInvoiceCount'] = $creditInvoiceCount;



    echo json_encode($output);
    
    
    