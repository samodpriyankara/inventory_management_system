<?php
    require_once('../database/db.php');
    $db=new DB();
    $conn=$db->connect();
    session_start();


    $output=[];
    $dataArray = array();

    if($_POST)
    {
        $rep_id = htmlspecialchars($_POST['rep_id']);
        $record = htmlspecialchars($_POST['record']);



        $sql = "INSERT INTO `sales_rep_records`(`rep_id`, `record`) VALUES (?,?)";
        $stmt = mysqli_prepare($conn, $sql);
        mysqli_stmt_bind_param($stmt, "ss", $rep_id, $record);
        $result = mysqli_stmt_execute($stmt);
        if($result)
        {
            // echo 'Completed';

            $lastId=0;
        
            $getRepRecord=$conn->query("SELECT record,record_datetime FROM sales_rep_records WHERE rep_id='$rep_id' ORDER BY sales_rep_record_id DESC");
            while($gorRs=$getRepRecord->fetch_array()){

              $RepRecord=nl2br($gorRs[0]);
              $RepRecordDateTime=$gorRs[1];
              

              $row='
                    <div class="profile-uoloaded-post border-bottom-1 pb-5">
                        <h5>Remark Note <span class="pull-right" style="font-size: 12px;">'.$RepRecordDateTime.'</span></h5>
                        <p>'.$RepRecord.'</p>                                  
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