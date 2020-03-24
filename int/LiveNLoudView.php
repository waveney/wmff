<?php
  include_once("fest.php");
  A_Check('Staff');

  dostaffhead("List Live N Load Applications");
  global $db,$PLANYEAR;
  include_once("SignupLib.php");

  $extra = '';
  if (!isset($_REQUEST['ALL'])) $extra = "AND State<3 ";
  echo "Click on Band Name for more info.<p>";
  if ($extra) echo "<h2><a href=LiveNLoudView?ALL>Show All including canceled and Declined</a></h2>";
  $coln = 0;  
//  echo "<form method=post action=LiveNLoudView>";
  echo "<div class=tablecont><table id=indextable border>\n";
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

  $res=$db->query("SELECT * FROM SignUp WHERE Year='$PLANYEAR' $extra AND Activity<4 ORDER BY SN");
  
  if ($res) {
    while ($lnl = $res->fetch_assoc()) {
      $id = $lnl['id'];
      echo "<tr><td>$id";
//      echo "<td><a href=lnledit?id=$id>" . $lnl['SN'] . "</a>";
      echo "<td><a href=LiveNLoudForm?i=$id>" . $lnl['SN'] . "</a>";
      echo "<td>" . $lnl['Style'];
      echo "<td style='background:" . $Colours[$lnl['Activity']] . ";'>" . $lnlclasses[$lnl['Activity']];
      if ($lnl['TotalSize']) {
        $siz = $lnl['TotalSize'];
      } else {
        $siz = 0;
        for ($i=1;$i<7;$i++) if (isset($lnl["SN$i"]) && ($lnl["SN$i"])) $siz++;
      }
      echo "<td>$siz";
      echo "<td>" . $lnl['Contact'];
      echo "<td>" . $lnl['Email'];
      echo "<td style='background:" . $SignupStateColours[$lnl['State']] . "'><form method=post action=LiveNLoudForm>" . 
           fm_hidden('id',$id) . $SignupStates[$lnl['State']] . " " . SignupActions('LNL',$lnl['State']) . "</form>";

    }
  }
  echo "</tbody></table></div>\n";

  dotail();
?>
