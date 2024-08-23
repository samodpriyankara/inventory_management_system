<?php
date_default_timezone_set('Asia/Colombo');
	//--------------------------------
require 'db/db_connection.php';
$db=new DB();
$conn=$db->connect();
//--------------------------------

	$categoryList=array();
if(isset($_POST['userId'])){


	
	$userId=htmlspecialchars($_POST['userId']);
	// $rs=$conn->query("SELECT * FROM tbl_category");
	

	$rs=$conn->query("SELECT * FROM tbl_category tc INNER JOIN supplier_has_category shc ON tc.category_id=shc.category_id INNER JOIN tbl_supplier ts ON ts.supplier_id=shc.supplier_id");

	while ($row=$rs->fetch_array()) {

		$category["id"]=$row[0];
		$category["name"] = htmlspecialchars_decode($row[1]);
		$category["supplier_id"]=$row[4];
		$category["distributor_id"]=$row[7];

		// $category["supplier_id"]= -1;
		// $category["distributor_id"]= -1;

		array_push($categoryList,$category);

}
	}



 echo json_encode($categoryList);


