<?php
  include_once("ProgLib.php");

/* Read all events for each venue order by start check end of one /start of next / allow for setup (sometimes)
 * Repeat including subevents?  Or do sub events as part of main check?
 *
 * Check for don't use ifs
 */

function EventCheck() {
  global $db, $YEAR, $DayList;
  $Venues = Get_Venues(1); // All info not just names

  $EVENT_Types = Get_Event_Types(1);

  $LastVenue = -1;
  $LastEventEmpty = 1;
  $errors = 0;
  $res=$db->query("SELECT * FROM Events WHERE Year=$YEAR ORDER BY Venue, Day, Start");
  if ($res) {
    while($ev = $res->fetch_assoc()) { // Basic Events against basic events check
      if ($ev['IgnoreClash']) continue;
      $evlist[] = $ev;
      if ($ev['Venue'] != $LastVenue) { // New Venue
	$LastVenue = $ev['Venue'];
	$LastEvent = $ev;
      } else if ($LastEvent['Day'] != $ev['Day']) { // New Day
	$LastEvent = $ev;
      } else {
	$end = $LastEvent['End'];
	if ($LastEvent['SubEvent'] < 0 ) $end = $LastEvent['SlotEnd'];
	if ($ev['Start'] == $ev['End']) continue; // Skip this at present - don't even update last
	if ($end <= timeadd($ev['Start'],-$ev['Setup'])) { // No error
	} else {
	  if ($Venues[$ev['EventId']]['SetupOverlap']) {
	    if ($end <= timeadd($ev['Start'] && $EVENT_Types[$LastEvent['Type']]['HasDance'] )) { // No error
	    } else {
	      echo "The <a href=EventAdd.php?e=" . $ev['EventId'] . ">Event</a> at " . SName($Venues[$ev['Venue']]) . " starting at " .
		   $ev['Start'] . " on " . $DayList[$ev['Day']] . " clashes with <a href=EventAdd.php?e=" . 
		   $LastEvent['EventId'] . ">this event</a><p>\n";
	      $errors++;
	    }
	  } else {
	    if ($ev['SubEvent'] == $LastEvent['EventId'] && $LastEventEmpty) { // No Error
	    } else {
	      echo "The <a href=EventAdd.php?e=" . $ev['EventId'] . ">Event</a> at " . SName($Venues[$ev['Venue']]) . " starting at " .
		   $ev['Start'] . " on " . $DayList[$ev['Day']] . " clashes with <a href=EventAdd.php?e=" . 
		   $LastEvent['EventId'] . ">this event</a><p>\n";
	      $errors++;
	    }
	  }
	}
	$LastEvent = $ev;
      }
      $LastEventEmpty = 1;
      for ($i=1;$i<5;$i++) if ($ev["Side$i"] || $ev["Act$i"] || $ev["Other$i"]) $LastEventEmpty = 0;
    }   
    // Big Events...

    foreach($evlist as $e=>$ev) {
      if ($ev['BigEvent']) {
	$realstart = timereal($ev['Start']) - $ev['Setup'];
	$realend = timereal($ev['SubEvent']<0 ? $ev['SlotEnd'] : $ev['End']);
        $Other = Get_Other_Things_For($ev['EventId']);
	if ($Other) foreach($Other as $oi=>$oe) {// Big Events other venues against ordinary events
	  if ($oe['Type'] == 'Venue') { 
	    $cfv=$oe['Identifier'];
	    foreach($evlist as $ci=>$ce) {
	      if ($ce['Day'] == $ev['Day']) {
	        if ($ce['Venue'] == $cfv ) {
		  $chkstart = timereal($ce['Start']) - $ce['Setup'];
		  $chkend = timereal($ce['SubEvent']<0 ? $ce['SlotEnd'] : $ce['End']);
	          if (($chkstart >= $realstart && $chkstart < $realend) || ($chkend > $realstart && $chkend <= $realend)) {
	            echo "The <a href=EventAdd.php?e=" . $ev['EventId'] . ">Big Event</a> at " . $Venues[$ce['Venue']]['SName'] . " starting at " .
		           $ev['Start'] . " on " . $DayList[$ev['Day']] . " clashes with <a href=EventAdd.php?e=" . 
		           $ce['EventId'] . ">this event" . "</a><p>\n";
	            $errors++;
		  }
	        }
	      }
	    }
	  }
	}
        // Now cross check other big events for other venues against other venues
        foreach ($evlist as $f=>$fv) {	
	  if ($e!=$f && $fv['BigEvent'] && $ev['Day'] == $fv['Day']) {
	    $chkstart = timereal($fv['Start']) - $fv['Setup'];
	    $chkend = timereal($fv['SubEvent']<0 ? $fv['SlotEnd'] : $fv['End']);
	    if (($chkstart >= $realstart && $chkstart <= $realend) || ($chkend >= $realstart && $chkend <= $realend)) { // Overlap now check o vens
	      $COther = Get_Other_Things_For($fv['EventId']);
	      foreach($COther as $icoi=>$coe) {
	  	if ($coe['Type'] == 'Venue') {
	          foreach($Other as $oi=>$oe) {
	            if ($oe['Type'] == 'Venue' && $coe['Identifier'] == $oe['Identifier']) { // Clash
	              echo "The <a href=EventAdd.php?e=" . $ev['EventId'] . ">Big Event</a>  starting at " .
		             $ev['Start'] . " on " . $DayList[$ev['Day']] . " clashes with <a href=EventAdd.php?e=" . 
		             $ce['EventId'] . ">this big event</a> on use of " . SName($Venues[$oe['Identifier']]) . "<p>\n";
	              $errors++;
		    }
		  }
		}
	      }
	    }
	  }
	}
      }
    }

    foreach ($evlist as $e=>$ev) { //Check for don't use if other venue used
      if ($e['BigEvent']) continue; // For now
      if ($Venues[$ev['Venue']]['DontUseIf']) {
	$block = $Venues[$ev['Venue']]['DontUseIf'];
	$realstart = timereal($ev['Start']) - $ev['Setup'];
	$realend = timereal($ev['SubEvent']<0 ? $ev['SlotEnd'] : $ev['End']);

	foreach ($evlist as $f=>$fv) {
	  if ($fv['Venue'] == $block) {
	    $chkstart = timereal($fv['Start']) - $fv['Setup'];
	    $chkend = timereal($fv['SubEvent']<0 ? $fv['SlotEnd'] : $fv['End']);
	    if (($chkstart > $realstart && $chkstart < $realend) || ($chkend > $realstart && $chkend < $realend)) { // In use...
	      echo "The <a href=EventAdd.php?e=" . $ev['EventId'] . ">Event</a> is at " . SName($Venues[$ev['Venue']]) . " when " . 
		SName($Venues[$fv['Venue']]) . " is being used for <a href=EventAdd.php?e=" . $fv['EventId'] . ">This Event</a>.<p>\n";
	      $errors++;
	    }
          }
        }
      }
    }

    if ($errors == 0) echo "No errors found<p>\n";
  } else {
    echo "No events have been found...<p>\n";
  }
}	      
?>
