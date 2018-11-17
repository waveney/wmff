<?php
  include_once("int/fest.php");

  dohead("Whats on When");

  set_ShowYear();
  include_once("int/ProgLib.php");
  include_once("int/DateTime.php");
  include_once("int/DispLib.php");
  include_once("int/DanceLib.php");
  include_once("int/MusicLib.php");

  global $db,$YEAR,$PLANYEAR,$MASTER,$DayList,$DayLongList,$Event_Types_Full ;

  $Vens = Get_Venues(1);

  /* Get all events that are public, sort by day, time
     opening display is each day - click to expand 
     sub events not shown - click to expand
     More to come from event states and general
  */
  $More = 0; 
  foreach ($Event_Types_Full as $et) if ($et['State'] != 4) $More++;
  if ($MASTER['FamilyComplete'] != 4) $More++;
  if ($MASTER['SpecialComplete'] != 4) $More++;
  if ($YEAR < $PLANYEAR) $More = 0;

  echo "<h2 class=subtitle>What is on When in $YEAR?</h2>";
  echo "<script src=/js/WhatsWhen.js></script>";
  $xtr = (isset($_GET['Mode']) || $YEAR<$PLANYEAR)?'':"AND ( e.Public=1 OR (e.Type=t.ETypeNo AND t.State>1 AND e.Public<2 ))";

  $res = $db->query("SELECT DISTINCT e.* FROM Events e, EventTypes t WHERE e.Year=$YEAR AND (e.SubEvent<=0 OR e.LongEvent=1) $xtr ORDER BY Day, Start");

  if (!$res || $res->num_rows==0) {
    echo "<h3>There are currently no publicised events</h3>\n";
  } else {

    if ($More) echo "<h3>Only publicised events are listed, there are " . ($More > 3?"LOTS ":'') . "more to come</h3>\n";
    echo "<h2 class=subtitle>Click on a Day to expand <button id=ShowAll class=DayExpand onclick=ShowAll()>Expand All</button></h2>";

    while( $e = $res->fetch_assoc()) {
      $eid = $e['EventId'];
      /* New day give table header, links to Dance Grid/Music Grid (if applicable), Events have click to expand */
      $dname = $DayLongList[$e['Day']];

      if (DayTable($e['Day'],"Events","<button id=DayClick$dname class=DayExpand)>Expand</button>","onclick=ShowDay('$dname')")) {
        echo "<tr class=Day$dname hidden><td>Time<td >What<td>Where<td>With<td>Price";
      }
        
      Get_Imps($e,$imps,1,(Access('Staff')?1:0));
      echo "<tr class=Day$dname hidden><td>" . timecolon($e['Start']) . " - " . timecolon($e['End']); 
      echo "<td><a href=/int/EventShow.php?e=$eid>" . $e['SN'] . "</a>";
      if ($e['Description']) echo "<br>" . $e['Description'];
      if (isset($Vens[$e['Venue']]['SN'])) {
        echo "<td><a href=/int/VenueShow.php?v=" . $e['Venue'] . ">" . $Vens[$e['Venue']]['SN'] . "</a>";
      } else {
        echo "<td>Unknown";
      }
      if ($e['BigEvent']) {
        $Others = Get_Other_Things_For($eid);
        foreach ($Others as $i=>$o) {
          if ($o['Type'] == 'Venue') echo ", <a href=/int/VenueShow.php?v=" . $o['Identifier'] . ">" . $Vens[$o['Identifier']]['SN'] . "</a>";
        }
      }
      echo "<td>" . ($e['BigEvent'] ? Get_Other_Participants($Others,0,1,15,1) : Get_Event_Participants($eid,0,1,15));
      echo "<td>" . Price_Show($e);
    }
    echo "</table>\n";
  }
  
  if ($YEAR > 2018) {
    echo "<h3><a href=WhatsOnWhen.php?Y=" . ($YEAR-1) . "> Whats on When from " . ($YEAR-1) . "</h3></a>";
  }


  dotail();

?>
