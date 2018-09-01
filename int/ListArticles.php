<?php
  include_once("fest.php");
  A_Check('Committee','News');

  dostaffhead("Manage Front Page Articles");
  global $Importance,$SHOWYEAR;

  include_once("TradeLib.php");
  include_once("NewsLib.php");
  include_once("Uploading.php");
  
//var_dump($_POST);

  $Arts = Get_All_Articles(1);
// Update Image Width and Height - Add to Post, UpdateMany then stores
  foreach ($Arts as $a) {
    if ($a['Image']) {
      if (preg_match('/^https?:\/\//i',$a['Image'])) {
        $stuff = getimagesize($a['Photo']);
      } else if (preg_match('/^\/(.*)/',$a['Image'],$mtch)) {
        if (file_exists($mtch[1])) {
          $stuff = getimagesize($mtch[1]);
        } else {
          $stuff = [0,0];
        }
      } else {
        $stuff = getimagesize($a['Image']);
      }
      if ($stuff) {
        $i = $a['id'];
        $_POST['ImageWidth' . $i] = $stuff[0];
        $_POST['ImageHeight' . $i] = $stuff[1];
      }
    }
  }
      
  if (UpdateMany('Articles','Put_Article',$Arts,0,'','','Title')) $Arts=Get_All_Articles(1);

  echo "<h2>Special Titles</h2>";
  echo "<table border><tr><td>Code<th>Meaning
<tr><td>@Dance_Imp<td>A Random dance side with some importance
<tr><td>@Dance_Many<td>How many Dance sides and a random one
<tr><td>@Music_Imp<td>A Random Music act with some importance
<tr><td>@Music_Many<td>How many Acts and a random one
<tr><td>@Other_Imp<td>A Random Other act with some importance
<tr><td>@Other_Many<td>How many Others and a random one
<tr><td>@Select<td>Highlight Events of selected Importance, Text gives number to do, All means all
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
  echo "<th><a href=javascript:SortTable(" . $coln++ . ",'T')>In Use</a>\n";
  echo "<th><a href=javascript:SortTable(" . $coln++ . ",'T')>Scale</a>\n";
  echo "</thead><tbody>";
  if ($Arts) foreach($Arts as $t) {
    $i = $t['id'];
    echo "<tr><td>" . $t['id'];
    echo "<td>" . fm_textinput("SN$i",$t['SN']);
    echo "<td>" . fm_textinput("Link$i",$t['Link']);
    echo "<td>" . fm_basictextarea($t,'Text',3,3,'',"Text$i");
    echo "<td>" . fm_textinput("Image$i",$t['Image']);
    echo "<td>" . fm_select($Importance,$t,'Importance',0,'',"Importance$i");
    echo "<td>" . fm_checkbox("",$t,'InUse','',"InUse$i");
    echo "<td>" . fm_smalltext('',"Scale$i",$t['Scale']);
    echo "\n";
  }
  echo "<tr><td>";
    echo "<td>" . fm_textinput("SN0",'');
    echo "<td>" . fm_textinput("Link0",'');
    echo "<td>" . fm_basictextarea($t,'Text',3,3,'',"Text0");
    echo "<td>" . fm_textinput("Image0",'');
    echo "<td>" . fm_select($Importance,$t,'Importance0');
    echo "<td>" . fm_checkbox("",$t,'InUse','',"InUse0");
    echo "<td>" . fm_smalltext('',"Scale0",1);
  echo "</table>\n";
  echo "<input type=submit name=Update value=Update>\n";
  echo "</form></div>";

  dotail();

?>
