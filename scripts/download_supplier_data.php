<?php
  require '../database/db.php';
  $db=new DB();
  $conn=$db->connect();
/////////////////////////////////

	
	$output=[];
	

	if(isset($_POST['dis_id'])){
		$supplierList=array();
		$baseOption="<option value=0>SELECT SUPPLIER</option>";
		array_push($supplierList, $baseOption);

		$disId=htmlspecialchars($_POST['dis_id']);


		$getSuppliersQuery=$conn->query("SELECT ts.supplier_id,ts.name FROM tbl_supplier ts WHERE ts.distributor_id='$disId' ORDER BY ts.name ASC");
		
		

		while ($supplier=$getSuppliersQuery->fetch_array()) {
			
			$supplierObject='<option value='.$supplier[0].'>'.$supplier[1].'</option>';
			array_push($supplierList, $supplierObject);


		}


		$output['result']=true;
		$output['list']=$supplierList;

		


	}else{
		$output['result']=false;
		$output['msg']="Required fields are not provided.";
	}


echo json_encode($output);