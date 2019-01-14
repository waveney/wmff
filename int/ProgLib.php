<?php
// Common Venue/Event/Programming Library

global $Venue_Status,$DayList,$InfoLevels,$VisParts,$Thing_Types,$Public_Event_Types,$Day_Type,$DayLongList,$Info_Type,$Public_Event_Type;
$Venue_Status = array('In Use','Not in Use');
$DayList = array(-3=>'Tue',-2=>'Wed',-1=>'Thur',0=>'Fri',1=>'Sat',2=>'Sun',3=>'Mon');
$DayLongList = array(-3=>'Tuesday',-2=>'Wednesday',-1=>'Thursday',0=>'Friday',1=>'Saturday',2=>'Sunday',3=>'Monday');
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
        'SN'=>'Full name eg Daccombe International Stage',
        'DanceImportance'=>'Higher numbers get listed first',
        'Description'=>'Sent to particpants so they know what to expect and where it is',
        'GoogleMap'=>'Link for partipants to know exactly where to go',
        'DanceRider'=>'Additional text sent to dance sides about this venue',
        'MusicRider'=>'Additional text sent to music acts about this venue',
        'OtherRider'=>'Additional text sent to other participants about this venue',
        'Parking'=>'Is parking provided by/for the Venue',
        'Bar'=>'Does the venue have a bar?',
        'Food'=>'Does the venue serve food?',
        'BarFoodText'=>'Any text that expands on the food and drink available',
        'Website'=>'If the venue has a website put it here',
        'MapImp'=>'Range 0-20, 0 means 16 which is default, 15 is VERY important, 18 very minor, -1 do not display',
        'DirectionsExtra'=>'Extra info to be put at end of directions to venue',
        'IsVirtual'=>'Combined site for display purposes, do not use for real events',
        'PartVirt'=>'What virtual site this is part of (if any)',
        'SupressFree'=>'If the venue has an entry change set this',
        'Minor'=>'Treatment of venue in final dance grid',
  );
  Set_Help_Table($t);
}

