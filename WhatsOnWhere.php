<?php
  include_once("int/fest.php");

  dohead("Whats on By Venue");

  set_ShowYear();
  include_once("int/ProgLib.php");
  include_once("int/DateTime.php");
  global $db,$YEAR,$PLANYEAR,$MASTER,$DayList,$DayLongList;

  $Vens = Get_Active_Venues();

  echo "<h2 class=subtitle>Select a Venue</h2>";
  if ($YEAR < $PLANYEAR) {
    echo "All these venues have events schedualled, not all may be public yet.<p>";
  } else {
    echo "All these venues had events schedualled.<p>";
  }
  echo "<ul>";
  foreach ($Vens as $ven) {
    echo "<li><a href=/int/VenueShow.php?v=" . $ven['VenueId'] . "&Y=$YEAR>" . $ven['SN'] . "</a><br>";
  }
  echo "</ul>";

  dotail();

?>

