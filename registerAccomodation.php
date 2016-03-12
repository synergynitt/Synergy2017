<?php
require 'connect.php';
require 'functions.php';
session_start();

if (isset($_SESSION['userid'])){
  $userid=$_SESSION['userid'];
  $sql = "SELECT * FROM `users` WHERE `userid`=\"$userid\"";
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

    $sql = "SELECT * FROM `accomodation` WHERE `email`=\"$email\"";
    $result = executeQuery($db, $sql);
    if ($result->num_rows == 0){
      $insert_sql = "INSERT INTO `accomodation` (userid, name, college, email, rollno, phone) VALUES(\"$userid\",\"$name\",\"$college\",\"$email\",\"$rollno\",\"$phone\")";
      executeQuery($db, $insert_sql);
      $message = array("status" => "success", "description" => "Registered For Accomodation");
      echo json_encode($message);
    }else{
      $message = array("status" => "error", "description" => "You have already registered for accomodation");
      echo json_encode($message);
    }
  }
}
?>
