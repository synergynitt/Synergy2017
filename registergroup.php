<?php
require 'connect.php';
require 'functions.php';

$groupUserData = json_decode($_POST['groupjsondata']);
$groupName = mysqli_real_escape_string($db, $_POST['groupName']);

$members = count($groupUserData);

$groupid = createGroup($db, $groupName, $members );
foreach ($groupUserData as $user) {

  $email = $user->email;
  $userid = getUserId($db, $email);
  if ($userid == 0){
    $name = $user->name;
    $rollno = $user->rollno;
    $college = $user->college;
    $insert_sql = "INSERT INTO `users` (name, college, email, rollno) VALUES(\"$name\",\"$college\",\"$email\",\"$rollno\")";
    executeQuery($db, $insert_sql);
    $sql = "SELECT * FROM `users` WHERE `email`=\"$email\"";
    $result = executeQuery($db, $sql);
    $row = $result->fetch_assoc();
    $userid = $row['userid'];
  }
  addUserToGroup($db, $userid, $groupid);
}
$message = array('status' => "success", 'groupid' => $groupid );
echo json_encode($message);
?>
