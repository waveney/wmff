<?php
  include_once("fest.php");

  dostaffhead("Steward / Volunteer Application", "/js/Participants.js");

  include_once("VolLib.php");
  global $USER,$USERID,$db,$PLANYEAR,$StewClasses,$Relations,$Days;
  
  if (isset($_REQUEST['A'])) {
    VolAction($_REQUEST['A']);
  } else {
    VolAction('New');
  }
  
  dotail();
?>
