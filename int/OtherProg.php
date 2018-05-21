<?php
  include_once("fest.php");
  A_Check('Committee','Dance');
?>

<html>
<head>
<title>Wimborne Folk Festival Committee</title>
<?php include_once("minimalfiles/header.php"); ?>
<script src="http://code.jquery.com/jquery-3.1.0.min.js"></script>
<script src="js/tableHeadFixer.js"></script>
<script src="js/DanceProg.js"></script>
<?php
  include_once("festcon.php");
  echo "</head> <body>\n";

  include_once("OtherLib.php");
  include_once("ProgLib.php");

  if (isset($_GET{'d'})) { $DAY = $_GET{'d'}; } else { $DAY='Sat'; }

  if (!isset($_GET{'EInfo'})) $_GET{'EInfo'} = 0;
  for ($t=10;$t<18;$t++) {
    $Times[] = $t*100;
    $Times[] = $t*100+30;
  }

  $Others = Get_Other_People();
  foreach ($Times as $t) $lineLimit[$t]=2;

  $evs = Get_Events_For('Other',$DAY);
  foreach ($evs as $ev) {
    $eid = $ev['EventId'];
    if (!$ev['BigEvent']) {
      $v = $ev['Venue'];
      $t = timeround($ev['Start'],30);
      
      $EV[$v][$t]['e'] = $eid;
      if ($ev["Side1"]) { $EV[$v][$t]['S1'] = $ev["Side1"]; } 
      if ($ev["Side2"]) { $EV[$v][$t]['S2'] = $ev["Side2"]; }
      if ($ev["Side3"]) { $lineLimit[$t] = max($lineLimit[$t],3); $EV[$v][$t]['S3'] = $ev["Side3"]; }
      if ($ev["Side4"]) { $lineLimit[$t] = max($lineLimit[$t],4); $EV[$v][$t]['S4'] = $ev["Side4"]; }
    } else if (!$ev['ExcludeCount']) {
      $Other = Get_Other_Things_For($eid);
      foreach($Other as $i=>$o) if ($o['Type'] == 'Side') $SideCounts[$o['Identifier']]++;
    }
  }

// Displays Grid
function Prog_Grid() {
  global $DAY,$Times,$lineLimit,$EV,$Sides,$SideCounts;
  $Venues = Get_Venues_For('Dance');
  $VenueNames = Get_Real_Venues(0);

  echo "<div class=GridWrapper><div class=GridContainer>";
  echo "<table border id=Grid><thead><tr><th id=DayId>$DAY";
  foreach ($Venues as $v) echo "<th class=DPGridTH id=Ven$v>" . $VenueNames[$v];
  echo "</tr></thead><tbody>";

  foreach ($Times as $time) {
    echo "<tr><th rowspan=" . $lineLimit[$time] . ">$time";
    for ($line=0; $line < $lineLimit[$time]; $line++) {
      $sl = "S" .($line+1);
      if ($line) echo "<tr>";
      foreach ($Venues as $v) {
	if (isset($EV[$v][$time]['e'])) {
	  $eid = $EV[$v][$time]['e'];
          if (isset($EV[$v][$time][$sl])) { $s = $EV[$v][$time][$sl]; } else { $s = 0; }
          echo "<td id=G$eid:$v:$time:$line:$s draggable=true class=DPGridDisp ondragstart=drag(event) ondrop=drop(event) ondragover=allow(event)>";
 	  if ($s) {
	    if (isset($Sides[$s])) {
              echo $Sides[$s][0] . " (" .$Sides[$s][1] .")";;
  	      $SideCounts[$s]++;
	    } else {
	      echo "ERROR...";
	    }
          } else {
	    echo "&nbsp;";
	  }
	} else {
          echo "<td class=DPGridGrey>&nbsp;";
	}
      }
    }
  }
	
  echo "</tbody></table>";
  echo "</div></div>\n";
}

function Side_List() {
  global $DAY,$Sides,$SideCounts;
  echo "<div class=SideListWrapper><div class=SideListContainer>";
  echo "<table border id=SideList>";
  echo "<thead><tr><th>Side<th>Want<th>Have</thead><tbody>\n";
  foreach ($Sides as $id=>$side) {
    echo "<tr><td draggable=true class='SideName' id=SideN$id ondragstart=drag(event) ondragover=allow(event) ondrop=drop(event)>";
    echo $side[0] . " (" . $side[1] . ") <td id=SideW$id align=right>" . $side[2];
    echo "<td id=SideH$id align=right>";
    echo $SideCounts[$id] . "\n";
  }
  echo "</table></div></div>\n";
}

function Controls() {
  global $InfoLevels;
  echo "<div class=DPControls><center>";
  echo "Dance Programming Controls";
  echo "<form method=get action=DanceProg.php>";
  echo "<table><tr><td>";
  echo "<td><input type=submit name=d value=Fri> <input type=submit name=d value=Sat> <input type=submit name=d value=Sun >\n";
  echo "<tr>" . fm_radio("Info",$InfoLevels,$_GET,'EInfo',"onchange=UpdateInfo()");
  echo "</table></form>\n";
  echo "<h2><a href=Staff.php>Main Page</a></h2></center>";
  echo "</div>\n";
}

include ("CheckDance.php");
function ErrorPane() {
  echo "<div class=ErrorWrapper><div class=ErrorContainer id=InformationPane>";
  CheckDance($_GET['EInfo']);
  echo "</div></div>\n";
}

function Notes_Pane() {
  echo "<div id=Notes_Pane>";
  echo "To add a 3rd or 4th side to a time edit the event, for more than 4 use a Big Event.<br>";
  echo "To remove a side drag back to the side list.<br>";
//  echo "Adding small notes to the programme will be possible soon.  ";
  echo "<div>";
}

// MAIN CODE HERE
  Prog_Grid();
  Side_List();
  Controls();
  ErrorPane();
  Notes_Pane()

// No standard footer - will use whole screen
?>

  </div>
</div>
</body>
</html>
