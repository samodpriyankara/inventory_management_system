<?php

	require '../database/db.php';
    $db=new DB();
    $conn=$db->connect();
    session_start();
    $output=[];
    date_default_timezone_set('Asia/Colombo');
    // $currentDate=date('Y-m-d');
    //$today=date('Y-m-d');


    $is_distributor = false;
    if(isset($_SESSION['DISTRIBUTOR'])){
      $is_distributor = $_SESSION['DISTRIBUTOR'];
    }

    if(isset($_SESSION['ID'])){
      $user_id = $_SESSION['ID'];
    }
    
    $datalist=array();
    $datalistCheque=array();

    
    
    if(isset($_POST['start_date']) && isset($_POST['end_date'])){
        
        $start_date=htmlspecialchars($_POST['start_date']);
        $end_date=htmlspecialchars($_POST['end_date']);
        $fullIncome = 0.0;
        $creditIncome = 0.0;
        $grandIncome = 0.0;
        $grandTotal = 0.0;
        ////////////////
       
        $allBillCount = 0;



        ///////////////////
        $allChequeCount = 0;
        $allChequeCollectAmount = 0;
        $allChequeRealizedAmount = 0;

        $output['result'] = true;


        //get Full Income/////////

        if($is_distributor){
            $query="SELECT id FROM tbl_order WHERE distributor_id = '$user_id' AND (payment_method='0' OR payment_method='1') AND DATE(invoice_date) BETWEEN '$start_date' AND '$end_date'";
        }else{
            $query="SELECT id FROM tbl_order WHERE (payment_method='0' OR payment_method='1') AND DATE(invoice_date) BETWEEN '$start_date' AND '$end_date'";
        }

        $fullIncomeTotal = 0;
        $fullCostTotal = 0;

        $GetCashInvoicesql=$conn->query($query);
        while ($GCIrow=$GetCashInvoicesql->fetch_array()) {
            $CashInvoiceId=$GCIrow[0];

            $fullIncome=0;
            $fullcost=0;
            $getGrandTotalQuary = $conn->query("SELECT * FROM tbl_order_item_details toid INNER JOIN tbl_item tit ON toid.itemId=tit.itemId WHERE toid.order_id='$CashInvoiceId'");
            while($GGTrs = $getGrandTotalQuary->fetch_array()){
                $ProductOrderId=$GGTrs[0];                       
                $ProductItemId=$GGTrs[1];                       
                $ProductQty=$GGTrs[3];                       
                $ProductDiscountedPrice=$GGTrs[4];                        
                $ProductDiscountedValue=$GGTrs[5];                       
                $ProductPrice=$GGTrs[6];     
                $RPID=$GGTrs[7]; 

                //////////////////
                $ItemCode=$GGTrs[9]; 
                $ItemName=$GGTrs[10]; 
                $ItemPrice=$GGTrs[11];
                
                $ItemCost=$GGTrs[16];

                ////////Calculation//////////////
                $DiscountedPrice = (double)$ItemPrice-(((double)$ItemPrice*(double)$ProductDiscountedValue)/100);
                //With QTY
                $ItemTotal = (double)$DiscountedPrice*(double)$ProductQty;

                //Grand Total
                $fullIncome += $ItemTotal;
                ////////Calculation//////////////
                
                //Calvulation Cost///
                $ItemCostTotal = (double)$ItemCost*(double)$ProductQty;
                $fullcost += $ItemCostTotal;
                /////////////////////
                
                
                
            }

            $fullIncomeTotal += $fullIncome;
            $fullCostTotal += $fullcost;


        }
        //////////////////////////////////////////////////


        //get Delivery Income/////////

        if($is_distributor){

            $CrdeitInvoicequery="SELECT id FROM tbl_order WHERE distributor_id = '$user_id' AND payment_method='2' AND DATE(invoice_date) BETWEEN '$start_date' AND '$end_date'";

        }else{

            $CrdeitInvoicequery="SELECT id FROM tbl_order WHERE payment_method='2' AND DATE(invoice_date) BETWEEN '$start_date' AND '$end_date'";

        }

        $creditIncomeTotal=0;
        $creditCostTotal=0;
        
        $GetCrdeitInvoicesql=$conn->query($CrdeitInvoicequery);
        while ($GCRIrow=$GetCrdeitInvoicesql->fetch_array()) {
            $CreditInvoiceId=$GCRIrow[0];

            $creditIncome=0;
            $creditcost=0;
            $getGrandCreditTotalQuary = $conn->query("SELECT * FROM tbl_order_item_details toid INNER JOIN tbl_item tit ON toid.itemId=tit.itemId WHERE toid.order_id='$CreditInvoiceId'");
            while($GGCTrs = $getGrandCreditTotalQuary->fetch_array()){
                                  
                $ProductCreditQty=$GGCTrs[3];                            
                $ProductCreditDiscountedValue=$GGCTrs[5];     
                ////////////////// 
                $ItemCreditPrice=$GGCTrs[11];
                
                $ItemCreditCost=$GGCTrs[16];

                ////////Calculation//////////////
                $DiscountedCreditPrice = (double)$ItemCreditPrice-(((double)$ItemCreditPrice*(double)$ProductCreditDiscountedValue)/100);
                //With QTY
                $ItemCreditTotal = (double)$DiscountedCreditPrice*(double)$ProductCreditQty;

                //Grand Total
                $creditIncome += $ItemCreditTotal;
                ////////Calculation//////////////
                
                //Calvulation Cost///
                $ItemCostCreditTotal = (double)$ItemCreditCost*(double)$ProductCreditQty;
                $creditcost += $ItemCostCreditTotal;
                /////////////////////
                
                
            }

            $creditIncomeTotal += $creditIncome;
            $creditCostTotal += $creditcost;

        }

        ////////////////////////////////////////

        //get Full Income/////////
        $grandIncome=0;
        $grandIncome=$fullIncomeTotal+$creditIncomeTotal;
        ////////////////////////
        
        //get Full Cost/////////
        $grandcost=0;
        $grandcost=$fullCostTotal+$creditCostTotal;
        ////////////////////////
        
        
        
        
        

        //get all bill count/////////

        if($is_distributor){
            $getAllBillCount = $conn->query("SELECT COUNT(*) FROM tbl_order WHERE distributor_id = '$user_id' AND DATE(invoice_date) BETWEEN '$start_date' AND '$end_date'");
        }else{
            $getAllBillCount = $conn->query("SELECT COUNT(*) FROM tbl_order WHERE DATE(invoice_date) BETWEEN '$start_date' AND '$end_date'");
        }

        if($gabc = $getAllBillCount->fetch_array()){
            
            $allBillCount = $gabc[0];
        }
        //////////////////////
        
        //get Retuern value////
        
        if($is_distributor){
            $ReturnInvoicesql = "SELECT * FROM tbl_return_order tor INNER JOIN tbl_route tro ON tor.route_id=tro.route_id WHERE tor.distributor_id = '$user_id' AND DATE(tor.invoice_date) BETWEEN '$start_date' AND '$end_date' ";
        }else{
            $ReturnInvoicesql = "SELECT * FROM tbl_return_order WHERE DATE(invoice_date) BETWEEN '$start_date' AND '$end_date' ";
        }
        $ReturnGrandTotal=0;
        $ReturnInvoicesqlQuery=$conn->query($ReturnInvoicesql);
        while($RIrow = $ReturnInvoicesqlQuery->fetch_array()){
            
            $ReturnId=$RIrow[0];  
            
            
            $ReturnItemTotal=0;
            $getReturnTotalQuary = $conn->query("SELECT * FROM tbl_return_order_item_details troid INNER JOIN tbl_item tit ON troid.itemId=tit.itemId WHERE troid.order_id='$ReturnId'");
            while($ROTrs = $getReturnTotalQuary->fetch_array()){
                   
                $ReturnProductQty=$ROTrs[3];                       
                $ReturnProductDiscountedPrice=$ROTrs[4];                        
                $ReturnProductDiscountedValue=$ROTrs[5];                       


                //////////////////
                $ReturnItemPrice=$ROTrs[6];

                ////////Calculation//////////////
                $ReturnDiscountedPrice = (double)$ReturnItemPrice-(((double)$ReturnItemPrice*(double)$ReturnProductDiscountedValue)/100);
                //With QTY//
                $ReturnItemTotal = (double)$ReturnDiscountedPrice*(double)$ReturnProductQty;

                
            }
            
            //Grand Total
            $ReturnGrandTotal += $ReturnItemTotal;
            ////////Calculation//////////////
            
            
        }
        //////////////////////
        
        //Real Income ///
        $NetIncomeView=$grandIncome-$grandcost-$ReturnGrandTotal;
        /////////////////
        
        
        $obj='
                <tr>
                    <td style="display: none;">1</td>
                    <td>Cash Sales</td>
                    <td></td>
                    <td style="text-align: right;"><font style="font-weight: 800;">'.number_format($fullIncomeTotal,2).'</font></td>
                </tr>
                
                <tr>
                    <td style="display: none;">2</td>
                    <td>Credit Sales</td>
                    <td></td>
                    <td style="text-align: right;"><font style="font-weight: 800;">'.number_format($creditIncomeTotal,2).'</font></td>
                </tr>
                
                <tr>
                    <td style="display: none;">3</td>
                    <td><font style="font-weight: 800; font-size: 20px; color: #FF0000;">Total Sales</font></td>
                    <td></td>
                    <td style="text-align: right;"><font style="font-weight: 800; font-size: 20px; color: #FF0000;">'.number_format($grandIncome,2).'</font></td>
                </tr>
                
                
                <tr>
                    <td style="display: none;">4</td>
                    <td>Cost of Sales</td>
                    <td></td>
                    <td style="text-align: right;"><font style="font-weight: 800;">'.number_format($grandcost,2).'</font></td>
                </tr>
                
                <tr>
                    <td style="display: none;">4</td>
                    <td>Returns</td>
                    <td></td>
                    <td style="text-align: right;"><font style="font-weight: 800;">'.number_format($ReturnGrandTotal,2).'</font></td>
                </tr>
                
                
                <tr>
                    <td style="display: none;">5</td>
                    <td><font style="font-weight: 800; font-size: 20px; color: #03AC13;">Gross Income</font></td>
                    <td></td>
                    <td style="text-align: right;"><font style="font-weight: 800; font-size: 20px; color: #03AC13;">'.number_format($NetIncomeView,2).'</font></td>
                </tr>
                
              ';
    
              array_push($datalist,$obj);
        
        



              //Cheque Data




                //get all Cheque count/////////

                    if($is_distributor){
                        $getAllChequeCount = $conn->query("SELECT COUNT(*) FROM tbl_order_cheque_payment_details WHERE DATE(added_date) BETWEEN '$start_date' AND '$end_date'");
                    }else{
                        $getAllChequeCount = $conn->query("SELECT COUNT(*) FROM tbl_order_cheque_payment_details WHERE DATE(added_date) BETWEEN '$start_date' AND '$end_date'");
                    }

                    if($gacc = $getAllChequeCount->fetch_array()){
                        
                        $allChequeCount = $gacc[0];
                    }
                //////////////////////


                //get Cheque Collect Amount/////////

                if($is_distributor){
                    $getChequeCollectAmount = $conn->query("SELECT SUM(amount) FROM tbl_order_cheque_payment_details WHERE is_cleared = '0' AND DATE(added_date) BETWEEN '$start_date' AND '$end_date'");
                }else{
                    $getChequeCollectAmount = $conn->query("SELECT SUM(amount) FROM tbl_order_cheque_payment_details WHERE is_cleared = '0' AND DATE(added_date) BETWEEN '$start_date' AND '$end_date'");
                }

                if($gcca = $getChequeCollectAmount->fetch_array()){
                    
                    $allChequeCollectAmount = $gcca[0];
                }
                //////////////////////


                //get Cheque Realized Amount/////////

                if($is_distributor){
                    $getChequeCollectAmount = $conn->query("SELECT SUM(amount) FROM tbl_order_cheque_payment_details tocpd INNER JOIN tbl_order_cheque_payment_realized_date tocprd ON tocpd.id=tocprd.cheque_id WHERE tocpd.is_cleared = '1' AND DATE(tocprd.cheque_realized_date) BETWEEN '$start_date' AND '$end_date'");
                }else{
                    $getChequeCollectAmount = $conn->query("SELECT SUM(amount) FROM tbl_order_cheque_payment_details tocpd INNER JOIN tbl_order_cheque_payment_realized_date tocprd ON tocpd.id=tocprd.cheque_id WHERE tocpd.is_cleared = '1' AND DATE(tocprd.cheque_realized_date) BETWEEN '$start_date' AND '$end_date'");
                }

                if($gcca = $getChequeCollectAmount->fetch_array()){
                    
                    $allChequeRealizedAmount = $gcca[0];
                }
                //////////////////////



                /////////Cheque History//////////
                if($is_distributor){
                    $ChequePaymentDetailsSql = "SELECT * FROM tbl_order_cheque_payment_details tocpd INNER JOIN tbl_order tor ON tocpd.invoice_id=tor.id AND DATE(tocpd.added_date) BETWEEN '$start_date' AND '$end_date'";
                }else{
                    $ChequePaymentDetailsSql = "SELECT * FROM tbl_order_cheque_payment_details tocpd INNER JOIN tbl_order tor ON tocpd.invoice_id=tor.id AND DATE(tocpd.added_date) BETWEEN '$start_date' AND '$end_date'";
                }

                    $ChequePaymentDetailsRs=$conn->query($ChequePaymentDetailsSql);
                    while($CPDrow=$ChequePaymentDetailsRs->fetch_array())
                    {
                        $ChequeId=$CPDrow[0];                       
                        $ChequeNumber=$CPDrow[1];   
                        $ChequeBank=$CPDrow[2];     
                        $ChequeDateToCash=$CPDrow[3];   
                        $ChequeAmount=$CPDrow[4];   
                        $ChequeIsCleared=$CPDrow[5];    
                        $ChequeAddedDate=$CPDrow[6];    
                        $ChequeInvoiceId=$CPDrow[7];    
                        $ChequeAddedUserId=$CPDrow[8];    
                        $ChequePaymentHistoryId=$CPDrow[9];  

                        //////
                        $InvoiceNumber=$CPDrow[11];
                        $OutletId=$CPDrow[25];
                        
                            $OutletDetailsSql = "SELECT * FROM tbl_outlet WHERE outlet_id='$OutletId' ";
                            $OutletDetailsRs=$conn->query($OutletDetailsSql);
                            if($ODrow=$OutletDetailsRs->fetch_array())
                            {
                                $OutletName=$ODrow[1];     
                            }

                            $RealizedSql = "SELECT * FROM tbl_order_cheque_payment_realized_date WHERE cheque_id='$ChequeId' ";
                            $RRs=$conn->query($RealizedSql);
                            if($RRrow=$RRs->fetch_array())
                            {
                                $ChequeRealizedDate=$RRrow[2];
                            }


                            $CheckStatus='';
                            $ChequeColor='';
                            if($ChequeIsCleared=='0'){
                                $CheckStatus='-';
                                $ChequeColor='#4a4a4a';
                            }else{
                                $CheckStatus=$ChequeRealizedDate;
                                $ChequeColor='#26580F';
                            }


                        $objCheque='

                            <tr style="color: '.$ChequeColor.';">
                                <td>'. $InvoiceNumber.'</td>
                                <td>'. $OutletName.'</td>
                                <td>'.$ChequeNumber.'</td>
                                <td>'.$ChequeBank.'</td>
                                <td>'.$ChequeDateToCash.'</td>
                                <td>'.$ChequeAddedDate.'</td>
                                <td>'.$CheckStatus.'</td>
                                <td><font style="float: right; font-weight: 700;">Rs. '.number_format($ChequeAmount,2).'</font></td>
                            </tr>


                        ';

                        array_push($datalistCheque,$objCheque);



                    }
                //////////////////////////

        
        

        $output['data']=$datalist;
        $output['data_cheque']=$datalistCheque;
        
        $output['full_income'] = $fullIncomeTotal;
        $output['credit_income'] = $creditIncomeTotal;
        $output['grand_income'] = $grandIncome;

        //////////////////////////////////////////////////

        $output['all_bill_count'] = $allBillCount; 



        //////////////////////////////////////////
        $output['all_cheque_count'] = $allChequeCount; 
        $output['all_cheque_collect_amount'] = $allChequeCollectAmount; 
        $output['all_cheque_realized_amount'] = $allChequeRealizedAmount; 
        
    }else{
        $output['result']=false;
        $output['msg']="Invalid request, Please try again.";
    }
    
    echo json_encode($output);
    
    
    