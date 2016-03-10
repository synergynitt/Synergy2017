<?php

function executeQuery($db, $sql){
  if (!$result = $db->query($sql)){
    $message = array ("status" => "error","description" => "Database Error", "error" => $db->error);
    echo json_encode($message);
    die();
  }
  return $result;
}

function getUserId($db, $email){
  $sql = "SELECT * FROM `users` WHERE `email`=\"$email\"";
  $result = executeQuery($db, $sql);
  if ($result->num_rows>0){
    $row = $result->fetch_assoc();
    $userid = $row['userid'];
    return $userid;
  }else{
    return 0;
  }
}

function createGroup($db, $groupname, $members){
  $sql = "SELECT * FROM `groups` ORDER BY `groupid` DESC";
  $result = executeQuery($db, $sql);
  $row = $result->fetch_assoc();
  $groupid = $row['groupid'] + 1;
  $insert_sql = "INSERT INTO `groups` (groupid, groupname, members) VALUES(\"$groupid\", \"$groupname\", \"1\")";
  $insert_result = executeQuery($db, $insert_sql);
  return $groupid;
}

function addUserToGroup($db, $userid, $groupid){
  $sql = "SELECT * FROM `groups` WHERE `groupid`=\"$groupid\"";
  $result = executeQuery($db, $sql);
  if ($result->num_rows>0){
    $row=$result->fetch_assoc();
    $groupname = $row['groupname'];
    $insert_sql = "INSERT INTO `usergroup` (userid, groupid, groupname) VALUES(\"$userid\", \"$groupid\", \"$groupname\")";
    $insert_result = executeQuery($db, $insert_sql);
    return 1;
  }else{
    $message = array ("status" => "error","description" => "Group Not Found");
    echo json_encode($message);
    die();
  }
}

function getOwnGroupId($db, $userid){
  $sql = "SELECT * FROM `usergroup` WHERE `userid`=\"$userid\" AND `own`=\"1\"";
  $result = executeQuery($db, $sql);
  if ($result->num_rows == 1){
    $row = $result->fetch_assoc();
    $groupid = $row['groupid'];
    return $groupid;
  }else{
    $message = array ("status" => "error","description" => "Own Group Error");
    echo json_encode($message);
    die();
  }
}
 ?>
