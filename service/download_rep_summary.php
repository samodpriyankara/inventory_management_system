<?php
date_default_timezone_set('Asia/Colombo');
//--------------------------------
require '../database/db.php';
$db=new DB();
$conn=$db->connect();

//--------------------------------

$output=[];
$date=date('Y-m-d');

if(isset($_POST['user_id']) && isset($_POST['app_type'])){

	$user_id=htmlspecialchars($_POST['user_id']);
	$app_type=htmlspecialchars($_POST['app_type']);



	

	$searchDate = date("Y-m-d");
	$totalIncome = 0;
	$invoices = array();
	$returns = array();
	$freeList = array();


// // // 	//get total income of cash/cheque/credit//


// // // 	  									///payment methods////
// // // 										//0 - cash
// // //                                         //1 - cheque
// // //                                         //2 - credit(loan)
// // //                                         //3 - visa/master
// // //                                         ////////////////////////

		//get cash total//
	$cashTotal = 0;
	$getCashResult = $conn->query("SELECT * FROM tbl_order WHERE payment_method = 0 AND invoice_date = '$searchDate' AND user_id='$user_id'");
	while($cashRs = $getCashResult->fetch_array()){
		$orderId = $cashRs[0];
		$getOrderDetails = $conn->query("SELECT qty,price FROM tbl_order_item_details WHERE order_id = '$orderId'");
		while($odRs = $getOrderDetails->fetch_array()){
			$qty = $odRs[0];
			$price = $odRs[1];
			$cashTotal += ($qty*$price);
		}
	}


		//get cheque total//
	$chequeTotal = 0;
	$getChequeResult = $conn->query("SELECT * FROM tbl_order WHERE payment_method = 1 AND invoice_date = '$searchDate' AND user_id='$user_id'");
	while($chequeRs = $getChequeResult->fetch_array()){
		$orderId = $chequeRs[0];
		$getOrderDetails = $conn->query("SELECT qty,price FROM tbl_order_item_details WHERE order_id = '$orderId'");
		while($odRs = $getOrderDetails->fetch_array()){
			$qty = $odRs[0];
			$price = $odRs[1];
			$chequeTotal += ($qty*$price);
		}
	}

	//get credit total//
	$creditTotal = 0;
	$getCreditResult = $conn->query("SELECT * FROM tbl_order WHERE payment_method = 2 AND invoice_date = '$searchDate' AND user_id='$user_id'");
	while($creditRs = $getCreditResult->fetch_array()){
		$orderId = $creditRs[0];
		$getOrderDetails = $conn->query("SELECT qty,price FROM tbl_order_item_details WHERE order_id = '$orderId'");
		while($odRs = $getOrderDetails->fetch_array()){
			$qty = $odRs[0];
			$price = $odRs[1];
			$creditTotal += ($qty*$price);
		}
	}

	$totalIncome = ($cashTotal+$chequeTotal+$creditTotal);











// // // 	/////////////////////////////////////////


// // // 	///////load invoices/////



	$getInvoices = $conn->query("SELECT * FROM tbl_order WHERE user_id = '$user_id' AND invoice_date = '$searchDate'");
	while ($iRs = $getInvoices->fetch_array()) {
		$orderKey = $iRs[0];
		$orderNo = $iRs[1];
		$paymentMethod = $iRs[12];

		if($paymentMethod == '0'){
			$paymentMethod = 'Cash';
		}else if($paymentMethod == '1'){
			$paymentMethod = 'Cash';
		}else if($paymentMethod == '2'){
			$paymentMethod = 'Credit';
		}else{
			$paymentMethod = 'N/A';
		}

		$invoice['id'] = $orderKey;
		$invoice['order_id'] = $orderNo;
		$invoice['order_type'] = $paymentMethod;



		$orderTotal = 0;
		$getOrderDetails = $conn->query("SELECT * FROM tbl_order_item_details WHERE order_id = '$orderKey'");
		while($odRs = $getOrderDetails->fetch_array()){
			$qty = $odRs[3];
			$price = $odRs[6];

			$orderTotal += ($qty*$price);

		}

		$invoice['order_total'] = $orderTotal;

		array_push($invoices,$invoice);



	}




// // 		////check free issues//////

// $getFree = $conn->query("SELECT tofi.item_name,SUM(tofi.free_qty) FROM tbl_order_free_issues tofi INNER JOIN tbl_order tor ON tofi.order_id = tor.id WHERE tor.user_id = '$user_id' AND tor.invoice_date = '$searchDate' GROUP BY item_id");

$getFree = $conn->query("SELECT tofi.item_name,SUM(tofi.free_qty) FROM tbl_order_free_issues tofi INNER JOIN tbl_order tor ON tofi.order_id = tor.id WHERE tor.user_id = '$user_id' AND tor.invoice_date = '$searchDate' GROUP BY tofi.item_name");




		while($fRs = $getFree->fetch_array()){

			$free['item_name'] = $fRs[0];
			$free['item_qty'] = $fRs[1];

			array_push($freeList, $free);


		}

// 	// $output['qu'] = "SELECT tofi.item_name,SUM(tofi.free_qty) FROM tbl_order_free_issues tofi INNER JOIN tbl_order tor ON tofi.order_id = tor.id WHERE tor.user_id = '$user_id' AND tor.invoice_date = '$searchDate' GROUP BY item_id";




// // // 	/////////////////////////



// // // 	///////load returns/////



	$getReturns = $conn->query("SELECT * FROM tbl_return_order WHERE user_id = '$user_id' AND invoice_date = '$searchDate'");
	while ($rRs = $getReturns->fetch_array()) {
		$orderKey = $rRs[0];
		$orderNo = $rRs[1];
		$returnType = $rRs[2];


		if($returnType == '0'){
				$returnType = "Sales Return";
		}else if($returnType == '1'){
				$returnType = "Damage Return";
		}else{
				$returnType = "N/A";
		}

	

		$return['id'] = $orderKey;
		$return['order_id'] = $orderNo;
		$return['return_type'] = $returnType;



		$returnTotal = 0;
		$getReturnOrderDetails = $conn->query("SELECT * FROM tbl_return_order_item_details WHERE order_id = '$orderKey'");
		while($rodRs = $getReturnOrderDetails->fetch_array()){
			$qty = $rodRs[3];
			$price = $rodRs[6];

			$returnTotal += ($qty*$price);

		}

		$return['return_total'] = $returnTotal;




		array_push($returns,$return);
	}





// // // 	/////////////////////////






















	$printPreview = "Test Print";







	$output["result"]=true;
	$output["cash_total"]=$cashTotal;
	$output["cheque_total"]=$chequeTotal;
	$output["credit_total"]=$creditTotal;
	$output["total_income"]=$totalIncome;

	$output["invoices"]=$invoices;
	$output["returns"]=$returns;
	$output["free"]=$freeList;

	
	$output["print_preview"]=$printPreview;


	

}else{

	$output["result"]=false;
	$output["msg"]="Something went wrong.Please try again.";
	// $output["login_time_stamp"]=$login_time_stamp;

}
echo json_encode($output);







	