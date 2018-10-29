<?php
  include_once("int/fest.php");

  set_ShowYear();  
  dohead('7,8,9 June 2019', '/js/WmffAds.js', "/js/HomePage.js");

  global $MASTER_DATA;
  include("int/TradeLib.php");
  include("int/NewsLib.php");
  include("int/DispLib.php");
  $host= "https://" . $_SERVER['HTTP_HOST'];
  
  Show_Articles_For("Top");

  echo "<br clear=all>";
  echo '<h2 class="subtitle">Sponsors & Supporters</h2>';
  echo "Wimborne Minster Folk Festival would not be possible without the amazing help and generosity of the following companies and organisations:<p>";
  
  $Spons = Get_Sponsors();
  echo "<div hidden>";
  foreach ($Spons as $s) {
    echo "<li class=SponsorsIds id=" .$s['id'] . "><div class=sponcontainer>";
    if ($s['Website']) echo weblinksimple($s['Website']);
    if ($s['Image']) echo "<img src='" . $s['Image'] . "' width=150>";
    if (!$s['Image'] || $s['IandT']) echo "<h2 class=sponText>" . $s['SN'] . "</h2>";
    if ($s['Website']) echo "</a>";
    echo "</div>";
  }
  echo "</div>\n";
  echo "<center><table style='table-layout: fixed;'><tr id=SponsorRow></table></center><p>";

  dotail();
?>
