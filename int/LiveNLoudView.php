<?php
  include_once("fest.php");
  A_Check('Staff');

  dostaffhead("List Live N Load Applications","<script src=/js/Tools.js></script>");
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

  $res=$db->query("SELECT * FROM SignUp WHERE Year=$THISYEAR ORDER BY Name");
  
  if ($res) {
    while ($lnl = $res->fetch_assoc()) {
      $id = $lnl['id'];
      echo "<tr><td>$i";
      echo "<td>" . $lnl['Name'];
      echo "<td>" . $lnl['Style'];
      echo "<td>" . $lnlclasses[$lnl['Activity']];
      if ($lnl['TotalSize']) {
	$siz = $lnl['TotalSize'];
      } else {
	$siz = 0;
	for ($i=1;$i<7;$i++) if ($lnl["Name$i"]) $siz++;
      }
      echo "<td>$siz";
      echo "<td>" . $lnl['Contact'];
      echo "<td>" . $lnl['Email'];
      echo "<td>Submitted\n";

/*
    
      
      if (Access('Staff','Venues')) echo "<a href=EventAdd.php?e=$i>";
      if (strlen($evnt['Name']) >2) { echo $evnt['Name'] . "</a>"; } else { echo "Nameless</a>"; };
      echo "<td>" . $DayList[$evnt['Day']] . "<td>" . $evnt['Start'] . "<td>";
      if ($se > 0 && $evnt['SubEvent'] < 0) { echo $evnt['SlotEnd']; } else { echo $evnt['End']; }; 
      echo "<td>" . $Venues[$evnt['Venue']] . "<td>" . $Event_Types[$evnt['Type']];
      echo "<td>" . $Public_Event_Types[$evnt['Public']];
      echo "<td>" ; 
      if ($evnt['SubEvent'] <= 0 ) {
	if ($evnt['Price1']) { echo "&pound;" . $evnt['Price1']; } else echo "Free";
	if ($evnt['Price2']) echo " /&pound;" . $evnt['Price2']; 
	if ($evnt['DoorPrice']) echo " /&pound;" . $evnt['DoorPrice']; 
      }
      echo "<td>" .($evnt['BigEvent'] ? "Big" : "Normal" );
      if ($se == 0) {
        if ($evnt['SubEvent'] == 0) { echo "<td>No\n"; }
	else { echo "<td><a href=EventList.php?se=$i>Yes</a>\n"; }
      }
      if ($se != 0) {
	echo "<td>";
	if ($evnt['SubEvent']>0) {
	  echo Get_Event_Participants($i,2) ;
	} else {
	}
      }
      echo "<td>" . $evnt['Notes'];
      if ($se > 0 && $evnt['SubEvent'] < 0) echo " Full end: " . $evnt['End'] . " PARENT";
      echo "<td><a href=EventShow.php?e=$i>Show</a>\n";
*/

    }
  }
  echo "</tbody></table>\n";

  dotail();
?>
