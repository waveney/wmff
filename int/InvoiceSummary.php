<?php
  include_once("fest.php");
  
  A_Check('Committee', 'Finance');

  dostaffhead("Invoice Summary");

/* List all budget areas for year, if current allow edits, if historical not.
   Add Items, change items etc 
   Aimed for Treasurer/Me to update ONLY.  
   
   No user control to budgets YET think sections, think people, think...
   
   There is a much simpler BudgetView for lesser committee members
*/

  include_once("BudgetLib.php");
  include_once("InvoiceLib.php");
  include_once("TradeLib.php");

  global $YEAR,$PLANYEAR,$BUDGET,$USER;

//var_dump($_REQUEST);

  $Invs = Get_Invoices();
  $Codes = Get_InvoiceCodes(1);
  $Codes[0] = ['Code'=>'0000','SN'=>'Unknown'];
     
  $Now = time();
  $coln = 0;
  echo "<table id=indextable border>\n";
  echo "<thead><tr>";
  echo "<th><a href=javascript:SortTable(" . $coln++ . ",'D')>Date Raised</a>\n";
  echo "<th><a href=javascript:SortTable(" . $coln++ . ",'T')>Client</a>\n";
  echo "<th><a href=javascript:SortTable(" . $coln++ . ",'T')>Client Ref</a>\n";    
  echo "<th><a href=javascript:SortTable(" . $coln++ . ",'N')>Invoice</a>\n";
 
  echo "<th><a href=javascript:SortTable(" . $coln++ . ",'T')>Decription</a>\n";
  echo "<th><a href=javascript:SortTable(" . $coln++ . ",'N')>Nominal</a>\n";
  echo "<th><a href=javascript:SortTable(" . $coln++ . ",'T')>Invoice Type</a>\n";
  echo "<th><a href=javascript:SortTable(" . $coln++ . ",'N')>Value</a>\n";
  echo "<th><a href=javascript:SortTable(" . $coln++ . ",'D')>Date Paid</a>\n";
  echo "</thead><tbody>";
  foreach($Invs as $i=>$inv) {
    echo "<tr>";
    $id = $inv['id'];
    $type = "Full";
    $Reason = $inv['Reason'];
    if (preg_match('/deposit/i',$Reason)) $type = "Deposit";
    if (preg_match('/balance/i',$Reason)) $type = "Balance";
    if (preg_match('/change/i',$Reason)) $type = "Change";
    
    echo "<td>" . date('d/m/y',$inv['IssueDate']);
    echo "<td>" . $inv['BZ']; 
    echo "<td>" . $inv['OurRef'];  
    echo "<td>$id";
    echo "<td>" . $Codes[$inv['InvoiceCode']]['SN'];
    echo "<td>" . $Codes[$inv['InvoiceCode']]['Code'];            
    echo "<td>" . $type; // Type
    echo "<td>" . Print_Pence($inv['Total']);
    echo "<td>" . ($inv['PayDate'] <0 ? 'Credit Note' : ($inv['PayDate'] > 0 ? date('d/m/y',$inv['PayDate']) : ""));
  }
  echo "</table>\n";
  
  dotail();

?>
