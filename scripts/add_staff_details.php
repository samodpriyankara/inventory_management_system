<?php
	
  require '../database/db.php';
  $db=new DB();
  $conn=$db->connect();
  date_default_timezone_set('Asia/Colombo');
  $currentDate=date('Y-m-d H:i:s');

  /////////////////////////////////

	
	$output=[];
	
	
	   // var name=$("#txt-name").val();
    //     var address=$("#txt-address").val();
    //     var birthday=$("#txt-birthday").val();
    //     var contact=$("#txt-contact").val();
    //     var email=$("#txt-email").val();
    //     var job=$("#txt-job-description").val();
    //     var salary=$("#txt-salary").val();
    //     //
    //     var bankname=$("#txt-bank-name").val();
    //     var bankdescription=$("#txt-bank-branch").val();
    //     var accountname=$("#txt-account-name").val();
    //     var accountnumber=$("#txt-account-number").val();


if(isset($_POST['name']) && isset($_POST['address']) && isset($_POST['birthday']) && isset($_POST['contact']) && isset($_POST['email']) && isset($_POST['job']) && isset($_POST['salary']) && isset($_POST['nic'])){

		$date=date('Y-m-d h:i:s');
		$name=htmlspecialchars($_POST['name']);
		$address=htmlspecialchars($_POST['address']);
		$birthday=htmlspecialchars($_POST['birthday']);
		$contact=htmlspecialchars($_POST['contact']);
		$email=htmlspecialchars($_POST['email']);
		$job=htmlspecialchars($_POST['job']);
		$salary=htmlspecialchars($_POST['salary']);
		$nic=htmlspecialchars($_POST['nic']);
		//
		$bankname=htmlspecialchars($_POST['bankname']);
		$bankbranch=htmlspecialchars($_POST['bankbranch']);
		$accountname=htmlspecialchars($_POST['accountname']);
		$accountnumber=htmlspecialchars($_POST['accountnumber']);
		$stat=1;



		$checkUserName=$conn->query("SELECT * FROM tbl_staff WHERE nic_number='$nic'");
		if(mysqli_num_rows($checkUserName) > 0){

				$output['result']=false;
				$output['msg']="Already exists.";

		}else{
		
			if($conn->query("INSERT INTO tbl_staff VALUES(null,'$name','$address','$birthday','$nic','$contact','$email','$job','$salary','$bankname','$bankbranch','$accountname','$accountnumber','$stat','$currentDate')")){
            
				
				//map user to distributor

                $output['result']=true;
			    $output['msg']="Staff registration successful.";

			
			}else{

				$output['result']=false;
				$output['msg']="Something went wrong please try again.";

			}

		}

	}else{

		$output['result']=false;
		$output['msg']='Required fields are not provided.';

	}



	echo json_encode($output);

