<?php

  include_once("fest.php");
  include_once("BudgetLib.php");
  include_once("DanceLib.php");
  
  A_Check('Committee','Finance');

  dostaffhead("All Performer Payments");
  
  global $db,$YEAR;
  
  $qry = "SELECT s.*, y.* FROM Sides s, SideYear y WHERE y.Year=$YEAR AND y.TotalFee>0 AND s.SideId=y.SideId AND (y.Coming=2 OR y.Yearstate>=2 ) ORDER BY s.SN";
//  echo $qry;
  $pays = $db->query($qry);
  if (!$pays) { 
    echo "Nothing to pay";
    dotail();
  }
  
  $coln = 0;
  echo "<div class=tablecont><table id=indextable border>\n";
  echo "<thead><tr>";
  echo "<th><a href=javascript:SortTable(" . $coln++ . ",'T')>id</a>\n";
  echo "<th><a href=javascript:SortTable(" . $coln++ . ",'T')>Name</a>\n";
  echo "<th><a href=javascript:SortTable(" . $coln++ . ",'N')>Total Fee</a>\n";
  echo "<th><a href=javascript:SortTable(" . $coln++ . ",'T')>Sort Code</a>\n";
  echo "<th><a href=javascript:SortTable(" . $coln++ . ",'T')>Ac Number</a>\n";
  echo "<th><a href=javascript:SortTable(" . $coln++ . ",'T')>Ac Name</a>\n";

  foreach($BUDGET as $i=>$b) {
    if ($b['id']) echo "<th><a href=javascript:SortTable(" . $coln++ . ",'N')>" . $b['SN'] . "</a>\n";
  }
  echo "<th><a href=javascript:SortTable(" . $coln++ . ",'T')>Homeless</a>\n";
  echo "</thead><tbody>";
  
  while ($payee = $pays->fetch_assoc()) {
    echo "<tr><td>" . $payee['syId'] . "<td>" . $payee['SN'];
    echo "<td>" . $payee['TotalFee'];
    echo "<td>" . $payee['SortCode'] . "<td>" . $payee['Account'] . "<td>" . $payee['AccountName'];
    
    $bud = [];
    $bud[$payee['BudgetArea']] = $payee['TotalFee'];
    if ($payee['BudgetArea2']) {
      $bud[$payee['BudgetArea2']] = $payee['BudgetValue2'];
      $bud[$payee['BudgetArea']] -= $payee['BudgetValue2'];
    }
    if ($payee['BudgetArea3']) {
      $bud[$payee['BudgetArea3']] = $payee['BudgetValue3'];
      $bud[$payee['BudgetArea']] -= $payee['BudgetValue3'];
    }
    
    foreach($BUDGET as $i=>$b) {
      echo "<td>";
      if (isset($bud[$i])) echo $bud[$i];
    }
    echo "\n";
  }
  echo "</table></div>";
    
  dotail();


