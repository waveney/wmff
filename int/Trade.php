<?php
  include_once("fest.php");
  A_Check('Committee','Trade');

  dostaffhead("Trade Stall Booking", "/js/Participants.js");

  include_once("TradeLib.php");
  include_once("DateTime.php"); 

  Trade_Main((isset($_REQUEST['ORGS'])?2:1),'Trade.php');

  dotail();
?>
