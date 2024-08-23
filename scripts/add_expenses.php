<?php
  session_start();
  require '../database/db.php';
  $db=new DB();
  $conn=$db->connect();
  date_default_timezone_set('Asia/Colombo');
  $date_time=date('Y-m-d h:i:s');

  
	
/////////////////////////////////

	
	$output=[];

	if(isset($_POST['description']) && $_POST['description'] != "" && isset($_POST['debit_amount']) && $_POST['debit_amount'] != "" && isset($_POST['debit_date']) && $_POST['debit_date'] != "" ){

		$description = mysqli_real_escape_string($conn,$_POST['description']);
		$debit_amount = mysqli_real_escape_string($conn,$_POST['debit_amount']);
		$debit_date = mysqli_real_escape_string($conn,$_POST['debit_date']);
		
		$user_id = $_SESSION['ID'];
		
		$total_credit_amount = 0;
		$total_debit_amount = 0;
		
		$credit_sum_rs = $conn -> query("SELECT SUM(credit) FROM tbl_petty_cash_and_expenses WHERE status = 1");
		if($credit_sum = $credit_sum_rs->fetch_array()){
		    $total_credit_amount = $credit_sum[0];
		}
		
		$debit_sum_rs = $conn -> query("SELECT SUM(debit) FROM tbl_petty_cash_and_expenses WHERE status = 1");
		if($debit_sum = $debit_sum_rs->fetch_array()){
		    $total_debit_amount = ($debit_sum[0] + $debit_amount);
		}
		
		$balance = $total_credit_amount - $total_debit_amount;
		
		
		
		
		if($conn -> query("INSERT INTO tbl_petty_cash_and_expenses VALUES(null,'$debit_date','$description',0,'$debit_amount','$balance',1,'$user_id','$date_time')")){
		    $output['result'] = true;
		    $output['msg'] = "Expense has been registered successfully.";
		}else{
		    $output['result'] = false;
		    $output['msg'] = "Failed to register expenses, please try again later or contact administrator.";
		}
	




	}else{

		$output['result']=false;
		$output['msg']="Required fields are not provided.";


	}



	echo json_encode($output);