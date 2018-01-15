<?php
  include_once ("int/fest.php");

  dohead("Music Line-up");
  include_once("int/MusicLib.php");
?>

<h2 class="maintitle">Music Line-up</h2>
<p>We are currently building our line-up for the 2018 festival.  Acts include:<p>

Click on the name of an Act, or their photograph to find out more about them and where they are dancing.<p>

<?php
  include_once ("int/ProgLib.php");
  include_once ("int/DispLib.php");
  global $db,$Book_State,$YEAR;
  $SideQ = $db->query("SELECT s.*, y.* FROM Sides AS s, ActYear AS y " .
           "WHERE s.SideId=y.SideId AND y.year=$YEAR AND y.YearState>=" . $Book_State['Booking'] . " AND s.IsAnAct=1 ORDER BY s.Importance DESC, s.Name");
  
  while($side = $SideQ->fetch_assoc()) {
    formatminimax(&$side,'ShowMusic.php');
  }

  dotail();
?>

