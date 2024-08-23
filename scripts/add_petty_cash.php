<?php
  session_start();
  require '../database/db.php';
  $db=new DB();
  $conn=$db->connect();
  date_default_timezone_set('Asia/Colombo');
  $date_time=date('Y-m-d h:i:s');

  
	
/////////////////////////////////

	
	$output=[];

	if(isset($_POST['description']) && $_POST['description'] != "" && isset($_POST['credit_amount']) && $_POST['credit_amount'] != "" ){

		$description = mysqli_real_escape_string($conn,$_POST['description']);
		$credit_amount = mysqli_real_escape_string($conn,$_POST['credit_amount']);
		$user_id = $_SESSION['ID'];
		
		$total_credit_amount = 0;
		$total_debit_amount = 0;
		
		$credit_sum_rs = $conn -> query("SELECT SUM(credit) FROM tbl_petty_cash_and_expenses WHERE status = 1");
		if($credit_sum = $credit_sum_rs->fetch_array()){
		    $total_credit_amount = ($credit_sum[0] + $credit_amount);
		}
		
		$debit_sum_rs = $conn -> query("SELECT SUM(debit) FROM tbl_petty_cash_and_expenses WHERE status = 1");
		if($debit_sum = $debit_sum_rs->fetch_array()){
		    $total_debit_amount = $debit_sum[0];
		}
		
		$balance = $total_credit_amount - $total_debit_amount;
		
		
		
		
		if($conn -> query("INSERT INTO tbl_petty_cash_and_expenses VALUES(null,'$date_time','$description','$credit_amount',0,'$balance',1,'$user_id','$date_time')")){
		    $output['result'] = true;
		    $output['msg'] = "Petty cash has been added successfully.";
		}else{
		    $output['result'] = false;
		    $output['msg'] = "Failed to add petty cash, please try again later or contact administrator.";
		}
	




	}else{

		$output['result']=false;
		$output['msg']="Required fields are not provided.";


	}



	echo json_encode($output);