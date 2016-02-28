<?php
require 'connect.php';
session_start();
$event = mysqli_real_escape_string($db, $_GET['event']);

if (isset($_SESSION['userid'])){
  $userid=$_SESSION['userid'];
  $sql = "SELECT * FROM `events` WHERE `userid`=\"$userid\"";
  if (!$result = $db->query($sql)){
    $message = array ("status"=>"fail","description"=>"Can't Access Database", "error"=>$db->error);
    echo json_encode($message);
    die();
  }
  if ($result->num_rows == 0){
    $insert_sql = "INSERT INTO `events` (userid) VALUES (\"$userid\")";
    if (!$insert_result = $db->query($insert_sql)){
      $message = array ("status"=>"fail","description"=>"User couldn't be registered", 'error'=>$db->error);
      echo json_encode($message);
      die();
    }
  }
  $update_sql = "UPDATE `events` SET `$event`=\"1\" WHERE `userid`=\"$userid\"";
  if (!$update_result = $db->query($update_sql)){
    $message = array ("status"=>"fail","description"=>"User couldn't be registered", 'error'=>$db->error);
    echo json_encode($message);
    die();
  }
  $message = array ("status"=>"success","description"=>"You are registered", 'code'=>$db->affected_rows);
  echo json_encode($message);
} else {
  $message = array ("status"=>"logout", "description"=>"You need to log in to register");
  echo json_encode($message);
  die();
}

$db->close();
 ?>
