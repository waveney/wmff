<?php
  include_once("int/fest.php");

  set_ShowYear();  
  $Banner  = "<div class=WMFFBanner800><img src=" . $MASTER_DATA['DefaultPageBanner'] . " class=WMFFBannerDefault>";
  $Banner .= "<div class=BanOverlay><img src=/images/icons/wimborne-folk-festival-logo-white-shadow.png>";
  $Banner .= "<img src=/images/icons/underline.png>";
  $Banner .= "</div>";
  $Banner .= "<a href=/Tickets.php class=BanDates>" . ($MASTER['DateFri']+$MASTER['FirstDay']) . " - " . ($MASTER['DateFri']+$MASTER['LastDay']) . 
             " June $SHOWYEAR<br>Buy Tickets</a>";

  $Banner .= "<img src=/images/icons/torn-top.png class=TornTopEdge>";
  $Banner .= "</div>";

  dohead('6 - 9 June 2019', ['/js/WmffAds.js', "/js/HomePage.js"],$Banner );

  global $MASTER_DATA;
  include("int/TradeLib.php");
  include("int/NewsLib.php");
  include("int/DispLib.php");
  
  Show_Articles_For("NewTop");
  echo "<div style=margin:10>";
  echo '<center><h2>Sponsors & Supporters</h2></center>';
  echo "<center>Wimborne Minster Folk Festival would not be possible without the amazing help and generosity of the following companies and organisations:<p>";
  echo "</center>";
  $Spons = Get_Sponsors();
  echo "<div hidden>";
  foreach ($Spons as $s) {
    echo "<li class=SponsorsIds id=" .$s['id'] . "><div class=sponcontainer><div class=sponContent>";
    if ($s['Website']) echo weblinksimple($s['Website']);
    if ($s['Image']) echo "<img src='" . $s['Image'] . "' class=sponImage>";
    if (!$s['Image'] || $s['IandT']) echo "<div class=sponText>" . $s['SN'] . "</div>";
    if ($s['Website']) echo "</a>";
    echo "</div></div>";
  }
  echo "</div>\n";
  echo "<center><table style='table-layout: fixed;width:100%' id=SponDisplay><tr id=SponsorRow></table></center>";

  dotail();
?>
