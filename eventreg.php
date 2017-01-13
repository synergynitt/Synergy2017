<?php
require 'connect.php';
require 'functions.php';

session_start();
$event = mysqli_real_escape_string($db, $_GET['event']);
$groupid = mysqli_real_escape_string($db, $_GET['groupid']);

if (!isset($_SESSION['userid'])){
  $message = array ("status" => "logout", "description" => "You need to log in to register for events");
  echo json_encode($message);
  die();
}

if (!isset($_GET['deregister'])){
  if (!checkIfRegIsPossible($db, $groupid, $event, "events")){
    $message = array ("status" => "fail", "description" => "Someone in this Group Already registered with an another group");
    echo json_encode($message);
    die();
  }
  $sql = "SELECT * FROM `events` WHERE `groupid`=\"$groupid\"";
  $result = executeQuery($db, $sql);
  if ($result->num_rows == 0){
    $insert_sql = "INSERT INTO `events` (groupid) VALUES (\"$groupid\")";
    executeQuery($db, $insert_sql);
  }
  $update_sql = "UPDATE `events` SET `$event`=\"1\" WHERE `groupid`=\"$groupid\"";
  executeQuery($db, $update_sql);
  $message = array ("status" => "success", "description" => "You are registered", 'code' => $db->affected_rows);

  // require 'events.php';
  // $email_to = $_SESSION['email'];
  // $name = $_SESSION['name'];
  // $subject = "Synergy Event Registration";
  // $email_from = "synergy@nitt.edu";
  // $password = "Ri159487263z_";
  //
  // $body = "Synergy 2016\n\n";
  // $body = $body . "You have been registered for the event : " . $events[$event];
  // $body = $body . "You Registration ID : " . $groupid;
  //
  // require("phpmailer/class.phpmailer.php");
  // date_default_timezone_set('Etc/UTC');
  //
  // require 'phpmailer/PHPMailerAutoload.php';
  //
  // $mail = new PHPMailer;
  // $mail->isSMTP();
  // $mail->SMTPDebug = 0;
  // $mail->Debugoutput = 'html';
  // $mail->Host = 'smtp.gmail.com';
  // $mail->Port = 587;
  // $mail->SMTPSecure = 'tls';
  // $mail->SMTPAuth = true;
  // $mail->Username = $email_from;
  // $mail->Password = $password;
  // $mail->setFrom( $email_from, 'Synergy 2016');
  // $mail->addReplyTo($email_from, "Synergy 2016");
  // $mail->addAddress($email_to, $name);
  // $mail->Subject = 'Synergy Events Registration';
  // $mail->msgHTML($body, dirname(__FILE__));
  // $mail->AltBody = $body;
  // if(!$mail->send()) {
  //   $message['email']="send to ". $email_to;
  // }
  // else{
  //   $message['email']="not send";
  // }
  echo json_encode($message);
}else{

  $update_sql = "UPDATE `events` SET `$event`=\"0\" WHERE `groupid`=\"$groupid\"";
  executeQuery($db, $update_sql);
  $message = array ("status" => "deregistered", "description" => "You are deregistered", 'code' => $db->affected_rows);
  echo json_encode($message);

}

$db->close();

?>
