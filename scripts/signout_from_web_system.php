<?php

  session_start();
  session_destroy();
  // $output['result']=true;
  // echo json_encode($output);

  header("Location:../index");

?>
