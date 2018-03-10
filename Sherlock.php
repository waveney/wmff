<?php
  include_once("int/fest.php");

  $Types = $Type = $_GET['t'];
  if (strlen($Type) > 20) $Types = $Type = 'Dance';
  dohead("Whats on $Type");

  include_once("int/ProgLib.php");
  include_once("int/DateTime.php");
  global $db,$YEAR,$MASTER,$DayList,$DayLongList;

  $Ets = Get_Event_Types(1);
  $Vens = Get_Venues(1);

  $Extras = array('Music'=>' OR e.ListMusic=1'); // Need Dance Equiv


  $Ett = -1;
  foreach($Ets as $eti=>$et) if ($et['SName'] == $Type) $Ett = $eti;
  $xtr = (isset($Extras[$Type]))? $Extras[$Type] : '';
  $Evs = array();
  $Complete = 0;

  if ($Ett >= 0) { 
    $ans = $db->query("SELECT DISTINCT e.* FROM Events e, EventTypes t WHERE e.Year=$YEAR AND ( e.Type=$Ett $xtr ) AND e.SubEvent<1 AND " .
		"( e.Public=1 OR (e.Type=t.ETypeNo AND t.State>1 AND e.Public<2 )) ORDER BY e.Day, e.Start"); // Need to work with release settings as well
    if ($ans) while ($e = $ans->fetch_assoc()) $Evs[] = $e;
    if (count($Evs) > 1) $Types = $Ets[$Ett]['Plural'];
    $Complete = $Ets[$Ett]['State'];
  } else { // Handle other Sherlock calls
    switch ($Type) {
      case 'Family':
        $ans = $db->query("SELECT DISTINCT e.* FROM Events e, EventTypes t WHERE e.Year=$YEAR AND e.Family=1 AND e.SubEvent<1 AND " .
		"( e.Public=1 OR (e.Type=t.ETypeNo AND t.State>1 AND e.Public<2 )) ORDER BY e.Day, e.Start"); 
        if ($ans) while ($e = $ans->fetch_assoc()) $Evs[] = $e;
	$Types = "Family Event";
        if (count($Evs) > 1) $Types .= "s";
        $Complete = $MASTER[$Type . 'State'];
        break;
      case 'Special':
        $ans = $db->query("SELECT DISTINCT e.* FROM Events e, EventTypes t WHERE e.Year=$YEAR AND e.Special=1 AND (e.SubEvent<1 OR e.LongEvent=1) AND " .
		"( e.Public=1 OR (e.Type=t.ETypeNo AND t.State>1 AND e.Public<2 )) ORDER BY e.Day, e.Start"); 
        if ($ans) while ($e = $ans->fetch_assoc()) $Evs[] = $e;
	$Types = "Special Event";
        if (count($Evs) > 1) $Types .= "s";
        $Complete = $MASTER[$Type . 'State'];
        break;
      default:
	break;
    }
  }

  $Titles = array("", // Not used
		"Currently known $Types for $YEAR, there will be more", // Draft
		"Currently known $Types for $YEAR, there will be more", // Partial
		"Currently known $Types for $YEAR, there may be more", // Provisional
		"$Types for $YEAR", // Complete
		);

  if ($Evs && $Complete) {
    echo "<h2 class=subtitle>" . $Titles[$Complete] . "</h2>";

    echo "Click on the event name for more information.<p>";

    foreach ($Evs as $i=>$E) {
      $eid = $E['EventId'];
      if (DayTable($E['Day'],$Types,($Complete>2?'':'(More to come)'))) echo "<tr><td>When<td>What<td>Where<td>Description<td>Price\n";

      echo "<tr>";
      echo "<td>"; // . $DayList[$E['Day']] . " " . ($MASTER['DateFri']+$E['Day']) ."th June $YEAR" . "<br>";
        echo timecolon($E['Start']) . " to " . timecolon($E['End']);
      echo "<td><strong><a href=/int/EventShow.php?e=$eid>" . $E['SName'] . "</a></strong>"; 
      echo "<td><a href=/int/VenueShow.php?v=" . $E['Venue'] . ">" . $Vens[$E['Venue']]['SName'] . "</a>";
      if ($E['BigEvent']) {
	$Others = Get_Other_Things_For($eid);
	foreach ($Others as $i=>$o) {
	  if ($o['Type'] == 'Venue') echo ", <a href=/int/VenueShow.php?v=" . $o['Identifier'] . ">" . $Vens[$o['Identifier']]['SName'] . "</a>";
	}
      }
      echo "<td>";
        if ($E['Description']) echo $E['Description'] . "<p>";
        echo "With: ";
	if ($E['BigEvent']) {
	  echo Get_Other_Participants($Others,1,15);
	} else {
	  echo Get_Event_Participants($eid,1,15);
	}
      echo "<td>" . Price_Show($E) . "\n";
    }
    echo "</table><p>";

  } else {
    echo "<h3>Sorry there are currently no announced $Types for $YEAR, please check back later</h3>";
  }

  dotail();

?>

