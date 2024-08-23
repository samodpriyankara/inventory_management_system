<?php
    require_once('../database/db.php');
    $db=new DB();
    $conn=$db->connect();
    session_start();
    date_default_timezone_set('Asia/Colombo');
    $currentDate=date('Y-m-d H:i:s');

    $output=[];
    $output['sp'] = 0;
    $output['cp'] = 0;

    if(isset($_POST['price_batch_id']) && isset($_POST['item_no'])){

    $item_no = htmlspecialchars($_POST['item_no']);
    $price_batch_id = htmlspecialchars($_POST['price_batch_id']);

        $checkPbatch = $conn->query("SELECT * FROM tbl_item WHERE genaricName = '$item_no' AND itemId = '$price_batch_id'");
        if($cpRs = $checkPbatch->fetch_array()){

            $selling_price = $cpRs[3];
            $part_cost = $cpRs[8];


            $output['sp'] = $selling_price;
            $output['cp'] = $part_cost;
           
        }
        

    $output['result'] = true;

}else{
    $output['result'] = false;
    $output['msg'] = "Required fields are not provided.";
}
    
mysqli_close($conn);
echo json_encode($output);


?>