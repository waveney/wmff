<?php
  include_once("fest.php");
  A_Check('Committee','Finance');

  dostaffhead("Manage Invoice Codes");
  global $PLANYEAR;

  include_once("InvoiceLib.php");
  
  $Codes = Get_InvoiceCodes(1);
  if (UpdateMany('InvoiceCodes','Put_InvoiceCode',$Codes,1)) $Codes=Get_InvoiceCodes(1);

  $invs = Get_Invoices();
  foreach ($invs as $inv) {
    
  
  }
  

  echo "This is for the Invoice Codes, also shows total Invoiced and total Paid.<p>\n";

  $coln = 0;
  echo "<form method=post>";
  echo "<table id=indextable border>\n";
  echo "<thead><tr>";
  echo "<th><a href=javascript:SortTable(" . $coln++ . ",'N')>Id</a>\n";
  echo "<th><a href=javascript:SortTable(" . $coln++ . ",'N')>Code</a>\n";
  echo "<th><a href=javascript:SortTable(" . $coln++ . ",'T')>Name</a>\n";
  echo "<th><a href=javascript:SortTable(" . $coln++ . ",'T')>Notes</a>\n";
  echo "<th><a href=javascript:SortTable(" . $coln++ . ",'N')>Total Invoiced</a>\n";
  echo "<th><a href=javascript:SortTable(" . $coln++ . ",'N')>Total Paid</a>\n";
  echo "<th><a href=javascript:SortTable(" . $coln++ . ",'N')>Invoices</a>\n";
  echo "</thead><tbody>";
  if ($Codes) foreach($Codes as $C) {
    $i = $C['id'];
    echo "<tr><td>$i" . fm_number1("",$C,'Code','','',"Code$i");
    echo fm_text1("",$C,'SName',1,'','',"SName$i");
    echo fm_text1("",$C,'Notes',1,'','',"Notes$i");
    echo "<td>x";
    echo "<td>y";
    echo "<td>z";
  }
  echo "<tr><td><td><input type=number name=Code0><td><input type=text size=16 name=SName0 >";
  echo "<td><input type=text name=Notes0><td><td><td>";
   echo "</table>\n";
  echo "<input type=submit name=Update value=Update>\n";
  echo "</form></div>";

  dotail();

?>
