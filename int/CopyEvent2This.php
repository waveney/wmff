<?php
  include_once("fest.php");
  include_once("DanceLib.php");
  include_once("ProgLib.php");
  A_Check('SysAdmin');
  
  dostaffhead("Copy Last years acts to this year");


  global $db;
     
  echo "<h1>Copy Music, Ceilidhs and Concerts forward</h1>";
  
  $res=$db->query("SELECT * FROM Events WHERE Year='2020' AND SubEvent<=0");
  
  if ($res) {
    while ($evnt = $res->fetch_assoc()) {
      if ($Event_Types[$evnt['Type']] == 'Concert' || $Event_Types[$evnt['Type']] == 'Music' || $Event_Types[$evnt['Type']] == 'Ceilidh') {
        $se = $evnt['EventId'];
        $evnt['Year'] = 2022;
        $evnt['EventId'] = 0;
        $Nse = Insert_db('Events', $evnt);
        
        if ($evnt['SubEvent'] < 0) { // There are sub events
          $seres=$db->query("SELECT * FROM Events WHERE Year='2020' AND SubEvent=$se");
          if ($seres) {
            while ($sevnt = $seres->fetch_assoc()) {
              $sevnt['Year'] = 2022;
              $sevnt['EventId'] = 0;
              $sevnt['SubEvent'] = $Nse;
              $NNse = Insert_db('Events', $sevnt);
            }
          }  
        }
//echo "Copied " . $evnt['Name'];
      }
    }  
  }  
  echo "Finished<p>";
  
  dotail();
?>

