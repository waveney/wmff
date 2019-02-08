<?php
  include_once("fest.php");
  A_Check('Staff');

  dostaffhead("List Buskers Bash Applications");
  global $db,$PLANYEAR,$SignupStates,$SignupStateColours,$YEAR;
  include_once("SignupLib.php");

  echo "Click on Band Name for more info.<p>";
  $coln = 0;  
//  echo "<form method=post action=BuskersBashView.php>";
  echo "<table id=indextable border>\n";
  echo "<thead><tr>";

  echo "<th><a href=javascript:SortTable(" . $coln++ . ",'N')>Id</a>\n";
  echo "<th><a href=javascript:SortTable(" . $coln++ . ",'T')>Band Name</a>\n";
  echo "<th><a href=javascript:SortTable(" . $coln++ . ",'N')>Contact</a>\n";
  echo "<th><a href=javascript:SortTable(" . $coln++ . ",'T')>Email</a>\n";
  echo "<th><a href=javascript:SortTable(" . $coln++ . ",'T')>Example</a>\n";
  echo "<th><a href=javascript:SortTable(" . $coln++ . ",'T')>State/Actions</a>\n";
  echo "</thead><tbody>";

  $res=$db->query("SELECT * FROM SignUp WHERE Year=$PLANYEAR AND " . ($PLANYEAR==$YEAR?"State!=3 AND":"") . " Activity=4 ORDER BY SN");
  
  if ($res) {
    while ($bb = $res->fetch_assoc()) {
      $id = $bb['id'];
      echo "<tr><td>$id";
      echo "<td><a href=BuskersBashForm.php?i=$id>" . $bb['SN'] . "</a>";
      echo "<td>" . $bb['Contact'];
      echo "<td>" . $bb['Email'];
      echo "<td>" . weblink( $bb['Example'] , "Example");
      echo "<td style='background:" . $SignupStateColours[$bb['State']] . "'><form method=post action=BuskersBashForm.php>" . 
           fm_hidden('id',$id) . $SignupStates[$bb['State']] . " " . SignupActions('BB',$bb['State']) . "</form>";

    }
  }
  echo "</tbody></table>\n";

  dotail();
?>
