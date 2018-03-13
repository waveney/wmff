<?php
  include_once("fest.php");
  A_Check('Staff');

  dostaffhead("Stewarding Applications");
  include("SignupLib.php");
  global $db,$THISYEAR,$StewClasses;

  echo "Click on name for full info<p>";
  $coln = 0;  
  echo "<form method=post action=StewardView.php>";
  echo "<table id=indextable border>\n";
  echo "<thead><tr>";

  echo "<th><a href=javascript:SortTable(" . $coln++ . ",'N')>Id</a>\n";
  echo "<th><a href=javascript:SortTable(" . $coln++ . ",'T')>Name</a>\n";
  echo "<th><a href=javascript:SortTable(" . $coln++ . ",'T')>Email</a>\n";
  echo "<th><a href=javascript:SortTable(" . $coln++ . ",'T')>Phone</a>\n";
  echo "<th><a href=javascript:SortTable(" . $coln++ . ",'T')>Stewarding</a>\n";
  echo "<th><a href=javascript:SortTable(" . $coln++ . ",'T')>Tech</a>\n";
  echo "<th><a href=javascript:SortTable(" . $coln++ . ",'T')>Artistic</a>\n";
  echo "<th><a href=javascript:SortTable(" . $coln++ . ",'T')>Media</a>\n";
  echo "<th><a href=javascript:SortTable(" . $coln++ . ",'T')>Fri</a>\n";
  echo "<th><a href=javascript:SortTable(" . $coln++ . ",'T')>Sat</a>\n";
  echo "<th><a href=javascript:SortTable(" . $coln++ . ",'T')>Sun</a>\n";
  echo "</thead><tbody>";

  $res=$db->query("SELECT * FROM Stewards WHERE Year=$THISYEAR ORDER BY SName");
  
  if ($res) while ($stew = $res->fetch_assoc()) {
    $id = $stew['id'];
    echo "<tr><td>$id";
    echo "<td><a href=ViewStew.php?id=$id>" . $stew['SName'] . "</a>";
    echo "<td>" . $stew['Email'];
    echo "<td>" . $stew['Phone'];
    foreach ($StewClasses as $c=>$exp) echo "<td>" . $stew["SC_$c"];
    echo "<td>" . $stew['AvailFri'];
    echo "<td>" . $stew['AvailSat'];
    echo "<td>" . $stew['AvailSun'];
  }
  echo "</tbody></table>\n";

  dotail();
?>
