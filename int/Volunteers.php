<?php
  include_once("fest.php");

  dostaffhead("Steward / Volunteer Application", "/js/Volunteers.js");

  include_once("VolLib.php");
 
  global $USER,$USERID,$db,$PLANYEAR,$StewClasses,$Relations,$Days;
//echo "HERE";
  if (isset($_REQUEST['NotThisYear'])) {
    VolAction('NotThisYear');
  } else if (isset($_REQUEST['Delete'])) {
    VolAction('Delete');
  } else if (isset($_REQUEST['A'])) {
    VolAction($_REQUEST['A']);
  } else {
    VolAction('New');
  }
  
  dotail();
?>
