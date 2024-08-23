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

    if(isset($_POST['product_selling_start_date']) && isset($_POST['product_selling_end_date']) && isset($_POST['product_selling_distributor_id']) && isset($_POST['product_selling_sales_rep_id']) ){

        $product_selling_start_date=htmlspecialchars($_POST['product_selling_start_date']);
        $product_selling_end_date=htmlspecialchars($_POST['product_selling_end_date']);
        $product_selling_distributor_id=htmlspecialchars($_POST['product_selling_distributor_id']);
        $product_selling_sales_rep_id=htmlspecialchars($_POST['product_selling_sales_rep_id']);

        $ProductCount=0;
        $getItemsQuary = $conn->query("SELECT * FROM tbl_item_details");
        while($GGTrs = $getItemsQuary->fetch_array()){

            $ItemDetailId=$GGTrs[0];      
            $ItemCode=$GGTrs[1]; 
            $ItemName=$GGTrs[2];

            

                if ($product_selling_distributor_id=='0' && $product_selling_sales_rep_id=='0') {

                   $GetDataquery="SELECT SUM(toid.qty) FROM tbl_order_item_details toid INNER JOIN tbl_item tit ON toid.itemId=tit.itemId INNER JOIN tbl_order ton ON toid.order_id=ton.id WHERE DATE(ton.invoice_date) BETWEEN '$product_selling_start_date' AND '$product_selling_end_date' AND tit.genaricName='$ItemDetailId'";

                }elseif($product_selling_distributor_id!='0' && $product_selling_sales_rep_id=='0'){

                    $GetDataquery="SELECT SUM(toid.qty) FROM tbl_order_item_details toid INNER JOIN tbl_item tit ON toid.itemId=tit.itemId INNER JOIN tbl_order ton ON toid.order_id=ton.id WHERE DATE(ton.invoice_date) BETWEEN '$product_selling_start_date' AND '$product_selling_end_date' AND ton.distributor_id='$product_selling_distributor_id' AND tit.genaricName='$ItemDetailId'";

                }elseif($product_selling_distributor_id!='0' && $product_selling_sales_rep_id!='0'){

                    $GetDataquery="SELECT SUM(toid.qty) FROM tbl_order_item_details toid INNER JOIN tbl_item tit ON toid.itemId=tit.itemId INNER JOIN tbl_order ton ON toid.order_id=ton.id WHERE DATE(ton.invoice_date) BETWEEN '$product_selling_start_date' AND '$product_selling_end_date' AND ton.distributor_id='$product_selling_distributor_id' AND ton.user_id='$product_selling_sales_rep_id' AND tit.genaricName='$ItemDetailId'";

                }else{
                    //Load All
                    $GetDataquery="SELECT SUM(toid.qty) FROM tbl_order_item_details toid INNER JOIN tbl_item tit ON toid.itemId=tit.itemId INNER JOIN tbl_order ton ON toid.order_id=ton.id WHERE DATE(ton.invoice_date) BETWEEN '$product_selling_start_date' AND '$product_selling_end_date' AND tit.genaricName='$ItemDetailId'";
                }

                $getItemCountQuary=$conn->query($GetDataquery);
                while ($ICQrs=$getItemCountQuary->fetch_array()){

                  $ProductCount=$ICQrs[0];

                    if($ProductCount==''){
                        $ProductCountView='0';
                    }else{ 
                        $ProductCountView=$ProductCount;
                    }

                }

      $obj=' 
            <tr>
                <td>'.$ItemName.'</td> 
                <td>'.$ItemCode.'</td>
                <td class="text-right"><b>'.$ProductCountView.'</b></td>
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
    
    
    