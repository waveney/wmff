<?php
  include_once("int/fest.php");

  dohead("Dance Line-up");
  set_ShowYear();
  include_once("int/ProgLib.php");
  include_once("int/DispLib.php");

  global $db,$Coming_Type,$YEAR,$PLANYEAR,$EType_States;

  $ET = Get_Event_Type_For("Dancing");

  echo "<h2 class=maintitle>Dance Line-up</h2>";
  if ($YEAR < $PLANYEAR) {
    echo "In $YEAR, These Dance teams were in Wimborne.  Click on the name or photograph to find out more and where they were dancing.<p>\n";
    echo "<b><a href=/int/ShowDanceProg.php?Cond=1&Pub=1&Y=$YEAR>Dance Programme for $YEAR</a></b><p>\n";
  } else {
    echo "In $YEAR, it's going to be another fun filled weekend of colourful dance displays through the streets of Wimborne.<p>\n";
    echo "Dance teams already confirmed for $YEAR include:<p>\n";
    echo "Click on the name of a team, or their photograph to find out more about them and where they are dancing.<p>\n";
    if ($ET['State'] >=3 ) echo "<b><a href=/int/ShowDanceProg.php?Cond=1&Pub=1&Y=$YEAR>" . $EType_States[$ET['State']] . " Dance Programme for $YEAR</a></b><p>\n";
  }

  $SideQ = $db->query("SELECT s.*, y.* FROM Sides AS s, SideYear AS y " .
           "WHERE s.SideId=y.SideId AND y.year=$YEAR AND y.Coming=" . $Coming_Type['Y'] . " AND s.IsASide=1 ORDER BY s.Importance DESC, s.SName");
  
  while($side = $SideQ->fetch_assoc()) formatminimax($side,'ShowDance.php',99);

  echo "<div style='clear:both;'>";
  $Prev = $YEAR-1;
  if ($Prev >= $ET['FirstYear']) {
    echo "<b><a href=/int/ShowDanceProg.php?Cond=1&Pub=1&Y=$Prev>Complete Dance Programme for $Prev</a>, ";
    echo "<a href=/LineUpDance.php?Y=$Prev>Dance Line Up $Prev</a></b><p>";
  }
  dotail();
?>
