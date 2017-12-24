<?php
  include_once("int/fest.php");

  global $db,$YEAR,$MASTER,$DayList;
  $Types = $Type = $_GET['t'];
  dohead("Whats on $Type");

  include_once("int/ProgLib.php");
  include_once("int/DateTime.php");

  $Ets = Get_Event_Types(1);
  $Vens = Get_Venues(1);

  $Ett = -1;
  foreach($Ets as $eti=>$et) if ($et['Name'] == $Type) $Ett = $eti;
  $Evs = array();

  if ($Ett < 0) { // Handle other Sherlock calls
  } else {
    $ans = $db->query("SELECT * FROM Events WHERE Year=$YEAR AND Type=$Ett AND SubEvent<1 ORDER BY Day,Start"); // Need to work with release settings as well
    if ($ans) while ($e = $ans->fetch_assoc()) $Evs[] = $e;
    if (count($Evs) > 1) $Types .= "s";
  }

  if ($Evs) {
    if ($MASTER[$Type . "Complete"]) {
      echo "<h2 class=subtitle>$Types for $YEAR</h2>";
    } else {
      echo "<h2 class=subtitle>Currently known $Types for $YEAR, there may be more in the future</h2>";
    }

    echo "Click on the event name for more information.<p>";

    // "Day/ date, Name, Venue, Description, Price";
    echo '<table cellspacing=5 cellpadding=5 style="background-color:#59B404; border-color:#59B404;">';
    echo "<tr><th colspan=5>$Types for $YEAR</th>\n";
    echo "<tr><td>When<td>What<td>Where<td>Description<td>Price\n";

    foreach ($Evs as $i=>$E) {
      $eid = $E['EventId'];
      echo "<tr>";
      echo "<td>" . $DayList[$E['Day']] . " " . ($MASTER['DateFri']+$E['Day']) ."th June $YEAR" . "<br>";
        echo "From: " . timecolon($E['Start']) . " to " . timecolon($E['End']);
      echo "<td><strong><a href=/int/EventShow.php?e=$eid>" . $E['Name'] . "</a></strong>"; 
      echo "<td>" . VenName($Vens[$E['Venue']]);
      echo "<td>";
        if ($E['Description']) echo $E['Description'] . "<p>";
        echo "With: " . Get_Event_Participants($eid,1,15);
      echo "<td>" . Price_Show($E) . "\n";
    }
    echo "</table><p>";

  } else {
    echo "Sorry there are currently no known $Type for $YEAR, please check back later.<p>";
  }

  dotail();

?>

