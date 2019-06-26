<?php

  include_once("fest.php");
  include_once("BudgetLib.php");
  include_once("DanceLib.php");
  
  A_Check('Committee','Finance');

  $csv = 0;
  if (isset($_GET['F'])) $csv = $_GET['F'];

  if ($csv) {
    header('Content-Type: text/csv; charset=utf-8');
    header('Content-Disposition: attachment; filename=PerformerPayments.csv');

    // create a file pointer connected to the output stream
    $output = fopen('php://output', 'w');

  } else {
    dostaffhead("All Performer Payments");
  }
  
  global $db,$YEAR;
  
  $qry = "SELECT s.*, y.* FROM Sides s, SideYear y WHERE y.Year=$YEAR AND y.TotalFee>0 AND s.SideId=y.SideId AND (y.Coming=2 OR y.Yearstate>=2 ) ORDER BY s.SN";
  $pays = $db->query($qry);
  if (!$pays) { 
    echo "Nothing to pay";
    dotail();
  }
  
  if ($csv) {
    $heads = ['Name','Total Fee','Sort Code','Ac Number','Ac Name'];
    foreach($BUDGET as $i=>$b) {
      if ($b['id']) $heads[] = $b['SN'];
    }
    $heads[] = 'Homeless';
    
    fputcsv($output, $heads,',','"');

  } else {  
    echo "<h2><a href=Payments?Y=$YEAR&F=CSV>Output as CSV</a></h2>";
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
    echo "<th><a href=javascript:SortTable(" . $coln++ . ",'T')>Contract</a>\n";
    echo "</thead><tbody>";
  }
  
  while ($payee = $pays->fetch_assoc()) {
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

    if ($csv) {
      $data = [$payee['SN'],$payee['TotalFee'], $payee['SortCode'], $payee['Account'], $payee['AccountName']];

      foreach($BUDGET as $i=>$b)  $data[]= (isset($bud[$i])?$bud[$i]:"");
      $csvdata = [];
      foreach ($data as $d) $csvdata[] = (is_numeric($d)?"'$d'":$d);
      
      fputcsv($output,$csvdata);
    } else {
      echo "<tr><td>" . $payee['SideId'] . "/" . $payee['syId'] . "<td>" . $payee['SN'];
      echo "<td>" . $payee['TotalFee'];
      echo "<td>" . $payee['SortCode'] . "<td>" . $payee['Account'] . "<td>" . $payee['AccountName'];
    
      foreach($BUDGET as $i=>$b) {
        echo "<td>";
        if (isset($bud[$i])) echo $bud[$i];
      }
      echo "<td>";
      if ($files = glob("Contracts/$YEAR/" . $payee['SideId'] . ".*")) {
        $IssPfx = '';
        $file = '';
        if ($payee['Contracts']) $IssPfx = "." . $payee['Contracts'];
        $files = glob("Contracts/$YEAR/" . $payee['SideId'] . "$IssPfx.*");
        if ($files) {
          $file = $files[0];
        } else if ($payee['Contracts'] == 1) {
          $files = glob("Contracts/$YEAR/" . $payee['SideId'] . ".*");
          if ($files) $file = $files[0];
        }
        if ($file) {
          echo "<a href='ShowFile?l=$file'>View</a>";
        }
      }
      echo "\n";
    }
  }
  
  if ($csv) {
  
  } else {
    echo "</table></div>";
    
    dotail();
  }
?>

