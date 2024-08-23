<?php
    require_once('../database/db.php');
    $db=new DB();
    $conn=$db->connect();
    session_start();


    $output=[];

    if($_POST)
    {
        $user_id = htmlspecialchars($_POST['user_id']);
        $name = htmlspecialchars($_POST['name']);


        $sql = "UPDATE tbl_web_console_user_account SET name='$name' WHERE user_id='$user_id' ";

        if ($conn->query($sql) === TRUE) {
        //   echo "Record updated successfully";
        
            //get user name
            
            $getUserName=$conn->query("SELECT * FROM tbl_web_console_user_account WHERE user_id='$user_id' ");
            if($gunRs = $getUserName->fetch_array()){
                
                // $UserName = $gunRs[0];
                
                $username=$gunRs['name'];

                $output['result']=true;
                $output['msg']="Record updated successfully.";

                $output['username']=$username;
                
                    
                
                
                
            }else{

                $output['result']=true;
                $output['msg']="Record updated successfully, please re-load to see changes.";
                
                
                $output['username']='';

            }
            
            /////////////////
            
            
            
            // echo 'Completed';
        }else{  
            // echo 'Error';   
            
            $output['result'] = false;
            $output['msg'] = 'Error activate please reload the page'; 
        }

        $conn->close();
    }
    
    
    echo json_encode($output);


    ?>