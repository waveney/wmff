<?php
  include "fest.php";
  dostaffhead("Ticketed Events");
  include_once "ProgLib.php";

  echo "<h2 class=subtitle>Ticketed Events</h2>\n";

  global $YEAR,$db;

  $Vens = Get_Real_Venues(0);

  $qry = "SELECT * FROM Events WHERE Year='$YEAR' AND (DoorPrice!=0 OR Price1!=0) ORDER BY Day,Start";
  $Evs = $db->query($qry);

  $coln = 0;
  echo "<div class=tablecont><table id=indextable border=1>\n";
  echo "<thead><tr>";
  echo "<th><a href=javascript:SortTable(" . $coln++ . ",'T')>Day</a>\n";
  echo "<th><a href=javascript:SortTable(" . $coln++ . ",'T')>Start</a>\n";
  echo "<th><a href=javascript:SortTable(" . $coln++ . ",'T')>End</a>\n";
  echo "<th><a href=javascript:SortTable(" . $coln++ . ",'T')>Name</a>\n";
  echo "<th><a href=javascript:SortTable(" . $coln++ . ",'T')>With</a>\n";
  echo "<th><a href=javascript:SortTable(" . $coln++ . ",'T')>Venue</a>\n";
  echo "<th><a href=javascript:SortTable(" . $coln++ . ",'T')>Price</a>\n";
  echo "<th><a href=javascript:SortTable(" . $coln++ . ",'T')>Door Price</a>\n";
  echo "<th><a href=javascript:SortTable(" . $coln++ . ",'T')>Buy Link</a>\n";
  echo "<th><a href=javascript:SortTable(" . $coln++ . ",'T')>Public</a>\n";
  echo "</thead><tbody>";

  while ($E = $Evs->fetch_assoc()) {
    echo "<tr>";
    echo "<td>" . $DayList[$E['Day']];
    echo "<td>" . $E['Start'];
    echo "<td>" . $E['End'];
    echo "<td><a href=EventAdd?e=" . $E['EventId'] . ">" . $E['SN'] . "</a>";
    echo "<td>Coming...";
    echo "<td>" . (isset($Vens[$E['Venue']])? $Vens[$E['Venue']] : "Unknown");
    echo "<td>" . $E['Price1'];
    echo "<td>" . $E['DoorPrice'];
    echo "<td><a href=" . $E['TicketCode'] . "><strong>Buy Now</strong></a>\n";
    echo "<td>" . $E['Public'];
  }
  echo "</tbody></table></div>\n";
  
  dotail();
?>
