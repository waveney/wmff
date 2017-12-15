<?php
// Common Venue/Event/Programming Library

global $Venue_Status,$DayList,$InfoLevels,$VisParts,$Thing_Types,$Public_Event_Types,$Day_Type,$Info_Type,$Public_Event_Type;
$Venue_Status = array('In Use','Not in Use');
$DayList = array('Fri','Sat','Sun');
$InfoLevels = array('None','Errors','All');
$VisParts = array('All','None'); // Add subcats when needed
$Thing_Types = array('Sides','Acts','Others');
$Public_Event_Types = array('As Master','Yes', 'Not yet','Never');

$Day_Type = array_flip($DayList);
$Info_Type = array_flip($InfoLevels);
$Public_Event_Type = array_flip($Public_Event_Types);
include_once("DateTime.php");


function Set_Venue_Help() {
  static $t = array(
 	'ShortName'=>'Short name eg Cornmarket - OPTIONAL',
	'Name'=>'Full name eg Daccombe International Stage',
	'DanceImportance'=>'Higher numbers get listed first',
	'Description'=>'Sent to particpants so they know what to expect and where it is',
	'GoogleMap'=>'Link for partipants to know exactly where to go',
	'DanceRider'=>'Additional text sent to dance sides about this venue',
	'MusicRider'=>'Additional text sent to music acts about this venue',
	'OtherRider'=>'Additional text sent to other participants about this venue',
	'Parking'=>'Is parking provided by/for the Venue',
	'Minor'=>'Treatment of venue in final dance grid'
  );
  Set_Help_Table($t);
}

function Get_Venues($type=0,$extra='') { //0 = short, 1 = full
  global $db;
  static $short,$full;
  if (!$short) {
    $res = $db->query("SELECT * FROM Venues $extra ORDER BY Name");
    if ($res) {
      while ($Ven = $res->fetch_assoc()) {
        $i = $Ven['VenueId']; 
        $short[$i] = SName($Ven);
        $full[$i] = $Ven;
      }
    }
  }
  if ($type) return $full;
  return $short;
}

function Get_Venues_For($What) {
  global $db;
  $res = $db->query("SELECT VenueId FROM Venues WHERE $What=1 AND Status=0 ORDER BY $What" . "Importance DESC");
  if ($res) {
    while ($Ven = $res->fetch_assoc()) $ids[] = $Ven['VenueId']; 
  }
  return $ids;
}

function Get_Venue($vid) {
  static $Venues;
  global $db;
  if (isset($Venues[$vid])) return $Venues[$vid];
  $res = $db->query("SELECT * FROM Venues WHERE VenueId=$vid");
  if ($res) {
    $ans = $res->fetch_assoc();
    $Venues[$vid] = $ans;
    return $ans;
  }
  return 0; 
}

function Put_Venue(&$now) {
  $v=$now['VenueId'];
  $Cur = Get_Venue($v);
  Update_db('Venues',$Cur,$now);
}

function Get_Map_Point_Types() {
  global $db;
  $res = $db->query("SELECT * FROM MapPointTypes ORDER BY id");
  if ($res) while ($mpt = $res->fetch_assoc()) $full[] = $mpt;
  return $full;
}

function Get_Map_Point_Type($mid) {
  global $db;
  $res = $db->query("SELECT * FROM MapPointTypes WHERE id=$mid");
  if ($res) $ans = $res->fetch_assoc();
  return $ans;
}

function Put_Map_Point_Type(&$now) {
  $Cur = Get_Map_Point_Type($now['id']);
  Update_db('MapPointTypes',$Cur,$now);
}

function Set_Event_Help() {
  static $t = array(
 	'Start'=>'Use 24hr clock for all times eg 1030, 1330',
	'Sides'=>'Do not use this tool for dance programming use the tool under Dance, once the events have been created',
	'Acts'=>'I hope to do something better eventually - Richard',
	'Name'=>'Needed for now, need not be unique',
	'Description'=>'Brief description of event for website and programme book, max 150 chars',
	'Blurb'=>'Longer blurb if wanted, that will follow the description when this particular events is being looked at online',
	'Setup'=>'IF the event has setup prior to the start time, set it here in minutes to block out the venue',
	'Duration'=>'Duration in minutes of the event, this will normally be calculated from the End time',
	'BigEvent'=>'For large events needing more than 4 participants of each type eg the procession',
	'Public'=>'Controls public visibility of Event, "Not Yet" and "Never" are handled the same',
	'ExcludeCount'=>'For Big Events - if set exclude this event from Dance Spot counts - eg Procession',
	'Price'=>'In pounds for entire event - there are no prices for sub events',
	'Venue'=>'For Big Events - put the starting Venue here',
	'SlotEnd'=>'If a large event is divided into a number of slots, this is the end of the first slot, not needed otherwise',
	'Budget'=>'What part of the festival budget this Event comes under'
  );
  Set_Help_Table($t);
}

