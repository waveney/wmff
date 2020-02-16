<?php
  include_once("fest.php");

  dostaffhead("Trade Stand Map");

  include_once("TradeLib.php");
/* If logged in or trade stae >=partial view actual traders, otherwise just the grid */

  global $Pitches,$tloc,$loc,$YEARDATA,$EType_States,$Traders;

  if (!isset($_REQUEST['l'])) Error_Page("No Location Requested");
  $loc = $_REQUEST['l'];
  if (!is_numeric($loc)) Error_Page("No Hacking please");
  $Traders = [];
  if (Access('Staff') || $YEARDATA['TradeState']>= (array_flip($EType_States))['Partial']) $Traders = Get_Traders_For($loc, (Access('Staff')?1:0));
  $Pitches = Get_Trade_Pitches($loc);  

  $tloc = Get_Trade_Loc($loc);
  
  if (Access('Staff')) echo "Any Trader in White has not PAID<p>";
  Pitch_Map($tloc,$Pitches,$Traders,1,1);
  dotail();
   
?>




