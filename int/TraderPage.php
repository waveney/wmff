<?php
  include_once("fest.php");

  dostaffhead("Trader Application", ["/js/Participants.js","js/dropzone.js","css/dropzone.css"]);

  include_once("TradeLib.php");
  include_once("DateTime.php"); 

  Trade_Main(0,'TraderPage');

  dotail();
?>
