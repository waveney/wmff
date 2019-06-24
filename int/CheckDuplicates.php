<?php
  include_once("fest.php");

  A_Check('SysAdmin');
  
  include_once("DanceLib.php");
  global $db,$YEAR;

  dostaffhead("Check for and resolve duplicate Year Data");
  
  if (isset($_REQUEST['DELETE'])) {
    $syid = $_REQUEST['DELETE'];
    $db->query("UPDATE SideYear SET SideId=-SideId WHERE syId=$syid");
  }
  
  $qry = "SELECT * FROM SideYear WHERE Year=$YEAR AND SideId>0 ORDER BY SideId";
  $coln = 0;
  
  echo "<div class=tablecont><table id=indextable border>\n";
  echo "<thead><tr>";
  echo "<th><a href=javascript:SortTable(" . $coln++ . ",'T')>Name</a>\n";
  echo "<th><a href=javascript:SortTable(" . $coln++ . ",'N')>Year</a>\n";
  echo "<th><a href=javascript:SortTable(" . $coln++ . ",'T')>Record 1</a>\n";  
  echo "<th><a href=javascript:SortTable(" . $coln++ . ",'T')>R1 Coming State</a>\n";
  echo "<th><a href=javascript:SortTable(" . $coln++ . ",'T')>R1 Year State</a>\n";
  echo "<th><a href=javascript:SortTable(" . $coln++ . ",'N')>Year</a>\n";
  echo "<th><a href=javascript:SortTable(" . $coln++ . ",'T')>Record 2</a>\n";  
  echo "<th><a href=javascript:SortTable(" . $coln++ . ",'T')>R2 Coming State</a>\n";
  echo "<th><a href=javascript:SortTable(" . $coln++ . ",'T')>R2 Year State</a>\n";
  echo "<th><a href=javascript:SortTable(" . $coln++ . ",'T')>Delete R1?</a>\n";
  echo "<th><a href=javascript:SortTable(" . $coln++ . ",'T')>Delete R2?</a>\n";
  
  echo "</thead><tbody>";
  $res = $db->query($qry);
  
  $lastside = 0;
  while ($sidey = $res->fetch_assoc()) {
    $snum= $sidey['SideId'];
    if ($snum == $lastside) {
      $Side = Get_Side($snum);
      echo "<tr><td><a href=AddSide?sidenum=$snum>" . $Side['SN'] . "</a>";
      echo "<td>" . $lastsidey['Year'] . "<td>" . $lastsidey['syId'] . "<td>" . $lastsidey['Coming'] . "<td>" . $lastsidey['YearState'];
      echo "<td>" . $sidey['Year'] . "<td>" . $sidey['syId'] . "<td>" . $sidey['Coming'] . "<td>" . $sidey['YearState'];      
      echo "<td><a href=CheckDuplicates?DELETE=" . $lastsidey['syId'] . ">Delete R1 ";
      echo "<td><a href=CheckDuplicates?DELETE=" . $sidey['syId'] . ">Delete R2 ";
    }
    $lastside = $snum;
    $lastsidey = $sidey;
  }
  echo "</table></div><p>\n";
  echo "Finished";

  
  dotail();
?>

