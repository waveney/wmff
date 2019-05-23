<?php

  include_once("fest.php");
  include_once("TradeLib.php");

  global $db,$Trade_State,$Trader_Status,$Trade_States,$TradeTypeData,$TradeLocData;
  header('Content-Type: text/csv; charset=utf-8');
  header('Content-Disposition: attachment; filename=Traders.csv');

  // create a file pointer connected to the output stream
  $output = fopen('php://output', 'w');

  // output the column headings
  fputcsv($output, array('SN','Type','Goods','Contact','Email','Web','Booking State','Where','PublicHealth'));
  
  
  $qry = "SELECT t.*, y.* FROM Trade AS t, TradeYear AS y WHERE t.Tid = y.Tid AND y.Year=$YEAR AND y.BookingState>" . $Trade_State['Submitted'] .
                " ORDER BY SN";

  $res = $db->query($qry);
  while ($fetch = $res->fetch_assoc()) {
    $locs = "";
    if ($fetch['PitchLoc0']) $locs = $TradeLocData[$fetch['PitchLoc0']]['SN'];
    if ($fetch['PitchLoc1']) $locs .= ", " . $TradeLocData[$fetch['PitchLoc1']]['SN'];
    if ($fetch['PitchLoc2']) $locs .= ", " . $TradeLocData[$fetch['PitchLoc2']]['SN'];

    fputcsv($output, array(
        ($fetch['SN']?$fetch['SN']:'No Name Given'),
        $TradeTypeData[$fetch['TradeType']]['SN'],
        $fetch['GoodsDesc'],
        $fetch['Contact'],
        $fetch['Email'],
        $fetch['Website'],
        $Trade_States[$fetch['BookingState']],
        $locs,
        $fetch['PublicHealth']
        ));

  }

?>
