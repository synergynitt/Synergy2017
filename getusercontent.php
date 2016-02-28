<?php
require 'connect.php';
require 'events.php';
session_start();

if (isset($_SESSION['userid'])){

  $userid=$_SESSION['userid'];

  $sql = "SELECT * FROM `events` WHERE `userid`=\"$userid\"";
  if (!$result = $db->query($sql)){
    $message = array ("status"=>"fail","description"=>"Can't Access Database", "error"=>$db->error);
    echo json_encode($message);
    die();
  }

  while ($row=$result->fetch_assoc()) {
    $registeredEvents = array();
    $registeredEventsCode = array();
    foreach ($events as $key => $value) {
      if ($row[$key]==1){
        array_push($registeredEvents,"$value");
        array_push($registeredEventsCode,"$key");
      }
    }
    $message = array ("status"=>"success","registeredEvents"=>$registeredEvents,"registeredEventsCode"=>$registeredEventsCode);
    echo json_encode($message);
  }

}else{
  $message = array ("status"=>"logout", "description"=>"Not logged in");
  echo json_encode($message);
  die();
}
