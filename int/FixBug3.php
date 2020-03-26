<?php
  include_once("fest.php");

  A_Check('SysAdmin');
  
  include_once("DanceLib.php");
  dostaffhead("Update SideYEar Records");

  global $db,$YEAR;
  
  $ans = $db->query("SELECT * FROM SideYear WHERE Year='2020'");
  while ($sidey = $ans->fetch_assoc()) {
    $sid = $sidey['SideId'];
    $Side = Get_Side($sid);
    $SIDEY = Get_SideYear($sid,'2020A');
    
    if (strstr($sidey['Invited'],'Change')) {
      $SIDEY['Invite'] = $sidey['Invite'];
      $SIDEY['Invited'] = $sidey['Invited'];
    }

    if ($sidey['TickBox4']) $SIDEY['TickBox4'] = $sidey['TickBox4'];
    
    Put_SideYear($SIDEY);   
    echo "Done $sid - " . $Side['SN'] . "<br>";
  }
  echo "Finished<p>";
  //232x,284x,238x,326,356,394x,354,404
  dotail();
?>

