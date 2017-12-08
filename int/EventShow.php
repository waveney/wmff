<?php
  include_once("fest.php");

  dohead("Show Event");

/*
  Have different formats for different types of events, concerts, ceidihs, workshop
*/

function Print_Thing($thing,$right=0) {
  echo "<div class=EventMini id=" . AlphaNumeric($thing['Name']) . ">";
  echo "<a href=Show" . ($thing['IsAnAct']?"Music":'Dance') . ".php?sidenum=" . $thing['SideId'] . ">";
  if ($thing['Photo']) echo "<img class=EventMiniimg" . ($right?'right':'') . " src='" . $thing['Photo'] ."'>";
  echo "<h2 class=EventMinittl style='font-size:" . (27+ $thing['Importance']) . "px;'>"; 
  echo $thing['Name'];  
  if (isset($thing['Type']) && $thing['Type']) echo " (" . $thing['Type'] . ") ";
  echo "</a></h2>";
  if ($thing['Description']) echo "<p class=EventMinitxt>" . $thing['Description'] . "</p>";
  echo "</div>\n";
}

function Print_Participants($e,$when) {
  $imps=array();
  $things = 0;
  for($i=1;$i<5;$i++) {
    if (isset($e["Side$i"])) { if ($ee = $e["Side$i"])  { $s = Get_Side($ee);  if ($s) $imps[$s['Importance']][] = $s; }; };
    if (isset($e["Act$i"]))  { if ($ee = $e["Act$i"])   { $s = Get_Side($ee);  if ($s) $imps[$s['Importance']][] = $s; }; };
    if (isset($e["Other$i"])){ if ($ee = $e["Other$i"]) { $s = Get_Side($ee);  if ($s) $imps[$s['Importance']][] = $s; }; };
  }

  if ($imps) echo "<div class=Eventfloatleft>\n";
  if ($when && $imps) {
//    echo "<br clear=all><p>";
    if ($e['Start'] == $e['End']) {
      echo "Times not yet known";
    } else {
      echo "<p>From: " . ($e['Start']?timecolon($e['Start']):"Not Yet Known") ;
      echo " to: " . ($e['End']?timecolon($e['End']):"Not Yet Known") . "<p>\n";
    }
  }
//var_dump($imps);
  $ks = array_keys($imps);
  sort($ks);	
  foreach ( array_reverse($ks) as $imp) {
    foreach ($imps[$imp] as $thing) Print_Thing($thing);
  }
  if ($imps) echo "</div><br clear=all>\n";
}


  $LongDayList = array('Friday','Saturday','Sunday');

/* Name, Type, Where (inc address etc), From, Until, Cost (if any)
  If No Sub events Then:
    Participants + descr ordered by Importance
    If BE get participants from GET Stuff and more venues if applicable
  else if headliners give headliners
    for each time 
      particpants + descr + Photo - ordered by Importance

  If it is public then this will be accessable by main site, otherwise only if you have the link - not planning on restrictions (currently)
*/
  include_once("ProgLib.php");
  include_once("MusicLib.php");
  include_once("DanceLib.php");
  global $MASTER,$Importance;

  $Eid = $_GET{'e'};
  $Ev = Get_Event($Eid);  
  $Ven = Get_Venue($Ev['Venue']);
  $ETs = Get_Event_Types(1);
  $OtherPart = $OtherVenues = array();

  $Se = $Ev['SubEvent'];
  if ($Se < 0 ) {// Has Sub Events - Treat as Root
    $res=$db->query("SELECT * FROM Events WHERE SubEvent=$Eid ORDER BY Day, Start, Type");
    $Subs = array();
    while($sev = $res->fetch_assoc()) $Subs[] = $sev;
  } else if ($Se > 0) { // Is Sub Event - Find Root
    $Eid = $Se;
    $Ev = Get_Event($Eid);  
    $res=$db->query("SELECT * FROM Events WHERE SubEvent=$Eid ORDER BY Day, Start, Type");
    $Subs = array();
    while($sev = $res->fetch_assoc()) $Subs[] = $sev;
  } else if ($Ev['BigEvent']) {
    $Others = Get_Other_Things_For($Eid);
    foreach ($Others as $o) {
      switch ($o['Type']) {
	case 'Venue': 
	  $OtherVenues[] = $o; 
	  break;
	case 'Act':
	case 'Side':
	case 'Other':
	  $OtherPart[] = $o;
	  break;
	default:
	  break;
      }
    }
  }

  echo "<h2 class=subtitle>" . $Ev['Name'] . "</h2>\n";
