<?php
  include_once("fest.php");
  A_Check('Staff');

  dostaffhead("List Live N Load Applications");
  global $db,$THISYEAR;
  include("SignupLib.php");

  $coln = 0;  
  echo "<form method=post action=LiveNLoudView.php>";
  echo "<table id=indextable border>\n";
  echo "<thead><tr>";

  echo "<th><a href=javascript:SortTable(" . $coln++ . ",'N')>Id</a>\n";
  echo "<th><a href=javascript:SortTable(" . $coln++ . ",'T')>Band Name</a>\n";
  echo "<th><a href=javascript:SortTable(" . $coln++ . ",'T')>Style</a>\n";
  echo "<th><a href=javascript:SortTable(" . $coln++ . ",'T')>Category</a>\n";
  echo "<th><a href=javascript:SortTable(" . $coln++ . ",'N')>Size</a>\n";
  echo "<th><a href=javascript:SortTable(" . $coln++ . ",'N')>Contact</a>\n";
  echo "<th><a href=javascript:SortTable(" . $coln++ . ",'T')>Email</a>\n";
  echo "<th><a href=javascript:SortTable(" . $coln++ . ",'T')>State/Actions</a>\n";
  echo "</thead><tbody>";

  $res=$db->query("SELECT * FROM SignUp WHERE Year=$THISYEAR AND State<2 AND Activity<3 ORDER BY Name");
  
  if ($res) {
    while ($lnl = $res->fetch_assoc()) {
      $id = $lnl['id'];
      echo "<tr><td>$id";
//      echo "<td><a href=lnledit.php?id=$id>" . $lnl['Name'] . "</a>";
      echo "<td>" . $lnl['Name'];
      echo "<td>" . $lnl['Style'];
      echo "<td style='background:" . $Colours[$lnl['Activity']] . ";'>" . $lnlclasses[$lnl['Activity']];
      if ($lnl['TotalSize']) {
	$siz = $lnl['TotalSize'];
      } else {
	$siz = 0;
	for ($i=1;$i<7;$i++) if ($lnl["Name$i"]) $siz++;
      }
      echo "<td>$siz";
      echo "<td>" . $lnl['Contact'];
      echo "<td>" . $lnl['Email'];
      echo "<td>" . $States[$lnl['State']];

    }
  }
  echo "</tbody></table>\n";

  dotail();
?>