function Get_Event($eid) {
  static $Events;
  global $db;
  if (isset($Events[$eid])) return $Events[$eid];
  $res=$db->query("SELECT * FROM Events WHERE EventId=$eid");
  if ($res) {
    $ans = $res->fetch_assoc();
    $Events[$eid] = $ans;
    return $ans;
  }
  return 0; 
}

function Check_4Changes(&$Cur,&$now) {
  $tdchange = 0;
  if ($Cur['Day'] != $now['Day'] || $Cur['Start'] != $now['Start'] || $Cur['End'] != $now['End'] || $Cur['SlotEnd'] != $now['SlotEnd']) $tdchange = 1;
  if ($Cur['Venue'] != $now['Venue']) $tdchange = 1;
  for ($i=1;$i<=4;$i++) {
    if ($tdchange) {
      if ($Cur["Act$i"] != 0) { Contract_Changed($Cur["Act$i"]); }
      else if ($now["Act$i"] != 0) { Contract_Changed($now["Act$i"]); }
    } else if ($Cur["Act$i"] != $now["Act$i"]) {
      if ($Cur["Act$i"] != 0) { Contract_Changed($Cur["Act$i"]); }
      if ($now["Act$i"] != 0) { Contract_Changed($now["Act$i"]); }
    }
  }

// Will Probably need same code for "Other"
}

function Put_Event(&$now) {
  $e=$now['EventId'];
  $Cur = Get_Event($e);
  Update_db('Events',$Cur,$now);
  Check_4Changes($Cur,$now);
}

function Get_Events_For($what,$Day) {
  global $db,$YEAR,$Day_Type;
  $res=$db->query("SELECT e.* FROM Events e, EventTypes t WHERE Year=$YEAR AND e.Type=t.ETypeNo AND t.Has$what=1 AND Day=" . $Day_Type[$Day] . 
	" ORDER BY Day,Start");
  if ($res) {
    while($ev = $res->fetch_assoc()) $evs[] = $ev;
    return $evs;
  }
}

function Get_All_Events_For($what,$wnum) {
  global $db,$YEAR;
  $qry="SELECT DISTINCT e.* FROM Events e, BigEvent b WHERE Year=$YEAR AND Public<2 AND ($what" . "1=$wnum OR $what" . "2=$wnum OR $what". 
		"3=$wnum OR $what". "4=$wnum OR ( BigEvent=1 AND e.EventId=b.Event AND b.Type='$what' AND b.Identifier=$wnum ) ) " .
		" ORDER BY Day,Start";
  $res = $db->query($qry);
  if ($res) {
    while($ev = $res->fetch_assoc()) $evs[$ev['EventId']] = $ev;
    if (isset($evs)) return $evs;
  }
  return 0;
}

function Get_Other_Things_For($what) {
  global $db;
  $evs = array();
  $res = $db->query("SELECT * FROM BigEvent WHERE Event=$what ORDER BY EventOrder");
  if ($res) {
    while($ev = $res->fetch_assoc()) $evs[] = $ev;
  }
  return $evs;
}

function Get_BigEvent($b) {
  static $BigEvent;
  global $db;
  if (isset($BigEvent[$b])) return $BigEvent[$b];
  $res=$db->query("SELECT * FROM BigEvent WHERE BigEid=$b");
  if ($res) {
    $ans = $res->fetch_assoc();
    $BigEvent[$b] = $ans;
    return $BigEvent[$b];
  }
  return 0; 

}

function Put_BigEvent($now) {
  $e=$now['BigEid'];
  $Cur = Get_BigEvent($e);
  Update_db('BigEvent',$Cur,$now);
}

function New_BigEvent(&$data) {
  Insert_Db('BigEvent',$data);
}


function &Select_All_Acts() {
  static $dummy=0;
  return $dummy;
}

function &Select_All_Other() {
  static $dummy=0;
  return $dummy;
}

function Get_Event_Types($tup=0) { // 0 just names, 1 all data
  global $db;
  static $short,$full;
  if (!isset($short)) {
    $res = $db->query("SELECT * FROM EventTypes ORDER BY Name ");
    if ($res) {
      while ($typ = $res->fetch_assoc()) {
        $short[$typ['ETypeNo']] = $typ['Name'];
        $full[$typ['ETypeNo']] = $typ;
      }
    }
  }
  if ($tup) return $full;
  return $short;
}

