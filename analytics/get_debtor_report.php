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

    if(isset($_POST['debtor_reports_start_date']) && isset($_POST['debtor_reports_end_date']) && isset($_POST['debtor_reports_distributor_id']) ){

        $debtor_reports_start_date=htmlspecialchars($_POST['debtor_reports_start_date']);
        $debtor_reports_end_date=htmlspecialchars($_POST['debtor_reports_end_date']);
        $debtor_reports_distributor_id=htmlspecialchars($_POST['debtor_reports_distributor_id']);

        //Distributor Debtor
        if($debtor_reports_distributor_id=='0'){

            
            $getDistributorQuary = $conn->query("SELECT * FROM tbl_distributor");
            while($GDrs = $getDistributorQuary->fetch_array()){

                $DistributorId=$GDrs[0];      
                $DistributorName=$GDrs[1]; 
                $DistributorAddress=$GDrs[2];
                $DistributorContactNumber=$GDrs[2];

                $OutstandingDistributorSumSQL="SELECT SUM(`grand_total`) FROM `tbl_distributor_product_invoice` WHERE distributor_id='$DistributorId' AND pay='0' AND stat='1' AND DATE(distributor_product_invoice_datetime) BETWEEN '$debtor_reports_start_date' AND '$debtor_reports_end_date'";
                $OutstandingDistributorSumTotal=0;
                $OutstandingDistributorSumResult = mysqli_query($conn, $OutstandingDistributorSumSQL);
                $OutstandingDistributorSumTotal = mysqli_fetch_assoc($OutstandingDistributorSumResult)['SUM(`grand_total`)'];


                $obj=' 
                    <tr>
                        <td>'.$DistributorName.'</td> 
                        <td class="text-right"><b>'.number_format((double)$OutstandingDistributorSumTotal,2).'</b></td>
                    </tr>

                  ';

                array_push($datalist,$obj);

            }

        //Sales-rep Debtor
        }else{

            $getSalesRepQuary = $conn->query("SELECT * FROM tbl_user tu INNER JOIN tbl_distributor_has_tbl_user tdhtu ON tu.id=tdhtu.user_id WHERE tdhtu.distributor_id='$debtor_reports_distributor_id'");
            while($GSRrs = $getSalesRepQuary->fetch_array()){

                $SalesRepId=$GSRrs[0];      
                $SalesRepName=$GSRrs[1];

                    $OutstandingCorrectPrice=0;
                    $OutstandingFullPrice=0;
                    $OutstandingInvoiceIdSQL="SELECT id FROM `tbl_order` WHERE user_id='$SalesRepId' AND payment_status='0' AND DATE(invoice_date) BETWEEN '$debtor_reports_start_date' AND '$debtor_reports_end_date'";
                    $OutstandingInvoiceIdQuery=$conn->query($OutstandingInvoiceIdSQL);
                    while($OIIrow=$OutstandingInvoiceIdQuery->fetch_array()){
                    $OutstandingOrderId=$OIIrow[0];

                    $OutstandingProductsSQL="SELECT qty,price,discounted_value FROM `tbl_order_item_details` WHERE order_id='$OutstandingOrderId' ";
                    $OutstandingProductsQuery=$conn->query($OutstandingProductsSQL);
                    $OutstandingGrandTotal=0;
                    while($OIProw=$OutstandingProductsQuery->fetch_array()){
                        $OutstandingProductQty=$OIProw[0];
                        $OutstandingProductPrice=$OIProw[1];
                        $OutstandingProductDiscountedValue=$OIProw[2];

                        ////////Calculation//////////////
                        $OutstandingDiscountedPrice = (double)$OutstandingProductPrice-(((double)$OutstandingProductPrice*(double)$OutstandingProductDiscountedValue)/100);
                        //With QTY
                        $OutstandingItemTotal = (double)$OutstandingDiscountedPrice*(double)$OutstandingProductQty;

                        //Grand Total
                        $OutstandingGrandTotal += $OutstandingItemTotal;
                        ////////Calculation//////////////

                    }

                        $OutstandingFullPrice += $OutstandingGrandTotal;

                        $OutstandingPaiedFullPrice=0;
                        $OutstandingPaiedSQL="SELECT SUM(amount) FROM `tbl_outstanding_payments` WHERE order_id='$OutstandingOrderId' ";
                        $OutstandingPaiedQuery=$conn->query($OutstandingPaiedSQL);
                        if($OProw=$OutstandingPaiedQuery->fetch_array()){
                          $OutstandingPaiedTotal=$OProw[0];

                          $OutstandingPaiedFullPrice += $OutstandingPaiedTotal;
                        }


            }
                                              
                //OutstandingCorrectPriceCal
                $OutstandingCorrectPrice = $OutstandingFullPrice - $OutstandingPaiedFullPrice;

                $OutstandingCorrectPriceView=0;
                if($OutstandingCorrectPrice < 0){
                    $OutstandingCorrectPriceView=0;
                }else{
                    $OutstandingCorrectPriceView=$OutstandingCorrectPrice;
                }


                $obj=' 
                    <tr>
                        <td>'.$SalesRepName.'</td> 
                        <td class="text-right"><b>'.number_format((double)$OutstandingCorrectPriceView,2).'</b></td>
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