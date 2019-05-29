<?php
  include_once("fest.php");

  dostaffhead("Trade Stand Map");

  include_once("TradeLib.php");
/* If logged in or trade stae >=partial view actual traders, otherwise just the grid */

  global $Pitches,$tloc,$loc,$MASTER,$EType_States,$Traders;

  if (!isset($_REQUEST['l'])) Error_Page("No Location Requested");
  $loc = $_REQUEST['l'];
  if (!is_numeric($loc)) Error_Page("No Hacking please");
  $Traders = [];
  if (Access('Staff') || $MASTER['TradeState']>= (array_flip($EType_States))['Partial']) $Traders = Get_Traders_For($loc);
  $Pitches = Get_Trade_Pitches($loc);  

  $tloc = Get_Trade_Loc($loc);
  
  Pitch_Map($tloc,$Pitches,$Traders,1,1);
  dotail();
   
?>




