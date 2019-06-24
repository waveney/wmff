<?php
  include_once("fest.php");

  dostaffhead("Trader Application", ["/js/Participants.js"]);

  include_once("TradeLib.php");
  include_once("DateTime.php"); 

  Trade_Main(0,'TraderPage');

  dotail();
?>
