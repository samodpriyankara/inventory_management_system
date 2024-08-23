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

        $output['tp']= $cost_price." ".$selling_price;
        


        $distributor_price = htmlspecialchars($_POST['distributor_price']);
        $return_price = htmlspecialchars($_POST['return_price']);
        $mrp_price = htmlspecialchars($_POST['mrp_price']);
        $price_batch_code = htmlspecialchars($_POST['price_batch_code']);
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

            // $getItemFreeIssueDetails = "SELECT * FROM tbl_free_issue_scheme WHERE item_id='$selected_item_id' ";
            // $GIFIRs=$conn->query($getItemFreeIssueDetails);
            // if($GIFIRsrow =$GIFIRs->fetch_array())
            // {
            //     $Id=$GIFIRsrow[1];
            //     $ItemDescription=$GIFIRsrow[2];
            //     $PackSize=$GIFIRsrow[3];
            //     $RpId=$GIFIRsrow[4];
            // }



        }
        //////////////////////////////////////////


        // $sql = "INSERT INTO `tbl_item`(`itemCode`, `itemDescription`, `price`, `packSize`, `stock`, `rp_id`, `genaricName`, `re_price`, `minimumQty`, `itemWeight`, `sequenceId`, `maximumQty`, `brand_name`, `category_id`, `distributor_id`, `supplier_id`, `item_img`) VALUES (?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?)";
        // $stmt = mysqli_prepare($conn, $sql);
        // mysqli_stmt_bind_param($stmt, "ssiiiisiiiiisiiis", $ItemCode, $ItemDescription, $selling_price, $PackSize, $qty, $RpId, $selected_item_id, $cost_price, $MinimumQty, $ItemWeight, $SequenceId, $MaximumQty ,$BrandName, $CategoryId, $DistributorId, $SupplierId, $ItemImg);
        // $result = mysqli_stmt_execute($stmt);

        $sql = "INSERT INTO `tbl_item`(`itemCode`, `itemDescription`, `price`, `packSize`, `stock`, `rp_id`, `genaricName`, `re_price`, `minimumQty`, `itemWeight`, `sequenceId`, `maximumQty`, `brand_name`, `category_id`, `distributor_id`, `supplier_id`, `item_img`) VALUES ('$ItemCode', '$ItemDescription', '$selling_price', '$PackSize', '$qty', '$RpId', '$selected_item_id', '$cost_price', '$MinimumQty', '$ItemWeight', '$SequenceId', '$MaximumQty' ,'$BrandName', '$CategoryId', '$DistributorId', '$SupplierId', '$ItemImg')";

        $result = $conn->query($sql);

        if($result)
        {
            
            $getLast = $conn->query("SELECT tit.itemId,tit.itemDescription FROM tbl_item tit ORDER BY tit.itemId DESC LIMIT 1");
            if($glRs = $getLast->fetch_array()){
                $id = $glRs[0];
                $label = $glRs[1];

                $AdditemOtherPricesSql = "INSERT INTO `tbl_item_other_prices`(`id`, `return_price`, `distributor_price`, `item_id`, `mrp`, `price_batch_code`) VALUES (null, '$return_price', '$distributor_price', '$id', '$mrp_price', '$price_batch_code')";
                if($conn->query($AdditemOtherPricesSql) === TRUE){
                
                    $output['result'] = true;
                    $output['msg'] = 'Successfully added price batch.';

                }else{
                    
                    $output['result'] = false;
                    $output['msg'] = 'Something went wrong (CODE SNAKE)';

                }

            }

        }else{  
            $output['result'] = false;
            $output['msg'] = 'Something went wrong (CODE SPRITE)';
        }


    }

    mysqli_close($conn);
    echo json_encode($output);

?>