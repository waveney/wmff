<?php
  include_once("fest.php");
  A_Check('SysAdmin');

  dostaffhead("Manage Links");

  include_once("TradeLib.php");
  global $THISYEAR;

  $coln = 0;
  $lnks = Get_OtherLinks();

  if (UpdateMany('OtherLinks','Put_OtherLink',$lnks,1)) $lnks = Get_OtherLinks();

  $coln = 0;
  echo "<h2>Links</h2><p>";
  echo "Heading 'Category' not yet used.<p>";
  echo "<form method=post action=LinkManage.php>";
  echo "<table id=indextable border>\n";
  echo "<thead><tr>";
  echo "<th><a href=javascript:SortTable(" . $coln++ . ",'N')>Id</a>\n";
  echo "<th><a href=javascript:SortTable(" . $coln++ . ",'T')>Name</a>\n";
  echo "<th><a href=javascript:SortTable(" . $coln++ . ",'T')>Link</a>\n";
  echo "<th><a href=javascript:SortTable(" . $coln++ . ",'T')>Image</a>\n";
  echo "<th><a href=javascript:SortTable(" . $coln++ . ",'N')>Category</a>\n";
  echo "<th><a href=javascript:SortTable(" . $coln++ . ",'N')>Year</a>\n";
  echo "</thead><tbody>";
  foreach($lnks as $g) {
    echo "<tr><td>" . $g['id'];
    echo fm_text1("",$g,'SName',1,'','',"SName$i");
    echo fm_text1("",$g,'URL',1,'','',"URL$i");
    echo fm_text1("",$g,'Image',1,'','',"Image$i");
    echo fm_text1("",$g,'LinkType',1,'','',"LinkType$i");
    echo fm_text1("",$g,'Year',1,'','',"Year$i");
    echo "\n";
  }
  echo "<tr><td><td><input type=text size=20 name=SName0 >";
  echo "<td><input type=text name=URL0 >";
  echo "<td><input type=text name=Image0 >";
  echo "<td><input type=text name=LinkType0 >";
  echo "<td><input type=text name=Year0 value=$THISYEAR >";
  echo "</table>\n";
  echo "<input type=submit name=Update value=Update>\n";
  echo "</form></div>";

  dotail();

?>
