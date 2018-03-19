<?php
  include_once("fest.php");
  A_Check('Committee','Photos');

  dostaffhead("Manage A Gallery");

/* List all photos with edit boxes and thumb nails
   comment a photo may be part of more than one gallery
   import from a directory all files
   no upload, remove file to remove from gallery - SName = filename??
*/


  include_once("ImageLib.php");
  include_once("TradeLib.php");

  echo "<div class='content'><h2>Manage A Gallery</h2>\n";
  
  $Gals = Get_Gallery_Names(1);
  $Galid = ( $_GET['g'] || $_POST['g'] );

  $Gal = Get_Gallery_Photos($Galid);

  if (UpdateMany('Galleries','Put_Gallery_Photo',$Gal,1,'','','File')) $Gal = Get_Gallery_Photos($Galid);

  $coln = 0;
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
