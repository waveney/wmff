<?php
  include_once("fest.php");
  A_Check('Committee','Bugs');

  dostaffhead("Manage Email Proformas");

  include_once("TradeLib.php");
  echo "<div class='content'><h2>Manage Email Proformas</h2>\n";
  
  $Pros=Get_Email_Proformas(1);

  if (UpdateMany('EmailProformas','Put_Email_Proforma',$Pros,0)) $Pros=Get_Email_Proformas(1);

  echo "Standard replaces:<p>";
  echo "<table border>\n";
  echo "<tr><td>*WHO*<td>First name of contact\n";
  echo "<tr><td>*THISYEAR<td>Year for the booking\n";
  echo "<tr><td>*DATES*<td>Dates of Saturday and Sunday\n";
  echo "<tr><td>*LOCATION*<td>Location(s) of Pitches\n";
  echo "<tr><Td>*PRICE*<td>Total Price quoted\n";
  echo "<tr><td>*LINK*<td>Personal Link for trader\n";
  echo "<tr><td>*HERE*<td>Remove Request\n";
  echo "<tr><td>*WMFFLINK*<td>Link for Moe/Mandy direct to that trader\n";
  echo "<tr><td>*DEPOSIT*<td>Deposit Required\n";
  echo "<tr><td>*BALANCE*<td>Balance Required\n";
  echo "<tr><td>*DETAILS*<td>Full details of trader\n";
  echo "</table><p>\n";

  $coln = 0;
  echo "<form method=post action=TEmailProformas.php>";
  echo "<table id=indextable border>\n";
  echo "<thead><tr>";
  echo "<th><a href=javascript:SortTable(" . $coln++ . ",'N')>Index</a>\n";
  echo "<th><a href=javascript:SortTable(" . $coln++ . ",'T')>Name</a>\n";
  echo "<th><a href=javascript:SortTable(" . $coln++ . ",'T')>Body of Message</a>\n";
  echo "</thead><tbody>";
  foreach($Pros as $t) {
    $i = $t['id'];
    echo "<tr><td>$i" . fm_text1("",$t,'SName',1,'','',"SName$i");
    echo "<td>" . fm_basictextarea($t,'Body',6,8,'',"Body$i");
    echo "\n";
  }
  echo "<tr><td><td><input type=text name=Name0 >";
  echo "<td><textarea name=Body0 rows=6 cols=120></textarea>";
  echo "</table>\n";
  echo "<input type=submit name=Update value=Update>\n";
  echo "</form></div>";

  dotail();

?>
