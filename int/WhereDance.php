<?php
  include_once("fest.php");
  A_Check('SysAdmin');

  dostaffhead('Where Dancers Came From');
  include_once('DanceLib.php');
  
  $AllSides = Select_Come_All();

  $PCodes = array();
  $PCs = array();
  $Total = 0;

  foreach($AllSides as $Sid) {
    $Perf = $Sid['Performers'];
    if ($Perf > 0) {
      $PCode = strtoupper(trim($Sid['PostCode']));
      preg_match('/(\w+)/',$PCode,$mtch);
      $PCa = $mtch[1];
      if (preg_match('/(.*)\d\w\w$/',$PCa,$PCb)) $PCa = $PCb[1];
      $PCs[$PCa] = 1;
      if ($Sid['Sat']) {
        $PCodes[$PCa][$Sid['Sun']?'Both':'Sat'] += $Perf;
      } else if ($Sid['Sun']) $PCodes[$PCa]['Sun'] += $Perf;
      if ($PCa == '' && $Perf>0) echo "No Postcode for: " . $Sid['SN'] . " " . $Perf . "<br>";
      $Total += $Perf;
    } else {
      echo "No Numbers for: " . $Sid['SN'] . "<br>";
    }
  }

  $ks = array_keys($PCs);
 
  sort($ks);

  echo "<div class=tablecont><table border><td>PostCode<td>Sat<td>Sun<td>Both\n";
  foreach ($ks as $pc) {
    echo "<tr><td>$pc<td>" . $PCodes[$pc]['Sat'] . "<td>" . $PCodes[$pc]['Sun'] . "<td>" . $PCodes[$pc]['Both'] . "\n";
  }

  echo "</table></div>\n";
  echo "Total: $Total\n<br>";

  dotail();
?>
