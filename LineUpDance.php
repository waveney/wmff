<?php
    include_once ("int/fest.php");

  dohead("Dance Line-up");
?>

<h2 class="maintitle">Dance Line-up</h2>
<b><a href=/int/ShowDanceProg.php?Cond=1&Pub=1&Y=2017>Complete Dance Programme for 2017</a>, <a href=/line-up/dance/index.php?Y=2017>Dance Line Up 2017</a></b><p>

<p>In 2018, it's going to be another fun filled weekend of colourful dance displays through the streets of Wimborne.<p>

Dance teams already confirmed for 2018 include:<p>

Click on the name of a team, or their photograph to find out more about them and where they are dancing.<p>

<?php
  include_once ("int/ProgLib.php");
  include_once ("int/DispLib.php");
  global $db,$Coming_Type,$YEAR;
  $SideQ = $db->query("SELECT s.*, y.* FROM Sides AS s, SideYear AS y " .
           "WHERE s.SideId=y.SideId AND y.year=$YEAR AND y.Coming=" . $Coming_Type['Y'] . " AND s.IsASide=1 ORDER BY s.Importance DESC, s.Name");
  
  while($side = $SideQ->fetch_assoc()) {
    formatminimax($side,'ShowDance.php');
  }

  dotail();
?>
