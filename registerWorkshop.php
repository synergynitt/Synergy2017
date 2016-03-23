<?php
require 'connect.php';
require 'functions.php';

session_start();
$workshop = mysqli_real_escape_string($db, $_GET['workshop']);
$groupid = mysqli_real_escape_string($db, $_GET['groupid']);
$slot = mysqli_real_escape_string($db, $_GET['slot']);

if (!isset($_SESSION['userid'])){
  $message = array ("status" => "logout", "description" => "You need to log in to register for events");
  echo json_encode($message);
  die();
}
if (!checkIfRegIsPossible($db, $groupid, $workshop, "workshops")){
  $message = array ("status" => "fail", "description" => "Someone in this Group Already registered with an another group");
  echo json_encode($message);
  die();
}
$sql = "SELECT * FROM `workshops` WHERE `groupid`=\"$groupid\"";
$result = executeQuery($db, $sql);
if ($result->num_rows == 0){
  $insert_sql = "INSERT INTO `workshops` (groupid) VALUES (\"$groupid\")";
  executeQuery($db, $insert_sql);
}
$update_sql = "UPDATE `workshops` SET `$workshop`=\"$slot\" WHERE `groupid`=\"$groupid\"";
executeQuery($db, $update_sql);
$message = array ("status" => "success", "description" => "You are registered", 'code' => $db->affected_rows);
echo json_encode($message);
