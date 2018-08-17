<?php
  include_once("fest.php");
  A_Check('SysAdmin');

  dostaffhead("Master Data Settings");

  global $MASTER_DATA;
  echo "<h2>Master Data Settings and Global Actions</h2>\n";

  if (isset($_POST['Update'])) Update_db_post('MasterData',$MASTER_DATA);

  echo "<form method=post>\n";
  echo fm_hidden('id',1);
  echo "<table>";
  echo "<tr>" . fm_text("Festival Name",$MASTER_DATA,'FestName',3);
  echo "<tr>" . fm_text("Festival Short Name",$MASTER_DATA,'ShortName');
  echo "<tr>" . fm_number("Version",$MASTER_DATA,'Version');
  echo "<tr>" . fm_number("Show Year",$MASTER_DATA,'ShowYear');
  echo "<tr>" . fm_number("Plan Year",$MASTER_DATA,'PlanYear');
  echo "<tr>" . fm_text("Host URL",$MASTER_DATA,'HostURL');
  echo "<tr>" . fm_text("SMTP user",$MASTER_DATA,'SMTPuser');
  echo "<tr>" . fm_text("SMTP password",$MASTER_DATA,'SMTPpwd');
  echo "<tr>" . fm_textarea("Features",$MASTER_DATA,'Features',4,10);
  echo "</table>\n";

  echo "<Center><input type=Submit name='Update' value='Update'></center>\n";
  echo "</form>\n";
  
  echo "Features: Separate features by lines then Name:Value:Comment<p>";
 
  dotail();

?>
