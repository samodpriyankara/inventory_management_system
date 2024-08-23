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

    if(isset($_POST['free_issue_start_date']) && isset($_POST['free_issue_end_date']) && isset($_POST['free_issue_distributor_id']) ){

        $free_issue_start_date=htmlspecialchars($_POST['free_issue_start_date']);
        $free_issue_end_date=htmlspecialchars($_POST['free_issue_end_date']);
        $free_issue_distributor_id=htmlspecialchars($_POST['free_issue_distributor_id']);


            if($free_issue_distributor_id=='0'){
                $getFreeIssueQuary = $conn->query("SELECT *,SUM(free_qty) FROM tbl_order_free_issues tofi INNER JOIN tbl_order tor ON tofi.order_id=tor.id WHERE DATE(tor.invoice_date) BETWEEN '$free_issue_start_date' AND '$free_issue_end_date' GROUP BY (item_id)");
            }else{
                $getFreeIssueQuary = $conn->query("SELECT *,SUM(free_qty) FROM tbl_order_free_issues tofi INNER JOIN tbl_order tor ON tofi.order_id=tor.id WHERE tofi.distributor_id='$free_issue_distributor_id' AND DATE(tor.invoice_date) BETWEEN '$free_issue_start_date' AND '$free_issue_end_date' GROUP BY (item_id)"); 
            }
            while($GFIrs = $getFreeIssueQuary->fetch_array()){

                $ItemID=$GFIrs[1];
                $ItemName=$GFIrs[2];
                $FreeQty=$GFIrs[27];
                
                    $TotCost=0.0;
                    $getItemDetailsQuary = $conn->query("SELECT * FROM tbl_item WHERE itemId='$ItemID' ");
                    if($GIDrs = $getItemDetailsQuary->fetch_array()){
                        $ItemCode =$GIDrs[1];
                        $ItemCost =$GIDrs[8];
                        
                        $TotCost=$ItemCost*$FreeQty;
                    }

                $obj=' 
                    <tr>
                        <td>'.$ItemName.'</td> 
                        <td>'.$ItemCode.'</td>
                        <td class="text-right"><b>'.$FreeQty.'</b></td>
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