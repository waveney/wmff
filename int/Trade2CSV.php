<?php

  include_once("fest.php");
  include_once("TradeLib.php");

  global $db,$Trade_State,$Trader_Status,$Trade_States,$TradeTypeData,$TradeLocData;
  header('Content-Type: text/csv; charset=utf-8');
  header('Content-Disposition: attachment; filename=Traders.csv');

  // create a file pointer connected to the output stream
  $output = fopen('php://output', 'w');

  // output the column headings
  fputcsv($output, array('SName','Type','Goods','Contact','Email','Web','Status','Booking State','BID','CC','Before','Where'));
  
  
  $qry = "SELECT t.*, y.* FROM Trade AS t, TradeYear AS y WHERE t.Tid = y.Tid AND y.Year=$YEAR AND y.BookingState>=" . $Trade_State['Submitted'] .
		" ORDER BY SName";

  $res = $db->query($qry);
  while ($fetch = $res->fetch_assoc()) {
    $locs = "";
    if ($fetch['PitchLoc0']) $locs = $TradeLocData[$fetch['PitchLoc0']]['SName'];
    if ($fetch['PitchLoc1']) $locs .= ", " . $TradeLocData[$fetch['PitchLoc1']]['SName'];
    if ($fetch['PitchLoc2']) $locs .= ", " . $TradeLocData[$fetch['PitchLoc2']]['SName'];

    fputcsv($output, array(
        ($fetch['SName']?$fetch['SName']:'No Name Given'),
	$TradeTypeData[$fetch['TradeType']]['SName'],
	$fetch['GoodsDesc'],
        $fetch['Contact'],
        $fetch['Email'],
	$fetch['Website'],
        $Trader_Status[$fetch['Status']],
	$Trade_States[$fetch['BookingState']],
        $fetch['BID'],
        $fetch['ChamberTrade'],
        $fetch['Previous'],
	$locs
	));

  }

?>
