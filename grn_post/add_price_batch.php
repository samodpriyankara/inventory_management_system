<?php
    require_once('../database/db.php');
    $db=new DB();
    $conn=$db->connect();
    session_start();
    date_default_timezone_set('Asia/Colombo');
    $currentDate=date('Y-m-d H:i:s');


    $output=[];

    if($_POST)
    {
        $selected_item_id = htmlspecialchars($_POST['selected_item_id']);
        $cost_price = htmlspecialchars($_POST['cost_price']);
        $selling_price = htmlspecialchars($_POST['selling_price']);
        $distributor_price = htmlspecialchars($_POST['distributor_price']);
        $return_price = htmlspecialchars($_POST['return_price']);
        $mrp_price = htmlspecialchars($_POST['mrp_price']);
        $batch_label = htmlspecialchars($_POST['batch_label']);
        $qty=0;


        //////////////////GET DATA////////////////////////
        $getItemDetails = "SELECT * FROM tbl_item_details WHERE item_detail_Id='$selected_item_id' ";
        $GIDRs=$conn->query($getItemDetails);
        if($GIDRsrow =$GIDRs->fetch_array())
        {
            $ItemCode=$GIDRsrow[1];
            $ItemDescription=$GIDRsrow[2];
            $PackSize=$GIDRsrow[3];
            $RpId=$GIDRsrow[4];
            // $GenaricName=$GIDRsrow[5];
            $MinimumQty=$GIDRsrow[6];
            $ItemWeight=$GIDRsrow[7];
            $SequenceId=$GIDRsrow[8];
            $MaximumQty=$GIDRsrow[9];
            $BrandName=$GIDRsrow[10];
            $CategoryId=$GIDRsrow[11];
            $DistributorId=$GIDRsrow[12];
            $SupplierId=$GIDRsrow[13];
            $ItemImg=$GIDRsrow[14];  
        }
        //////////////////////////////////////////


        $sql = "INSERT INTO `tbl_item`(`itemCode`, `itemDescription`, `price`, `packSize`, `stock`, `rp_id`, `genaricName`, `re_price`, `minimumQty`, `itemWeight`, `sequenceId`, `maximumQty`, `brand_name`, `category_id`, `distributor_id`, `supplier_id`, `item_img`) VALUES (?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?)";
        $stmt = mysqli_prepare($conn, $sql);
        mysqli_stmt_bind_param($stmt, "ssdiiisdiiiisiiis", $ItemCode, $ItemDescription, $selling_price, $PackSize, $qty, $RpId, $selected_item_id, $cost_price, $MinimumQty, $ItemWeight, $SequenceId, $MaximumQty ,$BrandName, $CategoryId, $DistributorId, $SupplierId, $ItemImg);
        $result = mysqli_stmt_execute($stmt);
        if($result)
        {
              
            $data = "";

            $getLast = $conn->query("SELECT tit.itemId,tit.itemDescription FROM tbl_item tit ORDER BY tit.itemId DESC LIMIT 1");
            $PriceBatchCount=0;
            if($glRs = $getLast->fetch_array()){
                $id = $glRs[0];
                //$label = $glRs[1];
                
                $getOtherData=$conn->query("SELECT * FROM tbl_item_other_prices WHERE item_id='$id'");
                if($godRs=$getOtherData->fetch_array()){
    
                    $label = $godRs[5];
                    
                }

                $PriceBatchCount += 1;

                $data = '<option value='.$id.'>'.$label.'</option>';


                $AdditemOtherPricesSql = "INSERT INTO `tbl_item_other_prices`(`id`, `return_price`, `distributor_price`, `item_id`, `mrp`, `price_batch_code`) VALUES (null, '$return_price', '$distributor_price', '$id', '$mrp_price', '$batch_label')";
                if($conn->query($AdditemOtherPricesSql) === TRUE){
                
                    $output['result'] = true;
                    $output['data'] = $data;

                }else{
                    
                    $output['result'] = false;
                    $output['data'] = $data;

                }




            }

            // $output['result'] = true;
            // $output['data'] = $data;





        }else{  
            $output['result'] = false;
            $output['data'] = $data;
        }


    }

    mysqli_close($conn);
    echo json_encode($output);

?>