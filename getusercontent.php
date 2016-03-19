<?php
require 'connect.php';
require 'events.php';
require 'functions.php';

session_start();

if (isset($_SESSION['email'])){

  $email = $_SESSION['email'];
  $sql = "SELECT * FROM `users` WHERE `email`=\"$email\"";
  $result = executeQuery($db, $sql);

  if ($result->num_rows == 0){

    $message = array ("status" => "error", "description" => "User Not Registered");
    $ch = json_encode($message);
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
    $ch = json_encode($message);
  }
  $response = json_decode($ch);
  if ($response->status == "error"){
    echo json_encode($response);
    die();
  }
  $noofgroups= $response->user->noofgroups;
  $groups= $response->user->groups;

  $registeredEvents = array();
  $registeredEventsCode = array();
  $registeredEventGroupName = array();
  $registeredEventGroupId = array();

  $registeredWorkshops =array();
  $registeredWorkshopsSlots = array();
  $registeredWorkshopsGroupName = array();
  $registeredWorkshopsGroupId = array();

  while($noofgroups){
    $groupid = $groups[$noofgroups-1]->id;
    $groupname = $groups[$noofgroups-1]->name;

    $sql = "SELECT * FROM `workshops` WHERE `groupid`=\"$groupid\"";
    $result = executeQuery($db, $sql);
    while ($row=$result->fetch_assoc()) {
      foreach ($workshops as $key => $value) {
        if ($row[$key] != 0){
          $slot = $row[$key];
          array_push($registeredWorkshops, "$value");
          array_push($registeredWorkshopsSlots, "$slot");
          array_push($registeredWorkshopsGroupName, "$groupname");
          array_push($registeredWorkshopsGroupId, "$groupid");
        }
      }
    }

    $sql = "SELECT * FROM `events` WHERE `groupid`=\"$groupid\"";
    $result = executeQuery($db, $sql);
    while ($row=$result->fetch_assoc()) {
      foreach ($events as $key => $value) {
        if ($row[$key] == 1){
          array_push($registeredEvents, "$value");
          array_push($registeredEventsCode, "$key");
          array_push($registeredEventGroupName, "$groupname");
          array_push($registeredEventGroupId, "$groupid");
        }
      }
    }

    $noofgroups--;
  }

  $groupid = getOwnGroupId($db, $_SESSION['userid']);

  $message = array ("status" => "success", "email" => $email, "owngroup" => $groupid, "registeredEvents" => $registeredEvents, "registeredEventsCode" => $registeredEventsCode, "registeredEventGroupId" => $registeredEventGroupId, "registeredEventGroupName" => $registeredEventGroupName, "registeredWorkshops" => $registeredWorkshops, "registeredWorkshopsSlots" => $registeredWorkshopsSlots, "registeredWorkshopsGroupName" => $registeredWorkshopsGroupName, "registeredWorkshopsGroupId" => $registeredWorkshopsGroupId);
  echo json_encode($message);

}else{
  $message = array ("status" => "logout", "description" => "Not logged in");
  echo json_encode($message);
  die();
}
