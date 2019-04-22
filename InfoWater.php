<?php
  include_once("int/fest.php");

  dohead("Water Bottle Refills", ['/files/gallery.css']);

  include_once("int/ProgLib.php");
  echo "<h2 class=subtitle>Water Bottle Refills</h2>";
  echo "<table><tr><td>";
  echo "<img src=images/icons/WMFFwaterposter.jpg width=400>";
  echo "<td>Anywhere you see a poster like these:<br>";
  echo "These fine businesses will refill your water bottles for free.<p>";
  echo "<p>If your business would like to join in, get the poster from Tammy at the Bid and let her or the " .
	"<a href=mailto:webmaster@wimbornefolk.co.uk>Webmaster</a> know (before the festival).<p>";

  echo "<td><img src=images/icons/WMFFwaterposter.jpg width=400>";
  echo "</table><p>\n";

  include_once("int/TradeLib.php");
  $Refills = Get_WaterRefills();
  echo '<div id=galleryflex>';

  if ($Refills) {
    foreach ($Refills as $s) {
      echo "<div class=galleryarticle>";
      if ($s['Web']) echo weblinksimple($s['Web']);
      echo "<img class=galleryarticleimg src='" . $s['Image'] . "'>";
      echo "<div class=gallerycaption> " . $s['SN'] . "</div>";
      if ($s['Web']) echo "</a>";
      echo "</div>\n";
    }
  } 

  dotail();
?>
