<?php
  include_once("fest.php");
  A_Check('Committee','Venues');

  dostaffhead("Manage Additional Map Points");

  include_once("ProgLib.php");
  include_once("TradeLib.php");
  include_once("MapLib.php");

  echo "<div class='content'><h2>Manage Additional Map Points</h2>\n";
  echo "This is for things on the maps that are NOT venues.  Please don't have too many.<p>\n";
  echo "Importance should be lower than x (Most important), can be range x-y<p>\n";
  echo "To have text by an icon, set the Text Size.  For text without marker create a text type entry.<p>\n";
  
  $Types = Get_Map_Point_Types();
  foreach ($Types as $t) $Icons[] = $t['Name'];
  array_unshift($Icons,"");
  $Pts = Get_Map_Points(1);
  if (UpdateMany('MapPoints','Put_Map_Point',$Pts,1)) {
    $Pts = Get_Map_Points(1);
    Update_MapPoints();
  }

  $coln = 0;

  echo "<h2>Map Points</h2><p>";
  echo "<form method=post action=MapPoints.php>";
  echo "<table id=indextable border>\n";
  echo "<thead><tr>";
  echo "<th><a href=javascript:SortTable(" . $coln++ . ",'N')>id</a>\n";
  echo "<th><a href=javascript:SortTable(" . $coln++ . ",'T')>Name</a>\n";
  echo "<th><a href=javascript:SortTable(" . $coln++ . ",'T')>What</a>\n";
  echo "<th><a href=javascript:SortTable(" . $coln++ . ",'T')>Lat</a>\n";
  echo "<th><a href=javascript:SortTable(" . $coln++ . ",'T')>Long</a>\n";
  echo "<th><a href=javascript:SortTable(" . $coln++ . ",'T')>Importance</a>\n";
  echo "<th><a href=javascript:SortTable(" . $coln++ . ",'T')>Text Size</a>\n";
  echo "<th><a href=javascript:SortTable(" . $coln++ . ",'T')>Not Used</a>\n";
  echo "</thead><tbody>";
  foreach($Pts as $t) {
    $i = $t['id'];
    echo "<tr><td>$i" . fm_text1("",$t,'Name',1,'','',"Name$i");
    echo "<td>" . fm_select($Icons,$t,'Type',0,'',"Type$i");
    echo fm_text1("",$t,'Lat',1,'','',"Lat$i");
    echo fm_text1("",$t,'Lng',1,'','',"Lng$i");
    echo fm_text1("",$t,'MapImp',1,'','',"MapImp$i");
    echo fm_text1("",$t,'AddText',1,'','',"AddText$i");
    echo "<td>" . fm_checkbox("",$t,'InUse','',"InUse$i");
    echo "\n";
  }

  $t = array();
  echo "<tr><td>" . fm_text1("",$t,'Name',1,'','',"Name0");
  echo "<td>" . fm_select2($Icons,$t,'Type',0,'',"Type0");
  echo fm_text1("",$t,'Lat',1,'','',"Lat0");
  echo fm_text1("",$t,'Lng',1,'','',"Lng0");
  echo fm_text1("",$t,'MapImp',1,'','',"MapImp0");
  echo fm_text1("",$t,'AddText',1,'','',"AddText0");
  echo "<td>" . fm_checkbox("",$t,'InUse','',"InUse0");
  echo "</table>\n";
  echo "<input type=submit name=Update value=Update>\n";
  echo "</form></div>";

  dotail();

?>