//var_dump($Ev);

  if ($Ev['Description']) echo $Ev['Description'] . "<P>";
  // On, Start, End, Durration, Price, Where 
  echo "<table><tr><td>On:<td>" . $LongDayList[$Ev['Day']] . " " . ($MASTER['DateFri']+$Ev['Day']) . "th June " . $Ev['Year'] . "\n";
  echo "<tr><td>Starting at:<td>" . ($Ev['Start']?timecolon($Ev['Start']):"Not Yet Known") . "\n";
  echo "<tr><td>Finishing at:<td>" . ($Ev['End']?timecolon($Ev['End']):"Not Yet Known") . "\n";
  if ($Ev['Price1']) {
    echo "<tr><td>Price:<td>" . Price_Show($Ev) . ", or by Weekend ticket or " . $LongDayList[$Ev['Day']] . " ticket\n";
    if ($Ev['TicketCode']) {
      $bl = "<a href=https://www.ticketsource.co.uk/event/" . $Ev['TicketCode'] . " target=_blank>" ;
      echo " -  <strong>$bl Buy Now</a></strong>\n";
    }
  } else {
    echo "<tr><td>Price:<td>Free\n";
  }
  echo "<tr><td>";
    if (isset($OtherVenues[0])) {
      $OVens = Get_Venues();
      echo "Starting Location:<td><a href=VenueUse.php?v=" . $Ven['VenueId'] . ">" . $Ven['ShortName'] . "</a>";
      if ($Ven['Address']) echo " - " . $Ven['Address'] . $Ven['PostCode'] ."\n";
      if ($Ven['Description']) echo "<br>" . $Ven['Description'] . "\n";
      echo "<tr><td>Also at:<td>";
      $ct=0;
      foreach ($OtherVenues as $Ov) {
	$OVi = $Ov['Identifier'];
	if ($ct++) echo ", ";
        echo "<a href=VenueUse.php?v=$OVi>" . $OVens[$OVi] . "</a>";
      }
    } else {
      echo "Where:<td><a href=VenueUse.php?v=" . $Ven['VenueId'] . ">" . $Ven['Name'] . "</a>";
      if ($Ven['Address']) echo " - " . $Ven['Address'] . $Ven['PostCode'] ."\n";
      if ($Ven['Description']) echo "<br>" . $Ven['Description'] . "\n";
    }

  if ($Ev['Bar'] || $Ev['Food'] || $Ev['BarFoodText']) {
    echo "<tr><td>";
    if ($Ev['Bar']) echo "<img src=/images/icons/baricon.png width=50 title='There is a bar'> ";
    if ($Ev['Food']) echo "<img src=/images/icons/foodicon.jpeg width=50 title='There is Food'> ";
    if ($Ev['BarFoodText']) echo "<td>" . $Ev['BarFoodText'];
  }
  echo "</table><p>\n";

  // Headlines
  if ($ETs[$Ev['Type']]['UseImp']) {
    // scan e + se by imp , then if any imp > 0 list them, with in page links
    $imps=array();
    $sublst = array($Ev);
    $sublst = array_merge($sublst,$Subs);
    foreach ($sublst as $e) {
      for($i=1;$i<5;$i++) {
        if (isset($e["Side$i"])) { if ($ee = $e["Side$i"])  { $s = Get_Side($ee);  if ($s) $imps[$s['Importance']][] = $s; }; };
        if (isset($e["Act$i"]))  { if ($ee = $e["Act$i"])   { $s = Get_Side($ee);  if ($s) $imps[$s['Importance']][] = $s; }; };
        if (isset($e["Other$i"])){ if ($ee = $e["Other$i"]) { $s = Get_Side($ee);  if ($s) $imps[$s['Importance']][] = $s; }; };
      }
    }
    $HighImp = 0;
    foreach ($Importance as $i=>$v) if ($i > 0 && isset($imps[$i])) $HighImp = $i;
    if ($HighImp) {
      echo "With: ";
      $with = 0;
      foreach(array_reverse(array_keys($imps)) as $i) {
        if (isset($imps[$i])) {
          foreach ($imps[$i] as $thing) {
            if ($with++) echo ", ";
	    echo "<a href=#" . AlphaNumeric($thing['Name']) . " style='font-size:" . (17+$i*2) . "'>" . $thing['Name'] . "</a>";
	  }
	}
      }
    }
  }

  if ($Ev['Blurb']) echo $Ev['Blurb'] . "<P>";
//  echo "<h2>Detail</h2>";
  // Detail
  if (!$Se) { // Single Event
    if ($Ev['BigEvent']) {
      echo "Participants in order:<p>\n";
      echo "<div class=Eventfloatleft>\n";
      foreach ($OtherPart as $O) {
	Print_Thing(Get_Side($O['Identifier']));
      }
      echo "</div><br clear=all>\n";
    } else {
      Print_Participants($Ev);
    }
  } else { // Sub Events
    Print_Participants($Ev,1);
    foreach($Subs as $sub) {
      if (Event_Has_Parts($sub)) {
        Print_Participants($sub,1);
      }
    }
    echo "<p>Ending at: " . $Ev['End'];
  }
   
  dotail();
?> 
