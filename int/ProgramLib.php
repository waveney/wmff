<?php

function Prog_Headers($Public='',$headers =1,$What='Dance') {
  echo "<html><head>";
  if ($Public && $headers) { 
    echo "<title>Wimborne Minster Folk Festival | $What Programme</title>\n"; 
    include("files/header.php"); 
  } else { 
    echo "<title>WMFF Staff | $What Programme</title>\n"; 
    include("minimalheader.php"); 
  }
  echo '<script src="/js/jquery-3.2.1.min.js"></script>
<script src="js/tableHeadFixer.js"></script>
<script src="js/DanceProg.js"></script>';

  include_once("festcon.php");
  echo "</head><body>\n";

  include_once("DanceLib.php");
  include_once("ProgLib.php");
  if ($Public && $headers) include_once("files/navigation.php");
}

function Grab_Data($day='') {
  global $DAY,$Times,$lineLimit,$Sides,$SideCounts,$EV,$VenueUse,$evs,$Sand;

  $Times = array();
  $lineLimit = array();
  $SideCounts = array();
  $EV = array();
  $VenueUse = array();
  $evs = array();
  $Sand = 0;
  if (isset($_GET{'SAND'})) $Sand = 1;
  if (isset($_POST{'SAND'})) $Sand = 1;

  if ($day) { $DAY=$day;
  } else if (isset($_GET{'d'})) { $DAY = $_GET{'d'}; } else { $DAY='Sat'; }

  if (!isset($_GET{'EInfo'})) $_GET{'EInfo'} = 0;
  for ($t=10;$t<19;$t++) {
    $Times[] = $t*100;
    $Times[] = $t*100+30;
  }

  $Sides = Select_Come_Day($DAY);
  foreach ($Sides as $i=>$s) { $SideCounts[$i]=0; }
  foreach ($Times as $t) $lineLimit[$t]=2;

  $evs = Get_Events_For('Dance',$DAY);
//var_dump($evs);
  foreach ($evs as $ei=>$ev) {
    $eid = $ev['EventId'];
    if (!$ev['BigEvent']) {
      $v = $ev['Venue'];
      $VenueUse[$v] = 1;
      $t = timeround($ev['Start'],30);
      if ($ev['SubEvent'] < 0) { $et = $ev['SlotEnd']; } else { $et = $ev['End']; };
      $duration = timeround(timeadd2($ev['Start'],-$st),30);
      
      $EV[$v][$t]['e'] = $ei;
      $EV[$v][$t]['d'] = $duration;

      $ll = 0;
      if ($ev['Name'] && $ev['Name'] != 'Dancing') {
        $EV[$v][$t]['n'] = $ev['Name'];
	$ll = 1;
      }
      if ($ev["Side1"]) { $EV[$v][$t]['S1'] = $ev["Side1"]; } 
      if ($ev["Side2"]) { $lineLimit[$t] = max($lineLimit[$t],2+$ll); $EV[$v][$t]['S2'] = $ev["Side2"]; }
      if ($ev["Side3"]) { $lineLimit[$t] = max($lineLimit[$t],3+$ll); $EV[$v][$t]['S3'] = $ev["Side3"]; }
      if ($ev["Side4"]) { $lineLimit[$t] = max($lineLimit[$t],4+$ll); $EV[$v][$t]['S4'] = $ev["Side4"]; }
    } else if (!$ev['ExcludeCount']) {
      $Other = Get_Other_Things_For($eid);
      foreach($Other as $i=>$o) if ($o['Type'] == 'Side') $SideCounts[$o['Identifier']]++;
    }
  }
}

/*
  1) Events more than half an hour - afects #2 - done for scan
  2) Number of Other locations needed - done
  3) Events with titles
*/

