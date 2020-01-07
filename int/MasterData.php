<?php
  include_once("fest.php");
  A_Check('SysAdmin');

  dostaffhead("Master Data Settings");

  global $FESTSYS;
  echo "<h2>Master Data Settings and Global Actions</h2>\n";

  if (isset($_POST['Update'])) Update_db_post('MasterData',$FESTSYS);

  echo "<form method=post>\n";
  echo fm_hidden('id',1);
  echo "<div class=tablecont><table>";
  echo "<tr>" . fm_text("Festival Name",$FESTSYS,'FestName',3);
  echo "<tr>" . fm_text("Festival Short Name",$FESTSYS,'ShortName');
  echo "<tr>" . fm_number("Version",$FESTSYS,'Version') ;//. "<td>Not used now";
  echo "<tr>" . fm_number("Show Year",$FESTSYS,'ShowYear');
  echo "<tr>" . fm_number("Plan Year",$FESTSYS,'PlanYear');
  echo "<tr>" . fm_text("Host URL",$FESTSYS,'HostURL');
  echo "<tr>" . fm_text("SMTP user",$FESTSYS,'SMTPuser');
  echo "<tr>" . fm_text("SMTP password",$FESTSYS,'SMTPpwd');
  echo "<tr>" . fm_textarea("Features",$FESTSYS,'Features',4,10);
  if (Access('Internal')) echo "<tr>" . fm_textarea("Capabilities",$FESTSYS,'Capabilities',4,10);
/*  echo "<tr>" . fm_text("Left Ad Img",$FESTSYS,'AdvertImgLeft') . fm_text("Left Ad Link",$FESTSYS,'AdvertLinkLeft');
  echo "<tr>" . fm_text("Right Ad Img",$FESTSYS,'AdvertImgRight') . fm_text("Left Ad Right",$FESTSYS,'AdvertLinkRight'); */
  echo "<tr>" . fm_text("Website Coloured Banner",$FESTSYS,'WebSiteBanner',3);
  echo "<tr>" . fm_text("Website White Banner",$FESTSYS,'WebsiteBanner2',3);
  echo "<tr>" . fm_text("Default Page Banner",$FESTSYS,'DefaultPageBanner',3);
  echo "<tr>" . fm_textarea("Analytics code",$FESTSYS,'Analytics',3,3);
  echo "<tr>" . fm_text("Directions Google API key",$FESTSYS,'GoogleAPI',3);
  echo "<tr>" . fm_textarea("Trade Terms and Conditions",$FESTSYS,'TradeTandC',3,3);
  echo "<tr>" . fm_textarea("Trade Times",$FESTSYS,'TradeTimes',3,3);
  echo "<tr>" . fm_textarea("Trade FAQ",$FESTSYS,'TradeFAQ',3,3);
  echo "</table></div>\n";

  echo "<Center><input type=Submit name='Update' value='Update'></center>\n";
  echo "</form>\n";
  
  echo "Features: Separate features by lines then Name:Value:Comment<p>";
 
  dotail();

?>
