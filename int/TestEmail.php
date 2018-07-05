<?php
  include_once("fest.php");
  A_Check('Committee','Users');

  dostaffhead("Test Email");
  include_once("UserLib.php");

  if (isset($_GET['U'])) {
    $uid = $_GET['U'];
    $user = Get_User($uid);

    $letter = firstword($user['SName']) . ",<p>" .
        "This is yet another test message ...<p>".
        "Richard";
 
    SendEmail($user['Email'],"Test From " . firstword($user['SName']) . " from WMFF Staff pages",$letter);

    echo "Email sent:<p>$letter";
  } else {
    echo "No user..."; 
  }

  dotail();
?>
