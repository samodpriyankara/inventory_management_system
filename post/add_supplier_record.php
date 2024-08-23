<?php
    require_once('../database/db.php');
    $db=new DB();
    $conn=$db->connect();
    session_start();


    $output=[];
    $dataArray = array();

    if($_POST)
    {
        $supplier_id = htmlspecialchars($_POST['supplier_id']);
        $record = htmlspecialchars($_POST['record']);



        $sql = "INSERT INTO `supplier_records`(`supplier_id`, `record`) VALUES (?,?)";
        $stmt = mysqli_prepare($conn, $sql);
        mysqli_stmt_bind_param($stmt, "ss", $supplier_id, $record);
        $result = mysqli_stmt_execute($stmt);
        if($result)
        {
            // echo 'Completed';

            $lastId=0;
        
            $getDistributorRecord=$conn->query("SELECT record,record_datetime FROM supplier_records WHERE supplier_id='$supplier_id' ORDER BY supplier_record_id DESC");
            while($gdrRs=$getDistributorRecord->fetch_array()){

              $SupplierRecord=nl2br($gdrRs[0]);
              $SupplierRecordDateTime=$gdrRs[1];
              

              $row='
                    <div class="profile-uoloaded-post border-bottom-1 pb-5">
                        <h5>Remark Note <span class="pull-right" style="font-size: 12px;">'.$SupplierRecordDateTime.'</span></h5>
                        <p>'.$SupplierRecord.'</p>                                  
                        <hr>
                    </div>
                    ';
                    
                    array_push($dataArray,$row);
                
                
            }
            
            /////////////////
            
            
            $output['result'] = true;
            $output['msg'] = 'Successfully record added.';
            $output['data'] = $dataArray;


        }else{  
            // echo 'Error';   
            $output['result']=false;
            $output['msg']="Error updating record.";
        }


    }

    mysqli_close($conn);
    echo json_encode($output);

    ?>