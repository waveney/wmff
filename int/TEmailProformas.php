<?php
  include_once("fest.php");
  A_Check('Staff');

  dostaffhead("Manage Email Proformas");

  include_once("Email.php");
  echo "<div class='content'><h2>Manage Email Proformas</h2>\n";
  
  if (Access('SysAdmin')) {
    $Edit = 1;
  } else {
    echo "These are the proforma messages.  You cannot change them, email changes to Richard.<p>";
    fm_addall('disabled readonly');
    $Edit = 0;
  }
  
  $Pros=Get_Email_Proformas(1);

  if ($Edit && UpdateMany('EmailProformas','Put_Email_Proforma',$Pros,0)) $Pros=Get_Email_Proformas(1);

  echo "Standard replaces:<p>";
  echo "<table border>\n";
  echo "<tr><td>Code<td>What it does<td>Areas \n";
  echo "<tr><td>*WHO*<td>First name of contact<td>All\n";
  echo "<tr><td>*PLANYEAR*<td>Year for the booking<td>All\n";
  echo "<tr><td>*DATES*<td>Dates of Saturday and Sunday<td>All\n";
  echo "<tr><td>*LOCATION*<td>Location(s) of Pitches<td>Trade\n";
  echo "<tr><Td>*PRICE*<td>Total Price quoted<td>Trade\n";
  echo "<tr><td>*LINK*<td>Personal Link for trader<td>Trade, Stewards\n";
  echo "<tr><td>*REMOVE*<td>Remove Request<td>Trade\n";
  echo "<tr><td>*WMFFLINK*<td>Link for Committee members direct to that trader<td>Trade\n";
  echo "<tr><td>*DEPOSIT*<td>Deposit Required<td>Trade\n";
  echo "<tr><td>*BALANCE*<td>Balance Required<td>Trade\n";
  echo "<tr><td>*DETAILS*<td>Full details of booking<td>Trade, BB, LOL, LNL, Stewards\n";
  echo "<tr><td>*FINANCIAL*<td>Trade financial statement<td>Trade\n";
  echo "<tr><td>*STATE*<td>Decsription of application state<td>Trade\n";
  echo "<tr><td>*PAIDSOFAR*<td>Total payments so far<td>Trade\n";
  echo "<tr><td>*FESTIVAL*<td>Name of Festival<td>All\n";
  echo "<tr><td>*HOST*<td>Host URL for festival<td>All\n";
  echo "<tr><td>*MAILTO_name*<td>Inserts a mailto link to name@festival.org\n";
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
    echo "<tr><td>$i" . fm_text1("",$t,'SN',1,'','',"SN$i");
    echo "<td>" . fm_basictextarea($t,'Body',6,8,'',"Body$i");
    echo "\n";
  }
  if ($Edit) {
    echo "<tr><td><td><input type=text name=SN0 >";
    echo "<td><textarea name=Body0 rows=6 cols=120></textarea>";
  }
  echo "</table>\n";
  if ($Edit) echo "<input type=submit name=Update value=Update>\n";
  
  echo "</form></div>";

  dotail();

?>
