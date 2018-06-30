<?php
  include_once("fest.php");
  A_Check('Steward');

  dostaffhead("List Traders", "/js/clipboard.min.js", "/js/emailclick.js");
  global $YEAR,$THISYEAR,$Trade_States,$Trader_Status;
  include_once("files/navigation.php");
  include_once("TradeLib.php");
  echo "<h2>List Traders $YEAR</h2>\n";

  echo "Click on column header to sort by column.  Click on Traders's name for more detail<p>\n";

  echo "If you click on the email link, press control-V afterwards to paste the standard link into message.<p>";

  $qry = "SELECT y.*, t.* FROM Trade AS t LEFT JOIN TradeYear AS y ON t.Tid = y.Tid AND y.Year=$YEAR ORDER BY SName";
  $res = $db->query($qry);
  $Trade_Types = Get_Trade_Types(1);

  if (!$res || $res->num_rows==0) {
    echo "<h2>No Traders Found</h2>\n";
  } else {
    $coln = 0;
    echo "<table id=indextable border>\n";
    echo "<thead><tr>";
    echo "<th><a href=javascript:SortTable(" . $coln++ . ",'T')>Name</a>\n";
    echo "<th><a href=javascript:SortTable(" . $coln++ . ",'T')>Type</a>\n";
    echo "<th><a href=javascript:SortTable(" . $coln++ . ",'T')>Goods</a>\n";
    echo "<th><a href=javascript:SortTable(" . $coln++ . ",'T')>Contact</a>\n";
    echo "<th><a href=javascript:SortTable(" . $coln++ . ",'T')>Email</a>\n";
    echo "<th><a href=javascript:SortTable(" . $coln++ . ",'T')>Web</a>\n";
    echo "<th><a href=javascript:SortTable(" . $coln++ . ",'T')>Status</a>\n";
    echo "<th><a href=javascript:SortTable(" . $coln++ . ",'T')>Booking State</a>\n";
    echo "<th><a href=javascript:SortTable(" . $coln++ . ",'T')>BID</a>\n";
    echo "<th><a href=javascript:SortTable(" . $coln++ . ",'T')>CC</a>\n";
    echo "<th><a href=javascript:SortTable(" . $coln++ . ",'T')>Before</a>\n";
    echo "</thead><tbody>";
    while ($fetch = $res->fetch_assoc()) {
      $tt = $fetch['TradeType'];
      if ($tt == 0) $tt=1;
      echo "<tr><td width=300><a href=Trade.php?id=" . $fetch['Tid'] . ">" . ($fetch['SName']?$fetch['SName']:'No Name Given') . "</a>";
      echo "<td style='background:" . $Trade_Types[$tt]['Colour'] . ";'>" . $Trade_Types[$tt]['SName'];
      echo "<td width=400>" . $fetch['GoodsDesc'];
      echo "<td>" . $fetch['Contact'];
      echo "<td>" . linkemailhtml($fetch,'Trade');
      echo "<td>";
        if (strlen($fetch['Website'])>6) echo weblink($fetch['Website'],'Web','target=_blank');
      echo "<td>" . ($fetch['Status']?$Trader_Status[$fetch['Status']]:'');
      echo "<td id=TR" . $fetch['Tid'];
        $stat = $fetch['BookingState'];
        if (!$stat) $stat = 1;
        if ($stat == $Trade_State['Fully Paid'] && ($fetch['Insurance'] == 0 || $fetch['RiskAssessment'] == 0)) {
          echo " class=TSNoInsRA>Paid";
          if ($fetch['Insurance'] ==0) echo ", no Insurance";
          if ($fetch['RiskAssessment'] ==0) echo ", no Risk Assess";
        } else {
          echo " class=" . $Trade_StateClasses[$stat] . ">" . $Trade_States[$stat];
        }
      echo Disp_CB($fetch['BID']);
      echo Disp_CB($fetch['ChamberTrade']);
      echo Disp_CB($fetch['Previous']);
// echo " " .$fetch['PostCode'];
    }
    echo "</tbody></table>\n";
  }
  dotail();
?>
