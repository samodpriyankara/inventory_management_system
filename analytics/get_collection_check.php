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
    
    $datalistCollection=array();

    $ViewInvoiceAndCollectionCount = "0.' / '.0";
    
    if(isset($_POST['start_date']) && isset($_POST['end_date']) && isset($_POST['dis_id']) ){
        
        $start_date=htmlspecialchars($_POST['start_date']);
        $end_date=htmlspecialchars($_POST['end_date']);
        $dis_id=htmlspecialchars($_POST['dis_id']);
        ////////////////
       

        $output['result'] = true;


                /////////Collection History//////////
                
                $ChequeAmountTot =0.0;
                
                if($dis_id=='0'){
                    $CollectionDetailsSql = "SELECT * FROM tbl_outstanding_payments WHERE DATE(date_time) BETWEEN '$start_date' AND '$end_date'";
                }else{
                    $CollectionDetailsSql = "SELECT * FROM tbl_outstanding_payments top INNER JOIN tbl_order tor ON top.order_id=tor.id WHERE tor.distributor_id='$dis_id' AND DATE(top.date_time) BETWEEN '$start_date' AND '$end_date'";
                }
                    
                    $FullAmount = 0;
                    $CollectionDetailsRs=$conn->query($CollectionDetailsSql);
                    while($CDrow=$CollectionDetailsRs->fetch_array())
                    {
                        $CollectionId=$CDrow[0];                       
                        $OrderId=$CDrow[1];   
                        $Amount=$CDrow[2];     
                        $DateTime=$CDrow[3];   
                        $SalesUser=$CDrow[4];   
                        $AdminUser=$CDrow[5];    
                        $PaymentMethod=$CDrow[6]; 
                        
                        $FullAmount += $Amount;

                        //////

                            $OrderNumberSql = "SELECT order_id FROM tbl_order WHERE id='$OrderId' ";
                            $ONRs=$conn->query($OrderNumberSql);
                            if($ONrow=$ONRs->fetch_array())
                            {
                                $OrderNumber=$ONrow[0];
                            }
                            
                            $CheckStatus='';
                            if($PaymentMethod=='Cheque'){
                                
                                $CheckClearedSql = "SELECT is_cleared,amount FROM tbl_order_cheque_payment_details WHERE invoice_id='$OrderId' ";
                                $CCRs=$conn->query($CheckClearedSql);
                                if($CCrow=$CCRs->fetch_array())
                                {
                                    $ClearStatus=$CCrow[0];
                                    $ChequeAmount=$CCrow[1];
                                    
                                    if($ClearStatus=='0'){
                                        $CheckStatus = '(Not Realized)';
                                    }else{
                                        $CheckStatus = '(Realized)';
                                        
                                        $ChequeAmountTot += $ChequeAmount;
                                        
                                        
                                        
                                    }
                                    
                                    
                                }
                                
                            }else{
                                $CheckStatus = '';
                            }
                            
                            
                        $objCollection='
                            <tr>
                                <td>'.$OrderNumber.'</td>
                                <td>'.$PaymentMethod.' '.$CheckStatus.'</td>
                                <td>'.$DateTime.'</td>
                                <td><font style="float: right; font-weight: 700;">Rs. '.number_format($Amount,2).'</font></td>
                            </tr>
                        ';

                        array_push($datalistCollection,$objCollection);

                    }
                //////////////////////////
                
                
                //get Full Income/////////

                if($dis_id=='0'){
                    $query="SELECT id FROM tbl_order WHERE (payment_method='0' OR payment_method='1') AND DATE(invoice_date) BETWEEN '$start_date' AND '$end_date'";
                }else{
                    $query="SELECT id FROM tbl_order WHERE distributor_id = '$dis_id' AND (payment_method='0' OR payment_method='1') AND DATE(invoice_date) BETWEEN '$start_date' AND '$end_date'";
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
                
                ///Calculation///
                
                $TodayIncome = $fullIncomeTotal + $FullAmount;
                
                // $OnlyCashCollection = $FullAmount - $ChequeAmountTot;
                
                ////////////////
                
                
                //get all bill count/////////

                if($dis_id=='0'){
                    $getAllBillCount = $conn->query("SELECT COUNT(*) FROM tbl_order WHERE (payment_method='0' OR payment_method='1') AND DATE(invoice_date) BETWEEN '$start_date' AND '$end_date'");
                }else{
                    $getAllBillCount = $conn->query("SELECT COUNT(*) FROM tbl_order WHERE distributor_id = '$dis_id' AND (payment_method='0' OR payment_method='1') AND DATE(invoice_date) BETWEEN '$start_date' AND '$end_date'");
                }
        
                if($gabc = $getAllBillCount->fetch_array()){
                    
                    $allBillCount = $gabc[0];
                }
                
                /////////////////////
                
                if($dis_id=='0'){
                    $getAllCollectionCount = $conn->query("SELECT COUNT(*) FROM tbl_outstanding_payments WHERE DATE(date_time) BETWEEN '$start_date' AND '$end_date' ");
                }else{
                    $getAllCollectionCount = $conn->query("SELECT COUNT(*) FROM tbl_outstanding_payments top INNER JOIN tbl_order tor ON top.order_id=tor.id WHERE tor.distributor_id='$dis_id' AND DATE(top.date_time) BETWEEN '$start_date' AND '$end_date' ");
                }
        
                if($gacc = $getAllCollectionCount->fetch_array()){
                    
                    $allCillectionCount = $gacc[0];
                }
                
                $ViewInvoiceAndCollectionCount = $allBillCount.' / '.$allCillectionCount;
                
                
                //////////////////////
                
                
                /////////////////////
                
                if($dis_id=='0'){
                    $getCashColl = $conn->query("SELECT SUM(amount) FROM tbl_outstanding_payments WHERE payment_method='Cash' AND DATE(date_time) BETWEEN '$start_date' AND '$end_date' ");
                }else{
                    $getCashColl = $conn->query("SELECT SUM(amount) FROM tbl_outstanding_payments top INNER JOIN tbl_order tor ON top.order_id=tor.id WHERE tor.distributor_id='$dis_id' AND top.payment_method='Cash' AND DATE(top.date_time) BETWEEN '$start_date' AND '$end_date' ");
                }
        
                if($gccn = $getCashColl->fetch_array()){
                    
                    $OnlyCashCollection = $gccn[0];
                }
                
                
                //////////////////////
                
                
                //////////////////////////////////////////////////
                
                ///Calculation///
                
                $TodayIncome = $fullIncomeTotal + $OnlyCashCollection;
                
                // $OnlyCashCollection = $FullAmount - $ChequeAmountTot;
                
                ////////////////
                
                
                
                
        $output['full_income'] = $fullIncomeTotal;
        $output['collection_income'] = $OnlyCashCollection;
        $output['today_income'] = $TodayIncome;
        $output['invoice_collection_count'] = $ViewInvoiceAndCollectionCount;
        $output['cheque_realized_amount'] = $ChequeAmountTot;
        
        $output['data_collection']=$datalistCollection;
        

        
    }else{
        $output['result']=false;
        $output['msg']="Invalid request, Please try again.";
    }
    
    echo json_encode($output);
    
    
    