function Get_Venues($type=0,$extra='') { //0 = short, 1 = full
  global $db;
  static $short,$full;
  if (!$short) {
    $res = $db->query("SELECT * FROM Venues $extra ORDER BY SN");
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

function Get_AVenues($type=0,$extra='') { //0 = short, 1 = full
  global $db;
  static $short,$full;
  if (!$short) {
    $res = $db->query("SELECT * FROM Venues WHERE status=0 $extra ORDER BY SN");
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

function &Report_To() { // List of report to locs
  static $List;
  if (isset($List)) return $List;
  $List = ['Information Point','None'];
  $Vens = Get_AVenues();
  $List = array_merge($List,$Vens);
  return $List;
}


function Get_Real_Venues($type=0) { // 0 =short, 1 =full
  $Vens = Get_Venues(1);
  $real = array();
  foreach ($Vens as $vi=>$v) if (!$v['IsVirtual']) $real[$v['VenueId']] = ($type?$v:SName($v));
  return $real;
}

function Get_Virtual_Venues($type=0) {
  $Vens = Get_Venues(1);
  $virt = array();
  foreach ($Vens as $vi=>$v) if ($v['IsVirtual']) $virt[$v['VenueId']] = ($type?$v:SName($v));
  return $virt;
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

function Set_Event_Help() {
  static $t = array(
        'Start'=>'Use 24hr clock for all times eg 1030, 1330',
        'Sides'=>'Do not use this tool for dance programming use the tool under Dance, once the events have been created',
        'Acts'=>'Normally only select one Act/Other, if the event has many timed participants use sub-events',
        'Others'=>'Normally only select one Act/Other, if the event has many timed participants use sub-events',
        'SN'=>'Needed for now, need not be unique',
        'Type'=>'Broad event category, if in doubt ask Richard',
        'Description'=>'Brief description of event for website and programme book, max 150 chars',
        'Blurb'=>'Longer blurb if wanted, that will follow the description when this particular events is being looked at online',
        'Setup'=>'IF the event has setup prior to the start time, set it here in minutes to block out the venue',
        'Duration'=>'Duration in minutes of the event, this will normally be calculated from the End time',
        'BigEvent'=>'For large events needing more than 4 participants of each type eg the procession and/or use more than one venue
Set No Order to prevent the order in the event being meaningful.
Set Use Notes to fmt to use the Big Event programming Notes to describe types of performers',
        'IgnoreClash'=>'Ignore two events at same time and surpress gap checking',
        'Public'=>'Controls public visibility of Event, "Not Yet" and "Never" are handled the same',
        'ExcludeCount'=>'For Big Events - if set exclude this event from Dance Spot counts - eg Procession',
        'Price'=>'In pounds for entire event - there are no prices for sub events - negative prices have special meanings -1 = museum',
        'Venue'=>'For Big Events - put the starting Venue here',
        'SlotEnd'=>'If a large event is divided into a number of slots, this is the end of the first slot, not needed otherwise',
        'NonFest'=>'Event not run by the Festival, but included in programme - only for friendly non fesival events',
        'Family'=>'List as a family event',
        'Special'=>'List as a Special event',
        'LongEvent'=>'Enable event to ran over many days',
        'Owner'=>'Who created the event, editable by this person, the Alt Edit and any with global edit rights',
        'Owner2'=>'This person is also allowed to edit this event',
        'Importance'=>'Affects appearance of event on home page',
        'NoPart'=>'Set if the event has no particpants (Sides, Acts or Other)',
        'Image'=>'These are all for handling weird cases only',
        'Status'=>'Only mark as cancelled to have it appear in online lists and say cancelled, otherwise just delete',
        'Budget'=>'What part of the festival budget this Event comes under',
        'DoorsOpen'=>'If significantly before Start',
        'NeedSteward'=>'Most ticketed events (unless managed by third parties) and a few others will need stewards',
        'InvisiblePart' => 'Not currently used',
        'Bar'=>'Does the venue have a bar?',
        'Food'=>'Does the venue serve food?',
        'BarFoodText'=>'Any text that expands on the food and drink available',
        'StewardTasks'=>'Use this to elaborate on the Stewarding requirements for the event',
        'SetupTasks'=>'Use this to elaborate the Setup requirements for the event',
        'StagePA'=>'IF this event needs extra PA other than identified by the performers, list it here',
        'ExcludePA'=>'Exclude participents in this event from PA requirements for the venue - for the procession',
        
  );
  Set_Help_Table($t);
}

function Get_Event($eid,$new=0) {
  static $Events;
  global $db;
  if ($new == 0 && isset($Events[$eid])) return $Events[$eid];
  $res=$db->query("SELECT * FROM Events WHERE EventId=$eid");
  if ($res) {
    $ans = $res->fetch_assoc();
    $Events[$eid] = $ans;
    return $ans;
  }
  return 0; 
}

function Get_Event_VT($v,$t,$d) {
  global $db,$YEAR;
  $res=$db->query("SELECT * FROM Events WHERE Year=$YEAR AND Venue=$v AND Start=$t AND Day=$d AND Status=0");
  if ($res) return $res->fetch_assoc();
}

function Check_4Changes(&$Cur,&$now) {
  $tdchange = 0;
  if (!isset($Cur['Day'])) return;

  if ($Cur['Day'] != $now['Day'] || $Cur['Start'] != $now['Start'] || $Cur['End'] != $now['End'] || $Cur['SlotEnd'] != $now['SlotEnd']) $tdchange = 1;
  if ($Cur['Venue'] != $now['Venue']) $tdchange = 1;

  for ($i=1;$i<=4;$i++) {
    if ($tdchange) {
      if ($Cur["Act$i"] != 0) { Contract_Changed_id($Cur["Act$i"]); }
      else if ($now["Act$i"] != 0) { Contract_Changed_id($now["Act$i"]); }
    } else if ($Cur["Act$i"] != $now["Act$i"]) {
      if ($Cur["Act$i"] != 0) { Contract_Changed_id($Cur["Act$i"]); }
      if ($now["Act$i"] != 0) { Contract_Changed_id($now["Act$i"]); }
    }
  }

// Will Probably need same code for "Other"
}

function Put_Event(&$now,$new=0) {
  $e=$now['EventId'];
  $Cur = Get_Event($e,$new);
  Update_db('Events',$Cur,$now);
  Check_4Changes($Cur,$now);
}

function Get_Events_For($what,$Day) {
  global $db,$YEAR,$Day_Type;
  $evs = [];
  $xtra = ($what=='Dance'?' OR e.ListDance=1 ':($what=='Music'?' OR e.ListMusic=1':''));
  $res=$db->query("SELECT DISTINCT e.* FROM Events e, EventTypes t WHERE e.Year=$YEAR AND Status=0 AND (( e.Type=t.ETypeNo AND t.Has$what=1) $xtra ) AND e.Day=" . 
                $Day_Type[$Day] );
  if ($res) {
    while($ev = $res->fetch_assoc()) $evs[$ev['EventId']] = $ev;
    return $evs;
  }
}

function Get_All_Events_For($what,$wnum,$All=0) {// what is not used
  global $db,$YEAR;
  if (Feature('NewPERF2')) {
    $qry="SELECT DISTINCT e.* FROM Events e, BigEvent b WHERE Year=$YEAR " . ($All?'':"AND Public<2") . " AND ( " .
                "Side1=$wnum OR Side2=$wnum OR Side3=$wnum OR Side4=$wnum" .
                " OR ( BigEvent=1 AND e.EventId=b.Event AND ( b.Type='Side' OR b.Type='Perf') AND b.Identifier=$wnum ) ) " .
                " ORDER BY Day,Start";
  } else {
    $qry="SELECT DISTINCT e.* FROM Events e, BigEvent b WHERE Year=$YEAR " . ($All?'':"AND Public<2") . " AND ( " .
                "Side1=$wnum OR Side2=$wnum OR Side3=$wnum OR Side4=$wnum OR " .
                "Act1=$wnum OR Act2=$wnum OR Act3=$wnum OR Act4=$wnum OR " .
                "Other1=$wnum OR Other2=$wnum OR Other3=$wnum OR Other4=$wnum " .
                " OR ( BigEvent=1 AND e.EventId=b.Event AND b.Type='$what' AND b.Identifier=$wnum ) ) " .
                " ORDER BY Day,Start";
  } 
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

$Event_Types_Full = array();

function Event_Types_ReRead() {
  global $db, $Event_Types_Full;
  $Event_Types_Full = array();
  $res = $db->query("SELECT * FROM EventTypes ORDER BY SN ");
  if ($res) while ($typ = $res->fetch_assoc()) $Event_Types_Full[$typ['ETypeNo']] = $typ;
  return $Event_Types_Full;
}

$Event_Types_Full = Event_Types_ReRead();

function Get_Event_Types($tup=0) { // 0 just names, 1 all data
  global $Event_Types_Full;
  if ($tup) return $Event_Types_Full;
  $ans = array();
  foreach($Event_Types_Full as $t=>$et) $ans[$t] = $et['SN'];
  return $ans;
}

function Get_Event_Type($id) {
  global $Event_Types_Full;
  return $Event_Types_Full[$id];
}
 
function Put_Event_Type(&$now) {
  $e=$now['ETypeNo'];
  $Cur = Get_Event_Type($e);
  Update_db('EventTypes',$Cur,$now);
}

$Event_Types = Get_Event_Types(0);

function Get_Event_Type_For($nam) {
  global $Event_Types_Full;
  foreach ($Event_Types_Full as $ET) if ($ET['SN'] == $nam) return $ET;
  return null;
}

function Event_Has_Parts($e) {
  for ($i=1;$i<5;$i++) {
    if ($e["Side$i"] || $e["Act$i"] || $e["Other$i"]) return 1;
  }
  return 0;
}

function ListLinksNew(&$Perfs,$idx,$single,$plural,$size,$mult) {
  global $PerfTypes,$PerfIdx;
  
  if (!isset($Perfs[$idx])) return "";  
//  var_dump($Perfs);
  $ks = array_keys($Perfs[$idx]);
  $think = array();
  sort($ks);        
  $things = 0;
  $ans = '';
  foreach ( array_reverse($ks) as $imp) {
    if ($imp) $ans .= "<span style='font-size:" . ($size+$imp*$mult) . "px'>";
    foreach ($Perfs[$idx][$imp] as $thing) {
      $things++;
      $ttxt = "<a href='/int/ShowDance.php?sidenum=" . $thing['SideId'] . "'>";
      $ttxt .= NoBreak($thing['SN']);
      if (isset($thing['Type']) && $thing['Type']) $ttxt .= NoBreak(" (" . $thing['Type'] . ")");
      $ttxt .= "</a>";
      $think[] = $ttxt;
    }
  }
  if ($things == 1) return $single . " " . $think[0];
  $ans = $plural . " " . $think[0];
  for ($i = 2; $i<$things; $i++) $ans .= ", " . $think[$i-1];
  return $ans . " and " . $think[$things-1];
}

// Get Participants, Order by Importance/Time, if l>0 give part links as well
function Get_Event_Participants($Ev,$Mode=0,$l=0,$size=12,$mult=1,$prefix='') {
  global $db,$Event_Types_Full,$YEAR;

  include_once "DanceLib.php";
  include_once "MusicLib.php";
  $ans = "";
  $now = time();
  $flds = (Feature('NewPERF2')? ['Side'] : ['Side','Act','Other']);
  $MainEv = 0;
  $res = $db->query("SELECT * FROM Events WHERE EventId='$Ev' OR SubEvent='$Ev' ORDER BY Day, Start DESC");
  $found = array();
  if ($res) {
    $imps=[];
    $Perfs=[];
    while ($e = $res->fetch_assoc()) {
      if ($e['EventId'] == $Ev) $MainEv = $e;
      if ($e['BigEvent']) {
// TODO
      } else {
      
      
// need perfs[type][imp] and then condense to perf[type] in imporder - will be needed for Big Es as well in time
        foreach ($flds as $f) {
          for($i=1;$i<5;$i++) {
            if (isset($e["$f$i"])) { 
              $ee = $e["$f$i"];
              if ($ee) {

                if (!isset($found[$ee]) || !$found[$ee]) {
                  $s = Get_Side($ee);
                  $sy = Get_SideYear($ee,$YEAR);
//var_dump($sy); echo "<P>";
                  if ($sy) {
                    $s = array_merge($s, $sy);  
                    $s['NotComing'] = (($s['Coming'] != 2) && ($s['YearState'] < 2));
                  } else $s['NotComing'] = 1;
                  if ($s && ($sy['ReleaseDate'] < $now) || ( Access('Committee') && $Mode)) {
                    $imps[$s['Importance']][] = $s; 
                    $Perfs[$e["PerfType$i"]][$s['Importance']][] = $s; 
                  }
                  $found[$ee]=1;
                }
              }
            } 
          }
        }
      }
    }

    switch ($Event_Types_Full[$MainEv['Type']]['SN']) {
    case 'Ceildih':
      $ans .= (isset($Perfs[1])?ListLinksNew($Perfs,1,'Music by','Music by',$size,$mult):"Music to be announced");
//echo "ans= $ans</br>";
      if (isset($Perfs[4])) $ans .= "; " . ListLinksNew($Perfs,4,'Caller','Callers',$size,$mult);
      if (isset($Perfs[0])) $ans .= "<br>" . ListLinksNew($Perfs,0,'Dance spot by','Dance spots by',$size,$mult);
      if (isset($Perfs[2])) $ans .= "<br>" . ListLinksNew($Perfs,2,'Comedy spot by','Comedy spots by',$size,$mult);
      if (isset($Perfs[3])) $ans .= "<br>" . ListLinksNew($Perfs,3,'Entertainment spot by','Entertainment spots by',$size,$mult); 
      if ($ans) $ans .= "<p>";
      break;

    default: // Do default treatment below
      $ks = array_keys($imps);
      sort($ks);        
      $things = 0;
      foreach ( array_reverse($ks) as $imp) {
        if ($imp) $ans .= "<span style='font-size:" . ($size+$imp*$mult) . "px'>";
        foreach ($imps[$imp] as $thing) {
          if ($things++) $ans .= ", ";
          $link=0;
          if ($thing['NotComing']) {
            $ans .= "<del>" . NoBreak($thing['SN']) . "</del>";
          } else {
            if ($thing['Photo'] || $thing['Description'] || $thing['Blurb'] || $thing['Website']) $link=$l;
            if ($link) {
              $ans .= "<a href='/int/ShowDance.php?sidenum=" . $thing['SideId'] . "'>";
            }
            $ans .= NoBreak($thing['SN']);
            if (isset($thing['Type']) && $thing['Type']) $ans .= NoBreak(" (" . $thing['Type'] . ") ");
            if ($link) $ans .= "</a>";
          }
        }
        if ($imp) $ans .= "</span>";
      }
      break;
    }
    if ($ans) return "$prefix$ans";
  }
  if ($Event_Types_Full[$MainEv['Type']]['NoPart'] == 0 && $MainEv['NoPart']==0) return $prefix . "Details to follow";
  return "";
}

function Get_Other_Participants(&$Others,$Mode=0,$l=0,$size=12,$mult=1,$prefix='',&$Event=0) {
  global $db;
  include_once "DanceLib.php";
  $now = time();
  $imps=array();
  $found = array();
  $something = 0;
  $ans = '';
  $pfx = '';
  foreach ($Others as $oi=>$o) {
    if ($o['Type'] == 'Side' || $o['Type'] == 'Act' || $o['Type'] == 'Other' || $o['Type'] == 'Perf') {
      $si = $o['Identifier'];  
      if (!isset($found[$si])) {
        $s = Get_Side($si);
        $sy = Get_SideYear($si); // TODO munge/merge?
        if ($pfx) { $s['ZZZZZpfx'] = $pfx; $pfx = ''; };
        $iimp = ((isset($Event['UseBEnotes']) && $Event['UseBEnotes'])?0:$s['Importance']);
        if ($s && ($sy['ReleaseDate'] < $now) || ( Access('Committee') && $Mode)) $imps[$iimp][] = $s; 
        $something = 1;
        $found[$si] = 1;
      }
    }
    if ($o['Type'] == 'Note' && $Event['UseBEnotes']) $pfx = "<b>" . $o['Notes'] . "</b>: ";
  }
    
//var_dump($imps);
  if ($something) {
    $ks = array_keys($imps);
    sort($ks);        
    $things = 0;
    foreach ( array_reverse($ks) as $imp) {
      if ($imp) $ans .= "<span style='font-size:" . ($size+$imp*$mult) . "px'>";
      foreach ($imps[$imp] as $thing) {
        if ($things++) $ans .= ", ";
        $link=0;
        if (isset($thing['ZZZZZpfx'])) $ans .= $thing['ZZZZZpfx'];
        if ($thing['Photo'] || $thing['Description'] || $thing['Blurb'] || $thing['Website']) $link=$l;
        if ($link) {
          if ($link ==1) {
            $ans .= "<a href='/int/ShowDance.php?sidenum=" . $thing['SideId'] . "'>";
          } else if ($Mode)  {
            $ans .= "<a href='/int/AddPerf.php?sidenum=" . $thing['SideId'] . "'>";           
          }
        }
        $ans .= NoBreak($thing['SN']);
        if (isset($thing['Type']) && $thing['Type']) $ans .= NoBreak(" (" . $thing['Type'] . ") ");
        if ($link) $ans .= "</a>";
       }
      if ($imp) $ans .= "</span>";
    }
  }
  if ($ans) return "$prefix$ans";
  return $prefix . "Details to follow";
}

function Price_Show(&$Ev) {
  global $MASTER;

  if ($Ev['SpecPrice']) return $Ev['SpecPrice'];

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
      }
    $Cpri = $Npri;
    }
  }
  
  if ($MASTER['PriceChange2']) {
    $pc = $MASTER['PriceChange2'];
    $Npri = $Ev['Price3'];
    if ($Npri != $Cpri && $Npri != 0) {
      if ($pc > time()) {
        if ($str) $str .= ", then ";
        $str .= "&pound;" . $Cpri . " until " . date('j M Y',$pc);
      }
      $Cpri = $Npri;
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
  return ($V['ShortName']?$V['ShortName']:$V['SN']);
}

function DayTable($d,$Types,$xtr='',$xtra2='') {
  global $DayList,$DayLongList,$YEAR,$MASTER;
  static $lastday = -99;
  if ($d != $lastday) {
    if ($lastday != -99) echo "</table><p>\n";
    $lastday = $d;
    echo '<p><table class=' . $DayList[$d] . 'tab>';
    echo "<tr><th colspan=99 $xtra2>$Types on " . FestDate($d,'L') . " $xtr</th>\n";
    return 1;
  }
  return 0;
}

function &Get_Active_Venues($All=0) {
  global $db,$YEAR;
  $res = $db->query("SELECT DISTINCT v.* FROM Venues v, Events e, EventTypes t WHERE ( v.VenueId=e.Venue AND e.Public=0 AND e.Type=t.ETypeNo AND t.State>1 AND " .
                    " e.Year=$YEAR AND v.PartVirt=0) OR (v.IsVirtual AND e.Year=$YEAR e.Venue ORDER BY v.SN");
  if ($res) while($ven = $res->fetch_assoc()) $ans[] = $ven;
  return $ans;
}

?>
