<?php

require 'connect.php';

$email=mysqli_real_escape_string($db, $_POST['email']);
$password=mysqli_real_escape_string($db, $_POST['password']);

$sql = <<<SQL
    SELECT *
    FROM `users`
    WHERE `email`="$email"
SQL;

if (!$result = $db->query($sql)){
    $message = array ("status"=>"fail","description"=>"You couldn't be logged in, Try Again", "error"=>$db->error);
    echo json_encode($message);
    die();
}

if ($result->num_rows == 0){
  $message = array ("status"=>"fail","description"=>"You have not registered");
  echo json_encode($message);
}

while ($row=$result->fetch_assoc()){
  $password_indb=$row['password'];
  if ($password_indb == $password){
    $userid=$row['userid'];
    $name=$row['name'];
    $college=$row['college'];
    $email=$row['email'];

    session_start();
    $_SESSION['userid']=$userid;
    $_SESSION['name']=$name;
    $_SESSION['college']=$college;
    $_SESSION['email']=$email;

    $message = array ("status" =>"success" , "description"=>"Logged In");
    echo json_encode($message);
  }else {
    $message = array ("status"=>"fail" , "description"=>"Wrong Email Password Combination ");
    echo json_encode($message);
  }
}

?>
