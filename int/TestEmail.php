<?php
  include_once("fest.php");
  A_Check('Committee','Users');

  dostaffhead("Test Email");
  include_once("UserLib.php");
  include_once("Email.php");

  if (isset($_GET['U'])) {
    $uid = $_GET['U'];
    $user = Get_User($uid);

    $letter = firstword($user['SN']) . ",<p>" .
        "This is yet another test message ...<p>".
        "Richard";
 
    NewSendEmail(0,0,$user['Email'],"Test From " . firstword($user['SN']) . " from WMFF Staff pages",$letter);

    echo "Email sent:<p>$letter";
  } else {
    echo "No user..."; 
  }

  dotail();
?>
