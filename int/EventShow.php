<?php
  include_once("fest.php");

  dohead("Show Event");

  include_once("ProgLib.php");
  include_once("DispLib.php");
  include_once("MusicLib.php");
  include_once("DanceLib.php");
  global $MASTER,$Importance,$DayLongList,$YEAR;
/*
  Have different formats for different types of events, concerts, ceidihs, workshop
*/

function Print_Thing($thing,$right=0) {
  echo "<div class=EventMini id=" . AlphaNumeric($thing['SName']) . ">";
  if (( $thing['IsASide'] && $thing['Coming'] != 2) || (($thing['IsAnAct'] || $thing['IsOther']) && $thing['YearState'] < 2)) {
    echo "<a href=/int/ShowDance.php?sidenum=" . $thing['SideId'] . ">" . NoBreak($thing['SName'],3) . "</a>";
    echo " are no longer coming";
  } else {
    echo "<a href=Show" . ($thing['IsAnAct']?"Music":'Dance') . ".php?sidenum=" . $thing['SideId'] . ">";
    if ($thing['Photo']) echo "<img class=EventMiniimg" . ($right?'right':'') . " src='" . $thing['Photo'] ."'>";
    echo "<h2 class=EventMinittl style='font-size:" . (27+ $thing['Importance']) . "px;'>"; 
    echo $thing['SName'];  
    if (isset($thing['Type']) && $thing['Type']) echo " (" . $thing['Type'] . ") ";
    echo "</a></h2>";
    if ($thing['Description']) echo "<p class=EventMinitxt>" . $thing['Description'] . "</p>";
  }
  echo "</div>\n";
}

$lemons = 0;

function Print_Participants($e,$when=0,$thresh=0) {
  global $lemons,$DayLongList,$MASTER;
  Get_Imps($e,$imps,1,(Access('Staff')?1:0));
  $things = 0;

  if (!$imps) return;

  if ($lemons++ == 0) echo "<table class=lemontab border>\n";
  if ($imps) echo "<tr>";
  if ($when && $imps) {
    echo "<td>";
    if ($e['Start'] == $e['End']) {
      echo "Times not yet known";
    } else {
      if ($e['LongEvent']) echo "On: " . $DayLongList[$e['Day']] . " " . ($MASTER['DateFri']+$e['Day']) . "th June " . $e['Year'] . "<br>\n";
      echo "From: " . timecolon($e['Start']) . "<br>";
      echo " to: " . timecolon($e['End']) . "<br>";
    }
  }
  $ks = array_keys($imps);
  sort($ks);        
  $things = 0;
  foreach ( array_reverse($ks) as $imp) {
    foreach ($imps[$imp] as $thing) {
      if ($things && (($things&1) == 0)) echo "<tr><td>";
      $things++;
      echo "<td>";
      if (( $thing['IsASide'] && $thing['Coming'] != 2) || (($thing['IsAnAct'] || $thing['IsOther']) && $thing['YearState'] < 2)) {
        echo "<a href=/int/ShowDance.php?sidenum=" . $thing['SideId'] . ">" . NoBreak($thing['SName'],3) . "</a>";
        echo " are no longer coming";
      } else {
        formatminimax($thing,'ShowDance.php',$thresh); // 99 should be from Event type
      }
    }
  }
  echo "\n";
}

