<?php
date_default_timezone_set('Asia/Colombo');
//--------------------------------
require '../database/db.php';
$db=new DB();
$conn=$db->connect();

session_start();
//--------------------------------

		$output=[];
		$date=date('Y-m-d');
		$sessionId=session_id();

		$freeIssueList = array();
		$orderItemsList = array();
		

if(isset($_POST['order_id']) && isset($_POST['user_id'])){

	$order_id = htmlspecialchars($_POST['order_id']);
	$user_id = htmlspecialchars($_POST['user_id']);


	$getBaseOrder = $conn->query("SELECT * FROM tbl_order tblo INNER JOIN tbl_outlet tblout ON tblo.outlet_id= tblout.outlet_id WHERE tblo.id = '$order_id'");
	if($gboRs = $getBaseOrder->fetch_array()){

	///basic order details

		$orderId = $gboRs[1];//inv...
		$outletId = $gboRs[19];
		$routeId = $gboRs[16];
		$invoiceTime = $gboRs[4];
		$battery = $gboRs[7];
		$lng = $gboRs[5];
		$lat = $gboRs[6];
		$distId = $gboRs[17];
		$syncStatus = 1;
		$paymentMethod = $gboRs[12];

		

		$timestamp = $gboRs[8];



		$i_date = $gboRs[3];
		$i_time = $gboRs[4];


		





		$order['order_id'] = $orderId;
		$order['outlet_id'] = $outletId;
		$order['route_id'] = $routeId;
		$order['invoice_time'] = $invoiceTime;
		$order['battery'] = $battery;
		$order['lng'] = $lng;
		$order['lat'] = $lat;
		$order['dist_id'] = $distId;
		$order['sync_status'] = $syncStatus;
		$order['payment_method'] = $paymentMethod;
		
		// $order['i_date'] = $i_date;
		// $order['i_time'] = $i_time;
		$order['time_stamp'] = $timestamp;




		// $gross = $gboRs[];
		// $total = $gboRs[];
		

		///free issue details

		$getOrderFree = $conn->query("SELECT * FROM tbl_order_free_issues WHERE order_id = '$order_id'");
		while($gofRe = $getOrderFree->fetch_array()){

			


			$freeId = $gofRe[0];
			$itemName = $gofRe[2];
			$itemPrice = $gofRe[3];
			$freeQty = $gofRe[4];
			$supplierId = $gofRe[6];
			$distId = $gofRe[7];

			$free['free_id'] = $freeId;
			$free['item_name'] = $itemName;
			$free['item_price'] = $itemPrice;
			$free['free_qty'] = $freeQty;
			$free['supplier_id'] = $supplierId;
			$free['dist_id'] = $distId;



			array_push($freeIssueList, $free);



		}


		////////////////////

		$order['free_list'] = $freeIssueList;






		$getOrderDeliveryData = $conn->query("SELECT * FROM tbl_order_delivery WHERE order_id = '$order_id'");
		if($odelRs = $getOrderDeliveryData->fetch_array()){

			$deliveryStatus = $odelRs[2];
			$deliveryDate = $odelRs[3];

			$order['delivery_status'] =  $deliveryStatus;
			$order['delivery_date'] =  $deliveryDate;

		}



		$getRouteName = $conn->query("SELECT route_name FROM tbl_route WHERE route_id = '$routeId'");
		if($rRs = $getRouteName->fetch_array()){
			$routeName = $rRs[0];
			$order['route_name'] = $routeName;
		}





		/////get outlet details////



$getOutletDetails = $conn->query("SELECT tblout.outlet_id,tblout.outlet_name,tblout.address,tblout.contact,tblout.outlet_discount FROM tbl_outlet tblout WHERE tblout.outlet_id = '$outletId' AND tblout.route_id ='$routeId'");

if($outRs = $getOutletDetails->fetch_array()){
	
	$outletId = $outRs[0];
	$outletName = $outRs[1];
	$address = $outRs[2];
	$contact = $outRs[3];
	$outletDiscount = $outRs[4];
	$outstandingTotal = 0;


	/////////////////


		$getOutstanding = $conn->query("SELECT editable_total FROM tbl_credit_orders WHERE outlet_id = '$outletId'");
		while($osRs = $getOutstanding->fetch_array()){
			$outstandingTotal += $osRs[0];
		}

	/////////////////




	$outlet['outlet_id'] = $outletId;
	$outlet['outlet_name'] = $outletName;
	$outlet['outlet_address'] = $address;
	$outlet['outlet_contact'] = $contact;
	$outlet['outlet_discount'] = $outletDiscount;
	$outlet['outstanding'] = $outstandingTotal;



	$order['outlet'] = $outlet;


}






		///////////////////////////get order details///////


	$getOrderDetails = $conn->query("SELECT ti.itemDescription,ti.itemId,tod.qty,tod.price,tod.id FROM tbl_order_item_details tod INNER JOIN tbl_item ti ON tod.itemId=ti.itemId WHERE tod.order_id = '$order_id'");
	while($oiRs = $getOrderDetails->fetch_array()){

		  // od.setItemDescription(getOrderDetails.getString(19));
    //                 od.setItemId(getOrderDetails.getInt(1));
    //                 od.setQty(getOrderDetails.getInt(4));
    //                 od.setPrice(getOrderDetails.getDouble(2));

		$itemDes = $oiRs[0];
		$itemId = $oiRs[1];
		$qty = $oiRs[2];
		$price = $oiRs[3];
		$order_item_detail_id = $oiRs[4];
		$unit_per_pack = 0;


		$get_pack_details = $conn->query("SELECT unit_per_pack FROM tbl_order_detail_packs WHERE order_item_details_id = '$order_item_detail_id'");

		if($pd_rs = $get_pack_details->fetch_array()){
			$unit_per_pack = $pd_rs[0];
		}



		  if($qty > $unit_per_pack){

		  	if($unit_per_pack > 0){
		  		$pack_qty = intval($qty/$unit_per_pack);
		  	}else{
		  		$pack_qty = 0;
		  	}

		  	
		  	$unit_qty = ( $qty - ($pack_qty*$unit_per_pack) );

		  	$order_item['test_u_q'] = $qty." - (".$pack_qty." * ".$unit_per_pack.")";
		  

		  }else{
		  	$pack_qty = 0;
		  	$unit_qty = $qty;
		  }

		  



		$order_item['item_des'] = $itemDes;
		$order_item['item_id'] = $itemId;
		
		$order_item['qty'] = $unit_qty;
		$order_item['pack_qty'] = $pack_qty;
		$order_item['units_per_pack'] = $unit_per_pack;
		$order_item['total_qty'] = $qty;
		


		$order_item['price'] = $price;


		array_push($orderItemsList, $order_item);



	}


	$order['order_details_list'] = $orderItemsList;






		//////////////////////////










		$output["result"]=true;
		$output["data"] = $order;


	}else{
		$output["result"]=false;
		$output["msg"] = "Failed to verify order.";
	}
	
	
	

	
	

}else{

	$output["result"]=false;
	$output["msg"]="Something went wrong.Please try again.";
	// $output["login_time_stamp"]=$login_time_stamp;

}
echo json_encode($output);







	