<?php
  include_once("fest.php");
  A_Check('Committee','Trade');

  dostaffhead("Trade Stall Booking", ["/js/Participants.js","js/dropzone.js",'js/emailclick.js',"/js/clipboard.min.js","css/dropzone.css"]);

  include_once("TradeLib.php");
  include_once("DateTime.php"); 
  include_once("InvoiceLib.php");

  Trade_Main((isset($_REQUEST['ORGS'])?2:1),'Trade');

  dotail();
?>
