<?php
      session_start();
      date_default_timezone_set('Asia/Colombo');
      require '../database/db.php';
      $db=new DB();
      $conn=$db->connect();

  ////////////////////////////////////////

  $output=[];
  $empDataList=array();


  

  if(isset($_POST['selected_date'])){

      $is_distributor = $_SESSION['DISTRIBUTOR'];
      $user_id = $_SESSION['ID'];
      $selectedDate=htmlspecialchars($_POST['selected_date']);
     
      
      if($selectedDate==""){
          
          $selectedDate=date('Y-m-d');
          
      }
      
      
      
      
    if($is_distributor){
      $getEmpDataQuery=$conn->query("SELECT * FROM tbl_user tu INNER JOIN tbl_distributor_has_tbl_user tdhu ON tu.id=tdhu.user_id tdhu.distributor_id = '$user_id' ORDER BY tu.name ASC");
    }else{
      $getEmpDataQuery=$conn->query("SELECT * FROM tbl_user ORDER BY name ASC");
    }

    

      $rowData="";
      while($empDataRs=$getEmpDataQuery->fetch_array()){
          
          $empId=$empDataRs[0];

          $empData['emp_id']=$empId;
          $empData['emp_name']=$empDataRs[1];
          $empData['inTime']='N/A';
          $empData['inDate']=$selectedDate;
          $empData['in_loc']="N/A";

          $empData['in_absent'] = true;

          $empData['outTime']='N/A';
          $empData['outDate']='N/A';
          $empData['out_absent']=true;
          $empData['out_loc']="N/A";

          
          
          $empInTime='00:00:00';
          $empOutTime='00:00:00';


          $empInDate='0000-00-00';
          $empOutDate='0000-00-00';

          
           $inAttId=0;
           $outAttId=0;
          

          $get_date_query = $conn->query("SELECT * FROM tbl_attendance WHERE user_id='$empId' ORDER BY attendance_id DESC");
          while($rs_date = $get_date_query->fetch_array()){

              $mil = $rs_date[1];
              
              $seconds = ceil($mil / 1000);
              $attendance_date = date("Y-m-d", $seconds);
              $attendance_time = date("H:i:s", $seconds);
              $status=$rs_date[5];


              if($selectedDate == $attendance_date && $status == '0'){
                  $inAttId=$rs_date[0];
                  $lat=$rs_date[2];
                  $lng=$rs_date[3];


                  $empInTime=$rs_date[2];
                  $empData['inTime']=$attendance_time;
                  $empData['inDate']=$attendance_date;
                  $empData['in_absent']=false;

                  if($lat==0 || $lng==0){
                    $empData['in_loc']="N/A";
                  }else{
              
                    $link="https://www.google.com/maps/search/?api=1&query=$lat,$lng";
              
                
                    $empData['in_loc']='<a href=# class="btn btn-dark" onClick=window.open("'.$link.'");>VIEW</a>';
                 
                 
                  }



              }




              ////////////////////////



              $get_out_date_query = $conn->query("SELECT * FROM tbl_attendance WHERE user_id='$empId' ORDER BY attendance_id ASC");
              while($rs_o_date = $get_out_date_query->fetch_array()){

                $mil = $rs_o_date[1];
              
              $seconds = ceil($mil / 1000);
              $attendance_date = date("Y-m-d", $seconds);
              $attendance_time = date("H:i:s", $seconds);
              $status=$rs_date[5];

                if($selectedDate == $attendance_date && $status == '1'){
                  $outAttId=$rs_date[0];
                  $lat=$rs_date[2];
                  $lng=$rs_date[3];


                  $empData['outTime']=$attendance_time;
                  $empData['outDate']=$attendance_date;
             

                  $empOutTime=$rs_date[1];

                  $empData['out_absent']=false;


                  if($lat==0 || $lng==0){
                      $empData['out_loc']="N/A";
                  }else{
                       
                    $link="https://www.google.com/maps/search/?api=1&query=$lat,$lng";
                    $empData['out_loc']='<a href=# class="btn btn-dark" onClick=window.open("'.$link.'");>VIEW</a>';
                  
                  
                  
                  }





                }

              }






          //     else if($selectedDate == $attendance_date && $status == '1'){

          //     $outAttId=$rs_date[0];
          //     $lat=$rs_date[2];
          //     $lng=$rs_date[3];
              
              
          //    $empData['outTime']=$attendance_time;
          //    $empData['outDate']=$attendance_date;
             

          //    $empOutTime=$rs_date[1];

          //    $empData['out_absent']=false;


          //   if($lat==0 || $lng==0){
          //       $empData['out_loc']="N/A";
          //   }else{
                 
          //     $link="https://www.google.com/maps/search/?api=1&query=$lat,$lng";
          //     $empData['out_loc']='<a href=# class="btn btn-dark" onClick=window.open("'.$link.'");>VIEW</a>';
            
            
            
          //   }




          // }





        }

        
          
          array_push($empDataList,$empData);
      }
      
      

      
      
      
      



    $output['result']=true;
    $output['emp']=$empDataList;

  }else{

    $output['result']=false;
    $output['msg']="invalid request";

    }



  echo json_encode($output);





