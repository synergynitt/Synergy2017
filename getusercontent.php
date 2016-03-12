<?php
require 'connect.php';
require 'events.php';
require 'functions.php';

session_start();

if (isset($_SESSION['email'])){

  $email = $_SESSION['email'];
  $url = "http://$_SERVER[HTTP_HOST]/getuserdetails.php?email=$email";
  $ch = curl_init();
  curl_setopt_array(
    $ch, array(
    CURLOPT_URL => $url,
    CURLOPT_RETURNTRANSFER => true
    )
  );
  $response = curl_exec($ch);
  $response = json_decode($response);
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

  $message = array ("status" => "success", "email" => $email, "owngroup" => $groupid, "registeredEvents" => $registeredEvents, "registeredEventsCode" => $registeredEventsCode, "registeredEventGroupId" => $registeredEventGroupId, "registeredEventGroupName" => $registeredEventGroupName, "registeredWorkshops" => $registeredWorkshops, "registeredWorkshopsSlots" => $registeredWorkshopsSlots, "registeredWorkshopsGroupName" => $registeredWorkshopsGroupName);
  echo json_encode($message);

}else{
  $message = array ("status" => "logout", "description" => "Not logged in");
  echo json_encode($message);
  die();
}
