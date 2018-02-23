<?php
  include_once("fest.php");
  A_Check('Committee','Venues');

  dostaffhead("Manage Map Point Types");

  include_once("ProgLib.php");
  include_once("TradeLib.php");
  include_once("MapLib.php");

  echo "<div class='content'><h2>Manage Map Point Types</h2>\n";
  echo "Please don't have too many types.<p>\n";

  echo "Entry 1 must be text.<p>";
  
  $Types = Get_Map_Point_Types(1);
  if (UpdateMany('MapPointTypes','Put_Map_Point_Type',$Types,1)) {
    $Types = Get_Map_Point_Types(1);
    Update_MapPoints();
  }

  $coln = 0;

  echo "<h2>Map Point Types</h2><p>";
  echo "<form method=post action=MapPTypes.php>";
  echo "<table id=indextable border>\n";
  echo "<thead><tr>";
  echo "<th><a href=javascript:SortTable(" . $coln++ . ",'N')>Type</a>\n";
  echo "<th><a href=javascript:SortTable(" . $coln++ . ",'T')>Name</a>\n";
  echo "<th><a href=javascript:SortTable(" . $coln++ . ",'T')>Icon</a>\n";
  echo "</thead><tbody>";
  foreach($Types as $t) {
    $i = $t['id'];
    echo "<tr><td>$i" . fm_text1("",$t,'SName',1,'','',"SName$i");
    echo fm_text1("",$t,'Icon',1,'','',"Icon$i");
    echo "\n";
  }
  echo "<tr><td><td><input type=text name=SName0 >";
  echo "<td><input type=text name=Icon0 >";
  echo "</table>\n";
  echo "<input type=submit name=Update value=Update>\n";
  echo "</form></div>";

  dotail();

?>
