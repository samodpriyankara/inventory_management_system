<?php
  require '../database/db.php';
  $db=new DB();
  $conn=$db->connect();
/////////////////////////////////

	
	$output=[];
	$productsList = array();
	

	if(isset($_POST['dist_id'])){
		
		$distId = htmlspecialchars($_POST['dist_id']);


		$get_dist_products = $conn->query("SELECT * FROM tbl_distributor_has_products WHERE distributor_id = '$distId'");
	

	while($dprs = $get_dist_products->fetch_array()){

		$product_id = $dprs[1];
		$assigned_qty = $dprs[3];
		$assigned_cost = $dprs[4];




		// $getProducts = $conn->query("SELECT itemId,itemDescription,price,stock,item_img FROM tbl_item WHERE distributor_id = '$distId' AND stock > 0 ORDER BY itemDescription ASC");
		

		$getProducts = $conn->query("SELECT ti.itemId,ti.itemDescription,ti.price,ti.stock,ti.item_img,ti.genaricName FROM tbl_item ti WHERE ti.itemId= '$product_id' ORDER BY ti.itemDescription ASC");


		if ($pRs = $getProducts->fetch_array()) {
			$itemId = $pRs[0];
			$description = $pRs[1];
			$id_discount = $pRs[5];
// 			$price = $pRs[2];


            /////////////GET DISTRIBUTOR PRICE AS SELLING PRICE//////////
            $price = 0;
            $get_other_prices = $conn->query("SELECT * FROM tbl_item_other_prices WHERE item_id = '$itemId'");
            if($gop = $get_other_prices->fetch_array()){
                $price = $gop[4];
            }
            
            //////////////////////



            ///GET CATEGORY WISE DISCOUNT////
            $category_discount = 0;
            $discount_margin = 0;
            $find_discount_price = $conn->query("SELECT discount,status_code,discount_margin FROM tbl_free_issue_scheme WHERE item_id = '$id_discount'");
			if ($drsp = $find_discount_price->fetch_array()) {
				if ($drsp[1] == 1) {

					$category_discount = $drsp[0];
					$discount_margin = $drsp[2];
				
					
				} else {
					$find_discount = $conn->query("SELECT tdfc.discount_percentage,tdfc.category_id FROM tbl_discount_for_each_category tdfc INNER JOIN tbl_category tc ON tdfc.category_id = tc.category_id INNER JOIN tbl_item ti ON ti.category_id=tc.category_id WHERE ti.itemId = '$itemId'");
					if ($drs = $find_discount->fetch_array()) {
						$category_discount = $drs[0];
						
					}
				}
			} else {


				$find_discount = $conn->query("SELECT tdfc.discount_percentage,tdfc.category_id FROM tbl_discount_for_each_category tdfc INNER JOIN tbl_category tc ON tdfc.category_id = tc.category_id INNER JOIN tbl_item ti ON ti.category_id=tc.category_id WHERE ti.itemId = '$itemId'");
				if ($drs = $find_discount->fetch_array()) {
					$category_discount = $drs[0];
					
				}
			}


			

			// $stockQty = $pRs[3];
			$stockQty = $assigned_qty;
			
			$image = $pRs[4];

			$obj = "";
			


			if($image == "" || $image == NULL){
				$image = 'placeholders/no_image_found.png';
			}

			$inputId = "'".'txt_'.$itemId."'";
			$lblId = "'".'lbl_'.$itemId."'";

			






			$obj = '<tr><td>'.$itemId.'</td><td><img src='.$image.' width=50 height=50/></td><td>'.$description.'</td><td>'.$stockQty.'</td><td>LKR '.number_format($price,2).'</td><td>  <input type="number" id='.$inputId.' min="1" class="form-control" style="width:80px;text-align:right;font-size: 24px;border-radius:0px"  onkeyup="check_key_up(event,'.$inputId.','.$lblId.','.$itemId.','.$price.','.$stockQty.',\''.$description.'\','.$category_discount.',' . $discount_margin . ')"/> 



			<input type="text" value="0" id='.$lblId.' class="form-control" style="width:80px;text-align:center;color:red;font-size: 24px;border-radius:0px" disabled/>  



			 </td><td><button class="btn btn-success" onclick="addToCart('.$inputId.','.$lblId.','.$itemId.','.$price.','.$stockQty.',\''.$description.'\','.$category_discount.','.$discount_margin.')">ADD</button></td></tr>';


			 if($assigned_qty > 0){
			 	array_push($productsList, $obj);
			 }

			
			


		}





	}	



		









		$output['result']=true;
		$output['data']=$productsList;
		

	}else{
		$output['result']=false;
		$output['msg']="Required fields are not provided.";
	}


echo json_encode($output);