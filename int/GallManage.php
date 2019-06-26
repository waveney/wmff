<?php
  include_once("fest.php");
  A_Check('Staff','Photos');

  dostaffhead("Manage Galleries");

  include_once("ImageLib.php");
  include_once("TradeLib.php");
  global $Medias;

  $coln = 0;
  $Gals = Get_Gallery_Names();
  if (UpdateMany('Galleries','Put_Gallery_Name',$Gals,1)) $Gals = Get_Gallery_Names();

  $coln = 0;
  echo "<h2>Galleries</h2><p>";
  echo "<form method=post action=GallManage>";
  echo "<div class=tablecont><table id=indextable border>\n";
  echo "<thead><tr>";
  echo "<th><a href=javascript:SortTable(" . $coln++ . ",'N')>Id</a>\n";
  echo "<th><a href=javascript:SortTable(" . $coln++ . ",'T')>Name</a>\n";
  echo "<th><a href=javascript:SortTable(" . $coln++ . ",'T')>Credits</a>\n";
  echo "<th><a href=javascript:SortTable(" . $coln++ . ",'T')>Media</a>\n";
  echo "<th><a href=javascript:SortTable(" . $coln++ . ",'T')>Banner</a>\n";
  echo "</thead><tbody>";
  foreach($Gals as $g) {
    $i =  $g['id'];
    echo "<tr><td>" . $i;
    echo fm_text1("",$g,'SN',1,'','',"SN$i") . "</a>";
    echo fm_text1("",$g,'Credits',1,'','',"Credits$i") . "</a>";

    echo "<td>" . fm_select($Medias,$g,'Media',0,'',"Media$i");
    echo fm_text1("",$g,'Banner',1,'','',"Banner$i");
    echo "<td><a href=" . ($g['Media']?'GallVManage':'GallCManage') . "?g=" . $g['id'] . ">Edit</a>";
    echo "<td><a href=ShowGallery?g=" . $g['id'] . ">Show</a>";

    echo "\n";
  }
  echo "<tr><td><td><input type=text size=20 name=SN0 >";
  echo "<td><input type=text name=Credits0 >";
  echo "<td>" . fm_select($Medias,$g,"Media0");
  echo "<td><input type=text name=Banner0 >";
  echo "</table></div>\n";
  echo "<input type=submit name=Update value=Update>\n";
  echo "</form></div>";

  dotail();

?>
