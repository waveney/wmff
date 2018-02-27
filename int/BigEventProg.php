<?php
  include_once("fest.php");
  A_Check('Committee','Dance');
?>

<html>
<head>
<title>WMFF Staff | Big Event Programming</title>
<?php include("minimalheader.php"); ?>
<script src="http://code.jquery.com/jquery-3.1.0.min.js"></script>
<script src="js/tableHeadFixer.js"></script>
<script src="js/BigE.js"></script>
<?php
  include_once("festcon.php");
  echo "</head> <body>\n";

  include_once("DanceLib.php");
  include_once("MusicLib.php");
  include_once("ProgLib.php");
//  include_once("PartLib.php");
  global $YEAR;

  if (isset($_GET{'e'})) { $Eid = $_GET{'e'}; } else { Error_Page('Big Event without Event'); };
  $Event = Get_Event($Eid);
  if (!$Event['BigEvent']) Error_Page('Not A Big Event');
  $DAY = $Event['Day'];
  $Day = $DayList[$DAY];
  $YEAR = $Event['Year'];

  if (!isset($_GET{'EInfo'})) $_GET{'EInfo'} = 0;

  $Sides = Select_Come_Day($Day,' AND y.Procession=1 ');

  $Acts = Select_Act_Come(1);
  $Others = Select_Other_Come(1);

//var_dump($Acts); exit;

// Displays Grid
function Prog_Grid() {
  global $Event,$DAY,$Sides,$Acts,$Others,$Eid,$ActsD,$OthersD;
  $things = Get_Other_Things_For($Eid);

  echo "<div class=BEGridWrapper><div class=BEGridContainer>";
  echo "<table border id=Grid><thead><tr><th>Order<th>What<th>Notes if any";
  echo "</tr></thead><tbody>";

  $CurOrder=1;
  if ($things) {
    foreach ($things as $i=>$t) {
      $id = $t['Identifier'];
      $tt = $t['Type'];
      if ($tt == 'Venue') Continue;
      while ($CurOrder < $t['EventOrder']) {
        echo "<tr><td>$CurOrder<td id=E$CurOrder:: draggable=true class=DPGridDisp ondragstart=drag(event) ";
        echo "ondrop=dropgrid(event) ondragover=allow(event)>";
        echo "<td id=N$CurOrder::><input type=text size=30 id=I$CurOrder:: oninput=newnote(event)>\n";
        $CurOrder++;
      }
      echo "<tr><td>$CurOrder<td id=S$CurOrder:$tt:$id draggable=true class=DPGridDisp ";
      echo "ondragstart=drag(event) ondrop=dropgrid(event) ondragover=allow(event)>";
      switch ($tt) {
        case 'Side':
	  echo SName($Sides[$id]) . " (" . trim($Sides[$id]['Type']) . ")";
	  if (isset($Sides[$id]['EventOrder'])) { $Sides[$id]['EventOrder'] = '!!'; }
	  else $Sides[$id]['EventOrder'] = $CurOrder;
	  break;
        case 'Act':
	  echo $Acts[$id];
//	  if ($Acts[$id]['Type']) echo " (" . trim($Acts[$id]['Type']) . ")";
	  if (isset($ActsD[$id]['EventOrder'])) { $ActsD[$id]['EventOrder'] = '!!'; }
	  else $ActsD[$id]['EventOrder'] = $CurOrder;
	  break;
        case 'Other':
	  echo $Others[$id];
//	  if ($Others[$id]['Type']) echo " (" . trim($Others[$id]['Type']) . ")";
	  if (isset($OthersD[$id]['EventOrder'])) { $OthersD[$id]['EventOrder'] = '!!'; }
	  else $OthersD[$id]['EventOrder'] = $CurOrder;
	  break;
        case 'Note':
	  break;
        default: // inc Venues
	  break;
      }
      echo "<td id=M$CurOrder:$tt:$id ondragover=allow(event)><input type=text size=30 id=J$CurOrder:$tt:$id oninput=newnote(event) value='" . 
        htmlspec($t['Notes']) ."' ondragover=allow(event)>\n";
      $CurOrder++;
    }
  }
  for ($i=0;$i<100;$i++) {
    echo "<tr><td>$CurOrder<td id=E$CurOrder:: draggable=true class=DPGridDisp ondragstart=drag(event) ondrop=dropgrid(event) " .
	"ondragover=allow(event)>";
    echo "<td id=N$CurOrder:: class=BE_Notes ondragover=allow(event)>";
    echo "<input type=text size=30 id=I$CurOrder:: oninput=newnote(event) ondragover=allow(event)>\n";
    $CurOrder++;
  }

  echo "</tbody></table>";
  echo "</div></div>\n";

}

