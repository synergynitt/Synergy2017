<?php
   session_start();
   session_destroy();
   $message = array ("status"=>"logout","description"=>"You have been logged out");
   echo json_encode($message);
   die();
?>
