<?php
  include_once("fest.php");

  A_Check('SysAdmin');
  include_once("ProgLib.php");
  
  dostaffhead("Convert Old Events to New Events");

/* Go through each event
   if Act/Others then set PerfType and overright Side data and save
   Sides left unchanged
*/

  global $db;
  $res = $db->query("SELECT * FROM Events");
  
  while ($E = $res->fetch_assoc()) {
    $Ch = 0;
    $nxt = 1;
    for($i=1; $i<5; $i++) if ($E["Side$i"]) $nxt++;
    
    for($i=1;
     $i<5; $i++) {
      if ($E["Act$i"]) {
        if ($nxt>4) {
          echo "Can't resolve event " . $E['EventId'] . "<br>";
          $Ch=100;
          continue;
        }
        $E["Side$nxt"] = $E["Act$i"];
        $E["PerfType$nxt"] = 1;
        $nxt++;
        $Ch=1;
      }
      
      if ($E["Other$i"]) {
        if ($nxt>4) {
          echo "Can't resolve event " . $E['EventId'] . "<br>";
          $Ch=100;
          continue;
        }
        $E["Side$nxt"] = $E["Other$i"];
        $E["PerfType$nxt"] = 4;
        $nxt++;
        $Ch=1;
      }
    }  
    if ($Ch == 1) {
      Put_Event($E);
      echo "Updated " . $E['EventId']. "<br>\n";
    }
      
  }
  echo "Finished<p>";
  
  dotail();
?>

