<?php
  include_once("fest.php");
  A_Check('Committee','Finance');

  dostaffhead("Manage Invoice Codes");
  global $PLANYEAR;

  include_once("InvoiceLib.php");
  
  $Codes = Get_InvoiceCodes(1);
  if (UpdateMany('InvoiceCodes','Put_InvoiceCode',$Codes,1)) $Codes=Get_InvoiceCodes(1);

  $invs = Get_Invoices(' PayDate>=0 ');
  $GTotInv = $GTotPaid = 0;
  foreach ($invs as $inv) {
    if (!isset($TotInv[$inv['InvoiceCode']])) $TotInv[$inv['InvoiceCode']] = $TotPaid[$inv['InvoiceCode']] = 0;
    if ($inv['InvoiceCode2'] || $inv['InvoiceCode3']) {
      $TotInv[$inv['InvoiceCode']] += $inv['Amount1'];
      $TotPaid[$inv['InvoiceCode']] += $inv['PaidTotal'] * $inv['Amount1']/$inv['Total'];
     
      if ($inv['InvoiceCode2']) {
        if (!isset($TotInv[$inv['InvoiceCode2']])) $TotInv[$inv['InvoiceCode2']] = $TotPaid[$inv['InvoiceCode2']] = 0;    
        $TotInv[$inv['InvoiceCode2']] += $inv['Amount2'];
        $TotPaid[$inv['InvoiceCode2']] += $inv['PaidTotal'] * $inv['Amount2']/$inv['Total']; 
      } else {
        $TotInv[$inv['InvoiceCode']] += $inv['Amount2'];
        $TotPaid[$inv['InvoiceCode']] += $inv['PaidTotal'] * $inv['Amount2']/$inv['Total'];      
      }
     
      if ($inv['InvoiceCode3']) {
        if (!isset($TotInv[$inv['InvoiceCode3']])) $TotInv[$inv['InvoiceCode3']] = $TotPaid[$inv['InvoiceCode3']] = 0;    
        $TotInv[$inv['InvoiceCode3']] += $inv['Amount3'];
        $TotPaid[$inv['InvoiceCode3']] += $inv['PaidTotal'] * $inv['Amount3']/$inv['Total']; 
      } else {
        $TotInv[$inv['InvoiceCode']] += $inv['Amount3'];
        $TotPaid[$inv['InvoiceCode']] += $inv['PaidTotal'] * $inv['Amount3']/$inv['Total'];      
      } 

    } else { // Simle case
      $TotInv[$inv['InvoiceCode']] += $inv['Total'];
      $TotPaid[$inv['InvoiceCode']] += $inv['PaidTotal'];
    }

    $GTotInv += $inv['Total'];
    $GTotPaid += $inv['PaidTotal'];   
  }
  

  echo "This is for the Invoice Codes, also shows total Invoiced and total Paid.<p>\n";

  $coln = 0;

  echo "<form method=post>";
  echo "<div class=tablecont><table id=indextable border>\n";
  echo "<thead><tr>";
  echo "<th><a href=javascript:SortTable(" . $coln++ . ",'N')>Id</a>\n";
  echo "<th><a href=javascript:SortTable(" . $coln++ . ",'N')>Code</a>\n";
  echo "<th colspan=3><a href=javascript:SortTable(" . $coln++ . ",'T')>Name</a>\n";
  echo "<th colspan=3><a href=javascript:SortTable(" . $coln++ . ",'T')>Notes</a>\n";
  echo "<th><a href=javascript:SortTable(" . $coln++ . ",'N')>Total Invoiced</a>\n";
  echo "<th><a href=javascript:SortTable(" . $coln++ . ",'N')>Total Paid</a>\n";
  echo "<th><a href=javascript:SortTable(" . $coln++ . ",'N')>Invoices</a>\n";
  echo "</thead><tbody>";
  if ($Codes) foreach($Codes as $C) {
    $i = $C['id'];
    echo "<tr><td>$i" . fm_number1("",$C,'Code','','',"Code$i");
    echo fm_text1("",$C,'SN',3,'','',"SN$i");
    echo fm_text1("",$C,'Notes',3,'','',"Notes$i");
    if (isset($TotInv[$C['Code']])) {
      echo "<td>" . Print_Pence($TotInv[$C['Code']]);
      echo "<td>" . Print_Pence($TotPaid[$C['Code']]);
      echo "<td>" . ($TotInv[$C['Code']]?"<a href=InvoiceManage.php?SHOWCODE=" . $C['Code'] . ">Show</a>" :"None Yet");
    } else {
      echo "<td>0<td>0<td>None Yet";    
    }
  }
  echo "<tr><td><td>Grand Totals<td colspan=3><td colspan=3><td>" . Print_Pence($GTotInv) . "<td>" . Print_Pence($GTotPaid) . "<td>\n";
  echo "<tr><td><td><input type=number name=Code0><td colspan=3><input type=text size=48 name=SN0 >";
  echo "<td colspan=3><input type=text  size=48 name=Notes0><td><td><td>";
   echo "</table></div>\n";
  echo "<input type=submit name=Update value=Update>\n";
  echo "</form></div>";

  dotail();

?>
