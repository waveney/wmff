<?php
  include_once("fest.php");

  dostaffhead("Trader Application", "<script src=/js/Participants.js></script>");

  include_once("TradeLib.php");
  include_once("DateTime.php"); 

  Trade_Main(0,'TraderPage.php');

  dotail();
?>
