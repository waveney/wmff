<?php
  include_once("fest.php");
  A_Check('Committee','Trade');

  dostaffhead("Manage Water Refils");
  global $THISYEAR;

  include_once("TradeLib.php");
  echo "<div class='content'><h2>Manage Water Refills</h2>\n";
  
  $Spons=Get_WaterRefills(1);
  if (UpdateMany('Water','Put_WaterRefill',$Spons,0)) $Spons=Get_WaterRefills(1);

  echo "Year is the most recent year they are a refiller.<p>";
// echo  Importance is a relative value (not yet used).<p>\n";
//  echo "Don't use Both - it does not work...<p>\n";

  $coln = 0;
  echo "<form method=post>";
  echo "<table id=indextable border>\n";
  echo "<thead><tr>";
  echo "<th><a href=javascript:SortTable(" . $coln++ . ",'N')>Index</a>\n";
  echo "<th><a href=javascript:SortTable(" . $coln++ . ",'T')>Name</a>\n";
  echo "<th><a href=javascript:SortTable(" . $coln++ . ",'N')>Year</a>\n";
  echo "<th><a href=javascript:SortTable(" . $coln++ . ",'T')>Website</a>\n";
  echo "<th><a href=javascript:SortTable(" . $coln++ . ",'T')>Image URL</a>\n";
//  echo "<th><a href=javascript:SortTable(" . $coln++ . ",'T')>Both</a>\n";
//  echo "<th><a href=javascript:SortTable(" . $coln++ . ",'T')>Description</a>\n";
//  echo "<th><a href=javascript:SortTable(" . $coln++ . ",'N')>Importance</a>\n";
  echo "</thead><tbody>";
  if ($Spons) foreach($Spons as $t) {
    $i = $t['id'];
    echo "<tr><td>$i" . fm_text1("",$t,'SName',1,'','',"SName$i");
    echo fm_number1('',$t,'Year','','',"Year$i");
    echo fm_text1("",$t,'Web',1,'','',"Web$i");
    echo fm_text1("",$t,'Image',1,'','',"Image$i");
//    echo "<td>" . fm_checkbox('',$t,'IandT','',"IandT$i");
//    echo "<td>" . fm_basictextarea($t,'Description',2,2,'',"Description$i");
//    echo fm_number1('',$t,'Importance','','',"Importance$i");
    echo "\n";
  }
  echo "<tr><td><td><input type=text name=SName0 >";
  echo "<td><input type=number name=Year0 value=$THISYEAR>";
  echo "<td><input type=text name=Web0>";
  echo "<td><input type=text name=Image0>";
//  echo "<td><input type=checkbox name=IandT0>";
//  echo "<td><textarea name=Description0 rows=2 cols=40></textarea>";
//  echo "<td><input type=number name=Importance0>";
  echo "</table>\n";
  echo "<input type=submit name=Update value=Update>\n";
  echo "</form></div>";

  dotail();

?>