function Side_List() {
  global $Event,$DAY,$Sides,$Acts,$Others,$Thing_Types,$ActsD,$OthersD;
  $Show['ShowThings'] = 'Sides';
  echo "<div class=SideListWrapper>";
  echo fm_radio("Show",$Thing_Types,$Show,'ShowThings','onchange=ShowThing()',0);
  echo "<div class=SideListContainer>";
  echo "<table border id=SideSide>";
  echo "<tr><th>Side<th>i";
  if (!$Event['ExcludeCount']) echo "<th>W<th>H";
  echo "<th>P\n";
  foreach ($Sides as $iid=>$side) {
    $id = $side['SideId'];
    echo "<tr><td draggable=true class='SideName' id=Z0:Side:$id ondragstart=drag(event) ondragover=allow(event) ondrop=drop(event)>";
    echo SName($side) . " (" . trim($side['Type']) . ")";
    echo "<td><img src=/images/icons/information.png onclick=dispinfo('Side',$id)>";
    if (!$Event['ExcludeCount']) {
      echo "<td id=SideW$id align=right>" . $side[$DAY . "Dance"] . "<td id=SideH$id align=right>" . $SideCounts[$id];
    }
    echo "<td id=SideP$id>";
    if (isset($side['EventOrder'])) echo $side['EventOrder'];
    echo "\n";
  }
  echo "</table><br><table border id=ActSide >";
  echo "<tr><th>Act<th>i";
  echo "<th>P\n";
  if ($Acts) foreach ($Acts as $id=>$act) {
    if (!$act) continue;
    echo "<tr><td draggable=true class='SideName' id=Z0:Act:$id ondragstart=drag(event) ondragover=allow(event) ondrop=dropside(event)>";
    echo $act;
//    if ($act['Type']) echo " (" . trim($act['Type']) . ")";
    echo "<td><img src=/images/icons/information.png onclick=dispinfo('Act',$id)>";
    echo "<td id=ActP$id>";
    if (isset($ActsD[$id]['EventOrder'])) echo $ActsD[$id]['EventOrder'];
    echo "\n";
  }
  echo "</table><br><table border id=OtherSide >";
  echo "<tr><th>Other<th>i";
  echo "<th>P\n";
  if ($Others) foreach ($Others as $id=>$Other) {
    if (!$Other) continue;
    echo "<tr><td draggable=true class='SideName' id=Z0:Other:$id ondragstart=drag(event) ondragover=allow(event) ondrop=dropside(event)>";
    echo $Other;
//    if ($Other['Type']) echo " (" . trim($Other['Type']) . ")";
    echo "<td><img src=/images/icons/information.png onclick=dispinfo('Other',$id)>";
    echo "<td id=OtherP$id>";
    if (isset($OthersD[$id]['EventOrder'])) echo $OthersD[$id]['EventOrder'];
    echo "\n";
  }
  echo "</table></div></div>\n";
}

function Controls() {
  global $InfoLevels,$Eid,$Event,$DAY,$DayList;
  echo "<div class=DPControls><center>";
  echo "Big Event Programming Controls<br>";
  echo "For " . $Event['SName'] . " on " . $DayList[$DAY] . "<br>\n"; 
  echo "<div id=EVENT hidden>$Eid</div>";
  echo "<form method=get action=BigEventProg.php>";
  echo fm_hidden('EV',$Eid);
  echo "<table>";
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
  echo "You can leave gaps in the order, to make easier to change later, the gaps will be invisible to the participants.<br>";
  echo "<div>";
}

function InfoPane() {
  echo "<div class=InfoWrapper><div class=InfoContainer id=InfoPane>";
  echo "If you click on a <img src=/images/icons/information.png> icon by a side, information about them will be displayed here";
  echo "</div></div>\n";
}

// MAIN CODE HERE
  Prog_Grid();
  Side_List();
  Controls();
  ErrorPane();
  InfoPane();
  Notes_Pane();

// No standard footer - will use whole screen
?>

  </div>
</div>
</body>
</html>
