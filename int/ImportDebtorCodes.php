<?php
  include_once("fest.php");
  A_Check('SysAdmin');

  dostaffhead("Import Debtor Codes");

  include_once("InvoiceLib.php");
  include_once("BudgetLib.php");
  include_once("TradeLib.php");
  
  // Read in codes, go through each trader - if no code, look through codes if match save, if no match report it
  // Later extend this to look through other orgs
  // Report all unused codes

  if (!isset($_FILES['CSVfile'])) {
    echo '<div class="content"><h2>Import Debtor Codes</h2>';
    echo '<form method=post enctype="multipart/form-data">';
    echo "<input type=file name=CSVfile><br>";
    echo "Test Only: <input type=checkbox name=TestFull checked><br>";
    echo "<input type=submit name=Import value=Import><br></form>\n";
    dotail();
  }
  
  $F = fopen($_FILES["CSVfile"]["tmp_name"],"r");  
  $TestOnly = (isset($_POST['TestFull'])?1:0);
  $orgs = [];
  $orgs_used = [];
  
  while (($cvs = fgetcsv($F)) !== FALSE) {
    $orgs[$cvs[1]] = $cvs[0];
    $orgs_used[$cvs[1]] = 0;
  }
  
  $qry = "SELECT * FROM Trade";
  $res = $db->query($qry);
  while ($trad = $res->fetch_assoc()) {
    if ($trad['SageCode']) {
      echo "<b>" . $trad['SN'] . "</b> already has code " . $trad['SageCode'] . "<br>";
      $orgs_used[$trad['SN']] = $trad['Tid'];
      continue;
    }
    if (isset($orgs[$trad['SN']])) {
      if ($TestOnly) {
        echo "Would assign " . $orgs[$trad['SN']] . " to <b>" . $trad['SN'] . "</b><br>";
        continue;
      }
      $trad['SageCode'] = $orgs[$trad['SN']];
      $orgs_used[$trad['SN']] = $trad['Tid'];
      echo "Assigned " . $orgs[$trad['SN']] . " to <b>" . $trad['SN'] . "</b><br>";
      Put_Trader($trad);
    }
  }
  
  // Other Orgs go here
  
  if (!$TestOnly) {
    foreach ($orgs as $nam=>$code) if ($orgs_used[$nam] == 0) echo "Code $code not used<br>";
  }

  dotail();

?>
