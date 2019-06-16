<?php
  include_once("int/fest.php");

  dohead("Venue Timetables and Info",[],1);

  set_ShowYear();
  include_once("int/ProgLib.php");
  include_once("int/DateTime.php");
  global $db,$YEAR,$PLANYEAR,$SHOWYEAR,$YEARDATA,$DayList,$DayLongList;

  if ( $YEAR != $SHOWYEAR) {
    $Vens = Get_Active_Venues();
  } else {
    $Vids = json_decode(file_get_contents("cache/VenueList"));
    foreach($Vids as $vid) $Vens[] = Get_Venue($vid);
  }

//var_dump($Vids);

  echo "<h2>Select a Venue:</h2>";
  if ($YEAR == $PLANYEAR) {
    if (Feature('NotAllPublic')) echo "All these venues have events schedualled, not all their events may be public yet.<p>";
  } else {
    echo "All these venues had events.<p>";
  }

  if ($Vens) {
    echo "<div id=flex5>\n";
    foreach ($Vens as $ven) echo "<div class=VenueFlexCont><a href=/int/VenueShow.php?v=" . $ven['VenueId'] . "&Y=$YEAR>" . $ven['SN'] . "</a></div>";
    echo "</div><br>";
  } else {
    echo "<h3>No venues have published events yet</h3>";
  }


  dotail();

?>