function Scan_Data($condense=0) {
  global $DAY,$Times,$lineLimit,$EV,$Sides,$SideCounts,$VenueUse,$evs,$MaxOther,$VenueInfo,$Venues,$VenueNames,$OtherLocs;
  
  $Venues = Get_Venues_For('Dance');
  $VenueNames = Get_Real_Venues(0);
  $VenueInfo = Get_Real_Venues(1);
  $OtherLocs = array();

  foreach ($Venues as $v) if (isset($VenueUse[$v]) && $condense && $VenueInfo[$v]["Minor$DAY"]) $OtherLocs[] = $v;

  $MaxOther = 0; 

  if ($condense) {
    foreach ($Times as $time) {
      $ThisO = 0;
      for ($i = 0; $i <10; $i++) if (isset($OtherLocUse[$i]['t']) && $OtherLocUse[$i]['t']>0) {
//	$ThisO++;  // Does not work yet
	$OtherLocUse[$i]['t']--;
      }
      foreach($OtherLocs as $v) {
        if (isset($EV[$v][$time]['e'])) {
          $inuse = 0;
          for ($i=1;$i<5;$i++) if (isset($EV[$v][$time]["S$i"]) && $EV[$v][$time]["S$i"] ) $inuse = 1;
	  if ($inuse) {
	    $ThisO++;
	    if ($EV[$v][$time]['d'] != 30) {
	      $slots = ceil(timereal($EV[$v][$time]['d'])/30);
	      $i=0; 
	      while(isset($OtherLocUse[$i]['t']) && $OtherLocUse[$i]['t']>0) $i++;
	      $OtherLocUse[$i]['t'] = $slots -1;
	      $OtherLocUse[$i]['v'] = $v;
	    }
	  }
        }
      }
      $MaxOther= max($MaxOther, $ThisO);
    }
  }
}

