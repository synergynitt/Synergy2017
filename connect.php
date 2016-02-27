<?php
$username="synergy";
$password="password";
$server="127.0.0.1";
$database="synergy";

$db = new mysqli($server,$username,$password,$database);

if ($db->connect_errno > 0){
  die("Can't connect to database[" . $db->connect_error . "]");
}

?>
