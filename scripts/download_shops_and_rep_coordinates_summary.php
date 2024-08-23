<?php
	session_start();
  require '../database/db.php';
  $db=new DB();
  $conn=$db->connect();

  /////////////////////////////////

	$output=[];
	$dataList=array();
	$outlet_list = array();


  if(isset($_SESSION['user'])){


    $get_all_users = $conn->query("SELECT id,name,contact_no FROM tbl_user WHERE status = 1 ORDER BY id ASC");
    while($urs = $get_all_users->fetch_array()){
      $user_id = $urs[0];
      $name = $urs[1];
      $contact = $urs[2];


      ////get last gps track////

      $get_track_data = $conn->query("SELECT tgt.lat,tgt.lon,tgt.date,tgt.time,tgt.battery_level FROM tbl_gps_track tgt WHERE tgt.user_id = '$user_id' ORDER BY tgt.id DESC LIMIT 1");
      if($trs = $get_track_data->fetch_array()){

          $data['id'] = $user_id;
          $data['name'] = $name;
          $data['contact'] = $contact;
          $data['lat']=$trs[0];
          $data['lng']=$trs[1];

          $data['date']=$trs[2];
          $data['time']=$trs[3];
          $data['battery']=$trs[4];

          array_push($dataList, $data);
      }

      //////////////////////////



    }





          $get_all_shops = $conn->query("SELECT tblo.outlet_name,tblo.lat,tblo.lon,tblo.outlet_id,tblo.owner_name,tblo.contact FROM tbl_outlet tblo");
          while($srs = $get_all_shops->fetch_array()){
            $outlet['name'] = $srs[0];
            $outlet['lat'] = $srs[1];
            $outlet['lng'] = $srs[2];


            $outlet['outlet_id'] = $srs[3];
            $outlet['owner'] = $srs[4];
            $outlet['contact'] = $srs[5];

            array_push($outlet_list, $outlet);

          }


    $output['result'] = true;
    $output['locations'] = $dataList;
    $output['outlets']=$outlet_list;


  }else{
    $output['result'] = false;
    $output['msg'] = "Something went wrong, plese try again.";
  }





	echo json_encode($output);





