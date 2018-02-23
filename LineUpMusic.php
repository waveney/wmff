<?php
  include_once ("int/fest.php");

  dohead("Music Line-up");
  include_once("int/MusicLib.php");
?>

<h2 class="maintitle">Music Line-up</h2>
We are putting together an exciting and varied line-up for your enjoyment at this year's festival. 
They will be on various stages and venues around town at both ticketed and free events. 
Do keep checking back to see who else will be appearing... there are still more to come! <p>

Here are our <b>Spotlight Performers</b> followed by <b>A - Z</b> of the rest of the line-up.<p>

<?php
  include_once ("int/ProgLib.php");
  include_once ("int/DispLib.php");
  global $db,$Book_State,$YEAR;
  $SideQ = $db->query("SELECT s.*, y.* FROM Sides AS s, ActYear AS y " .
           "WHERE s.SideId=y.SideId AND y.year=$YEAR AND y.YearState>=" . $Book_State['Booking'] . " AND s.IsAnAct=1 ORDER BY s.Importance DESC, s.SName");
  
  while($side = $SideQ->fetch_assoc()) {
    formatminimax($side,'ShowMusic.php',1);
  }

  dotail();
?>

