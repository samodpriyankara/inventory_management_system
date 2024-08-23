<?php
	
  require '../database/db.php';
  $db=new DB();
  $conn=$db->connect();

  /////////////////////////////////

	$output=[];
	$dataList=array();
	$timeFrameList = array();
	$outlet_list = array();

  if(isset($_POST['user_id']) && isset($_POST['search_date']) && isset($_POST['time_frame'])){
  		$userId=htmlspecialchars($_POST['user_id']);
  		$searchDate=htmlspecialchars($_POST['search_date']);
  		$time_frame=htmlspecialchars($_POST['time_frame']);




  		///get rep route and shops details///////////




  			$get_route = $conn->query("SELECT tuhr.route_id FROM tbl_user_has_routes tuhr WHERE tuhr.date = '$searchDate' AND tuhr.user_id = '$userId'");
  			if($rrs = $get_route->fetch_array()){

  				$route_id = $rrs[0];

  				$get_all_shops = $conn->query("SELECT tblo.outlet_name,tblo.lat,tblo.lon,tblo.outlet_id,tblo.owner_name,tblo.contact,tblo.image FROM tbl_outlet tblo WHERE tblo.route_id = '$route_id'");
  				while($srs = $get_all_shops->fetch_array()){
  					$outlet['name'] = $srs[0];
  					$outlet['lat'] = $srs[1];
  					$outlet['lng'] = $srs[2];
            
            $outlet['outlet_id'] = base64_encode($srs[3]);
            $outlet['owner'] = $srs[4];
            $outlet['contact'] = $srs[5];
            $outlet['image'] = html_entity_decode($srs[6]);

  					array_push($outlet_list, $outlet);

  				}









  			}




  			$output['outlets']=$outlet_list;






  		//////////////////////////////////////////////








  		if($time_frame == ""){

  			$timeFrameList = array();


  			$rs=$conn->query("SELECT tgt.lat,tgt.lon,tgt.time FROM tbl_gps_track tgt WHERE tgt.user_id='$userId' AND tgt.date='$searchDate' ORDER BY tgt.time ASC");
  		
  		$num_rows=mysqli_num_rows($rs);

  		

  		if($num_rows>0){
  				

  			while ($row=$rs->fetch_array()) {

	  			$data['lat']=$row[0];
	  			$data['lon']=$row[1];

	  			$timeFrame = '<option value='.$row[2].'>'.$row[2].'</option>';


	  			array_push($dataList,$data);
	  			array_push($timeFrameList,$timeFrame);

		}


		$output['result']=true;
		$output['locations']=$dataList;
		$output['time_frames']=$timeFrameList;

  		}else{

			$output['result']=false;
  			$output['msg']="No data found.";

  		}




  		}else{

  			$timeFrameList = array();

  			$rs=$conn->query("SELECT tgt.lat,tgt.lon,tgt.time,tgt.battery_level FROM tbl_gps_track tgt WHERE tgt.user_id='$userId' AND tgt.date='$searchDate' AND tgt.time='$time_frame' ORDER BY tgt.time ASC");
  		
  			$num_rows=mysqli_num_rows($rs);

  		

  		if($num_rows>0){
  				

  			while ($row=$rs->fetch_array()) {

	  			$data['lat']=$row[0];
	  			$data['lon']=$row[1];
          $data['battery_level']=$row[3];
				array_push($dataList,$data);
	  			

			}



		/////////get all time frames///







		$getFrames=$conn->query("SELECT tgt.time FROM tbl_gps_track tgt WHERE tgt.user_id='$userId' AND tgt.date='$searchDate' ORDER BY tgt.time ASC");
		while($gfRs = $getFrames->fetch_array()){

			$timeFrame = '<option value='.$gfRs[0].'>'.$gfRs[0].'</option>';
			array_push($timeFrameList,$timeFrame);

		}









		//////////////////////////////


		$output['result']=true;
		$output['locations']=$dataList;
		$output['time_frames']=$timeFrameList;

  		}else{

			$output['result']=false;
  			$output['msg']="No data found.";

  		}











  		}








  		

	}else{

  	$output['result']=false;
  	$output['msg']="Please fill all required fields.";

  	}








	/*$values=array();
	$output['result']=true;
	$output['data']="fetching data";

	$value=[];
	$value['id']=1;
	$value['name']='Haritha';


	$value1=[];
	$value1['id']=2;
	$value1['name']='Wicky';


	array_push($values,$value);
	array_push($values,$value1);

	$output['list']=$values;*/


	echo json_encode($output);





