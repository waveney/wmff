<?php
  include_once("fest.php");
  A_Check('Staff');

  dostaffhead("Event Summary");
  include_once("ProgLib.php");

  echo "<div class=content><h2>Event Summary $YEAR</h2>\n";

  $Types = Get_Event_Types(1);

  echo "<table class=TueTab><tr><td>Event type<td>Number";

  $tot = $sp = $fam = 0;
  foreach ($Types as $t) {
    $c = 0;
    $Ett = $t['ETypeNo'];
    $ans = $db->query("SELECT * FROM Events WHERE Year=$YEAR AND Type=$Ett");
    if ($ans) while ($e = $ans->fetch_assoc()) { 
      $Evs[] = $e; 
      $c++;
      if ($e['Family']) $fam++;
      if ($e['Special']) $sp++;
    };
    echo "<tr><td>" . $t['SName'] . "<td>" . $c;
    $tot += $c;
  }
  echo "<tr><td>Family<td>$fam";
  echo "<tr><td>Special<td>$sp";
  echo "<tr><td>Total<td>$sp";
    
  echo "</table>\n";
  dotail();

?>
