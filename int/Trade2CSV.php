<?php

  include_once("fest.php");
  include_once("TradeLib.php");

  global $db,$Trade_State,$Trader_Status,$Trade_States,$TradeTypeData,$TradeLocData;
  header('Content-Type: text/csv; charset=utf-8');
  header('Content-Disposition: attachment; filename=Traders.csv');

  // create a file pointer connected to the output stream
  $output = fopen('php://output', 'w');

  // output the column headings
  fputcsv($output, array('Name','Type','Goods','Contact','Email','Web','Status','Booking State','BID','CC','Before','Where'));
  
  
  $qry = "SELECT t.*, y.* FROM Trade AS t, TradeYear AS y WHERE t.Tid = y.Tid AND y.Year=$YEAR AND y.BookingState>=" . $Trade_State['Submitted'] .
		" ORDER BY Name";

  $res = $db->query($qry);
  while ($fetch = $res->fetch_assoc()) {
    $locs = "";
    if ($fetch['PitchLoc0']) $locs = $TradeLocData[$fetch['PitchLoc0']]['Name'];
    if ($fetch['PitchLoc1']) $locs .= ", " . $TradeLocData[$fetch['PitchLoc1']]['Name'];
    if ($fetch['PitchLoc2']) $locs .= ", " . $TradeLocData[$fetch['PitchLoc2']]['Name'];

    fputcsv($output, array(
        ($fetch['Name']?$fetch['Name']:'No Name Given'),
	$TradeTypeData[$fetch['TradeType']]['Name'],
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
