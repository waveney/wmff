<?php
  include_once("fest.php");
  A_Check('Staff');

  dostaffhead("List Art Applications");
  global $db,$PLANYEAR;
  include_once("SignupLib.php");

  $extra = '';
//  if (!isset($_REQUEST['ALL'])) $extra = "AND State<3 ";
  echo "Click Person's Name for more info.<p>";
  if ($extra) echo "<h2><a href=LiveNLoudView?ALL>Show All including canceled and Declined</a></h2>";
  $coln = 0;  
//  echo "<form method=post action=LiveNLoudView>";
  echo "<div class=tablecont><table id=indextable border>\n";
  echo "<thead><tr>";

  echo "<th><a href=javascript:SortTable(" . $coln++ . ",'N')>Id</a>\n";
  echo "<th><a href=javascript:SortTable(" . $coln++ . ",'T')>Artist</a>\n";
  echo "<th><a href=javascript:SortTable(" . $coln++ . ",'T')>Age</a>\n";
  echo "<th><a href=javascript:SortTable(" . $coln++ . ",'T')>Style</a>\n";
  echo "<th><a href=javascript:SortTable(" . $coln++ . ",'T')>Activity</a>\n";
  echo "<th><a href=javascript:SortTable(" . $coln++ . ",'T')>Hobby</a>\n";
  echo "<th><a href=javascript:SortTable(" . $coln++ . ",'T')>Where</a>\n";
  echo "<th><a href=javascript:SortTable(" . $coln++ . ",'N')>Phone</a>\n";
  echo "<th><a href=javascript:SortTable(" . $coln++ . ",'T')>Email</a>\n";
  echo "<th><a href=javascript:SortTable(" . $coln++ . ",'T')>State/Actions</a>\n";
  echo "</thead><tbody>";

  $res=$db->query("SELECT * FROM SignUp WHERE Year=$PLANYEAR $extra AND Activity=5 ORDER BY SN");
  
  if ($res) {
    while ($art = $res->fetch_assoc()) {
      $id = $art['id'];
      echo "<tr><td>$id";
      echo "<td><a href=ArtForm?i=$id>" . $art['SN'] . "</a>";
      echo "<td>" . $art['Age'];
      echo "<td>" . $art['Style'];
      echo "<td>" . $ArtClasses[isset($art['Tickbox1'])?$art['Tickbox1']:0];
      echo "<td>" . $ArtValues[isset($art['Tickbox3'])?$art['Tickbox3']:0];
      echo "<td>" . $ArtPosition[isset($art['Tickbox2'])?$art['Tickbox2']:0];
      echo "<td>" . $art['Phone'];
      echo "<td>" . $art['Email'];
      echo "<td style='background:" . $SignupStateColours[$art['State']] . "'><form method=post action=ArtForm>" . 
           fm_hidden('id',$id) . $SignupStates[$art['State']] . " " . SignupActions('ART',$art['State']) . "</form>";

    }
  }
  echo "</tbody></table></div>\n";

  dotail();
?>
