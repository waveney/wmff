<?php
  include_once("int/fest.php");
  set_ShowYear();  
  global $FESTSYS,$YEARDATA,$NEXTYEARDATA,$Months,$SHOWYEAR;
  include_once("int/TradeLib.php");
  include_once("int/NewsLib.php");
  include_once("int/DispLib.php");
  include_once("int/festfm.php");
  
  $future = (isset($_REQUEST['F'])?$_REQUEST['F']:0);
  
//  set_ShowYear();  
  $DFrom = ($YEARDATA['DateFri']+$YEARDATA['FirstDay']);
  $DTo = ($YEARDATA['DateFri']+$YEARDATA['LastDay']);
  $DMonth = $Months[$YEARDATA['MonthFri']];
//  var_dump($YEARDATA);
  if ($YEARDATA['Years2Show'] > 0) {
    $NFrom = ($NEXTYEARDATA['DateFri']+$NEXTYEARDATA['FirstDay']);
    $NTo = ($NEXTYEARDATA['DateFri']+$NEXTYEARDATA['LastDay']);
    $NMonth = $Months[$NEXTYEARDATA['MonthFri']];
    $NYear = substr($YEARDATA['NextFest'],0,4);
  }

  $Sy = substr($SHOWYEAR,0,4);
  $TopBans = Get_All_Articles(0,'TopPageBanner',$future);

  if ($TopBans) {
    $Imgs = explode("\n",$TopBans[0]['Text']);
    
    $Banner  = "<div class=WMFFBanner400>";
    $Banner .= '<div class="rslides_container" style="margin:0;"><ul class="rslides" id="slider1">';
    
    foreach ($Imgs as $img) {
      $Banner .= "<li><img src='$img' class=WMFFBannerDefault>";
    }
    $Banner .= '</ul></div><script>$(function() { $(".rslides").responsiveSlides(); });</script>';
    $Banner .= "<div class=BanOverlay><img src=/images/icons/wimborne-folk-festival-logo-white-shadow.png?1>";
    $Banner .= "<img src=/images/icons/underline.png?1>";
    $Banner .= "</div>";

    if ($YEARDATA['Years2Show'] == 2) {  
      $Banner .= "<div class=BanDates2>Next Year: $NFrom - $NTo $NMonth $NYear<p><div class=BanNotice></div></div>";
    } else {
      $Banner .= "<a href=/Tickets class=BanDates>$DFrom - $DTo $DMonth $Sy<br>Buy Tickets</a>";  
    }

    $Banner .= "<img align=center src=/images/icons/torn-top.png class=TornTopEdge>";
    $Banner .= "</div>";
  } else {
    $Banner  = "<div class=WMFFBanner400><img src=" . $FESTSYS['DefaultPageBanner'] . " class=WMFFBannerDefault>";
    $Banner .= "<div class=BanOverlay><img src=/images/icons/wimborne-folk-festival-logo-white-shadow.png?1>";
    $Banner .= "<img src=/images/icons/underline.png?1>";
    $Banner .= "</div>";

    if ($YEARDATA['Years2Show'] == 2) {  
      $Banner .= "<div class=BanDates2>Next Year: $NFrom - $NTo $NMonth $NYear<p><div class=BanNotice></div></div>";
    } else {
      $Banner .= "<a href=/Tickets class=BanDates>$DFrom - $DTo $DMonth $Sy<br>Buy Tickets</a>";  
    }

    $Banner .= "<img align=center src=/images/icons/torn-top.png class=TornTopEdge>";
    $Banner .= "</div>";
  }

  dohead("$DFrom - $DTo $DMonth $Sy", ['/js/WmffAds.js', "/js/HomePage.js"],$Banner );

  if ( !Show_Articles_For("Top",$future)) {
    echo "<center><a href=/Tickets><img align=center src=/images/stuff/Main_Acts_2020t2.jpg class=BrianImg></a></center>";
  }
  echo "<br clear=all>";

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

  if ($future) {
    echo "<form method=post>";
    echo fm_text("Days in Future", $_REQUEST,'F');
    echo "<input type=submit name=Show value=Show><p></form>\n";
  }

  dotail();
?>
