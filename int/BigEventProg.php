<?php
  include_once("fest.php");
  A_Check('Committee','Dance');

  dominimalhead("Big Event Programming", "js/tableHeadFixer.js", "js/Participants.js", "js/BigE.js", "files/festconstyle.css" );

  include_once("DanceLib.php");
  include_once("MusicLib.php");
  include_once("ProgLib.php");
//  include_once("PartLib.php");
  global $YEAR,$SelectPerf,$Sides,$Order,$things;

  if (isset($_GET{'e'})) { $Eid = $_GET{'e'}; } else { Error_Page('Big Event without Event'); };
  $Event = Get_Event($Eid);
  if (!$Event['BigEvent']) Error_Page('Not A Big Event');
  $DAY = $Event['Day'];
  $Day = $DayList[$DAY];
  $YEAR = $Event['Year'];

  if (!isset($_GET{'EInfo'})) $_GET{'EInfo'} = 0;
  $extra = $extra2 = '';
  if (preg_match('/Procession/',$Event['SN'])) {
    $extra = " AND y.Procession=1 AND y.$Day=1";
  }

  foreach ($PerfTypes as $p=>$d) $SelectPerf[$p] = ($d[0] == 'IsASide'? Select_Come_All($extra): Select_Perf_Come_All($d[0],$extra2)); 
  
  $Sides = Select_Perf_Full();

  $things = Get_Other_Things_For($Eid);
  foreach ($things as $t) if (preg_match('/Side|Act|Other|Perf/',$t['Type'])) $Order[$t['Identifier']] = $t['EventOrder'];
//  $Acts = Select_Act_Come(1);
//  $Others = Select_Other_Come(1);

//var_dump($Acts); exit;

// Displays Grid
function Prog_Grid() {
  global $Event,$DAY,$Sides,$Acts,$Others,$Eid,$ActsD,$OthersD,$things;


  echo "<div class=BEGridWrapper><div class=BEGridContainer>";
  echo "<table border id=Grid><thead><tr><th>Order<th>What<th>Notes if any";
  echo "</tr></thead><tbody>";

  $CurOrder=1;
  if ($things) {
//  var_dump($things);
    foreach ($things as $i=>$t) {
      $id = $t['Identifier'];
      $tt = $t['Type'];
      if ($tt == 'Venue') continue;
      if (preg_match('/Side|Act|Other/',$tt)) $tt = "Perf";

      while ($CurOrder < $t['EventOrder']) {
        echo "<tr><td>$CurOrder<td id=E$CurOrder:: draggable=true class=DPGridDisp ondragstart=drag(event) ";
        echo "ondrop=dropgrid(event) ondragover=allow(event)>";
        echo "<td id=N$CurOrder::><input type=text size=30 id=I$CurOrder:: oninput=newnote(event)>\n";
        $CurOrder++;
      }
      echo "<tr><td>$CurOrder<td id=S$CurOrder:$tt:$id draggable=true class=DPGridDisp ";
      echo "ondragstart=drag(event) ondrop=dropgrid(event) ondragover=allow(event)>";
      if ($tt=='Perf') {
 //       var_dump($id,$Sides[$id]);
        echo SName($Sides[$id]);
        if ($Sides[$id]['Type']) echo " (" . trim($Sides[$id]['Type']) . ")";
        if (isset($Sides[$id]['EventOrder'])) { $Sides[$id]['EventOrder'] = '!!'; }
        else $Sides[$id]['EventOrder'] = $CurOrder;
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

function Side_List($extra='',$extra2='') {
  global $Event,$DAY,$Sides,$Thing_Types,$ActsD,$OthersD,$PerfTypes,$SelectPerf,$Order;
  $Show['ShowThings'] = 'Sides';
  echo "<div class=SideListWrapper>";
  
  $PTypes = [];
  foreach ($PerfTypes as $p=>$d) $PTypes[] = $p;
  $stuff["PerfType0"] = 0;
  echo fm_radio('',$PTypes,$stuff,"PerfType0","onchange=EventPerfSel(event,###F,###V)",0);

  echo "<div class=SideListContainer>";
  foreach ($PTypes as $pi=>$p) {
    echo "<table border id=Perf$pi" . "_Side0 " . ($pi?"hidden":"") . ">";
    echo "<tr><th>" . $PTypes[$pi] . "<th>i";
//    if (!$Event['ExcludeCount']) echo "<th>W<th>H";
    echo "<th>P\n";
    foreach ($SelectPerf[$p] as $id=>$side) {
//      $id = $side['SideId'];
      if (!$id) continue;
      echo "<tr><td draggable=true class='SideName' id=Z0:Perf:$id ondragstart=drag(event) ondragover=allow(event) ondrop=drop(event)>";
      echo SName($side);
      if (isset($side['Type']) && $side['Type']) echo " (" . trim($side['Type']) . ")";
      echo "<td><img src=/images/icons/information.png onclick=dispinfo('Side',$id)>";
//      if (!$Event['ExcludeCount']) {
//        echo "<td id=SideW$id align=right>" . $side[$DAY . "Dance"] . "<td id=SideH$id align=right>" . $SideCounts[$id];
//      }
      echo "<td id=PerfP$id>";
      if (isset($Order[$id])) echo $Order[$id];
      echo "\n";
    }
    echo "</table>";
  }
  echo "</div></div>\n";
}

function Controls() {
  global $InfoLevels,$Eid,$Event,$DAY,$DayList;
  echo "<div class=DPControls><center>";
  echo "Big Event Programming Controls<br>";
  echo "For " . $Event['SN'] . " on " . $DayList[$DAY] . "<br>\n"; 
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
