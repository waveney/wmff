<?php
  include_once("fest.php");
  
  A_Check('Committee', 'Finance');

  dostaffhead("Other Payment Summary");

  include_once("InvoiceLib.php");

  global $YEAR,$PLANYEAR,$BUDGET,$USER,$Opaystates;

  $pays = Get_PayCodes();
  $tots = [];
  
  foreach($pays as $pay) {
    preg_match('/(\D*)(\d+)/',$pay['Code'],$match);
    $src = $match[1];
    if (!isset($tots[$src])) for ($stat = 0; $stat<=2; $stat++) $tots[$src][$stat] = $tots[$src]["A$stat"] = 0;
    $tots[$src][$pay['State']] ++;
    $tots[$src]['A' . $pay['State']] += $pay['Amount'];
  }
  
  echo "<h2>Other Payments Summary</h2><table><tr><td>For<td>States<td>Transactions<td>Total Value";
  foreach ($tots as $src=>$prec) {
    for ($stat = 0; $stat<=2; $stat++) {
      echo "<tr><td>" . $src . "<td>" . $OpayStates[$stat] . "<td>" . $prec[$stat] . "<td>" . $prec["A$stat"]/100;
    }
  }
  echo "</table><p>";
         
  dotail();

?>
