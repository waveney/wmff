<?php
  include_once("int/fest.php");
  set_ShowYear();  
  global $FESTSYS,$YEARDATA,$NEXTYEARDATA,$Months;
  include_once("int/TradeLib.php");
  include_once("int/NewsLib.php");
  include_once("int/DispLib.php");
  include_once("int/festfm.php");
  

//  set_ShowYear();  
  $DFrom = ($YEARDATA['DateFri']+$YEARDATA['FirstDay']);
  $DTo = ($YEARDATA['DateFri']+$YEARDATA['LastDay']);
  $DMonth = $Months[$YEARDATA['MonthFri']];
//  var_dump($YEARDATA);
  if ($YEARDATA['Years2Show'] > 0) {
    $NFrom = ($NEXTYEARDATA['DateFri']+$NEXTYEARDATA['FirstDay']);
    $NTo = ($NEXTYEARDATA['DateFri']+$NEXTYEARDATA['LastDay']);
    $NMonth = $Months[$NEXTYEARDATA['MonthFri']];
    $NYear = $YEAR+1;
  }

  $Banner  = "<div class=WMFFBanner800><img src=" . $FESTSYS['DefaultPageBanner'] . " class=WMFFBannerDefault>";
  $Banner .= "<div class=BanOverlay><img src=/images/icons/wimborne-folk-festival-logo-white-shadow.png?1>";
  $Banner .= "<img src=/images/icons/underline.png?1>";
  $Banner .= "</div>";

  if ($YEARDATA['Years2Show'] == 2) {  
    $Banner .= "<div class=BanDates2>Next Year: $NFrom - $NTo $NMonth $NYear<p><div class=BanNotice>" .
      "Music applications are open for the 2020 festival. If you or your band would like to perform please <a href=mailto:Music@wimbornefolk.co.uk>email us</a> " .
      " a short description of your act with contact details and video or audio link.<p>" .
      "Please <a href=InfoStewards.php>Volunteer</a> to be a Steward and/or help the Setup/Cleardown crew." . 
      "</div></div>";
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
