<?php
  include_once("int/fest.php");

  dohead("Festival Map");
  include_once("int/MapLib.php");
  include_once("int/ProgLib.php");

  echo "<h2 class=subtitle>Festival Map</h2>";
  echo "Zoom out to find Wimborne, Zoom in for more detail.  Every marker shows a venue for something, click on the marker for more info.<p>\n";

  echo "<div id=MapWrap>";
  echo "<div id=DirPaneWrap><div id=DirPane><div id=DirPaneTop></div><div id=Directions></div></div></div>";
  echo "<div id=map></div></div>";
//  echo "<button class=PurpButton onclick=ShowDirect()>Directions</button> (From the Square if it does not know your location)\n";
  Init_Map(-1,0,17);
  
  dotail();
?>
