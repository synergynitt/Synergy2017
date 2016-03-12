<?php
require 'connect.php';
require 'functions.php';

$email = mysqli_real_escape_string($db, $_GET['email']);

$sql = "SELECT * FROM `users` WHERE `email`=\"$email\"";
$result = executeQuery($db, $sql);

if ($result->num_rows == 0){

  $message = array ("status" => "error", "description" => "User Not Registered");
  echo json_encode($message);
  die();

}else{

  $row = $result->fetch_assoc();
  $userid = $row['userid'];
  $name = $row['name'];
  $college = $row['college'];
  $rollno = $row['rollno'];
  $email = $row['email'];
  $phone = $row['phone'];

  $sql = "SELECT * FROM `usergroup` WHERE `userid`=\"$userid\"";
  $result = executeQuery($db, $sql);
  $noofgroups = $result->num_rows;

  $groups = array();
  while($row = $result->fetch_assoc()){
    $groupid = $row['groupid'];
    $groupname = $row['groupname'];
    $sql2 = "SELECT * FROM `usergroup` WHERE `groupid`=\"$groupid\"";
    $result2 = executeQuery($db, $sql2);
    $members = $result2->num_rows;
    $group = array('id' => $groupid, 'name' => $groupname, 'members' => $members);
    array_push($groups, $group);
  }

  $user = array('name' => $name , 'college' => $college, 'rollno' => $rollno, 'email'=> $email, 'phone'=>$phone, 'noofgroups' => $result->num_rows, 'groups' => $groups );
  $message = array ("status" => "success", "description" => "User Found", "user" => $user);
  echo json_encode($message);

}
?>
