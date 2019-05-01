<?php
  include_once("int/fest.php");

  dohead("Whats on Now");

  include_once("int/ProgLib.php");
  include_once("int/DateTime.php");
  include_once("int/DispLib.php");
  include_once("int/DanceLib.php");
  include_once("int/MusicLib.php");

  global $db,$YEAR,$SHOWYEAR,$MASTER,$DayList,$DayLongList,$Event_Types_Full ;

  $Vens = Get_Venues(1);

  /* Get all events that are public, sort by day, time
     opening display is each day - click to expand 
     sub events are shown 
     find those with start < (now +1.5 hrs) & end >=now)
     if day !=0-2 (1.5hrs = whole day)
     More to come from event states and general
     Add Type to selection later
  */

  $Now = getdate();
  $now = time();
  
  $Now['hours']++; // Festivals is BST server is UTC
// Fudge for testing...
//  $Now['mon'] = 6;
//  $Now['mday']= 9;
 
  $StartTime = mktime(0,0,0,$MASTER['MonthFri'],$MASTER['DateFri']+$MASTER['FirstDay'],$PLANYEAR);
  $EndTime = mktime(23,59,59,$MASTER['MonthFri'],$MASTER['DateFri']+$MASTER['LastDay'],$PLANYEAR);
 
  if ($now < $StartTime || $now > $EndTime) {
    echo "<h3>There are no festival events today</h3>\n";
    dotail();  
  }
/*
  if (($Now['year'] != $SHOWYEAR) || ($Now['mon'] != 6) || ($Now['mday'] < ($MASTER['DateFri']-3)) || ($Now['mday'] > ($MASTER['DateFri']+3))) { // Not during festival
  }
*/

  $xtr = isset($_GET['Mode'])?'':"AND ( e.Public=1 OR (e.Type=t.ETypeNo AND t.State>1 AND e.Public<2 ))";
  $today = ($Now['mday']-$MASTER['DateFri']);

  $res = $db->query("SELECT DISTINCT e.* FROM Events e, EventTypes t WHERE e.Year=$YEAR AND Day=$today $xtr ORDER BY Start");
  $StartLim = (($today < 0 || $today>2) ? 2400 : ($Now['hours']+2)*100 );
  $EndLim = (($today < 0 || $today>2) ? 0 : ($Now['hours'])*100 + $Now['minutes']);

  if (!$res || $res->num_rows==0) {
    echo "<h3>There are no festival events today</h3>\n";
    dotail();
  }

//var_dump($StartLim,$EndLim);

  echo "<h2 class=subtitle>What is on Now?</h2>";
  echo "<script src=/js/WhatsWhen.js></script>";
  $something = 0;

  while( $e = $res->fetch_assoc()) {
    $eid = $e['EventId'];
    /* New day give table header, links to Dance Grid/Music Grid (if applicable), Events have click to expand */

    if ($e['Start'] > $StartLim) continue; 
    if ($e['End'] < $EndLim) continue; 
    if ($e['SubEvent'] < 0 && $e['SlotEnd'] < $EndLim ) continue; 

    if ($e['BigEvent'] == 0) {
      if ($e['Side1'] == 0 && $e['Side2'] == 0 && $e['Side3'] == 0 && $e['Side4'] == 0 &&
          $e['Act1'] == 0 && $e['Act2'] == 0 && $e['Act3'] == 0 && $e['Act4'] == 0 &&
          $e['Other1'] == 0 && $e['Other2'] == 0 && $e['Other3'] == 0 && $e['Other4'] == 0 && $e['NoPart'] == 0) continue; // Nobody there 
    }

    $dname = $DayLongList[$e['Day']];

    if (DayTable($e['Day'],"Events")) echo "<tr class=Day$dname><td>Time<td >What<td>Where<td>With<td>Price";
        
    Get_Imps($e,$imps,1,(Access('Staff')?1:0));
    echo "<tr class=Day$dname><td>" . timecolon($e['Start']) . " - " . timecolon($e['End']); 
    echo "<td><a href=/int/EventShow.php?e=$eid>" . $e['SN'] . "</a>";
    if ($e['Description']) echo "<br>" . $e['Description'];
    echo "<td><a href=/int/VenueShow.php?v=" . $e['Venue'] . ">" . $Vens[$e['Venue']]['SN'] . "</a>";
    if ($e['BigEvent']) {
      $Others = Get_Other_Things_For($eid);
      foreach ($Others as $i=>$o) {
        if ($o['Type'] == 'Venue') echo ", <a href=/int/VenueShow.php?v=" . $o['Identifier'] . ">" . $Vens[$o['Identifier']]['SN'] . "</a>";
      }
    }
    echo "<td>" . ($e['BigEvent'] ? Get_Other_Participants($Others,0,1,15,1,$e) : Get_Event_Participants($eid,0,1,15));
    echo "<td>" . Price_Show($e,1);
    $something = 1;
  }
  echo "</table>\n";

  if ($something == 0) {
    echo "<h3>There are no festival events later today</h3>\n";
  }

  dotail();

?>
