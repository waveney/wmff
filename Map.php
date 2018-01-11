<?php
  include_once("int/fest.php");

  dohead("Festival Map");
  include_once("int/MapLib.php");
  include_once("int/ProgLib.php");

  echo "<h2 class=subtitle>Festival Map</h2>";
  echo "Zoom out to find Wimborne, Zoom in for more detail, click on marker for more info.  If you allow your location to be known, it can provide directions.<p>\n";

  echo "<div id=MapWrap>";
  echo "<div id=DirPane><div id=DirPaneTop></div><div id=Directions></div></div>";
  echo "<div id=map></div></div>";
  Init_Map(-1,0,16);
  
  dotail();
?>
