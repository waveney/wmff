<?php
  include_once("fest.php");

  A_Check('SysAdmin');
  
  include_once("DanceLib.php");
  dostaffhead("Update Message Records");

  global $db,$YEAR;
  
    $ans = $db->query("SELECT * FROM SideYear WHERE Invited!='' AND Year='$YEAR'");
    while ($sidey = $ans->fetch_assoc()) {
      $Messages = explode(', ',$sidey['Invited']);
      if (!isset($Messages[1])) continue;
      $sidey['Invited'] = trim(implode(', ',array_reverse($Messages))," ,\t\n\r\0\x0B"); 
      Put_SideYear($sidey, 1);
      echo "Updated: " . $sidey['SideId'] . "<br>";
//      exit; // testing
    }
  echo "Finished<p>";
  
  dotail();
?>

