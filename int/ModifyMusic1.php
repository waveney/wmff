<?php
  include_once("fest.php");
  A_Check('SysAdmin');

  dostaffhead("Music Data Modify #1");
  include_once("DanceLib.php");
  include_once("MusicLib.php");

  /* Go through each side: read, modify, write, report.  If no year data, do not create unnessesarily */
  global $db,$YEAR,$MASTER;
  
  $res = $db->query("SELECT * FROM Sides WHERE IsAnAct=1");
  while ($Side = $res->fetch_assoc()) {
    if (isset($Side['Photo'])) {
      $NS = Get_Side($Side['SideId']);
      $NS['Photo'] = "/" . $NS['Photo'];
      Put_Side($NS);
      echo "Changed - " . $NS['SideId'] . " - " . SName($NS) . "<br>\n";
    }
  }
  echo "Finished...<p>";
  dotail();
?>
