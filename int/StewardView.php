<?php
  include_once("fest.php");
  A_Check('Staff');

  dostaffhead("Stewarding Applications");
  include_once("SignupLib.php");
  global $db,$YEAR,$StewClasses;

    echo "<button class='floatright FullD' onclick=\"($('.FullD').toggle())\">All Applications</button><button class='floatright FullD' hidden onclick=\"($('.FullD').toggle())\">Curent Aplications</button> ";


  echo "Click on name for full info<p>";
  $coln = 0;  
  echo "<form method=post action=StewardView.php>";
  echo "<div class=tablecont><table id=indextable border>\n";
  echo "<thead><tr>";

  echo "<th><a href=javascript:SortTable(" . $coln++ . ",'N')>Id</a>\n";
  echo "<th><a href=javascript:SortTable(" . $coln++ . ",'T')>Name</a>\n";
  echo "<th><a href=javascript:SortTable(" . $coln++ . ",'T')>Email</a>\n";
  echo "<th><a href=javascript:SortTable(" . $coln++ . ",'T')>Phone</a>\n";
  echo "<th><a href=javascript:SortTable(" . $coln++ . ",'T')>Steward</a>\n";
  echo "<th><a href=javascript:SortTable(" . $coln++ . ",'T')>Setup</a>\n";
  echo "<th><a href=javascript:SortTable(" . $coln++ . ",'T')>Artistic</a>\n";
  echo "<th><a href=javascript:SortTable(" . $coln++ . ",'T')>Media</a>\n";
  echo "<th><a href=javascript:SortTable(" . $coln++ . ",'T')>Before</a>\n";
  echo "<th><a href=javascript:SortTable(" . $coln++ . ",'T')>Thu</a>\n";
  echo "<th><a href=javascript:SortTable(" . $coln++ . ",'T')>Fri</a>\n";
  echo "<th><a href=javascript:SortTable(" . $coln++ . ",'T')>Sat</a>\n";
  echo "<th><a href=javascript:SortTable(" . $coln++ . ",'T')>Sun</a>\n";
  echo "<th><a href=javascript:SortTable(" . $coln++ . ",'T')>Mon</a>\n";
  echo "</thead><tbody>";

  $res=$db->query("SELECT * FROM Volunteers ORDER BY SN");
  
  if ($res) while ($stew = $res->fetch_assoc()) {
    $id = $stew['id'];
    echo "<tr" . (($stew['Year'] == $YEAR)?" class=FullD hidden" : "" ) . ">";
    echo "<td>$id";
    echo "<td><a href=ViewStew.php?id=$id>" . $stew['SN'] . "</a>";
    echo "<td>" . $stew['Email'];
    echo "<td>" . $stew['Phone'];
    foreach ($StewClasses as $c=>$exp) echo "<td>" . ['','Y'][$stew["SC_$c"]];
    echo "<td>" . $stew['AvailBefore'];
    echo "<td>" . $stew['AvailThu'];
    echo "<td>" . $stew['AvailFri'];
    echo "<td>" . $stew['AvailSat'];
    echo "<td>" . $stew['AvailSun'];
    echo "<td>" . $stew['AvailMon'];
  }
  echo "</tbody></table></div>\n";

  dotail();
?>
