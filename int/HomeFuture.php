<?php // Future front page options
  include_once("int/fest.php");

//  set_ShowYear();  // May want to do something more elaborate soon
  dohead('7,8,9 June 2019', ['/js/WmffAds.js', "/js/HomePage.js"]);

  global $FESTSYS;
  include_once("int/TradeLib.php");
  include_once("int/NewsLib.php");
  include_once("int/DispLib.php");
  $host= "https://" . $_SERVER['HTTP_HOST'];
  
  $future = $_REQUEST['F'];
  echo "<form method=post>";
  echo fm_text("Days in Future", $_REQUEST,'F');
  echo "<input type=submit name=Show value=Show><p></form>\n";
  

  Show_Articles_For("Top",$future);

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
