<?php
date_default_timezone_set('Asia/Colombo');
//--------------------------------
require 'db/db_connection.php';
$db=new DB();
$conn=$db->connect();
//--------------------------------


$categoriesList=array();
$output=[];

try {

	$rs=$conn->query("SELECT * FROM tbl_shop_category");
	while ($row=$rs->fetch_array()) {
		
		$category['id']=$row[0];
		$category['name']=$row[1];

		array_push($categoriesList,$category);



	}

$output['result']=true;
$output['category_list']=$categoriesList;



	
} catch (Exception $e) {

	$output['result']=false;
	$output['msg']="Something went wrong.";
	
}


echo json_encode($output);













