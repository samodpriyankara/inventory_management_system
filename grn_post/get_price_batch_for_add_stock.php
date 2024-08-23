<?php
    require_once('../database/db.php');
    $db=new DB();
    $conn=$db->connect();
    session_start();
    date_default_timezone_set('Asia/Colombo');
    $currentDate=date('Y-m-d H:i:s');


    $output=[];
    $dataList = array();

    if(isset($_POST['item_no'])){

    $item_no = htmlspecialchars($_POST['item_no']);


        $rowData = '<option value="" selected disabled>Select Price Batch</option>';

        array_push($dataList, $rowData);

        $checkPbatch = $conn->query("SELECT * FROM tbl_item WHERE genaricName = '$item_no'");
        $PriceBatchCount=0;
        while($cpRs = $checkPbatch->fetch_array()){
            $id = $cpRs[0];
            //$label = $cpRs[2];
            
            $getOtherData=$conn->query("SELECT * FROM tbl_item_other_prices WHERE item_id='$id'");
            if($godRs=$getOtherData->fetch_array()){

                $label = $godRs[5];
                
            }
            
            $PriceBatchCount += 1;

            $rowData = "<option value=".$id.">".$label."</option>";

            array_push($dataList, $rowData);
        }



        $output['result'] = true;
        $output['data'] = $dataList;


}else{
    $output['result'] = false;
    $output['msg'] = "Required fields are not provided.";
}
    
mysqli_close($conn);
echo json_encode($output);


?>