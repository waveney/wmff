<?php
  include_once("fest.php");
  A_Check('SysAdmin');

  dostaffhead("Dance Data Modify #1");
  include_once("DanceLib.php");

  /* Go through each side: read, modify, write, report.  If no year data, do not create unnessesarily */
  global $db,$YEAR,$MASTER;
  
  $res = $db->query("SELECT * FROM Sides ORDER BY SideId");
  while ($Side = $res->fetch_assoc()) {
    $Changed = 0;
    $SideY = Get_SideYear($Side['SideId'],2017);
    if ($SideY) {
      $YChanged = 0;
      $oln=1;
      if ($Side['OverlapD1']) {
	$SideY["Overlap$oln"] = $Side['OverlapD1'];
	$SideY["OverlapType$oln"] = $Overlap_Type['Major Dancer'];
	$oln++;
	$YChanged = 1;
      }
      if ($Side['OverlapD2']) {
	$SideY["Overlap$oln"] = $Side['OverlapD2'];
	$SideY["OverlapType$oln"] = $Overlap_Type['Major Dancer'];
	$oln++;
	$YChanged = 1;
      }
      if ($Side['OverlapM1']) {
	$SideY["Overlap$oln"] = $Side['OverlapM1'];
	$SideY["OverlapType$oln"] = $Overlap_Type['Major Musician'];
	$oln++;
	$YChanged = 1;
      }
      if ($Side['OverlapM2']) {
	$SideY["Overlap$oln"] = $Side['OverlapM2'];
	$SideY["OverlapType$oln"] = $Overlap_Type['Major Musician'];
	$oln++;
	$YChanged = 1;
      }
      if ($SideY['Share']) {
	$Side['Share'] = $SideY['Share'];
	$Changed=1;
      }
      if ($YChanged) Put_SideYear($SideY);
      if ($Changed) Put_Side($Side);
      if ($YChanged || $Changed) echo "Changed - " . $Side['SideId'] . " - " . SName($Side) . "<br>\n";
    }
  }
  echo "Finished...<p>";
  dotail();
?>
