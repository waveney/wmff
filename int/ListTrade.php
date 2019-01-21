<?php
  include_once("fest.php");
  A_Check('Steward');

  dostaffhead("List Traders", "/js/clipboard.min.js", "/js/emailclick.js");
  global $YEAR,$PLANYEAR,$Trade_States,$Trader_Status,$Trade_State_Colours;
  include_once("TradeLib.php");

  $Orgs = isset($_REQUEST['ORGS']);
  
  if ($Orgs) {
    echo "<h2>List Businesses and Organisions</h2>\n"; 
    echo "<div class=floatright><h2><a href=ListTrade.php?Y=$YEAR&orgs>List Traders</a></h2></div>";
  } else {
    echo "<h2>List Traders $YEAR</h2>\n";
    if (isset($_REQUEST['orgs'])) echo "<div class=floatright><h2><a href=ListTrade.php?Y=$YEAR&ORGS>List Buisnesses and Organisations</a></h2></div>";    
  }

  echo "Click on column header to sort by column.  Click on Business's name for more detail<p>\n";

  echo "If you click on the email link, press control-V afterwards to paste the standard link into message.<p>";



  if ($Orgs) {
    $qry = "SELECT t.* FROM Trade AS t WHERE t.IsTrader=0 ORDER BY SN";  
  } else {
    $qry = "SELECT y.*, t.* FROM Trade AS t LEFT JOIN TradeYear AS y ON t.Tid = y.Tid AND y.Year=$YEAR WHERE t.IsTrader=1 ORDER BY SN";
  }
  
  $res = $db->query($qry);
  $Trade_Types = Get_Trade_Types(1);

  if (!$res || $res->num_rows==0) {
    echo "<h2>No " . ($Orgs?"Businesses or Organisations":"Traders") . " Found</h2>\n";
  } else {
    $coln = 0;
    echo "<table id=indextable border>\n";
    echo "<thead><tr>";
    echo "<th><a href=javascript:SortTable(" . $coln++ . ",'T')>Name</a>\n";
    if (!$Orgs) echo "<th><a href=javascript:SortTable(" . $coln++ . ",'T')>Type</a>\n";
    if (!$Orgs) echo "<th><a href=javascript:SortTable(" . $coln++ . ",'T')>Goods</a>\n";
    echo "<th><a href=javascript:SortTable(" . $coln++ . ",'T')>Contact</a>\n";
    echo "<th><a href=javascript:SortTable(" . $coln++ . ",'T')>Email</a>\n";
    if (!$Orgs) {
      echo "<th><a href=javascript:SortTable(" . $coln++ . ",'T')>Web</a>\n";
      echo "<th><a href=javascript:SortTable(" . $coln++ . ",'T')>Status</a>\n";
      echo "<th><a href=javascript:SortTable(" . $coln++ . ",'T')>Booking State</a>\n";
      echo "<th><a href=javascript:SortTable(" . $coln++ . ",'T')>BID</a>\n";
      echo "<th><a href=javascript:SortTable(" . $coln++ . ",'T')>CC</a>\n";
      echo "<th><a href=javascript:SortTable(" . $coln++ . ",'T')>Before</a>\n";
    }
    if ($Orgs) {
      echo "<th><a href=javascript:SortTable(" . $coln++ . ",'T')>Invoices</a>\n";   
      echo "<th><a href=javascript:SortTable(" . $coln++ . ",'T')>Actions</a>\n";   
    }
    echo "</thead><tbody>";
    while ($fetch = $res->fetch_assoc()) {
      $tt = $fetch['TradeType'];
      $Tid = $fetch['Tid'];
      if ($tt == 0) $tt=1;
      echo "<tr><td width=300><a href=Trade.php?id=$Tid" . ($Orgs?"&ORGS":"") . ">" . ($fetch['SN']?$fetch['SN']:'No Name Given') . "</a>";
      if (!$Orgs) echo "<td style='background:" . $Trade_Types[$tt]['Colour'] . ";'>" . $Trade_Types[$tt]['SN'];
      if (!$Orgs) echo "<td width=400>" . $fetch['GoodsDesc'];
      echo "<td>" . $fetch['Contact'];
      echo "<td>" . linkemailhtml($fetch,'Trade');
      if (!$Orgs) {
        echo "<td>";
        if (strlen($fetch['Website'])>6) echo weblink($fetch['Website'],'Web','target=_blank');

        echo "<td>" . ($fetch['Status']?$Trader_Status[$fetch['Status']]:'');
        echo "<td id=TR" . $fetch['Tid'];
          $stat = $fetch['BookingState'];
          if (!$stat) $stat = 0;
          if ($stat == $Trade_State['Fully Paid'] && ($fetch['Insurance'] == 0 || $fetch['RiskAssessment'] == 0)) {
            echo " class=TSNoInsRA>Paid";
            if ($fetch['Insurance'] ==0) echo ", no Insurance";
            if ($fetch['RiskAssessment'] ==0) echo ", no Risk Assess";
          } else {
            echo " style='background:" . $Trade_State_Colours[$stat] . ";padding:4; white-space: nowrap;'>" . $Trade_States[$stat];
          }
        echo Disp_CB($fetch['BID']);
        echo Disp_CB($fetch['ChamberTrade']);
        echo Disp_CB($fetch['Previous']);
      } else {
        echo "<td><a href=InvoiceManage.php?FOR=$Tid>Invoices</a>";
        echo "<td><a href=InvoiceManage.php?ACTION=NEW&Tid=$Tid>New Invoice</a>";
      
      }
    }
    echo "</tbody></table>\n";
  }
  dotail();
?>