// Displays Grid
function Prog_Grid($drag=1,$types=1,$condense=0,$format='') {
  global $DAY,$Times,$lineLimit,$EV,$Sides,$SideCounts,$VenueUse,$evs,$MaxOther,$VenueInfo,$Venues,$VenueNames,$OtherLocs,$Sand;

  echo "<div class=GridWrapper$format><div class=GridContainer$format>";
  echo "<table border id=Grid><thead><tr><th id=DayId>$DAY";
  foreach ($Venues as $v) if (isset($VenueUse[$v])) {
    if ($condense && $VenueInfo[$v]["Minor$DAY"]) {
//      $OtherLocs[] = $v;
    } else { 
      echo "<th class=DPGridTH id=Ven$v>" . $VenueNames[$v];
    }
  }
  if ($condense) {
    for($i=1; $i<=$MaxOther; $i++) {
      echo "<th class=DPGridTH id=OLoc$i>Other Location<th class=DPGridTH id=OWhat$i>What";
    }
  }
  echo "</tr></thead><tbody>";

  foreach ($Times as $time) {
    $NoShare = array();
    $EventNames = '';
    $EventNamesUsed = 0;

/*
    foreach ($Venues as $v) {
      if (!isset($VenueUse[$v])) continue;
      if ($condense && $VenueInfo[$v]["Minor$DAY"]) continue; // do at end

      if (isset($EV[$v][$time]['e']) && isset($EV[$v][$time]['n'])) {
        $EventNames .= "<td class=DPNamed>" . $EV[$v][$time]['n']; 
	$EventNamesUsed = 1;
      } else {
        $EventNames .= "<td class=DPNotNamed>";
      }
    }
    if ($condense) foreach($OtherLocs as $v) {
      if (isset($EV[$v][$time]['e']) && isset($EV[$v][$time]['n'])) {
        $EventNames .= "<td class=DPNotNamed><td class=DPNamed>";
	$EventNames .= $EV[$v][$time]['n']; 
	$EventNamesUsed = 1;
      } else {
        $EventNames .= "<td class=DPNotNamed><td class=DPNotNamed>";
      }
    }
*/

    echo "<tr><th rowspan=4>$time";
//    if ($drag && $lineLimit[$time]<4) {
//      echo "<div class=botrightwrap><div class=botrightcont><button class=botx onclick=UnhideARow($time) id=AddRow$time>+</button></div></div>";
//    }
    for ($line=0; $line < 4; $line++) {
      $sl = "S" .($line+1);
      if ($line) echo "<tr>";
      if ($line >= $lineLimit[$time]) continue;
      foreach ($Venues as $v) {
	if (!isset($VenueUse[$v])) continue;
	if ($condense && $VenueInfo[$v]["Minor$DAY"]) continue; // do at end
        if (isset($EV[$v][$time]['e'])) {
          $eid = $EV[$v][$time]['e'];
	  $ee = $evs[$eid]['EventId'];

	  $sll = $sl;
	  $lin = $line;
	  if (isset($EV[$v][$time]['n'])) {
	    if ($line == 0) {
	      echo "<td id=Z$ee:$v:$time:0:0 class=DPNamed>" .$EV[$v][$time]['n'];
	      continue;
	    } else {
	      $sll = "S$line";
	      $lin = $line-1;
	    }
	  }
          if (isset($EV[$v][$time][$sll])) { $s = $EV[$v][$time][$sll]; } else { $s = 0; }
	  $row = '';
	  if ($s && $Sides[$s]['Share'] == 2) { $row=' rowspan=' . $lineLimit[$time]; $NoShare[$v] = 1; }
	  else if ($NoShare[$v]) $row=' hidden';
          echo "<td id=G$ee:$v:$time:$lin:$s class='DPGridDisp Side$s'";
	  if ($drag) echo "draggable=true ondragstart=drag(event) ondrop=drop(event,$Sand) ondragover=allow(event)";
	  echo "$row>";
          if ($s && ($drag || $evs[$eid]['InvisiblePart'] == 0)) {
            if (isset($Sides[$s])) {
	      if ($condense && !$types) echo "<a href=/int/ShowDance.php?sidenum=$s>";
              echo SName($Sides[$s]);
	      if ($types) echo " (" . trim($Sides[$s]['Type']) . ")";;
	      if ($condense && !$types) echo "</a>";
              if (!$evs[$eid]['ExcludeCount']) $SideCounts[$s]++;
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
      if ($condense) {
 	foreach($OtherLocs as $v) {
          if (isset($EV[$v][$time]['e'])) {
            $eid = $EV[$v][$time]['e'];
	    $ee = $evs[$eid]['EventId'];
	    $inuse = 0;
	    for ($i=1;$i<5;$i++) if (isset($EV[$v][$time]["S$i"]) && $EV[$v][$time]["S$i"] ) $inuse = 1;
	    if ($inuse) {
	      if ($line == 0) echo "<td class=DPGridDisp rowspan=" . $lineLimit[$time] . ">" . $VenueNames[$v];
	      $sll = $sl;
	      if (isset($EV[$v][$time]['n'])) {
	        if ($line == 0) {
	          echo "<td id=Z$ee:$v:$time:0:0 class=DPNamed>" .$EV[$v][$time]['n'];
	          continue;
	        } else {
	          $sll = "S$line";
	        }
	      }
              if (isset($EV[$v][$time][$sll])) { $s = $EV[$v][$time][$sll]; } else { $s = 0; }
	      $row = '';
	      if ($s && $Sides[$s]['Share'] == 2) { $row=' rowspan=' . $lineLimit[$time]; $NoShare[$v] = 1; }
	      else if ($NoShare[$v]) $row=' hidden';
              echo "<td id=G$ee:$v:$time:$line:$s class='DPGridDisp Side$s'";
	      if ($drag) echo "draggable=true ondragstart=drag(event) ondrop=drop(event,$Sand) ondragover=allow(event)";
	      echo "$row>";
              if ($s && ($drag || $evs[$eid]['InvisiblePart'] == 0)) {
                if (isset($Sides[$s])) {
	          if ($condense && !$types) echo "<a href=/int/ShowDance.php?sidenum=$s>";
                  echo SName($Sides[$s]);
	          if ($types) echo " (" . trim($Sides[$s]['Type']) .")";;
	          if ($condense && !$types) echo "</a>";
                  if (!$evs[$eid]['ExcludeCount']) $SideCounts[$s]++;
                } else {
                  echo "ERROR...";
                }
              } else {
                echo "&nbsp;";
	      }
            }
          }
        }
      }
    }
  }
  echo "</tbody></table>";
  echo "</div></div>\n";
}

function Side_List() {
  global $DAY,$Sides,$SideCounts,$Sand;
  echo "<div class=SideListWrapper><div class=SideListContainer>";
  echo "<table border id=SideList>";
  echo "<thead><tr><th>Side<th>i<th>W<th>H</thead><tbody>\n";
//  echo "<thead><tr><th>Side<th>H<th>i<th>W<th>H</thead><tbody>\n";
  foreach ($Sides as $id=>$side) {
    echo "<tr><td draggable=true class='SideName Side$id' id=SideN$id ondragstart=drag(event) ondragover=allow(event) ondrop=drop(event,$Sand)>";
    echo SName($side) . " (" . trim($side['Type']) . ")<td>";
//    echo "<input type=checkbox id=SideH$id onchange=highlight($id)><td>";
    if (Has_Info($side)) echo "<img src=/images/icons/information.png onclick=dispinfo('Side',$id)>";
    echo "<td id=SideW$id align=right>" . $side[$DAY . "Dance"];
    echo "<td id=SideH$id align=right>";
    echo $SideCounts[$id] . "\n";
  }
  echo "</table></div></div>\n";
}

function Controls($level=0,$condense=0) {
  global $InfoLevels,$DAY,$Sand,$YEAR;
  if (!isset($_GET['EInfo'])) $_GET['EInfo'] = $level;
  echo "<div class=DPControls><center>";
  echo "Programming Controls";
  echo "<form method=get action=''>";
  echo fm_hidden('Cond',$condense);
  echo "<table><tr><td>";
  echo "<td>";
  $classFri = $classSat = $classSun = '';
  $n = "class$DAY";
  $$n = "id=ProgDayHL";
  if ($Sand) echo fm_hidden('SAND',$Sand);
  echo fm_hidden('Y',$YEAR);
  echo "<input type=submit name=d value=Fri $classFri> ";
  echo "<input type=submit name=d value=Sat $classSat> ";
  echo "<input type=submit name=d value=Sun $classSun>\n";
  echo "<tr>" . fm_radio("Info",$InfoLevels,$_GET,'EInfo',"onchange=UpdateInfo()");
  echo "</table></form>\n";
  echo "<h2><a href=Staff.php>Staff Tools</a></h2></center>";
  echo "</div>\n";
}

function ErrorPane($level=0) {
  include ("CheckDance.php");
  global $Sand;
  echo "<div class=ErrorWrapper><div class=ErrorContainer id=InformationPane>";
  if ($Sand) echo "In Sandbox Mode Error Checking is not meaningful.";
  CheckDance($level);
  echo "</div></div>\n";
}

function MusicErrorPane($level=0) {
//  include ("CheckMusic.php");
  global $Sand;
  echo "<div class=ErrorWrapper><div class=ErrorContainer id=InformationPane>";
  if ($Sand) echo "In Sandbox Mode Error Checking is not meaningful.";
//  CheckMusic($level);
  echo "</div></div>\n";
}

function Notes_Pane() {
  echo "<div id=Notes_Pane>";
  echo "To add a 3rd or 4th side to a time edit the event, for more than 4 use a Big Event.<br>";
  echo "To remove a side drag back to the side list.<br>";
//  echo "Adding small notes to the programme will be possible soon.  ";
  echo "<div>";
}

function InfoPane() {
  echo "<div class=InfoWrapper><div class=InfoContainer id=InfoPane>";
  echo "If you click on a <img src=/images/icons/information.png> icon by a side, information about them will be displayed here";
  echo "</div></div>\n";
}

// MUSIC MUSIC MUSIC MUSIC

function Grab_Music_Data($day='') {
  global $DAY,$Times,$lineLimit,$Sides,$EV,$VenueUse,$evs,$Sand;

  $Times = array();
  $lineLimit = array();
  $SideCounts = array();
  $EV = array();
  $VenueUse = array();
  $evs = array();
  $Sand = 0;
  if (isset($_GET{'SAND'})) $Sand = 1;
  if (isset($_POST{'SAND'})) $Sand = 1;

  if ($day) { $DAY=$day;
  } else if (isset($_GET{'d'})) { $DAY = $_GET{'d'}; } else { $DAY='Sat'; }

  if (!isset($_GET{'EInfo'})) $_GET{'EInfo'} = 0;
  for ($t=10;$t<24;$t++) {
    $Times[] = $t*100;
    $Times[] = $t*100+15;
    $Times[] = $t*100+30;
    $Times[] = $t*100+45;
  }

  $Sides = Select_Act_Come($DAY);
  foreach ($Times as $t) $lineLimit[$t]=1;

  $evs = Get_Events_For('Music',$DAY);
//var_dump($evs);
  foreach ($evs as $ei=>$ev) {
    $eid = $ev['EventId'];
    if (!$ev['BigEvent']) {
      $v = $ev['Venue'];
      $VenueUse[$v] = 1;
      $t = timeround($ev['Start'],15);
      if ($ev['SubEvent'] < 0) { $et = $ev['SlotEnd']; } else { $et = $ev['End']; };
      $duration = timeround(timeadd2($ev['Start'],-$st),15);
      
      $EV[$v][$t]['e'] = $ei;
      $EV[$v][$t]['d'] = $duration;

      $ll = 0;
      if ($ev['Name'] && $ev['Name'] != 'Music') {
        $EV[$v][$t]['n'] = $ev['Name'];
	$ll = 1;
      }
      if ($ev["Act1"]) { $EV[$v][$t]['S1'] = $ev["Act1"]; } 
      if ($ev["Act2"]) { $lineLimit[$t] = max($lineLimit[$t],2+$ll); $EV[$v][$t]['S2'] = $ev["Act2"]; }
      if ($ev["Act3"]) { $lineLimit[$t] = max($lineLimit[$t],3+$ll); $EV[$v][$t]['S3'] = $ev["Act3"]; }
      if ($ev["Act4"]) { $lineLimit[$t] = max($lineLimit[$t],4+$ll); $EV[$v][$t]['S4'] = $ev["Act4"]; }
    } else { // No Handling of BEs yet
    }
  }
}

/* Go through a big grid writting every thing in, then scan for compression, then print out - Should do this for dance as well - The cell actionw will be different
   Slots can be moved and stretched, and allow for sound check before

   $grid[v][t][i0-4][d] i0 for label, i1-4 for parts 1-4, d= data per cell i = id, l=len (mins), s=sound check before(mins)
   $gridv[v] use count - venue event count
   $gridt[t] use count - time use count (any number of acts) 
   $gridn[t] Name use count - time use count (any number of acts) 
*/

/*

function Prog_Music_Grid($drag=1,$types=1,$condense=0,$format='') {
  global $DAY,$Times,$grid,$gridv,$gridt,$gridti;

  Prog_MG_Everything();
  Prog_MG_Compress($condense);
  Prog_MG_Print($drag,$types,$format);
}

function Prog_MG_Everything() {
  global $DAY,$Times,$grid,$gridv,$gridt,$gridti;

  foreach ($evs as $ei=>$ev) {
    $eid = $ev['EventId'];
    if (!$ev['BigEvent']) {
      $v = $ev['Venue'];
      $gridv[$v]++;
      $t = timeround($ev['Start'],15);
      if ($ev['SubEvent'] < 0) { $et = $ev['SlotEnd']; } else { $et = $ev['End']; };
      $duration = timeround(timeadd2real($ev['Start'],-$st),15);
      $gridt[$t]++;
      $Name = '';
      if ($ev['Name'] && $ev['Name'] != 'Music') {
	$Name = $ev['Name'];
        $grid[$v][$t]['n'] = $Name;
	$gridn[$t]++;
      }
      $grid[$v][$t]['d'] = $duration;
      for ($i=1;$i<5;$i++) {
	if ($ev["Act$i"]) $grid[$v][$t][$i]=$ev["Act$i"]; 
      }
    } else { // BIG EVENT not yet
    }
  } 
}

function Prog_MG_Compress($Cond=0) {

}

function Prog_MG_Print($drag,$types,$format) {
  global $Sides,$VenueInfo,$Venues,$VenueNames,$OtherLocs,$Sand;
  global $DAY,$Times,$grid,$gridv,$gridt,$gridti;

  echo "<div class=GridWrapper$format><div class=GridContainer$format>";
  echo "<table border id=Grid><thead><tr><th id=DayId>$DAY";

  foreach ($Venues as $v) if (isset($VenueUse[$v])) {
    if ($condense && $VenueInfo[$v]["Minor$DAY"]) {
      $OtherLocs[] = $v;
    } else { 
      echo "<th class=DPGridTH id=Ven$v>" . $VenueNames[$v];
    }
  }
  if ($condense) {
    for($i=1; $i<=$MaxOther; $i++) {
      echo "<th class=DPGridTH id=OLoc$i>Other Location<th class=DPGridTH id=OWhat$i>What";
    }
  }
  echo "</tr></thead><tbody>";

  foreach ($Times as $time) {
    echo "<tr><td>$time";

    foreach ($Venues as $v) if (isset($VenueUse[$v])) {
      if (isset($grid[$v][$time])) {
	$ht = $grid[$v][$time]['d'];
	echo "<td rowspan=$ht>";
	if (isset($grid[$v][$time]['n'])) echo "<span class=GridEventName>" . $grid[$v][$time]['n'] ."</span><br>";
	echo "<span class=GridActName>";
	for ($i=1;$i<5;$i++) {
	  $sid = $grid[$v][$time][$i];
	  if ($sid) {
	    $side = $Sides[$sid];
	    echo SName($side);
	    if ($types && $side['Type']) echo " (" . $side['Type'] . ")";
	    echo "<br>";
	  }
	}
        echo "</span>";
      }
    }
    
}

    $EventNames = '';
    $EventNamesUsed = 0;

    foreach ($Venues as $v) {
      if (!isset($VenueUse[$v])) continue;
      if ($condense && $VenueInfo[$v]["Minor$DAY"]) continue; // do at end

      if (isset($EV[$v][$time]['e']) && isset($EV[$v][$time]['n'])) {
        $EventNames .= "<td class=DPNamed>" . $EV[$v][$time]['n']; 
	$EventNamesUsed = 1;
      } else {
        $EventNames .= "<td class=DPNotNamed>";
      }
    }
    if ($condense) foreach($OtherLocs as $v) {
      if (isset($EV[$v][$time]['e']) && isset($EV[$v][$time]['n'])) {
        $EventNames .= "<td class=DPNotNamed><td class=DPNamed>";
	$EventNames .= $EV[$v][$time]['n']; 
	$EventNamesUsed = 1;
      } else {
        $EventNames .= "<td class=DPNotNamed><td class=DPNotNamed>";
      }
    }

    echo "<tr><th rowspan=4>$time";
//    if ($drag && $lineLimit[$time]<4) {
//      echo "<div class=botrightwrap><div class=botrightcont><button class=botx onclick=UnhideARow($time) id=AddRow$time>+</button></div></div>";
//    }
    for ($line=0; $line < 4; $line++) {
      $sl = "S" .($line+1);
      if ($line) echo "<tr>";
      if ($line >= $lineLimit[$time]) continue;
      foreach ($Venues as $v) {
	if (!isset($VenueUse[$v])) continue;
	if ($condense && $VenueInfo[$v]["Minor$DAY"]) continue; // do at end
        if (isset($EV[$v][$time]['e'])) {
          $eid = $EV[$v][$time]['e'];
	  $ee = $evs[$eid]['EventId'];

	  $sll = $sl;
	  $lin = $line;
	  if (isset($EV[$v][$time]['n'])) {
	    if ($line == 0) {
	      echo "<td id=Z$ee:$v:$time:0:0 class=DPNamed>" .$EV[$v][$time]['n'];
	      continue;
	    } else {
	      $sll = "S$line";
	      $lin = $line-1;
	    }
	  }
          if (isset($EV[$v][$time][$sll])) { $s = $EV[$v][$time][$sll]; } else { $s = 0; }
	  $row = '';
	  if ($s && $Sides[$s]['Share'] == 2) { $row=' rowspan=' . $lineLimit[$time]; $NoShare[$v] = 1; }
	  else if ($NoShare[$v]) $row=' hidden';
          echo "<td id=G$ee:$v:$time:$lin:$s class='DPGridDisp Side$s'";
	  if ($drag) echo "draggable=true ondragstart=drag(event) ondrop=drop(event,$Sand) ondragover=allow(event)";
	  echo "$row>";
          if ($s && ($drag || $evs[$eid]['InvisiblePart'] == 0)) {
            if (isset($Sides[$s])) {
	      if ($condense && !$types) echo "<a href=/int/ShowDance.php?sidenum=$s>";
              echo SName($Sides[$s]);
	      if ($types) echo " (" . trim($Sides[$s]['Type']) . ")";;
	      if ($condense && !$types) echo "</a>";
              if (!$evs[$eid]['ExcludeCount']) $SideCounts[$s]++;
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
      if ($condense) {
 	foreach($OtherLocs as $v) {
          if (isset($EV[$v][$time]['e'])) {
            $eid = $EV[$v][$time]['e'];
	    $ee = $evs[$eid]['EventId'];
	    $inuse = 0;
	    for ($i=1;$i<5;$i++) if (isset($EV[$v][$time]["S$i"]) && $EV[$v][$time]["S$i"] ) $inuse = 1;
	    if ($inuse) {
	      if ($line == 0) echo "<td class=DPGridDisp rowspan=" . $lineLimit[$time] . ">" . $VenueNames[$v];
	      $sll = $sl;
	      if (isset($EV[$v][$time]['n'])) {
	        if ($line == 0) {
	          echo "<td id=Z$ee:$v:$time:0:0 class=DPNamed>" .$EV[$v][$time]['n'];
	          continue;
	        } else {
	          $sll = "S$line";
	        }
	      }
              if (isset($EV[$v][$time][$sll])) { $s = $EV[$v][$time][$sll]; } else { $s = 0; }
	      $row = '';
	      if ($s && $Sides[$s]['Share'] == 2) { $row=' rowspan=' . $lineLimit[$time]; $NoShare[$v] = 1; }
	      else if ($NoShare[$v]) $row=' hidden';
              echo "<td id=G$ee:$v:$time:$line:$s class='DPGridDisp Side$s'";
	      if ($drag) echo "draggable=true ondragstart=drag(event) ondrop=drop(event,$Sand) ondragover=allow(event)";
	      echo "$row>";
              if ($s && ($drag || $evs[$eid]['InvisiblePart'] == 0)) {
                if (isset($Sides[$s])) {
	          if ($condense && !$types) echo "<a href=/int/ShowDance.php?sidenum=$s>";
                  echo SName($Sides[$s]);
	          if ($types) echo " (" . trim($Sides[$s]['Type']) .")";;
	          if ($condense && !$types) echo "</a>";
                  if (!$evs[$eid]['ExcludeCount']) $SideCounts[$s]++;
                } else {
                  echo "ERROR...";
                }
              } else {
                echo "&nbsp;";
	      }
            }
          }
        }
      }
    }
  }
  echo "</tbody></table>";
  echo "</div></div>\n";
}

function Notes_Music_Pane() {
  echo "<div id=Notes_Pane>";
  echo "This is just a visual display of the Music programme.  No editing can be done currently.<br>";
  echo "<div>";
}

*/
?>
