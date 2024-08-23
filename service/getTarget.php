<?php
date_default_timezone_set('Asia/Colombo');
//--------------------------------
require 'db/db_connection.php';
$db=new DB();
$conn=$db->connect();
//--------------------------------

	$output=[];
	$result=array();
	$data=[];
	$date=date('Y-m-d');

	if(isset($_POST['userId'])){

	try {



		$userId=htmlspecialchars($_POST['userId']);
		$currentDate=date('Y-m-d');

		$getTargets=$conn->query("SELECT * FROM tbl_rep_target WHERE userId='$userId' AND validity_period='$currentDate' AND status=1");
		if($trs=$getTargets->fetch_array()){

				$data['trg_amount']=$trs[1];
				$data['trg_qty']=$trs[2];
				
				
		}else{

				$data['trg_amount']=0;
				$data['trg_qty']=0;

		}
		

		$getInvoiceTotal=$conn->query("SELECT SUM(toid.qty*toid.price),SUM(toid.qty) FROM tbl_order_item_details toid INNER JOIN tbl_order tor ON toid.order_id=tor.id WHERE tor.user_id='$userId'");
		if ($irs=$getInvoiceTotal->fetch_array()) {
			$total=$irs[0];
			$data['invoiceTotal']=$total;
			$data['ach_qty']=$irs[1];

		}else{

			$data['invoiceTotal']=0;
			$data['ach_qty']=0;
		}



		$getInvoiceTotalToday=$conn->query("SELECT SUM(toid.qty*toid.price) FROM tbl_order_item_details toid INNER JOIN tbl_order tor ON toid.order_id=tor.id WHERE tor.user_id='$userId' AND tor.invoice_date='$date'");
		if ($itrs=$getInvoiceTotalToday->fetch_array()) {
			$totalToday=$itrs[0];

			$data['todayInvoiceAmount']=$totalToday;

		}else{

			$data['todayInvoiceAmount']=0;
		}




		$getUserRouteData=$conn->query("SELECT tr.route_id,tr.route_name FROM tbl_route tr INNER JOIN tbl_user_has_routes tuhr ON tr.route_id=tuhr.route_id WHERE tuhr.user_id='$userId'");

		if($grd=$getUserRouteData->fetch_array()){

				$data['ar_id']=$grd[0];
				$data['ar_name']=$grd[1];


		}else{

				$data['ar_id']=0;
				$data['ar_name']="N/A";
		}

				$data['pendingSalesTotal']="0";
				$data['workingDay']="10";
				$data['ex_town']="N/A";
				

		array_push($result,$data);


		$output['result']=true;
		$output['target']=$result;

	} catch (Exception $e) {

		$output['result']=false;
		$output['msg']='Error '.$e->getMessage();
		
	}

	}else{
		$output['result']=false;
		$output['msg']='Required data not provided.';

	}



	echo json_encode($output);






