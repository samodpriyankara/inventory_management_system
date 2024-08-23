<?php
    require '../database/db.php';
    $db=new DB();
    $conn=$db->connect();
    session_start();
    date_default_timezone_set('Asia/Colombo');
    $currentDate=date('Y-m-d H:i:s');


    $output=[];
    $dataList = array();

    if(isset($_POST['distributor_id'])){

    $distributor_id = htmlspecialchars($_POST['distributor_id']);


        $rowData = '<option value="0" selected>Selcet Sales-Rep</option>';

        array_push($dataList, $rowData);

        $GetSalesRepData = $conn->query("SELECT * FROM tbl_distributor_has_tbl_user tdhtu INNER JOIN tbl_user tu ON tdhtu.user_id=tu.id WHERE tdhtu.distributor_id='$distributor_id'");
        while($cpRs = $GetSalesRepData->fetch_array()){
            
            $SalesRepId = $cpRs[2];
            $SalesRepName = $cpRs[6];

            $rowData = "<option value=".$SalesRepId.">".$SalesRepName."</option>";

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