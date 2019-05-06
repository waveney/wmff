<?php
  include_once("fest.php");
  A_Check('Staff','Other');

  dostaffhead("Manage News");
  global $SHOWYEAR;

  include_once("TradeLib.php");
  include_once("NewsLib.php");
  include_once("Uploading.php");
  echo "<div class='content'><h2>Manage News</h2>\n";
  
  $DispTyp = 0;
  $DispLim = 50;
  if (isset($_GET{'S'}) || isset($_POST['S'])) {
    if (isset($_GET{'S'})) $DispLim = $_GET{'S'};
    if (isset($_POST{'S'})) $DispLim = $_POST{'S'};
    if ($DispLim == 'ALL') $DispLim = 1000;
    $DispTyp = 1;
  } else { 
    echo "<h2><a href=NewsManage.php?S=ALL>Show All News</a> &nbsp;,  &nbsp; <a href=NewsManage.php?S=50>Last 50 Items</a></h2>";
  }
  $News = Get_All_News($DispTyp,$DispLim,1);
  if (UpdateMany('News','Put_News',$News,0,'created')) $News=Get_All_News($DispTyp,$DispLim,1);

  $coln = 0;
  echo "<form method=post>";
  echo "<div class=tablecont><table id=indextable border>\n";
  echo "<thead><tr>";
  echo "<th><a href=javascript:SortTable(" . $coln++ . ",'N')>Index</a>\n";
  echo "<th><a href=javascript:SortTable(" . $coln++ . ",'T')>Title</a>\n";
  echo "<th><a href=javascript:SortTable(" . $coln++ . ",'T')>Date</a>\n";
  echo "<th><a href=javascript:SortTable(" . $coln++ . ",'T')>Disp</a>\n";
  echo "<th><a href=javascript:SortTable(" . $coln++ . ",'T')>Content</a>\n";
  echo "<th><a href=javascript:SortTable(" . $coln++ . ",'T')>Image</a>\n";
  echo "<th><a href=javascript:SortTable(" . $coln++ . ",'T')>Caption</a>\n";
  echo "<th><a href=javascript:SortTable(" . $coln++ . ",'T')>Who</a>\n";
  echo "</thead><tbody>";
  if ($News) foreach($News as $t) {
    $i = $t['id'];
    echo "<tr><td>$i<br><a href=NewsEdit.php?n=$i>Edit</a>" . fm_text1("",$t,'SN',1,'','',"SN$i");
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
  echo "<tr><td><td><input type=text name=SN0 >";
  echo "<td><input type=text size=10 name=created0 value='" . date('j M Y G:i') . "'>";
  echo "<td><input type=checkbox checked name=display0>";
  echo "<td><textarea name=content0 rows=3 cols=60></textarea>";
  echo "<td><input type=text name=image0>";
  echo "<td><input type=text name=caption0>";
  echo "<td><input type=text size=10 name=author0 value =" . firstword($USER['SN']) . ">";
  echo "</table></div>\n";
  echo "<input type=submit name=Update value=Update>\n";
  echo "</form></div>";

  dotail();

?>
