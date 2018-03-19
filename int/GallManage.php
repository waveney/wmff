<?php
  include_once("fest.php");
  A_Check('Staff','Photos');

  dostaffhead("Manage Galleries");

  include_once("ImageLib.php");
  include_once("TradeLib.php");

  $coln = 0;
  $Gals = Get_Gallery_Names($gid);
  if (UpdateMany('Galleries','Put_Gallery_Name',$cwGalsTypes,1)) $Gals = Get_Gallery_Names();

  $coln = 0;
  echo "<h2>Galleries</h2><p>";
  echo "<form method=post action=GallManage.php>";
  echo "<table id=indextable border>\n";
  echo "<thead><tr>";
  echo "<th><a href=javascript:SortTable(" . $coln++ . ",'N')>Id</a>\n";
  echo "<th><a href=javascript:SortTable(" . $coln++ . ",'T')>Name</a>\n";
  echo "<th><a href=javascript:SortTable(" . $coln++ . ",'T')>Credits</a>\n";
  echo "</thead><tbody>";
  foreach($Gals as $g) {
    echo "<tr><td>" . $g['id'];
    echo fm_text1("",$g,'SName',1,'','',"SName$i") . "</a>";
    echo fm_text1("",$g,'Credits',6,'','',"Credits$i") . "</a>";
    echo "<td><a href=GallCManage.php?g=" . $g['id'] . ">Edit</a>";
    echo "\n";
  }
  echo "<tr><td><td><input type=text size=20 name=SName0 >";
  echo "<td><input type=text size=100 name=Credits0 >";
  echo "</table>\n";
  echo "<input type=submit name=Update value=Update>\n";
  echo "</form></div>";

  dotail();

?>