/* Name, Type, Where (inc address etc), From, Until, Cost (if any)
  If No Sub events Then:
    Participants + descr ordered by Importance
    If BE get participants from GET Stuff and more venues if applicable
  else if headliners give headliners
    for each time 
      particpants + descr + Photo - ordered by Importance

  If it is public then this will be accessable by main site, otherwise only if you have the link - not planning on restrictions (currently)
*/

  $Eid = $_GET{'e'};
  if (!is_numeric($Eid)) exit("Invalid Event Number");
  $Ev = Get_Event($Eid);  
  $YEAR = $Ev['Year'];
  $Ven = Get_Venue($Ev['Venue']);
  $ETs = Get_Event_Types(1);
  $OtherPart = $OtherVenues = array();

  $Se = $Ev['SubEvent'];
  $Subs = array();
  if ($Se < 0 ) {// Has Sub Events - Treat as Root
    $res=$db->query("SELECT * FROM Events WHERE SubEvent=$Eid ORDER BY Day, Start, Type");
    while($sev = $res->fetch_assoc()) $Subs[] = $sev;
  } else if ($Se > 0) { // Is Sub Event - Find Root
    $Eid = $Se;
    $Ev = Get_Event($Eid);  
    $res=$db->query("SELECT * FROM Events WHERE SubEvent=$Eid ORDER BY Day, Start, Type");
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

  if (($ETs[$Ev['Type']]['IncType']) && !strpos(strtolower($Ev['SName']),strtolower($ETs[$Ev['Type']]['SName']))) $xtra = " (" . $ETs[$Ev['Type']]['SName'] . ")";
  echo "<h2 class=subtitle>" . $Ev['SName'] . "$xtra</h2>\n";

  if ($Ev['NonFest']) echo "This event is not run by the folk festival, but is shown here for your information.<p>\n";
  if ($Ev['Description']) echo $Ev['Description'] . "<P>";
  // On, Start, End, Durration, Price, Where 
  echo "<table><tr><td>";
  if ($Ev['LongEvent']) {
    echo "Starting On:<td>" . $DayLongList[$Ev['Day']] . " " . ($MASTER['DateFri']+$Ev['Day']) . "th June " . $Ev['Year'] . "\n";
    echo "<tr><td>Finishing On:<td>" . $DayLongList[$Ev['EndDay']] . " " . ($MASTER['DateFri']+$Ev['EndDay']) . "th June " . $Ev['Year'] . "\n";
  } else {
    echo "On:<td>" . $DayLongList[$Ev['Day']] . " " . ($MASTER['DateFri']+$Ev['Day']) . "th June " . $Ev['Year'] . "\n";
    echo "<tr><td>Starting at:<td>" . timecolon($Ev['Start']) . "\n";
    echo "<tr><td>Finishing at:<td>" . timecolon($Ev['End']) . "\n";
  }
  if ($Ev['Price1'] || $Ev['SpecPrice']) {
    echo "<tr><td>Price:<td>";
    if ($Ev['SpecPrice']) {
      echo $Ev['SpecPrice'];
    } else {
      echo Price_Show($Ev) . ", or by Weekend ticket or " . $DayLongList[$Ev['Day']] . " ticket\n";
    }
    if ($Ev['TicketCode']) {
      $bl = "<a href=https://www.ticketsource.co.uk/event/" . $Ev['TicketCode'] . " target=_blank>" ;
      echo " -  <strong>$bl Buy Now</a></strong>\n";
    } else if ($Ev['SpecPriceLink']) {
      echo " -  <strong><a href=" . $Ev['SpecPriceLink'] . " target=_blank>Buy Now</a></strong>\n";
    }
  } else {
    echo "<tr><td>Price:<td>Free\n";
  }
  echo "<tr><td>";
    if (isset($OtherVenues[0])) {
      $OVens = Get_Real_Venues();
      echo "Starting Location:<td><a href=VenueShow.php?v=" . $Ven['VenueId'] . ">" . VenName($Ven) . "</a>";
//      echo "<div class=floatright><a onclick=ShowDirect(" . $Ven['VenueId'] . ")>Directions</a></div>\n";
      if ($Ven['Address']) echo " - " . $Ven['Address'] . $Ven['PostCode'] ."\n";
      if ($Ven['Description']) echo "<br>" . $Ven['Description'] . "\n";
      echo "<tr><td>Also at:<td>";
      $ct=0;
      foreach ($OtherVenues as $Ov) {
        $OVi = $Ov['Identifier'];
        if ($ct++) echo ", ";
        echo "<a href=VenueShow.php?v=$OVi>" . $OVens[$OVi] . "</a>";
      }
    } else if ($Ven['VenueId']) {
      echo "Where:<td><a href=VenueShow.php?v=" . $Ven['VenueId'] . ">" . VenName($Ven) . "</a>";
//      echo "<div class=floatright><a onclick=ShowDirect(" . $Ven['VenueId'] . ")>Directions</a></div>\n";
      if ($Ven['Address']) echo " - " . $Ven['Address'] . $Ven['PostCode'] ."\n";
      if ($Ven['Description']) echo "<br>" . $Ven['Description'] . "\n";
    } else {
      echo "Where: <b>Not Yet Known</b><p>\n";
    }

  if ($Ven['Bar'] || $Ev['Bar'] || $Ven['Food'] || $Ev['Food'] || $Ven['BarFoodText'] || $Ev['BarFoodText']) {
    echo "<tr><td>&nbsp;<tr><td>";
    if ($Ven['Bar'] || $Ev['Bar']) echo "<img src=/images/icons/baricon.png width=50 title='There is a bar'> ";
    if ($Ven['Food'] || $Ev['Food']) echo "<img src=/images/icons/foodicon.jpeg width=50 title='There is Food'> ";
    if ($Ven['BarFoodText']) { echo "<td>" . $Ven['BarFoodText']; }
    else if ($Ev['BarFoodText']) { echo "<td>" . $Ev['BarFoodText']; }
  }
  echo "</table><p>\n";

  // Headlines
  if ($ETs[$Ev['Type']]['UseImp']) {
    switch ($ETs[$Ev['Type']]['SName']) {
    case 'Ceildih':
      echo Get_Event_Participants($Eid,0,$size=17,$mult=2);
      break;

    default:
      // scan e + se by imp , then if any imp > 0 list them, with in page links
      $imps=array();
      $sublst = array($Ev);
      $sublst = array_merge($sublst,$Subs);
      foreach ($sublst as $e) Get_imps($e,$imps,0);
      $HighImp = 0;
      foreach ($Importance as $i=>$v) if ($i > 0 && isset($imps[$i])) $HighImp = $i;
      if ($HighImp) {
        echo "With: ";
        $with = 0;
        $ks = array_keys($imps);
        sort($ks);
        foreach(array_reverse($ks) as $i) {
          if (isset($imps[$i])) {
            foreach ($imps[$i] as $thing) {
              if ($with++) echo ", ";
              echo "<a href=#" . AlphaNumeric($thing['SName']) . " style='font-size:" . (17+$i*2) . "'>" . $thing['SName'] . "</a>";
            }
          }
        }
      echo "<p>";
      }
    }
  }

  if ($Ev['Image']) echo "<img src=" . $Ev['Image'] . ">";
  if ($Ev['Blurb']) echo "<div style='width:800px;'>" . $Ev['Blurb'] . "</div><P>";
  if ($Ev['Website']) echo "<h3>" . weblink($Ev['Website'],'Website for this event') . "</h3><p>\n";



//  echo "<h2>Detail</h2>";
  // Detail

  if (!$Se) { // Single Event Big Events not done yet
    if ($Ev['BigEvent']) {
      if ($OtherPart[1]) echo "Participants in order:<p>\n";
      echo "<div class=mini style='width:480;'>\n";
      foreach ($OtherPart as $O) {
        Print_Thing(Get_Side($O['Identifier']));
      }
      echo "</div><br clear=all>\n";
    } else {
      Print_Participants($Ev);
    }
  } else { // Sub Events
    Print_Participants($Ev,$ETs[$Ev['Type']]['Format']-1);
    foreach($Subs as $sub) if (Event_Has_Parts($sub)) Print_Participants($sub,1,$ETs[$Ev['Type']]['Format']-1);
  }
  if ($lemons) echo "</table>";
  if ($Ev['LongEvent']) {
  } else {
    echo "<p>Ending at: " . $Ev['End'];
  }
   
  dotail();
?>
