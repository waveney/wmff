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
  global $db,$YEAR, $DayList, $Surfaces, $Share_Type,$Procession,$Event_Types_Full;

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
  $ErrC = $MerrC = 0;

  $res = $db->query("SELECT e.* FROM Events e WHERE Year=$YEAR AND Status=0 ORDER BY Day, Start" );
  if ($res) {
    while ($e = $res->fetch_assoc()) {
      $eid = $e['EventId'];
      $Events[$eid]=$e;
      for($i=1;$i<5;$i++) {
        if ($s = $e["Side$i"]) {
          $dancing[$s][] = $eid;
          if (($Sides[$s][$DayList[$e['Day']]])) {
                // No Action
          } else if ($Sides[$s]['IsASide']) { 
            echo "<a href=AddPerf.php?sidenum=$s>" . $sidenames[$s] . "</a>: ";
            echo "<span class=red>Is listed doing an <a href=EventAdd.php?e=" . $e['EventId'] . ">event</a> at " . $e['Start'] . " in " . SName($Venues[$e['Venue']]) .
              " on " . $DayList[$e['Day']] . ", but is <b>NOT</b> there that day</span><br>\n";
            $sideercount++;
            $ErrC++;
          }
        }
/*
        if ($s = $e["Act$i"]) {
          if (isset($Sides[$s])) {
            $dancing[$s][] = $eid;
          } else if ($Sides[$s]['IsASide']) { // Should never get here, wont work anyway
            echo "<a href=AddPerf.php?sidenum=$s>" . $sidenames[$s] . "</a>: ";
            echo "<span class=red>Is listed doing an event at " . $e['Start'] . " in " . SName($Venues[$e['Venue']]) .
                " on " . $DayList[$e['Day']] . ", but is <b>NOT</b> there that day</span><br>\n";
            $sideercount++;
          }
        }
        if ($s = $e["Other$i"]) {
          if (isset($Sides[$s])) {
            $dancing[$s][] = $eid;
          } else if ($Sides[$s]['IsASide']) {
            echo "<a href=AddPerf.php?sidenum=$s>" . $sidenames[$s] . "</a>: ";
            echo "<span class=red>Is listed doing an event at " . $e['Start'] . " in " . SName($Venues[$e['Venue']]) .
                 " on " . $DayList[$e['Day']] . ", but is <b>NOT</b> there that day</span><br>\n";
            $sideercount++;
          }
        }
*/
      }
      if ($e['BigEvent']) {
        if ($e['SN'] == 'Procession') $Procession = $eid;
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
              echo "<a href=AddPerf.php?sidenum=$s>" . $sidenames[$s] . "</a>: ";
              echo "<span class=red>Is listed doing an event at " . $e['Start'] . " in " . SName($Venues[$e['Venue']]) .
                   " on " . $DayList[$e['Day']] . ", but is <b>NOT</b> there that day</span>";
              $ErrC++;
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
    $FirstTime = $LastTime = $DayCounts = array(-3=>0,-2=>0,-1=>0,0=>0,1=>0,2=>0,3=>0); // Needs changing for longer festivals TODO
    $VenuesUsed = $Complained = array();
    $surfs = 0;
    $last_e = 0;
    $minorspots = 0;
    $side['Olaps'] = $Olaps = Get_Overlaps_For($side['SideId'],1);
    $badvens = array();
    foreach ($Surfaces as $ss=>$s) if ($ss < 5 && $s && $side["Surface_$s"]) $surfs++;
    $lastVen = -1;
    $lastStart = 0;
    $InProcession = 0;
    if (isset($dancing[$si])) {
      foreach ($dancing[$si] as $dd=>$e) { // Checking for ~30 minute gaps
        $Ven = $Events[$e]['Venue'];
        $daynum = $Events[$e]['Day']; 
        $daynam = $DayList[$daynum];
        $start = $Events[$e]['Start'];
        if ($Events[$e]['EventId'] == $last_e) {
          $Err .= "Doing the same event on $daynam at $start in " . SName($Venues[$Ven]) . ", ";  
          $ErrC++;
        }
        if ($Events[$e]['SubEvent'] < 0) { $End = $Events[$e]['SlotEnd']; } else { $End = $Events[$e]['End']; };
        if ($Events[$e]['BigEvent'] && ($Events[$e]['OtherPos'][$si] <= $Events[$e]['OtherCount']/2)) $End = timeadd($End, -30);
        if ($side['IsASide']) {
          if (!isset($side[$daynam])) { 
            $Err .= "Event Issue: Dances not allowed for on $daynam (yet), ";
            $ErrC++;
          } elseif (!$side[$daynam]) { 
            $Err .= "Not at Festival on $daynam, ";
            $ErrC++;
          } elseif ($daynum != $LastDay) {
            $VenuesUsed = array();
            $LastDay = $daynum;
            $LastTime[$daynum] = $End;
            $minorspots = 0;
          } elseif (timereal($start) - timereal($LastTime[$daynum]) < 20) { // Min 20 mins to allow for odd timing of some events
            if (!$Events[$e]['IgnoreClash'] && !$Events[$last_e]['IgnoreClash'] ) {
              $Err .= "Too close on $daynam $start at " . SName($Venues[$Ven]) . " to event from " . $lastStart . " ending at " . $LastTime[$daynum] . " at " . SName($Venues[$lastVen]) . ", ";
              $ErrC++;
            }
          } else {
            $LastTime[$daynum] = $End;
          }
        }

        $last_e = $Events[$e]['EventId'];
        if ($last_e == $Procession) $InProcession = 1;

        if (isset($VenuesUsed[$Ven])) {
          $VenuesUsed[$Ven]++;
          if ($VenuesUsed[$Ven] > Feature("MultiLim$daynam")) {
            if ($side['IsASide'] && !$Venues[$Ven]['AllowMult'] && !isset($Complained[$Ven])) {
              $Merr .= "Performing multiple times at " . SName($Venues[$Ven]) . " on $daynam, ";
              $MerrC++;
              $Complained[$Ven]=1;
            } 
          }
        } else {
          $VenuesUsed[$Ven] = 1;
        }
        if (isset($Venues[$Ven]["Minor$daynam"]) && ($Venues[$Ven]["Minor$daynam"])) {
          if ($minorspots++ && $side['IsASide'] && $Event_Types_Full[$Events[$e]['Type']]['SN'] == 'Dancing') {
            $Merr .= "Performing $minorspots times at minor spots on $daynam, ";
            $MerrC++;
          }
        }
        if ($side['IsASide'] && $surfs && $Event_Types_Full[$Events[$e]['Type']]['SN'] == 'Dancing') {
//if (!$Surfaces[$Venues[$Ven]['SurfaceType1']]) { echo "Surface - $Ven ..."; }
          if (($Surfaces[$Venues[$Ven]['SurfaceType1']] != '' && $side["Surface_" . $Surfaces[$Venues[$Ven]['SurfaceType1']]]) || 
              ($Surfaces[$Venues[$Ven]['SurfaceType2']] != '' && $side["Surface_" . $Surfaces[$Venues[$Ven]['SurfaceType2']]])) { // Good
          } else if ($last_e != $Procession) {
            if(!isset($badvens[$Ven])) {
              $Err .= "Do not like dancing on the surfaces at " . SName($Venues[$Ven]) . ", ";
              $ErrC++;
            }
           $badvens[$Ven]=1;
          }
        }

        if ($side['IsASide'] && !$Events[$e]['BigEvent'] && $Event_Types_Full[$Events[$e]['Type']]['SN'] == 'Dancing') { // Sharing Checks
          $ns = 0;
          for ($j=1; $j<5; $j++) if ($Events[$e]["Side$j"]>0) $ns++;
          if ($ns == 1) {
            if ($side['Share'] == $Share_Type['Always']) {
              $Err .= "Do not like being alone ( $daynam " . $Events[$e]['Start'] . " at " . SName($Venues[$Events[$e]['Venue']]) . "), ";
              $ErrC++;            
            }
          } else if ($side['Share'] == $Share_Type['Never']) {
            $Err .= "Do not like sharing ( $daynam " . $Events[$e]['Start'] . " at " . SName($Venues[$Events[$e]['Venue']]) . "), ";
            $ErrC++;
          }            
        }

        if (!$Events[$e]['ExcludeCount']) $DayCounts[$daynum]++;

        foreach ($Olaps as $Rule) {
          if ($Rule['OType'] == 0) { // Dancer Olap
            $Other = ($Rule['Sid1'] == $side['SideId'])?'Sid2':'Sid1';
            $o = $Rule[$Other];
            if (isset($dancing[$o])) {

              $oside = $Sides[$o];
              $oname = $oside['SN'];
              
              $starttime = timereal($start = $Events[$e]['Start']);
              $endtime = timereal($Events[$e]['SubEvent'] < 0 ? $Events[$e]['SlotEnd']: $Events[$e]['End']); 
              foreach ($dancing[$o] as $od=>$oe) {
                if ($oe == $e) { // Same event - minor
                  $Merr .= "Dancer Overlaping with $oname in the same event at $daynam $Start, ";
                  $Merr++;
                } else if ($Events[$oe]['Day'] == $daynum) {
                  if ($Rule['Days'] > 0 && $daynum != $Rule['Days'] ) continue;
                  $OStart = timereal($Events[$oe]['Start']);
                  $OEnd = timereal( ($Events[$oe]['SubEvent'] < 0) ? $Events[$oe]['SlotEnd'] : $Events[$oe]['End']);
                  
                  
                  if ($endtime <= $OStart) {
                    $gap = $OStart - $endtime;
                  } else if ($OEnd <= $starttime) {
                    $gap = $starttime - $OEnd;
                  } else {
                    $gap = $OStart - $endtime; // -ve
                  }
                  
//                  $gap = ($starttime < $OStart)? $OStart - $endtime : $OEnd - $starttime;
// if ($si==107 && $e == 1546 && $oe == $e) echo "Checking $o $e $oe $gap $starttime $endtime $OStart $OStart $OEnd <p>";

                  $msg = '';
                  $serv = 0;
                  
                  if ($gap > 20) { // No problem
                  } else if ($Events[$e]['Venue'] == $Events[$oe]['Venue']) { // Same Venue
                    if ($gap < 0) {
                      $msg = "Dancer overlap with $oname - Doing two different events at the same time at " . SName($Events[$e]['Venue']) . " on $dayname $start, ";
                    } else if ($gap < 5) {
                      $msg = "Dancer overlap with $oname - Doing two different events at " . SName($Events[$e]['Venue']) . " on $dayname $start and " . timeformat($OStart) . " with no gap, ";          
                      $serv = 1;
                    } else {
                      $msg = "Dancer overlap with $oname - Doing two different events at " . SName($Events[$e]['Venue']) . " on $dayname $start and " . timeformat($OStart) . " with little gap, ";
                      $serv = 1;
                    }
                  } else { // Diff Venues
                    if ($gap < 0) {
                      $msg = "Dancer overlap with $oname - Doing two different events at the same time at " . SName($Events[$e]['Venue']) . " on $dayname $start and at " . 
                              SName($Events[$oe]['Venue']) . " at " . timeformat($OStart) . ", ";
                    } else if ($gap < 5) {
                      $msg = "Dancer overlap with $oname - Doing two different events at " . SName($Events[$e]['Venue']) . " on $dayname $start and at " . 
                              SName($Events[$oe]['Venue']) . " at " . timeformat($OStart) . " with no gap, ";                           
                      $serv = 1;
                    } else {
                      $msg = "Dancer overlap with $oname - Doing two different events at " . SName($Events[$e]['Venue']) . " on $dayname $start and at " . 
                              SName($Events[$oe]['Venue']) . " at " . timeformat($OStart) . " with little gap, ";
                      $serv = 1;
                    }
                  }
                  
                  if ($msg) {
                    if ($Rule['Major'] && $serv==0) {
                      $Err .= $msg;
                      $ErrC++;
                    } else {
                      $Merr .= $msg;
                      $MerrC++;                    
                    }
                  }
                }
              }
            }
          }
        }
        $Ev = $Events[$e];
        $lastVen = $Ven;
        $LastTime[$Ev['Day']] = $End;
        $lastStart = $start;
        if ($FirstTime[$Ev['Day']] == 0) $FirstTime[$Ev['Day']] = $Ev['Start'];
        $LastDay = $Ev['Day'];
      }

      foreach ($Olaps as $Rule) {
        if ($Rule['OType'] == 1) {  // Musician Olap(1) | Avoid(2)
          $Other = ($Rule['Sid1'] == $side['SideId'])?'Sid2':'Sid1';
          $o = $Rule[$Other];
        // Musician Overlaps - can do same spot multi sides and 2 consecutive spots, not 3+ - 
          $Playing = $dancing[$side['SideId']];
          $otherplaying = 0;
          if (isset($dancing[$o])) {
            foreach ($dancing[$o] as $oei) {
              $pos = -1;
              $oe = $Events[$oei];
              if ($Rule['Days'] > 0 && $oe['Day'] != $Rule['Days'] ) continue;
              foreach ($Playing as $p=>$sei) {
                $se = $Events[$sei];
                if ($pos < 0 && ($oe['Day'] < $se['Day'] || ($oe['Day'] == $se['Day'] && $oe['Start'] < $se['Start']))) $pos = $p;
              }
              if ($pos >= 0) {
                array_splice($Playing,$pos,0,$oei);
              } else {
                $Playing[] = $oei;
              };
              $otherplaying = 1;
            }
          } // Playing now has events in order
//var_dump($Playing);
          if ($otherplaying) {
            if ($Rule['OType'] == 1) { // Musician
              $LastVen = 0;
              $Consec = 0;
              $LastD = -1;
              $LastET = 0;
              foreach ($Playing as $pd=>$e) {
                $Ev = $Events[$e];
                $start = timereal($Ev['Start']);
                if ($Ev['SubEvent'] < 0) { $End = timereal($Ev['SlotEnd']); } else { $End = timereal($Ev['End']); }
                if ($Ev['BigEvent'] && ($Ev['OtherPos'][$si] <= $Ev['OtherCount']/2)) $End = timeadd($End, -30);
                $Ven = $Ev['Venue'];
              
                if ($LastD == $Ev['Day'] && $start < ($LastET + 20) && $side[$DayList[$Ev['Day']]]) {
                  $daynam = $DayList[$LastD];
                  if ($Ven == $LastVen) {
                    $Consec += ($End - $LastET);
                    if ($Consec > 65) {
                      $Merr .= "Performing for $Consec minutes on $daynam at " . $Ev['Start'] . ", ";
                      $MerrC++;
                    }
                  } else {
                    $txt = " Playing at the same time in two locations: ";
                    if ($LastET == $Ev['Start']) $txt = " No gap between playing at two locations: ";

                    if ($Rule['Major']) {
                      $Err .= "/ " . $Sides[$o]['SN'] . $txt . SName($Venues[$LastVen]) . " at $LastST-" . timeformat($LastET) .
                                " on $daynam and at " . SName($Venues[$Ven]) . " at " . $Ev['Start'] . ", ";
                      $ErrC++;
                    } else {
                      $Merr .= "/ " . $Sides[$o]['SN'] . $txt . SName($Venues[$LastVen]) . " at $LastST-" . timeformat($LastET) .
                                " on $daynam and at " . SName($Venues[$Ven]) . " at " . $Ev['Start'] . ", ";
                      $MerrC++;
                    }
                  }
                } else {
                  $Consec = 0;
                }
                $LastVen = $Ven;
                $LastET = $End;
                $LastST = timeformat($start);
                $LastD = $Ev['Day'];
              }
            } else { // Avoids

            }
          }
        } else if ($Rule['OType'] == 2) { // Avoids
          $Other = ($Rule['Sid1'] == $side['SideId'])?'Sid2':'Sid1';                     
          $o = $Rule[$Other];
          $res = $db->query("SELECT * FROM Events WHERE Year=$YEAR AND (Side1=$si OR Side2=$si OR Side3=$si OR Side4=$si) AND (Side1=$o OR Side2=$o OR Side3=$o OR Side4=$o)");
           
          if ($res) {
            while ($e = $res->fetch_assoc()) {
              if ($Rule['Days'] > 0 && $e['Day'] != $Rule['Days'] ) continue;
              $Err .= "Want to avoid dancing with " . $sidenames[$o] . " both are at " . SName($Venues[$e['Venue']]) . " at " . $e['Start'] . " on " . $DayList[$e['Day']] . ", ";
              $ErrC++;
            }
          }
        }
      }

      if ($side['IsASide']) {
        // First/Last Check and number of spots

        if ($side['Sat']) {
          if ($DayCounts[1] != $side['SatDance']) {
            if ($DayCounts[1] > $side['SatDance']) { 
              $Err .= "Have " . $DayCounts[1] . " spots on Sat and wanted " . $side['SatDance'] . ", ";
              $ErrC++;        
            } else {
              $Merr .= "Have " . $DayCounts[1] . " spots on Sat and wanted " . $side['SatDance'] . ", ";
              $MerrC++;
            }
          }
          if ($side['SatArrive'] && $FirstTime[1] && ($side['SatArrive'] > $FirstTime[1])) { $Err .= "Dancing on Sat before arriving, "; $ErrC++; };
          if ($side['SatDepart'] && (timeadd2($side['SatDepart'],30) < $LastTime[1])) { $Err .= "Dancing on Sat after depature, "; $ErrC++; };
        }
        if ($side['Sun']) {
          if ($DayCounts[2] != $side['SunDance']) {
            if ($DayCounts[2] > $side['SunDance']) { 
              $Err .= "Have " . $DayCounts[2] . " spots on Sun and wanted " . $side['SunDance'] . ", ";
              $ErrC++;
            } else {
              $Merr .= "Have " . $DayCounts[2] . " spots on Sun and wanted " . $side['SunDance'] . ", ";
              $MerrC++;
            }
          }
          if ($side['SunArrive'] && $FirstTime[2] && ($side['SunArrive'] > $FirstTime[2])) { 
            $Err .= "Dancing on Sun before arriving, "; 
            $ErrC++;
          };
          if ($side['SunDepart'] && (timeadd2($side['SunDepart'],30) < $LastTime[2])) { 
            $Err .= "Dancing on Sun after depature, "; 
            $ErrC++;
          };
        }
        if ($side['Sat'] && $side['Procession'] != $InProcession) {
          if ($InProcession) { 
            $Err .= "In the Procession, but don't want to be.  ";
            $ErrC++;
          } else if (!$Procession) { 
            $Merr .= "Not yet in the procession.";
            $MerrC++;
          }
        }
      }
      // NOTE no checking (yet) of likes/dislikes

    } else {
      if ($side['IsASide']) {
        $Merr .= 'No Events, ';
        $MerrC++;
      }
    }

    // Update error list and dance list cache?
    $needbr=0;
    $link = 'AddPerf.php';
    if ($Err) {
      $sideercount++;
      echo "<a href=$link?sidenum=$si>" . $side['SN'] . "</a>: ";
      echo "<span class=red>$Err</span>";
      $needbr=1;
    }
    if ($Merr && $level==2) {
//var_dump($side);
      if (!$Err) {
        $sideercount++;
        echo "<a href=$link?sidenum=$si>" . $side['SN'] . "</a>: ";
      }
      echo "<span class=brown>$Merr</span>\n";
      $needbr=1;
    }
    if ($needbr) echo "<br>";
  }  

  if ($sideercount == 0) echo "No Errors!\n";
  echo "<div id=DanceErrsSrc>Major: $ErrC Minor: $MerrC</div>";
  echo "</div>\n"; 
}
?>