function Get_Event_Type($id) {
  global $db;
  static $Types;
  if (isset($Types[$id])) return $Types[$id];
  $res=$db->query("SELECT * FROM EventTypes WHERE ETypeNo=$id");
  if ($res) {
    $ans = $res->fetch_assoc();
    $Types[$id] = $ans;
    return $ans;
  }
  return 0; 
}

function Put_Event_Type(&$now) {
  $e=$now['ETypeNo'];
  $Cur = Get_Event_Type($e);
  Update_db('EventTypes',$Cur,$now);
}

$Event_Types = Get_Event_Types(0);

function Event_Has_Parts($e) {
  for ($i=1;$i<5;$i++) {
    if ($e["Side$i"] || $e["Act$i"] || $e["Other$i"]) return 1;
  }
  return 0;
}

// Get Participants, Order by Importance/Time, if l>0 give part links as well
function Get_Event_Participants($Ev,$l=0,$size=12,$mult=1) {
  global $db;
  include_once "DanceLib.php";
  $ans = "";
  $flds = array('Side','Act','Other');
  $res = $db->query("SELECT * FROM Events WHERE EventId='$Ev' OR SubEvent='$Ev' ORDER BY Day, Start DESC");
  if ($res) {
    $imps=array();
    while ($e = $res->fetch_assoc()) {
      foreach ($flds as $f) {
        for($i=1;$i<5;$i++) {
   	  if (isset($e["$f$i"])) { 
	    $ee = $e["$f$i"];
	    if ($ee) {
	     $s = Get_Side($ee);  
	     if ($s) $imps[$s['Importance']][] = $s; 
	    } 
	  }
	}
      }
    }

    
    $ks = array_keys($imps);
    sort($ks);	
    $things = 0;
    foreach ( array_reverse($ks) as $imp) {
      if ($imp) $ans .= "<span style='font-size:" . ($size+$imp*$mult) . "px'>";
      foreach ($imps[$imp] as $thing) {
	if ($things++) $ans .= ", ";
	$link=0;
	if ($thing['Photo'] || $thing['Description'] || $thing['Blurb'] || $thing['Website']) $link=$l;
	if ($link) {
	  if ($link ==1) {
	    $ans .= "<a href='/int/ShowDance.php?sidenum=" . $thing['SideId'] . "'>";
	  } else {
	    if ($thing['IsASide']) {
	      $ans .= "<a href='/int/AddDance.php?sidenum=" . $thing['SideId'] . "'>";
	    } else if ($thing['IsAnAct']) {
	      $ans .= "<a href='/int/AddMusic.php?sidenum=" . $thing['SideId'] . "'>";
	    } else {
	      $ans .= "<a href='/int/AddMusic.php?t=O&sidenum=" . $thing['SideId'] . "'>";
	    }
	  }
	}
	$ans .= NoBreak($thing['Name']);
	if (isset($thing['Type']) && $thing['Type']) $ans .= NoBreak(" (" . $thing['Type'] . ") ");
        if ($link) $ans .= "</a>";
       }
      if ($imp) $ans .= "</span>";
    }
  }
  if ($ans) return $ans;
  return "Details to follow";
}

function Price_Show(&$Ev) {
  global $MASTER;
  $dats = array();
  $str = '';
  $Cpri = $Ev['Price1'];
  if (!$Cpri) return 'Free';

  if ($MASTER['PriceChange1']) {
    $pc = $MASTER['PriceChange1'];
    $Npri = $Ev['Price2'];
    if ($Npri != $Cpri && $Npri != 0) {
      if ($pc > time()) {
	$str .= "&pound;" . $Cpri . " until " . date('j M Y',$pc);
	$Cpri = $Npri;
      }
    }
  }
  
  if ($MASTER['PriceChange2']) {
    $pc = $MASTER['PriceChange2'];
    $Npri = $Ev['Price3'];
    if ($Npri != $Cpri && $Npri != 0) {
      if ($pc > time()) {
	if ($str) $str .= ", then ";
	$str .= "&pound;" . $Cpri . " until " . date('j M Y',$pc);
	$Cpri = $Npri;
      }
    }
  }

  if ($Ev['DoorPrice'] && $Ev['DoorPrice'] != $Cpri) {
    if ($str) $str .= ", then ";
    $str .= "&pound;" . $Cpri . " in advance and &pound;" . $Ev['DoorPrice'] . " at the door";
  } else {
    if ($str) $str .= ", then ";
    $str .= "&pound;" . $Cpri;
  } 

  return $str;
}

function VenName(&$V) {
  return ($V['ShortName']?$V['ShortName']:$V['Name']);
}

?>
