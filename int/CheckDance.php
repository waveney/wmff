<?php
  include_once("DanceLib.php");
  include_once("ProgLib.php");

/*  Check Dance 
  Go through all events for this year with dance /Anything
  Find all sides and where they are dancing and who with
  For each side get list of venues, times count number -> Hold
  For each side check that at least 1 slot between spots -> list all errors
  For each side with overlaps check overlap not at same time - same = error, +/- 1 Note, but not error
  Check not on days not there, before arrive, after leave etc.  
  Sides with no dance/events = error, not same as spots = note
  Update report window is open, otherwise display all  
  Check Surfaces - if the side has none shown all are permitted otherwise check
  Check Sharing States (not for big events)

  Think about likes/dislikes

*/

include_once("DanceLib.php");

function CheckDance($level) { // 0 = None, 1 =Major, 2= All
  global $db,$YEAR, $DayList, $Surfaces, $Share_Type,$Procession;

// GRAB LOTS OF DATA
  echo "<div id=ChechedDance>";
  $Procession = 0;
  if ($level == 0) {
    echo "Errors not being checked for</div>";
    return;
  }
  $Venues = Get_Real_Venues(1);
  $Sides = &Part_Come_All();
  $sidenames = Sides_Name_List();
  $sideercount = 0;

  $res = $db->query("SELECT e.* FROM Events e WHERE Year=$YEAR ORDER BY Day, Start" );
  if ($res) {
    while ($e = $res->fetch_assoc()) {
      $eid = $e['EventId'];
      $Events[$eid]=$e;
      for($i=1;$i<5;$i++) {
	if ($s = $e["Side$i"]) {
	  $dancing[$s][] = $eid;
	  if (($Sides[$s][$DayList[$e['Day']]])) {
		// No Action
	  } else { 
      	    echo "<a href=AddDance.php?sidenum=$s>" . $sidenames[$s] . "</a>: ";
      	    echo "<span class=red>Is listed doing an <a href=EventAdd.php?e=" . $e['EventId'] . ">event</a> at " . $e['Start'] . " in " . SName($Venues[$e['Venue']]) .
		 " on " . $DayList[$e['Day']] . ", but is <b>NOT</b> there that day</span><br>\n";
      	    $sideercount++;
	  }
	}
	if ($s = $e["Act$i"]) {
	  if (isset($Sides[$s])) {
	    $dancing[$s][] = $eid;
	  } else { // Should never get here, wont work anyway
      	    echo "<a href=AddDance.php?sidenum=$s>" . $sidenames[$s] . "</a>: ";
      	    echo "<span class=red>Is listed doing an event at " . $e['Start'] . " in " . SName($Venues[$e['Venue']]) .
		 " on " . $DayList[$e['Day']] . ", but is <b>NOT</b> there that day</span><br>\n";
      	    $sideercount++;
	  }
	}
	if ($s = $e["Other$i"]) {
	  if (isset($Sides[$s])) {
	    $dancing[$s][] = $eid;
	  } else {
      	    echo "<a href=AddDance.php?sidenum=$s>" . $sidenames[$s] . "</a>: ";
      	    echo "<span class=red>Is listed doing an event at " . $e['Start'] . " in " . SName($Venues[$e['Venue']]) .
		 " on " . $DayList[$e['Day']] . ", but is <b>NOT</b> there that day</span><br>\n";
      	    $sideercount++;
	  }
	}
      }
      if ($e['BigEvent']) {
	if ($e['SName'] == 'Procession') $Procession = $eid;
        $Other = Get_Other_Things_For($eid);
	$sidcount = 1;
	$Events[$eid]['Other'] = $Other;
	foreach ($Other as $i=>$ot) {
	  if ($ot['Type'] == 'Side') {
	    $s = $ot['Identifier'];
	    $Events[$eid]['OtherPos'][$s] = $sidcount++;
	    if (isset($Sides[$s])) {
	      $dancing[$s][] = $eid;
	    } else {
      	      echo "<a href=AddDance.php?sidenum=$s>" . $sidenames[$s] . "</a>: ";
      	      echo "<span class=red>Is listed doing an event at " . $e['Start'] . " in " . SName($Venues[$e['Venue']]) .
		   " on " . $DayList[$e['Day']] . ", but is <b>NOT</b> there that day</span>";
	    }
	  }
	}
	$Events[$eid]['OtherCount'] = $sidcount;
      }
    }
  } else {
    $sideercount = 1;
    echo "<h2 class=Err>No Events Found</h2>";
  }
  if ($Procession == 0) {
    $sideercount = 1;
    echo "<span class=Err>No Procession Yet</span>";
  }

//var_dump($dancing);
// Go through each side checking for lots

  foreach ($Sides as $si=>$side) {
    $Err = '';
    $Merr = '';
    $LastDay = -99;
    $LastT = 0;
    $FirstTime = $LastTime = $DayCounts = array(-3=>0,-2=>0,-1=>0,0=>0,1=>0,2=>0,3=>0);
    $VenuesUsed = $Complained = array();
    $surfs = 0;
    $last_e = 0;
    $minorspots = 0;
    $side['Olaps'] = $Olaps = Get_Overlaps_For($side['SideId'],1);
    $badvens = array();
    foreach ($Surfaces as $ss=>$s) if ($ss < 5 && $s && $side["Surface_$s"]) $surfs++;
    $lastVen = -1;
    $InProcession = 0;
    if (isset($dancing[$si])) {
      foreach ($dancing[$si] as $dd=>$e) { // Checking for ~30 minute gaps
	$Ven = $Events[$e]['Venue'];
	$daynum = $Events[$e]['Day']; 
	$daynam = $DayList[$daynum];
	$start = $Events[$e]['Start'];
	if ($Events[$e]['EventId'] == $last_e) {
	  $Err .= "Doing the same event on $daynam at $start in " . SName($Venues[$Ven]) . ", ";
	}
	$last_e = $Events[$e]['EventId'];
	if ($last_e == $Procession) $InProcession = 1;
	if ($Events[$e]['SubEvent'] < 0) { $End = $Events[$e]['SlotEnd']; } else { $End = $Events[$e]['End']; };
	if ($Events[$e]['BigEvent'] && ($Events[$e]['OtherPos'][$si] <= $Events[$e]['OtherCount']/2)) $End = timeadd($End, -30);
//if ($si == 301) echo "$start - " . $LastTime[$daynum]. " $daynum - $LastDay<p>";
	if ($side['IsASide']) {
//if ($si == 301) echo "Here 1<br>";
	  if (!isset($side[$daynam])) { 
//if ($si == 301) echo "Here 2<br>";
	    $Err .= "Event Issue: Dances not allowed for on $daynam (yet), ";
	  } elseif (!$side[$daynam]) { 
//if ($si == 301) echo "Here 3<br>";
	    $Err .= "Not at Festival on $daynam, ";
	  } elseif ($daynum != $LastDay) {
//if ($si == 301) echo "Here 4<br>";
	    $VenuesUsed = array();
	    $LastDay = $daynum;
	    $LastTime[$daynum] = $End;
	    $minorspots = 0;
	  } elseif (timereal($start) - timereal($LastTime[$daynum]) < 20) { // Min 20 mins to allow for odd timing of some events
//if ($si == 301) echo "Here 5<br>";
	    if (!$Events[$e]['IgnoreClash']) $Err .= "Too close on $daynam $start to " . $LastTime[$daynum] . " at " . SName($Venues[$lastVen]) . ", ";
	  } else {
//if ($si == 301) echo "Here 6<br>";
	    $LastTime[$daynum] = $End;
	  }
	}
	if (isset($VenuesUsed[$Ven])) {
	  if ($side['IsASide'] && !$Venues[$Ven]['AllowMult'] && !isset($Complained[$Ven)) {
	    $Merr .= "Performing multiple times at " . SName($Venues[$Ven]) . " on $daynam, ";
	  }
	  $Complained[$Ven]=1;
	} else {
	  $VenuesUsed[$Ven] = 1;
	}
	if (isset($Venues[$Ven]["Minor$daynam"]) && ($Venues[$Ven]["Minor$daynam"])) {
	  if ($minorspots++ && $side['IsASide']) $Merr .= "Performing $minorspots times at minor spots on $daynam, ";
	}
	if ($side['IsASide'] && $surfs) {
//if (!$Surfaces[$Venues[$Ven]['SurfaceType1']]) { echo "Surface - $Ven ..."; }
	  if (($side["Surface_" . $Surfaces[$Venues[$Ven]['SurfaceType1']]]) || 
	      ($side["Surface_" . $Surfaces[$Venues[$Ven]['SurfaceType2']]])) { // Good
	  } else {
	    if(!isset($badvens[$Ven])) {
              $Err .= "Do not like dancing on the surfaces at " . SName($Venues[$Ven]) . ", ";
	    }
	   $badvens[$Ven]=1;
	  }
        }

	if ($side['IsASide'] && !$Events[$e]['BigEvent']) { // Sharing Checks
	  $ns = 0;
	  for ($j=1; $j<5; $j++) if ($Events[$e]["Side$j"]>0) $ns++;
	  if ($ns == 1) {
	    if ($side['Share'] == $Share_Type['Always']) $Err .= "Do not like being alone ( $daynam " . $Events[$e]['Start'] . 
				" at " . SName($Venues[$Events[$e]['Venue']]) . ", ";
	  } else if ($side['Share'] == $Share_Type['Never']) $Err .= "Do not like sharing ( $daynam " . $Events[$e]['Start'] . 
				" at " . SName($Venues[$Events[$e]['Venue']]) . ", ";
	}

	if (!$Events[$e]['ExcludeCount']) $DayCounts[$daynum]++;

	foreach ($Olaps as $Rule) {
	  if ($Rule['Type'] == 0) { // Dancer Olap
	    $Other = ($Rule['Sid1'] == $side['SideId'])?'Sid2':'Sid1';
  	    $o = $Rule[$Other];
//if ($side['SideId']==290) { var_dump($Rule); echo "Rule: $Other $o<p>\n"; var_dump($e); };
//if ($side['SideId']==356) { var_dump($Rule); echo "Rule: $Other $o<p>\n"; var_dump($e); };
	    if (isset($dancing[$o])) {

//if ($side['SideId']==290) echo "X1 " . $e['EventId'] . " - ";
//if ($side['SideId']==356) echo "X1 " . $e['EventId'] . " - ";
	      $oside = $Sides[$o];
	      $oname = $oside['SName'];
	      $starttime = timereal($start = $Events[$e]['Start']);
	      $endtime = timereal($Events[$e]['SubEvent'] < 0 ? $Events[$e]['SlotEnd']: $Events[$e]['End']); 
	      foreach ($dancing[$o] as $od=>$oe) {
//if ($side['SideId']==290) { echo "X2 "; var_dump($dancing[$o]); }
//if ($side['SideId']==356) { echo "X2 "; var_dump($dancing[$o]); }
		if ($Events[$oe]['Day'] == $daynum) {
//if ($side['SideId']==290) echo "X3 " . $oe . " yy ";
//if ($side['SideId']==356) echo "X3 " . $oe . " yy ";
		  $OStart = timereal($Events[$oe]['Start']);
		  $OEnd = timereal( ($Events[$oe]['SubEvent'] < 0) ? $Events[$oe]['SlotEnd'] : $Events[$oe]['End']);
		  $gap = ($starttime < $OStart)? $OStart - $endtime : $OEnd - $starttime;
//		  $gap = $starttime - timereal($OEnd);
//if ($side['SideId']==290) echo "X4 $gap $oname $starttime<p>";
//if ($side['SideId']==290) echo "Events are " . $Events[$e]['EventId'] . " and " . $Events[$oe]['EventId'] . "<p>";
//if ($side['SideId']==356) echo "X4 $gap $oname $starttime<p> ";
		  if ($gap <= -20) {
		  } else if ($gap <= 0) {
		    if ($Rule['Major']) {
//		      echo "Major Dancer Overlap on $daynam $start with $oname, ";
		      $Err .= "Dancer Overlap on $daynam $start with $oname, ";
		    } else {
//echo "Events are " . $Events[$e]['EventId'] . " and " . $Events[$oe]['EventId'] . "<p>";
//echo "Minor Dancer Overlap on $daynam $start with $oname, ";
		      $Merr .= "Dancer Overlap on $daynam $start with $oname, ";
		    }
		  } elseif ($gap < 5) { // 
		    if ($Rule['Major']) {
		      $Err .= "No dancer Gap on $daynam $start with $oname, ";
		    } else {
		      $Merr .= "No dancer Gap on $daynam $start with $oname, ";
		    }
		  } elseif ($gap < 20) { // Checking for 20, not 30 to allow for odd timings of some events
		    $Merr .= "Little dancer Gap on $daynam $start with $oname, ";
		  }
		}
	      }
	    }
	  }
	}
        $Ev = $Events[$e];
	$lastVen = $Ven;
        $LastTime[$Ev['Day']] = $End;
        if ($LastDay != $Ev['Day']) $FirstTime[$Ev['Day']] = $Ev['Start'];
        $LastDay = $Ev['Day'];
      }

      foreach ($Olaps as $Rule) {
	if ($Rule['Type'] == 1) { // Musician Olap
	  $Other = ($Rule['Sid1'] == $side['SideId'])?'Sid2':'Sid1';
  	  $o = $Rule[$Other];
	// Musician Overlaps - can do same spot multi sides and 2 consecutive spots, not 3+ - 
	  $Playing = $dancing[$side['SideId']];
	  $otherplaying = 0;
	  if (isset($dancing[$o])) {
	    foreach ($dancing[$o] as $oei) {
	      $pos = -1;
	      $oe = $Events[$oei];
	      foreach ($Playing as $p=>$sei) {
		$se = $Events[$sei];
		if ($pos < 0 && ($oe['Day'] < $se['Day'] || ($oe['Day'] == $se['Day'] && $oe['Start'] < $se['Start']))) $pos = $p;
	      }
	      array_splice($Playing,$pos,0,$oei);
	      $otherplaying = 1;
	    }
	  } // Playing now has events in order
//var_dump($Playing);
	  if ($otherplaying) {
	    $LastVen = 0;
	    $Consec = 0;
	    $LastD = -1;
	    $LastT = 0;
	    foreach ($Playing as $pd=>$e) {
	      $Ev = $Events[$e];
	      $start = timereal($Ev['Start']);
	      if ($Ev['SubEvent'] < 0) { $End = timereal($Ev['SlotEnd']); } else { $End = timereal($Ev['End']); }
	      $Ven = $Ev['Venue'];
	      if ($LastD == $Ev['Day'] && $start < ($LastT + 20)) {
	        $daynam = $DayList[$LastD];
		if ($Ven == $LastVen) {
		  $Consec += ($End - $LastT);
		  if ($Consec > 65) $Merr .= "Performing for $Consec minutes on $daynam at " . $Ev['Start'] . ", ";
	        } else {
		  if ($Rule['Major']) {
		    $Err .= "Playing at the same time in two locations: " . SName($Venues[$LastVen]) . " at " . timeformat($LastT) .
				" on $daynam and at " . SName($Venues[$Ven]) . " at " . $Ev['Start'] . ", ";
	          } else {
		    $Merr .= "Playing at the same time in two locations: " . SName($Venues[$LastVen]) . " at " . timeformat($LastT) .
				" on $daynam and at " . SName($Venues[$Ven]) . " at " . $Ev['Start'] . ", ";
		  }
		}
	      } else {
		$Consec = 0;
	      }
	      $LastVen = $Ven;
	      $LastT = $End;
	      $LastD = $Ev['Day'];
	    }
	  }
	}  
      }

      if ($side['IsASide']) {
        // First/Last Check and number of spots

        if ($side['Sat']) {
	  if ($DayCounts[1] != $side['SatDance']) $Merr .= "Have " . $DayCounts[1] . " spots on Sat and wanted " . $side['SatDance'] . ", ";
          if ($side['SatArrive'] && $FirstTime[1] && ($side['SatArrive'] > $FirstTime[1])) { $Err .= "Dancing on Sat before arriving, "; };
          if ($side['SatDepart'] && ($side['SatDepart'] < $LastTime[1])) { $Err .= "Dancing on Sat after depature, "; };
	}
        if ($side['Sun']) {
	  if ($DayCounts[2] != $side['SunDance']) $Merr .= "Have " . $DayCounts[2] . " spots on Sun and wanted " . $side['SunDance'] . ", ";
          if ($side['SunArrive'] && $FirstTime[2] && ($side['SunArrive'] > $FirstTime[2])) { $Err .= "Dancing on Sun before arriving, "; };
          if ($side['SunDepart'] && ($side['SunDepart'] < $LastTime[2])) { $Err .= "Dancing on Sun after depature, "; };
	}
        if ($side['Sat'] && $side['Procession'] != $InProcession) {
	  if ($InProcession) { $Err .= "In the Procession, but don't want to be.  "; }
          else if ($Procession) { $Merr .= "Not yet in the procession."; }
        }
      }
      // NOTE no checking (yet) of likes/dislikes

    } else {
      if ($side['IsASide']) $Merr .= 'No Events, ';
    }

    // Update error list and dance list cache?
    $needbr=0;
    $link = ($side['IsASide'])?'AddDance.php':'AddMusic.php';
    if ($Err) {
      $sideercount++;
      echo "<a href=$link?sidenum=$si>" . $side['SName'] . "</a>: ";
      echo "<span class=red>$Err</span>";
      $needbr=1;
    }
    if ($Merr && $level==2) {
//var_dump($side);
      if (!$Err) {
        $sideercount++;
        echo "<a href=$link?sidenum=$si>" . $side['SName'] . "</a>: ";
      }
      echo "<span class=brown>$Merr</span>\n";
      $needbr=1;
    }
    if ($needbr) echo "<br>";
  }  

  if ($sideercount == 0) echo "No Errors!\n";
  echo "</div>\n"; 
}
?>
