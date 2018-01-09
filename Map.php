<?php
  include_once("int/fest.php");

  dohead("Festival Map");
  include_once("int/MapLib.php");
  include_once("int/ProgLib.php");

  echo "<h2 class=subtitle>Festival Map</h2>";
  echo "Zoom out to find Wimborne, Zoom in for more detail.<p>\n";

  echo "<div id=map></div>";
  Init_Map(1,1,15);
  
  dotail();
?>
