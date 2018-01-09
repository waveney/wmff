<?php
  include_once("fest.php");

  dohead("Venue Details");

  include_once("ProgLib.php");
  include_once("int/MapLib.php");
  global $db, $YEAR;

  $V = $_GET['v'];
  $Ven = Get_Venue($V);

  echo "<h2 class=subtitle>" . $Ven['Name'] . "</h2>";

  /* Desc        Picture
     Address	 Map

     Programme
  */

  echo "<div class=venueimg>";
    if ($Ven['Image']) {
      echo "<img width=100% src=" . $Ven['Image'] . ">";
    } else {
      echo "No Image Yet<p>";
    }
    echo "<p><div id=map></div>";
    Init_Map(0,$V,18);
  echo "</div>\n";

  echo "DESCRIPTION<p>";

  echo "PROGRAMME OF EVENTS<p>";

  dotail();
?>
