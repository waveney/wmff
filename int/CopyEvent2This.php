<?php
  include_once("fest.php");
  include_once("DanceLib.php");
  include_once("ProgLib.php");
  A_Check('SysAdmin');
  
  dostaffhead("Copy Last years acts to this year");


  global $db;
     
  echo "<h1>Copy Music, Ceilidhs and Concerts forward</h1>";
  
  $res=$db->query("SELECT * FROM Events WHERE Year='2020'");
  
  if ($res) {
    while ($evnt = $res->fetch_assoc()) {
      if ($Event_Types[$evnt['Type']] == 'Concert' || $Event_Types[$evnt['Type']] == 'Music' || $Event_Types[$evnt['Type']] == 'Ceilidh') {
        $evnt['Year'] = 2022;
        $evnt['id'] = 0;
        Insert_db('Events', $evnt);       
//echo "Copied " . $evnt['Name'];
      }
    }  
  }  
  echo "Finished<p>";
  
  dotail();
?>

