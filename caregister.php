<?php
require 'connect.php';

$name=mysqli_real_escape_string($db, $_POST['name']);
$email=mysqli_real_escape_string($db, $_POST['email']);
$college=mysqli_real_escape_string($db, $_POST['college']);
$fbname=mysqli_real_escape_string($db, $_POST['fbname']);
$phone=mysqli_real_escape_string($db, $_POST['phone']);
$password=mysqli_real_escape_string($db, $_POST['password']);

if ($name==" "||$name==""){
  $message=array("status"=>"fail","description"=>"Name cannot be empty");
  echo json_encode($message);
  die();
}
if ($phone==""){
  $message=array("status"=>"fail","description"=>"Contact Number cannot be empty");
  echo json_encode($message);
  die();
}
if ($college==""){
  $message=array("status"=>"fail","description"=>"College cannot be empty");
  echo json_encode($message);
  die();
}
if ($email==""){
  $message=array("status"=>"fail","description"=>"Email cannot be empty");
  echo json_encode($message);
  die();
}
if ($password==""){
  $message=array("status"=>"fail","description"=>"Password cannot be empty");
  echo json_encode($message);
  die();
}

$sql = "SELECT * FROM `ambassadors` WHERE `email`=\"$email\"";
if (!$result = $db->query($sql)){
  $message = array ("status"=>"fail","description"=>"User couldn't be registered", "error"=>$db->error);
  echo json_encode($message);
  die();
}

if ($result->num_rows == 0){
  $insert_sql = "INSERT INTO `ambassadors` (name, college, email, fbname, phone, password) VALUES(\"$name\",\"$college\",\"$email\",\"$fbname\",\"$phone\",\"$password\")";
  if (!$insert_result = $db->query($insert_sql)){
    $message = array ("status"=>"fail","description"=>"User couldn't be registered", 'error'=>$db->error);
    echo json_encode($message);
    die();
  }
  $message=array("status"=>"success","description"=>"You have been Registered. We will get in touch with you soon.");
  echo json_encode($message);
}else{
  $message=array("status"=>"success","description"=>"You have registered before. We will get in touch with you soon.");
  echo json_encode($message);
}

?>
