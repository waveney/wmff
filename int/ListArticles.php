<?php
  include_once("fest.php");
  A_Check('Committee','News');

  dostaffhead("Manage Front Page Articles");
  global $THISYEAR;

  include_once("TradeLib.php");
  include_once("NewsLib.php");
  include_once("Uploading.php");
  
  $Arts = Get_All_Articles(1);
  if (UpdateMany('Articles','Put_Article',$Arts,0)) $Arts=Get_All_Articles(1);

  echo "<h2>Special Codes</h2>";
  echo "<table border><tr><td>Code<th>Meaning
<tr><td>Dance_Imp<td>A Random dance side with some importance
<tr><td>Dance_Many<td>How many Dance sides and a random one
<tr><td>Music_Imp<td>A Random Music act with some importance
<tr><td>Music_Many<td>How many Acts and a random one
<tr><td>Other_Imp<td>A Random Other act with some importance
<tr><td>Other_Many<td>How many Others and a random one
</table>\n<p>

<H2>Articles</h2>
The Articles in use will be displayed on the top page.  Sorting by Importance then random.<p>
";
  $coln = 0;
  echo "<form method=post>";
  echo "<table id=indextable border>\n";
  echo "<thead><tr>";
  echo "<th><a href=javascript:SortTable(" . $coln++ . ",'N')>Index</a>\n";
  echo "<th><a href=javascript:SortTable(" . $coln++ . ",'T')>Title</a>\n";
  echo "<th><a href=javascript:SortTable(" . $coln++ . ",'T')>Link</a>\n";
  echo "<th><a href=javascript:SortTable(" . $coln++ . ",'T')>Text</a>\n";
  echo "<th><a href=javascript:SortTable(" . $coln++ . ",'T')>Image</a>\n";
  echo "<th><a href=javascript:SortTable(" . $coln++ . ",'T')>Importance</a>\n";
  echo "<th><a href=javascript:SortTable(" . $coln++ . ",'T')>Special</a>\n";
  echo "<th><a href=javascript:SortTable(" . $coln++ . ",'T')>In Use</a>\n";
  echo "</thead><tbody>";
  if ($Arts) foreach($Arts as $t) {
    $i = $t['id'];
    echo "<tr><td>$i<br><a href=NewsEdit.php?n=$i>Edit</a>" . fm_text1("",$t,'SName',1,'','',"SName$i");
    echo "<td>" . fm_textinput("created$i",date('j M Y G:i',$t['created']));
    echo "<td>" . fm_checkbox("",$t,'display','',"display$i");
    echo "<td>" . fm_basictextarea($t,'content',3,3,'',"content$i");
    echo fm_text1("",$t,'image',1,'','',"image$i") . "<br>";
/*
    echo "Upload:";
      echo "<input type=file name=PhotoForm$i id=PhotoForm$i onchange=document.getElementById('PhotoButton$i').click()>";
      echo "<input hidden type=submit name=Action value=Photo id=PhotoButton$i>";
      if ($Mess && $Action == 'Photo') echo $Mess;
*/
    echo fm_text1("",$t,'caption',1,'','',"caption$i");
    echo fm_text1('',$t,'author',1,'','',"author$i");
    echo "\n";
  }
  echo "<tr><td><td><input type=text name=SName0 >";
  echo "<td><input type=text size=10 name=created0 value='" . date('j M Y G:i') . "'>";
  echo "<td><input type=checkbox checked name=display0>";
  echo "<td><textarea name=content0 rows=3 cols=60></textarea>";
  echo "<td><input type=text name=image0>";
  echo "<td><input type=text name=caption0>";
  echo "<td><input type=text size=10 name=author0 value =" . firstword($USER['SName']) . ">";
  echo "</table>\n";
  echo "<input type=submit name=Update value=Update>\n";
  echo "</form></div>";

  dotail();

?>
