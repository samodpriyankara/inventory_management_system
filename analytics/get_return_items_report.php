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

    if(isset($_POST['return_item_start_date']) && isset($_POST['return_item_end_date']) && isset($_POST['return_item_distributor_id']) && isset($_POST['return_report_type']) ){

        $return_item_start_date=htmlspecialchars($_POST['return_item_start_date']);
        $return_item_end_date=htmlspecialchars($_POST['return_item_end_date']);
        $return_item_distributor_id=htmlspecialchars($_POST['return_item_distributor_id']);
        $return_report_type=htmlspecialchars($_POST['return_report_type']);


            if($return_item_distributor_id=='0' && $return_report_type=='-1'){
                $getReturnItemQuary = $conn->query("SELECT *,SUM(troid.qty) FROM tbl_return_order_item_details troid INNER JOIN tbl_return_order tro ON troid.order_id=tro.id WHERE DATE(tro.invoice_date) BETWEEN '$return_item_start_date' AND '$return_item_end_date' GROUP BY (troid.itemId)");
            }else if($return_item_distributor_id=='0' && $return_report_type=='0'){
                $getReturnItemQuary = $conn->query("SELECT *,SUM(troid.qty) FROM tbl_return_order_item_details troid INNER JOIN tbl_return_order tro ON troid.order_id=tro.id WHERE tro.return_type='$return_report_type' AND DATE(tro.invoice_date) BETWEEN '$return_item_start_date' AND '$return_item_end_date' GROUP BY (troid.itemId)");
            }else if($return_item_distributor_id=='0' && $return_report_type=='1'){
                $getReturnItemQuary = $conn->query("SELECT *,SUM(troid.qty) FROM tbl_return_order_item_details troid INNER JOIN tbl_return_order tro ON troid.order_id=tro.id WHERE tro.return_type='$return_report_type' AND DATE(tro.invoice_date) BETWEEN '$return_item_start_date' AND '$return_item_end_date' GROUP BY (troid.itemId)");    
                
                
                
            }else if($return_item_distributor_id!='0' && $return_report_type=='-1'){    
                $getReturnItemQuary = $conn->query("SELECT *,SUM(troid.qty) FROM tbl_return_order_item_details troid INNER JOIN tbl_return_order tro ON troid.order_id=tro.id WHERE tro.distributor_id='$return_item_distributor_id' AND DATE(tro.invoice_date) BETWEEN '$return_item_start_date' AND '$return_item_end_date' GROUP BY (troid.itemId)"); 
            }else if($return_item_distributor_id!='0' && $return_report_type=='0'){  
                $getReturnItemQuary = $conn->query("SELECT *,SUM(troid.qty) FROM tbl_return_order_item_details troid INNER JOIN tbl_return_order tro ON troid.order_id=tro.id WHERE tro.return_type='$return_report_type' AND tro.distributor_id='$return_item_distributor_id' AND DATE(tro.invoice_date) BETWEEN '$return_item_start_date' AND '$return_item_end_date' GROUP BY (troid.itemId)"); 
            }else if($return_item_distributor_id!='0' && $return_report_type=='1'){ 
                $getReturnItemQuary = $conn->query("SELECT *,SUM(troid.qty) FROM tbl_return_order_item_details troid INNER JOIN tbl_return_order tro ON troid.order_id=tro.id WHERE tro.return_type='$return_report_type' AND tro.distributor_id='$return_item_distributor_id' AND DATE(tro.invoice_date) BETWEEN '$return_item_start_date' AND '$return_item_end_date' GROUP BY (troid.itemId)"); 
            }
            
            
            
            
            
            
            while($GFIrs = $getReturnItemQuary->fetch_array()){

                $ItemID=$GFIrs[1];
                $ReturnQty=$GFIrs[23];
                
                    $TotCost=0.0;
                    $getItemDetailsQuary = $conn->query("SELECT * FROM tbl_item WHERE itemId='$ItemID' ");
                    if($GIDrs = $getItemDetailsQuary->fetch_array()){
                        $ItemCode =$GIDrs[1];
                        $ItemName =$GIDrs[2];
                        $ItemCost =$GIDrs[8];
                        
                        $TotCost=$ItemCost*$ReturnQty;
                    }

                $obj=' 
                    <tr>
                        <td>'.$ItemName.'</td> 
                        <td>'.$ItemCode.'</td>
                        <td class="text-right"><b>'.$ReturnQty.'</b></td>
                        <td class="text-right"><b>'.number_format($TotCost,2).'</b></td>
                    </tr>

                  ';

                array_push($datalist,$obj);

            }

        

    $output['result']=true;
    $output['data']=$datalist;
    
    
    }else{
        $output['result']=false;
        $output['data']="Invalid request.";
    }

    echo json_encode($output);