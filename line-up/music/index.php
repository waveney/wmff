<?php
  include_once("int/fest.php");
  dohead("Music Line-up");
  include_once("int/MusicLib.php");
?>
<h2 class="maintitle">Music Line-up</h2>

<p>We are currently building our line-up for the 2018 festival.  Acts include:<p>

<?php
  global $db,$Book_State,$YEAR;
  $SideQ = $db->query("SELECT s.*, y.* FROM Sides AS s, ActYear AS y " .
           "WHERE s.SideId=y.SideId AND y.year=$YEAR AND y.YearState>=" . $Book_State['Booking'] . " AND s.IsAnAct=1 ORDER BY s.Importance DESC, s.Name");
  
  while($side = $SideQ->fetch_assoc()) {
    echo "<div class=floatleft>";
    echo "<div class=mini>";
    echo "<a href=/int/ShowMusic.php?sidenum=" . $side['SideId'] . ">";
    if ($side['Photo']) echo "<img class=maxminiimg src='" . $side['Photo'] ."'>";
    echo "<h2 class=minittl style='font-size:" . (27+$side['Importance']*2) . "px'>" . $side['Name'] . "</h2></a>";
    echo "<div class=minitxt>" . $side['Description'] . "</div>";
    echo "</div>";
    echo "</div>";
  }
?>

<br clear=all>
<h2 class="subtitle">Stay Updated</h2>
<p>Keep up to date with our latest music announcements by joining us on <a href="http://facebook.com/WimborneFolk" rel="tag" target="-blank"><strong>Facebook</strong></a>, <a href="http://twitter.com/WimborneFolk" rel="tag" target="_blank"><strong>Twitter</strong></a> and <a href="http://instagram.com/WimborneFolk" rel="tag" target="_blank"><strong>Instagram</strong></a>!</p>

<?php
  dotail();
?>
