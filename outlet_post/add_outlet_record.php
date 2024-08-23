<?php
    require_once('../database/db.php');
    $db=new DB();
    $conn=$db->connect();
    session_start();


    $output=[];
    $dataArray = array();

    if($_POST)
    {
        $outlet_id = htmlspecialchars($_POST['outlet_id']);
        $record = htmlspecialchars($_POST['record']);



        $sql = "INSERT INTO `outlet_records`(`outlet_id`, `record`) VALUES (?,?)";
        $stmt = mysqli_prepare($conn, $sql);
        mysqli_stmt_bind_param($stmt, "ss", $outlet_id, $record);
        $result = mysqli_stmt_execute($stmt);
        if($result)
        {
            // echo 'Completed';

            $lastId=0;
        
            $getOutletRecord=$conn->query("SELECT record,record_datetime FROM outlet_records WHERE outlet_id='$outlet_id' ORDER BY outlet_record_id DESC");
            while($gorRs=$getOutletRecord->fetch_array()){

              $OutletRecord=nl2br($gorRs[0]);
              $OutletRecordDateTime=$gorRs[1];
              

              $row='
                    <div class="profile-uoloaded-post border-bottom-1 pb-5">
                        <h5>Remark Note <span class="pull-right" style="font-size: 12px;">'.$OutletRecordDateTime.'</span></h5>
                        <p>'.$OutletRecord.'</p>                                  
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