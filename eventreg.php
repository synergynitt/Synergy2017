<?php
require 'connect.php';
require 'functions.php';

session_start();
$event = mysqli_real_escape_string($db, $_GET['event']);
$groupid = mysqli_real_escape_string($db, $_GET['groupid']);

if (!isset($_SESSION['userid'])){
  $message = array ("status" => "logout", "description" => "You need to log in to register for events");
  echo json_encode($message);
  die();
}

if (!isset($_GET['deregister'])){
  if (!checkIfRegIsPossible($db, $groupid, $event, "events")){
    $message = array ("status" => "fail", "description" => "Someone in this Group Already registered with an another group");
    echo json_encode($message);
    die();
  }
  $sql = "SELECT * FROM `events` WHERE `groupid`=\"$groupid\"";
  $result = executeQuery($db, $sql);
  if ($result->num_rows == 0){
    $insert_sql = "INSERT INTO `events` (groupid) VALUES (\"$groupid\")";
    executeQuery($db, $insert_sql);
  }
  $update_sql = "UPDATE `events` SET `$event`=\"1\" WHERE `groupid`=\"$groupid\"";
  executeQuery($db, $update_sql);
  $message = array ("status" => "success", "description" => "You are registered", 'code' => $db->affected_rows);
  echo json_encode($message);

}else{

  $update_sql = "UPDATE `events` SET `$event`=\"0\" WHERE `groupid`=\"$groupid\"";
  executeQuery($db, $update_sql);
  $message = array ("status" => "deregistered", "description" => "You are deregistered", 'code' => $db->affected_rows);
  echo json_encode($message);

}

$db->close();

?>
