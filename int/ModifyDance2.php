<?php
  include_once("fest.php");
  A_Check('SysAdmin');

  dostaffhead("Dance Data Modify #1");
  include_once("DanceLib.php");

  /* Go through each side: read, modify, write, report.  If no year data, do not create unnessesarily */
  global $db,$YEAR,$MASTER;
  
// $Invite_States = array('','Yes','YES','No','Prob','Poss','No Way');
// $Invite_States = array('','Yes','YES!','No','Maybe');
  $res = $db->query("SELECT * FROM Sides ORDER BY SideId");
  while ($Side = $res->fetch_assoc()) {
    $Changed = 0;
    $SideY = Get_SideYear($Side['SideId'],2017);
    if ($SideY) {
      $YChanged = 0;
      if ($SideY['Invite']) {
	switch ($SideY['Invite']) {
	case 5:
	  $SideY['Invite'] = 4;
	  $YChanged = 1;
	  break;
	case 6:
	  $SideY['Invite'] = 3;
	  $YChanged = 1;
	  break;
	}
      }
      if ($YChanged) Put_SideYear($SideY);
      if ($Changed) Put_Side($Side);
      if ($YChanged || $Changed) echo "Changed - " . $Side['SideId'] . " - " . SName($Side) . "<br>\n";
    }
  }
  echo "Finished...<p>";
  dotail();
?>
