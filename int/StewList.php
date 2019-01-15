<?php
  include_once("fest.php");
  A_Check('Staff');

  dostaffhead("List Stewarding Events");
  global $db,$Event_Types,$USERID,$Importance;
  $yn = array('','Y');
  include_once("ProgLib.php");
  include_once("DocLib.php");

//var_dump($Event_Types);

  $Venues = Get_Real_Venues();

  $coln = 0; 
  echo "Click on Id/Name to edit, on Show for public page.<p>\n";
  echo "<table id=indextable border>\n";
  echo "<thead><tr>";

  echo "<th><a href=javascript:SortTable(" . $coln++ . ",'N')>Event Id</a>\n";
  echo "<th><a href=javascript:SortTable(" . $coln++ . ",'T')>Name</a>\n";
  echo "<th><a href=javascript:SortTable(" . $coln++ . ",'T')>Day</a>\n";
  echo "<th><a href=javascript:SortTable(" . $coln++ . ",'N')>Start</a>\n";
  echo "<th><a href=javascript:SortTable(" . $coln++ . ",'N')>End</a>\n";
  echo "<th><a href=javascript:SortTable(" . $coln++ . ",'T')>Venue</a>\n";
  echo "<th><a href=javascript:SortTable(" . $coln++ . ",'T')>Stewards</a>\n";
  echo "<th><a href=javascript:SortTable(" . $coln++ . ",'T')>Steward Detail</a>\n";
  echo "<th><a href=javascript:SortTable(" . $coln++ . ",'T')>Setup Detail</a>\n";

  echo "</thead><tbody>";

  $res=$db->query("SELECT * FROM Events WHERE (NeedSteward=1 OR StewardTasks!='' OR SetupTasks!='') AND SubEvent<=0 ORDER BY Day, Start, Type");
  

  if ($res) {
    while ($evnt = $res->fetch_assoc()) {
      $i = $evnt['EventId'];
      echo "<tr><td>$i<td>";
      echo "<a href=EventAdd.php?e=$i>";
      if (strlen($evnt['SN']) >2) { echo $evnt['SN'] . "</a>"; } else { echo "Nameless</a>"; };
      echo "<td>" . $DayList[$evnt['Day']] . "<td>" . timecolon($evnt['Start']) . "<td>" . timecolon($evnt['End']);
      echo "<td>" . (isset($Venues[$evnt['Venue']]) ? $Venues[$evnt['Venue']] : "Unknown");
      echo "<td>" .($evnt['NeedSteward'] ? "Y" : "" );
      echo "<td>" . $evnt['StewardTasks'];
    }
  }
  echo "</tbody></table>\n";

  dotail();
?>
