  <?php
    require '../database/db.php';
    $db=new DB();
    $conn=$db->connect();
    session_start();
    $output=[];
    date_default_timezone_set('Asia/Colombo');
    // $currentDate=date('Y-m-d');
    //$today=date('Y-m-d');

    

    $output=[]; 
    $datalist=array();

    if(isset($_POST['shop_debtor_reports_start_date']) && isset($_POST['shop_debtor_reports_end_date']) && isset($_POST['shop_debtor_reports_route_id']) ){

        $shop_debtor_reports_start_date=htmlspecialchars($_POST['shop_debtor_reports_start_date']);
        $shop_debtor_reports_end_date=htmlspecialchars($_POST['shop_debtor_reports_end_date']);
        $shop_debtor_reports_route_id=htmlspecialchars($_POST['shop_debtor_reports_route_id']);

        //Distributor Debtor
        if($shop_debtor_reports_route_id=='0'){

            
            $getDistributorQuary = $conn->query("SELECT * FROM tbl_outlet");
            while($GDrs = $getDistributorQuary->fetch_array()){

                $OutletId=$GDrs[0];      
                $OutletName=$GDrs[1];

                $GrandTotal=0;
                $getCOrderQuary = $conn->query("SELECT * FROM tbl_order WHERE outlet_id='$OutletId' AND payment_method='2' AND DATE(invoice_date) BETWEEN '$shop_debtor_reports_start_date' AND '$shop_debtor_reports_end_date'");
                while($GCOrs = $getCOrderQuary->fetch_array()){
                    $Id=$GCOrs[0];
                    
                    
                    $getGrandTotalQuary = $conn->query("SELECT * FROM tbl_order_item_details toid INNER JOIN tbl_item tit ON toid.itemId=tit.itemId WHERE toid.order_id='$Id'");
                    while($GGTrs = $getGrandTotalQuary->fetch_array()){
                        $ProductOrderId=$GGTrs[0];                       
                        $ProductItemId=$GGTrs[1];                       
                        $ProductQty=$GGTrs[3];                       
                        $ProductDiscountedPrice=$GGTrs[4];                        
                        $ProductDiscountedValue=$GGTrs[5];                       
                        // $ProductPrice=$GGTrs[6];     
                        $RPID=$GGTrs[7]; 

                        //////////////////
                        $ItemCode=$GGTrs[9]; 
                        $ItemName=$GGTrs[10]; 
                        $ItemPrice=$GGTrs[6];

                        ////////Calculation//////////////
                        $DiscountedPrice = (double)$ItemPrice-(((double)$ItemPrice*(double)$ProductDiscountedValue)/100);
                        //With QTY
                        $ItemTotal = (double)$DiscountedPrice*(double)$ProductQty;

                        //Grand Total
                        $GrandTotal += $ItemTotal;
                        ////////Calculation//////////////
                    }
                    
                }
                
                $OutstandingSumSQL="SELECT SUM(`editable_total`) FROM `tbl_credit_orders` WHERE outlet_id='$OutletId'";
                $OutstandingSumResult = mysqli_query($conn, $OutstandingSumSQL);
                $OutstandingSumTotal = mysqli_fetch_assoc($OutstandingSumResult)['SUM(`editable_total`)'];

                $TdColor='';
                if($GrandTotal==$OutstandingSumTotal){
                    $TdColor='#459b45';
                }else{
                    $TdColor='#FF0000';
                }

                $obj=' 
                    <tr>
                        <td>'.$OutletName.'</td> 
                        <td class="text-right"><b style="color: '.$TdColor.';">'.number_format((double)$GrandTotal,2).'</b></td>
                        <td class="text-right"><b style="color: '.$TdColor.';">'.number_format((double)$OutstandingSumTotal,2).'</b></td>
                    </tr>

                  ';

                array_push($datalist,$obj);

            }

        //route Debtor
        }else{

            $getDistributorQuary = $conn->query("SELECT * FROM tbl_outlet WHERE route_id='$shop_debtor_reports_route_id' ");
            while($GDrs = $getDistributorQuary->fetch_array()){

                $OutletId=$GDrs[0];      
                $OutletName=$GDrs[1];

                $GrandTotal=0;
                $getCOrderQuary = $conn->query("SELECT * FROM tbl_order WHERE outlet_id='$OutletId' AND payment_method='2' AND DATE(invoice_date) BETWEEN '$shop_debtor_reports_start_date' AND '$shop_debtor_reports_end_date'");
                while($GCOrs = $getCOrderQuary->fetch_array()){
                    $Id=$GCOrs[0];
                    
                    
                    $getGrandTotalQuary = $conn->query("SELECT * FROM tbl_order_item_details toid INNER JOIN tbl_item tit ON toid.itemId=tit.itemId WHERE toid.order_id='$Id'");
                    while($GGTrs = $getGrandTotalQuary->fetch_array()){
                        $ProductOrderId=$GGTrs[0];                       
                        $ProductItemId=$GGTrs[1];                       
                        $ProductQty=$GGTrs[3];                       
                        $ProductDiscountedPrice=$GGTrs[4];                        
                        $ProductDiscountedValue=$GGTrs[5];                       
                        // $ProductPrice=$GGTrs[6];     
                        $RPID=$GGTrs[7]; 

                        //////////////////
                        $ItemCode=$GGTrs[9]; 
                        $ItemName=$GGTrs[10]; 
                        $ItemPrice=$GGTrs[6];

                        ////////Calculation//////////////
                        $DiscountedPrice = (double)$ItemPrice-(((double)$ItemPrice*(double)$ProductDiscountedValue)/100);
                        //With QTY
                        $ItemTotal = (double)$DiscountedPrice*(double)$ProductQty;

                        //Grand Total
                        $GrandTotal += $ItemTotal;
                        ////////Calculation//////////////
                    }
                    
                }
                
                $OutstandingSumSQL="SELECT SUM(`editable_total`) FROM `tbl_credit_orders` WHERE outlet_id='$OutletId'";
                $OutstandingSumResult = mysqli_query($conn, $OutstandingSumSQL);
                $OutstandingSumTotal = mysqli_fetch_assoc($OutstandingSumResult)['SUM(`editable_total`)'];
                
                
                $TdColor='';
                if($GrandTotal==$OutstandingSumTotal){
                    $TdColor='#459b45';
                }else{
                    $TdColor='#FF0000';
                }
                


                $obj=' 
                    <tr>
                        <td>'.$OutletName.'</td> 
                        <td class="text-right"><b style="color: '.$TdColor.';">'.number_format((double)$GrandTotal,2).'</b></td>
                        <td class="text-right"><b style="color: '.$TdColor.';">'.number_format((double)$OutstandingSumTotal,2).'</b></td>
                    </tr>

                  ';

                array_push($datalist,$obj);

            }

        }

    $output['result']=true;
    $output['data']=$datalist;
    
    
    }else{
        $output['result']=false;
        $output['data']="Invalid request.";
    }

    echo json_encode($output);