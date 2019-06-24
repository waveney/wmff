<?php
  include_once("int/fest.php");
  global $FESTSYS,$YEARDATA,$NEXTYEARDATA,$Months;
  include_once("int/TradeLib.php");
  include_once("int/NewsLib.php");
  include_once("int/DispLib.php");
  

  set_ShowYear();  
  $DFrom = ($YEARDATA['DateFri']+$YEARDATA['FirstDay']);
  $DTo = ($YEARDATA['DateFri']+$YEARDATA['LastDay']);
  $DMonth = $Months[$YEARDATA['MonthFri']];
  
  if ($YEARDATA['Years2Show'] > 0) {
    $NFrom = ($NEXTYEARDATA['DateFri']+$NEXTYEARDATA['FirstDay']);
    $NTo = ($NEXTYEARDATA['DateFri']+$NEXTYEARDATA['LastDay']);
    $NMonth = $Months[$NEXTYEARDATA['MonthFri']];
    $NYear = $YEAR+1;
  }

  $Banner  = "<div class=WMFFBanner800><img src=" . $FESTSYS['DefaultPageBanner'] . " class=WMFFBannerDefault>";
  $Banner .= "<div class=BanOverlay><img src=/images/icons/wimborne-folk-festival-logo-white-shadow.png>";
  $Banner .= "<img src=/images/icons/underline.png>";
  $Banner .= "</div>";

  if ($YEARDATA['Years2Show'] == 2) {  
    $Banner .= "<div class=BanDates>Next Year: $NFrom - $NTo $NMonth $NYear</div>";
  } else {
    $Banner .= "<a href=/Tickets class=BanDates>$DFrom - $DTo $DMonth $SHOWYEAR</a><br>Buy Tickets</a>";  
  }

  $Banner .= "<img src=/images/icons/torn-top.png class=TornTopEdge>";
  $Banner .= "</div>";



  dohead('12 - 14 June 2020', ['/js/WmffAds.js', "/js/HomePage.js"],$Banner );

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
