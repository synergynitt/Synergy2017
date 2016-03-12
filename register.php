<?php

require 'connect.php';
require 'functions.php';

$name=mysqli_real_escape_string($db, $_POST['name']);
$email=mysqli_real_escape_string($db, $_POST['email']);

$sql = "SELECT * FROM `users` WHERE `email`=\"$email\"";
$result = executeQuery($db, $sql);

if (isset($_POST['fbid'])){
  $fbid=mysqli_real_escape_string($db,$_POST['fbid']);

  if ($result->num_rows == 0){

    $insert_sql = "INSERT INTO `users` (name, email, fbid) VALUES(\"$name\",\"$email\",\"$fbid\")";
    executeQuery($db, $insert_sql);
    $userid = getUserId($db, $email);
    $groupid = createGroup($db,$name,1);
    addUserToGroup($db, $userid, $groupid);

    $update_sql = "UPDATE `usergroup` SET `own`=\"1\" WHERE `groupid`=\"$groupid\" AND `userid`=\"$userid\"";
    executeQuery($db, $update_sql);

  }else if($row=$result->fetch_assoc()) {

    $fbid_indb=$row['fbid'];
    if (is_null($fbid_indb)){
      $update_sql = "UPDATE `users` SET fbid=\"$fbid\" WHERE `email`=\"$email\"";
      executeQuery($db, $update_sql);
    }

    if (isset($_POST['college'])){
      $college=mysqli_real_escape_string($db,$_POST['college']);
      $rollno=mysqli_real_escape_string($db,$_POST['rollno']);
      $update_sql = "UPDATE `users` SET `college`=\"$college\",`rollno`=\"$rollno\" WHERE `email`=\"$email\"";
      executeQuery($db, $update_sql);
    }

  }

  $result = executeQuery($db, $sql);
  while ($row=$result->fetch_assoc()){
    $fbid_indb=$row['fbid'];
    if ($fbid_indb == $fbid){

      $college=$row['college'];
      if (is_null($college)){
        $message = array ("status" =>"success" , "description"=>"Get College Details");
        echo json_encode($message);
        die();
      }

      session_start();
      $_SESSION['userid']=$row['userid'];
      $_SESSION['name']=$row['name'];
      $_SESSION['college']=$row['college'];
      $_SESSION['rollno']=$row['rollno'];
      $_SESSION['email']=$row['email'];

      $message = array ("status" =>"success" , "description"=>"User Registered. Logged In");
      echo json_encode($message);
    }else {
      $message = array ("status"=>"fail" , "description"=>"User already registered. Wrong Email fbid Combination ");
      echo json_encode($message);
    }
  }

}

if (isset($_POST['password'])){
  $password=mysqli_real_escape_string($db, $_POST['password']);
  $college=mysqli_real_escape_string($db, $_POST['college']);
  $rollno=mysqli_real_escape_string($db,$_POST['rollno']);

  if ($result->num_rows == 0){
    $insert_sql = "INSERT INTO `users` (name, college, email, password, rollno) VALUES(\"$name\",\"$college\",\"$email\",\"$password\",\"$rollno\")";
    executeQuery($db, $insert_sql);
    $userid = getUserId($db, $email);
    $groupid = createGroup($db,$name,1);
    addUserToGroup($db, $userid, $groupid);
    $update_sql = "UPDATE `usergroup` SET `own`=\"1\" WHERE `groupid`=\"$groupid\" AND `userid`=\"$userid\"";
    executeQuery($db, $update_sql);
  }

  $result = executeQuery($db, $sql);
  while ($row=$result->fetch_assoc()){
    $password_indb=$row['password'];
    if (is_null($row['password'])){
      $update_sql = "UPDATE `users` SET password=\"$password\" WHERE `email`=\"$email\"";
      executeQuery($db, $update_sql);
      $name = $row['name'];
      $userid = $row['userid'];
      $groupid = createGroup($db, $name,"1");
      addUserToGroup($db, $userid, $groupid);
      $update_sql = "UPDATE `usergroup` SET `own`=\"1\" WHERE `groupid`=\"$groupid\" AND `userid`=\"$userid\"";
      executeQuery($db, $update_sql);
      $result = executeQuery($db, $sql);
      $row=$result->fetch_assoc();
      $password_indb=$row['password'];
    }

    if ($password_indb == $password){
      session_start();
      $_SESSION['userid']=$row['userid'];
      $_SESSION['name']=$row['name'];
      $_SESSION['college']=$row['college'];
      $_SESSION['rollno']=$row['rollno'];
      $_SESSION['email']=$row['email'];

      $message = array ("status" =>"success" , "description"=>"User already registered. Logged In");
      echo json_encode($message);
    }else {
      $message = array ("status"=>"fail" , "description"=>"User already registered. Wrong Email Password Combination ");
      echo json_encode($message);
    }
  }
}

?>
