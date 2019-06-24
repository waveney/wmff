<?php
  include_once("fest.php");
  A_Check('Staff');

  dostaffhead("List Buskers Bash Applications");
  global $db,$PLANYEAR,$SignupStates,$SignupStateColours,$YEAR;
  include_once("SignupLib.php");

  $extra = '';
  if (!isset($_REQUEST['ALL'])) $extra = "(State!=3 AND State !=5) AND ";
  echo "Click on Band Name for more info.<p>";
  if ($extra) echo "<h2><a href=BuskersBashView?ALL>Show All including canceled and Declined</a></h2>";
  
  $coln = 0;  
//  echo "<form method=post action=BuskersBashView>";
  echo "<div class=tablecont><table id=indextable border>\n";
  echo "<thead><tr>";

  echo "<th><a href=javascript:SortTable(" . $coln++ . ",'N')>Id</a>\n";
  echo "<th><a href=javascript:SortTable(" . $coln++ . ",'T')>Band Name</a>\n";
  echo "<th><a href=javascript:SortTable(" . $coln++ . ",'N')>Contact</a>\n";
  echo "<th><a href=javascript:SortTable(" . $coln++ . ",'T')>Email</a>\n";
  echo "<th><a href=javascript:SortTable(" . $coln++ . ",'T')>Example</a>\n";
  echo "<th><a href=javascript:SortTable(" . $coln++ . ",'T')>State/Actions</a>\n";
  echo "</thead><tbody>";

  $res=$db->query("SELECT * FROM SignUp WHERE Year=$PLANYEAR AND " . ($PLANYEAR==$YEAR?$extra:"") . " Activity=4 ORDER BY SN");
  
  if ($res) {
    while ($bb = $res->fetch_assoc()) {
      $id = $bb['id'];
      echo "<tr><td>$id";
      echo "<td><a href=BuskersBashForm?i=$id>" . $bb['SN'] . "</a>";
      echo "<td>" . $bb['Contact'];
      echo "<td>" . $bb['Email'];
      echo "<td>" . weblink( $bb['Example'] , "Example");
      echo "<td style='background:" . $SignupStateColours[$bb['State']] . "'><form method=post action=BuskersBashForm>" . 
           fm_hidden('id',$id) . $SignupStates[$bb['State']] . " " . SignupActions('BB',$bb['State']) . "</form>";

    }
  }
  echo "</tbody></table></div>\n";

  dotail();
?>
