<?php
  require '../database/db.php';
  $db=new DB();
  $conn=$db->connect();
  session_start();

/////////////////////////////////
  	$is_distributor = false;
  	$user_id = 0;
  	if(isset($_SESSION['DISTRIBUTOR'])){
      $is_distributor = $_SESSION['DISTRIBUTOR'];
    }
    if(isset($_SESSION['ID'])){
      $user_id = $_SESSION['ID'];
    }
/////////////////////////////////

	
	$output=[];
	$daysCount=0;
	$startingFrom="";


	if(isset($_POST['user_id']) && isset($_POST['year']) && isset($_POST['month'])){


			$userId=htmlspecialchars($_POST['user_id']);
			$year=htmlspecialchars($_POST['year']);
			$month=htmlspecialchars($_POST['month']);

			$getWorkingDaysCount=$conn->query("SELECT twd.days_count,twd.starting_from FROM tbl_working_days twd WHERE twd.year='$year' AND twd.month='$month'");
			if($gwdc=$getWorkingDaysCount->fetch_array()){

					$daysCount=$gwdc[0];
					$startingFrom=$gwdc[1];

			}

			$design="";
			$dayNo=1;
			$routeOptions="";


			$testVal="";
								
							






				for($i=0;$i<=$daysCount;$i++){

						
						$date = new DateTime($startingFrom);
						$date->modify("+$i day");
						$nxtDate=$date->format("Y-m-d");
						$routeDate="'".$nxtDate."'";


						//fetch data about user's route for the day

							$getUserCurrentRoute=$conn->query("SELECT * FROM tbl_route tr INNER JOIN tbl_user_has_routes tuhr ON tr.route_id=tuhr.route_id WHERE tuhr.user_id='$userId' AND tuhr.date='$nxtDate'");


							



								if($gucr=$getUserCurrentRoute->fetch_array()){

										$routeOptions.='<option value='.$gucr[0].'>'.$gucr[1].'</option>';


								}else{


									/////download distributors routes/////




									$routeOptions="<option>PICK A ROUTE</option>";


									if($is_distributor){

									   $getRoutes=$conn->query("SELECT tr.route_id,tr.route_name FROM tbl_route tr INNER JOIN tbl_distributor_has_route tdhr ON tr.route_id = tdhr.route_id WHERE tdhr.distributor_id = '$user_id'");



									}else{
										$getRoutes=$conn->query("SELECT route_id,route_name FROM tbl_route");
									}



									
								
										while ($gr=$getRoutes->fetch_array()) {
											$routeOptions.='<option value='.$gr[0].'>'.$gr[1].'</option>';
										}

								}



									

/////////////////////////





						// $design.='<tr>
      //                     <td class="text-center">'.$dayNo.'</td>
      //                     <td><input type="text" class="form-control" value="'.$nxtDate.'" disabled></td>
                          
      //                    <td><select id="txt-rp-route" class="form-control" onChange="changeUserRoute(this.value,'.$routeDate.')">
      //                      '.$routeOptions.'
						//  </select></td> 



      //                   <td><button class="btn btn-primary pull-right" onClick="enableChangingRoute('.$routeDate.','.$userId.')">change</button></td>
      //                   </tr>';


						$design.='<tr>
                         
                          <td><input type="text" class="form-control" value="'.$nxtDate.'" disabled></td>
                          
                         <td><select id="txt-rp-route" class="form-control" onChange="changeUserRoute(this.value,'.$routeDate.')">
                           '.$routeOptions.'
						 </select></td> 



                        <td><button class="btn btn-primary pull-right" onClick="enableChangingRoute('.$routeDate.','.$userId.')">change</button></td>
                        </tr>';


                        ++$dayNo;
                       
                       	$routeOptions="";






				}




				// $date = new DateTime($startingFrom);
				// $date->modify("+7 day");





			$output['result']=true;
			$output['msg']=$design;
			


	}else{

			$output['result']=false;
			$output['msg']="Required data not provided.";

	}


	



	echo json_encode($output);