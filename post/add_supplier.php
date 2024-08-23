<?php
    require_once('../database/db.php');
    $db=new DB();
    $conn=$db->connect();
    session_start();
    date_default_timezone_set('Asia/Colombo');
    // $currentDate=date('Y-m-d');
    $currentDate=date('Y-m-d H:i:s');


    $output=[];

    if($_POST)
    {
        $supplier_name = htmlspecialchars($_POST['supplier_name']);
        $nic_number = htmlspecialchars($_POST['nic_number']);
        $br_number = htmlspecialchars($_POST['br_number']);    
        $contact_person_name = htmlspecialchars($_POST['contact_person_name']);    
        $contact_number = htmlspecialchars($_POST['contact_number']);
        $address = htmlspecialchars($_POST['address']);

        $distributor_id='0';


        $AddSupplierSql = "INSERT INTO `tbl_supplier`(`supplier_id`, `name`, `distributor_id`) VALUES (null, '$supplier_name', '$distributor_id')";
        if($conn->query($AddSupplierSql) === TRUE){
        
            $lastId=0;
        
            $getLast=$conn->query("SELECT supplier_id FROM tbl_supplier ORDER BY supplier_id DESC LIMIT 1");
            if($lRs=$getLast->fetch_array()){

                $lastId=$lRs[0];
                
                $AddSupplierDetailsSql = "INSERT INTO `tbl_supplier_details`(`supplier_details_id`, `supplier_id`, `supplier_name`, `nic_number`, `br_number`, `contact_person_name`, `contact_number`, `address`, `supplier_reg_datetime`) VALUES (null, '$lastId', '$supplier_name', '$nic_number', '$br_number', '$contact_person_name', '$contact_number', '$address', '$currentDate')";
                if($conn->query($AddSupplierDetailsSql) === TRUE){
                    
                    $encodelastId=base64_encode($lastId);

                    $output['result']=true;
                    $output['msg']="Supplier register successfully.";
                    $output['lastId']=$encodelastId;

                }else{
                    $output['result']=false;
                    $output['msg']="Something went wrong (CODE SNAKE).";
                }



          
            }else{
                $output['result']=false;
                $output['msg']="Something went wrong (CODE SPRITE).";
            }

        }else{
            
            $output['result']=false;
            $output['msg']="Something went wrong (CODE COKE).";

        }
        

    }

    mysqli_close($conn);
    echo json_encode($output);

    ?>
