<?php
  include_once("fest.php");
  A_Check('Staff');

  dostaffhead("Manage Taxi Company List");

  include_once("TradeLib.php");

  global $USER,$THISYEAR,$TaxiAuthorities;
  echo "<div class='content'><h2>Manage Taxi Company List</h2>\n";
  
  $Taxes=Get_Taxis(1);
  if (UpdateMany('TaxiCompanies','Put_Taxi',$Taxes,0)) $Taxes=Get_Taxis(1);

  $coln = 0;
  echo "<form method=post action=TaxiCompanies.php>";
  echo "<table id=indextable border>\n";
  echo "<thead><tr>";
  echo "<th><a href=javascript:SortTable(" . $coln++ . ",'N')>Index</a>\n";
  echo "<th><a href=javascript:SortTable(" . $coln++ . ",'T')>Authority</a>\n";
  echo "<th><a href=javascript:SortTable(" . $coln++ . ",'T')>Company</a>\n";
  echo "<th><a href=javascript:SortTable(" . $coln++ . ",'T')>Phone</a>\n";
  echo "<th><a href=javascript:SortTable(" . $coln++ . ",'T')>Website</a>\n";
  echo "</thead><tbody>";
  foreach($Taxes as $t) {
    $i = $t['id'];
    echo "<tr><td>$i";
    echo "<td>" . fm_select($TaxiAuthorities,$t,'Authority',0,'',"Authority$i");
    echo fm_text1("",$t,'SName',1,'','',"SName$i");
    echo fm_text1('',$t,'Phone',1,'','',"Phone$i");
    echo fm_text1('',$t,'Website',1,'','',"Website$i");
    echo "\n";
  }
  echo "<tr><td>";
  echo "<td>" . fm_select($TaxiAuthorities,$t,'Authority0');
  echo "<td><input type=text name=SName0>";
  echo "<td><input type=text name=Phone0>";
  echo "<td><input type=text name=Website0>";
  echo "</table>\n";
  echo "<input type=submit name=Update value=Update>\n";
  echo "</form></div>";

  dotail();

?>
