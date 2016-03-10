<?php
require 'connect.php';

$email=mysqli_real_escape_string($db, $_GET['email']);
$sql = "SELECT * FROM `users` WHERE `email`=\"$email\"";
if (!$result = $db->query($sql)){
  $message = array ("status"=>"error","description"=>"Database Error", "error"=>$db->error);
  echo json_encode($message);
  die();
}
if ($result->num_rows == 0){
  $message = array ("status"=>"error","description"=>"User Not Registered", "error"=>$db->error);
  echo json_encode($message);
  die();
}else{
  $row=$result->fetch_assoc();
  $name=$row['name'];
  $college=$row['college'];
  $rollno=$row['rollno'];
  $email=$row['email'];

  $user = array('name' =>$name ,'college'=>$college,'rollno'=>$rollno,'email'=>$email );
  $message = array ("status"=>"success","description"=>"User Found", "user"=>$user);
  echo json_encode($message);

}



 ?>
