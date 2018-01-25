<?php
  include_once("fest.php");
  A_Check('Committee');

  dostaffhead("Manage Campsite Useage");

  function Get_Campsites() {
    global $db,$YEAR;
    $sc = array();
    $res = $db->query("SELECT * FROM CampsiteUse WHERE Year=$YEAR");
    if ($res) while($c = $res->fetch_assoc()) $cs[] = $c;
    return $cs;
  }

  function Get_Campsite($id) {
    global $db;
    $res = $db->query("SELECT * FROM CampsiteUse WHERE id=$id");
    if ($res) while($c = $res->fetch_assoc()) return $c;
  }

  function Put_Campsite($now) {
    $e=$now['id'];
    $Cur = Get_Campsite($e);
    return Update_db('CampsiteUse',$Cur,$now);
  }

  include_once("TradeLib.php");
  global $USER,$THISYEAR;
  echo "<div class='content'><h2>Manage Campsite Usage</h2>\n";
  
  echo "Campsite useage by department - After the free 60 one night tickets are used all subsequent passes will cost &pound7 each.<p>";

  echo "Please only update your own record(s).<p>";
  
  echo "Maintain the number of passes you need for your department, if one person is staying 3 nights you need 3 passes.<p>";

  $Camps=Get_Campsites(1);

  if (UpdateMany('CampsiteUse','Put_Campsite',$Camps,0)) $Camps=Get_Campsites(1);

  $coln = 0;
  echo "<form method=post action=Campsite.php>";
  echo "<table id=indextable border>\n";
  echo "<thead><tr>";
  echo "<th><a href=javascript:SortTable(" . $coln++ . ",'N')>Index</a>\n";
  echo "<th><a href=javascript:SortTable(" . $coln++ . ",'T')>Category</a>\n";
  echo "<th><a href=javascript:SortTable(" . $coln++ . ",'N')>Number of Passes</a>\n";
  echo "<th><a href=javascript:SortTable(" . $coln++ . ",'T')>Who</a>\n";
  echo "<th><a href=javascript:SortTable(" . $coln++ . ",'N')>Priority</a>\n";
  echo "<th><a href=javascript:SortTable(" . $coln++ . ",'T')>Notes</a>\n";
  echo "</thead><tbody>";
  foreach($Camps as $t) {
    $i = $t['id'];
    echo "<tr><td>$i" . fm_text1("",$t,'Name',1,'','',"Name$i");
    echo fm_number1('',$t,'Number','','',"Number$i");
    echo fm_text1('',$t,'Who',1,'','',"Who$i");
    echo fm_number1('',$t,'Priority','','',"Priority$i");
    echo fm_text1('',$t,'Notes',3,'','',"Notes$i");
    echo "\n";
  }
  echo "<tr><td><td><input type=text name=Name0 >";
  echo "<td><input type=number name=Number0>";
  echo "<td><input type=text name=Who0 value=" . $USER['Login'] . ">";
  echo "<td><input type=number name=Priority0>";
  echo "<td><input type=text name=Notes0 size=60>";
  echo fm_hidden('Year0',$THISYEAR);
  echo "</table>\n";
  echo "<input type=submit name=Update value=Update>\n";
  echo "</form></div>";

  dotail();

?>
