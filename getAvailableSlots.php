<?php
require 'connect.php';
require 'functions.php';
require 'events.php';

if (!empty($_GET['workshop'])){
  $workshop = mysqli_real_escape_string($db, $_GET['workshop']);
  $availabeSlots = array();

  foreach ($workshopslotmaxmembers[$workshop] as $slot => $maxmembers) {
    $sql = "SELECT * FROM `workshops` WHERE `$workshop` = \"$slot\"";
    $result = executeQuery($db, $sql);
    if ($result->num_rows < $maxmembers){
      array_push($availabeSlots, $slot);
    }
  }
  $message = array("status" => "success", "slots" => $availabeSlots);
  echo json_encode($message);
}else{
  $message = array("status" => "error", "description" => "No Workshop Mentioned");
  echo json_encode($message);
}
?>
