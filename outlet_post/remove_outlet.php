<?php
    require_once('../database/db.php');
    $db=new DB();
    $conn=$db->connect();
    session_start();


    $output=[];
    $dataArray = array();
    $GRNItemTotalCost=0;

    if($_POST)
    {
        $outlet_id = htmlspecialchars($_POST['outlet_id']);

        $InvoiceCount=0;
        $getInvoiceCount=$conn->query("SELECT COUNT(*) FROM tbl_order WHERE outlet_id='$outlet_id'");
        if($gicRs=$getInvoiceCount->fetch_array()){
            $InvoiceCount=$gicRs[0];



            if($InvoiceCount>0){
                $output['result'] = false;
                $output['msg'] = 'This Can not Remove, this outlet has Invoices.';
            }else{
                $RemoveOutletSql = "DELETE FROM tbl_outlet WHERE outlet_id='$outlet_id'";
                if($conn->query($RemoveOutletSql) === TRUE){
                    $output['result'] = true;
                    $output['msg'] = 'This outlet remove successfully';
                }else{
                    $output['result'] = false;
                    $output['msg'] = 'Error removing outlet. please try again (ERROR CODE SPRITE)';
                }
            }

              
        }

        


    }

    $conn->close();
    echo json_encode($output);

    ?>