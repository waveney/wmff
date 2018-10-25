<?php
  include_once ("int/fest.php");

  dohead("Other Line-up");

  set_ShowYear();
  include_once("int/ProgLib.php");
  include_once("int/DispLib.php");
  include_once("int/MusicLib.php");

  global $db,$Coming_Type,$YEAR,$PLANYEAR,$Book_State,$EType_States;

  echo "<h2 class=maintitle>Other/Comedy/Family Line-up</h2>";
  if ($YEAR < $PLANYEAR) {
    echo "In $YEAR, These Acts were in Wimborne.  Click on the name or photograph to find out more and where they performed.<p>\n";
    echo "Here are the <b>Spotlight Performers</b> followed by <b>A - Z</b> of the rest of the line-up.<p>\n";
  } else {
    echo "We are putting together an exciting and varied line-up for your enjoyment at this year's festival. <p>";
    echo "They will be on various venues around town at both ticketed and free events. <p>";
    echo "Here are our <b>Spotlight Performers</b> followed by <b>A - Z</b> of the rest of the line-up.<p>";

    echo "Click on the name of a Act, or their photograph to find out more about them and where they are performing.<p>\n";
  }
  $now=time();

  $SideQ = $db->query("SELECT s.*, y.* FROM Sides AS s, ActYear AS y " .
           "WHERE s.SideId=y.SideId AND y.year=$YEAR AND y.YearState>=" . $Book_State['Booking'] . 
           " AND s.IsOther=1 AND y.ReleaseDate<$now ORDER BY s.Importance DESC, s.RelOrder DESC, s.SN");
  
  while($side = $SideQ->fetch_assoc()) formatminimax($side,'ShowMusic.php',1);

  $Prev = $YEAR-1;
  $ET = Get_Event_Type_For("Other");
  if ($Prev >= $ET['FirstYear']) {
    echo "<a href=/LineUpOther.php?Y=$Prev>Other/Comedy/Family Line Up $Prev</a></b><p>";
  }

  dotail();
?>

