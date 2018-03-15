<?php
  include_once("int/fest.php");

  dohead("Whats on By Venue");

  include_once("int/ProgLib.php");
  include_once("int/DateTime.php");
  global $db,$YEAR,$MASTER,$DayList,$DayLongList;

  $Vens = Get_Active_Venues();

  echo "<h2 class=subtitle>Select a Venue</h2>";
  echo "All these venues have events schedualled.<p>  May have events that are public yet.<p>" .
	"There will be many more venues listed when the programme is complete.<p>";
  echo "<ul>";
  foreach ($Vens as $ven) {
    echo "<li><a href=/int/VenueShow.php?v=" . $ven['VenueId'] . ">" . $ven['SName'] . "</a><br>";
  }
  echo "</ul>";

  dotail();

?>

