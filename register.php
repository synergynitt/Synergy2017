<?php

require 'connect.php';

$name=mysqli_real_escape_string($db, $_POST['name']);
$college=mysqli_real_escape_string($db, $_POST['college']);
$email=mysqli_real_escape_string($db, $_POST['email']);
$password=mysqli_real_escape_string($db, $_POST['password']);

$sql = <<<SQL
    SELECT *
    FROM `users`
    WHERE `email`="$email"
SQL;
if (!$result = $db->query($sql)){
  $message = array ("status"=>"fail","description"=>"User couldn't be registered", "error"=>$db->error);
  echo json_encode($message);
  die();
}
if ($result->num_rows ==0){
  $insert_sql = <<<SQL
      INSERT INTO `users`
      (name, college, email, password)
      VALUES
      ("$name","$college","$email","$password")
SQL;
  if (!$insert_result = $db->query($insert_sql)){
    $message = array ("status"=>"fail","description"=>"User couldn't be registered", 'error'=>$db->error);
    echo json_encode($message);
    die();
  }

  if (!$result = $db->query($sql)){
    echo $db->error;
    $error = array('error'=>$db->error);
    echo json_encode($error);
    die();
  }
  while ($row=$result->fetch_assoc()){
    echo "row found after inserting";
    $userid=$row['userid'];
    $name=$row['name'];
    $college=$row['college'];
    $email=$row['email'];

    session_start();
    $_SESSION['userid']=$userid;
    $_SESSION['name']=$name;
    $_SESSION['college']=$college;
    $_SESSION['email']=$email;

    $message = array ("status"=>"sucsess","description"=>"User registered. Logged In");
    echo json_encode($message);
  }
}


while ($row=$result->fetch_assoc()){
  echo "row found";
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

    $message = array ("status" =>"sucsess" , "description"=>"User already registered. Logged In");
    echo json_encode($message);
  }else {
    $message = array ("status"=>"fail" , "description"=>"User already registered. Wrong Email Password Combination ");
    echo json_encode($message);
  }
}


?>
