<?php
  include_once("fest.php");
  
  A_Check('Steward');
  
  $VenueId = $_REQUEST['pa4v'];
  include_once("ProgLib.php");
  
  $Venue = Get_Venue($VenueId);

  dostaffhead("PA Requirements for " . $Venue['SN']);

  echo "TO BE WRITTEN";

/* For Each Event - event PA reqs (if any) all Perfs - if have PA req show text if small otherwise give a ref at end */

  dotail();
  
/* Need to see PA on venue list 
   Summary sheet per day, then details
   
   */

?>
