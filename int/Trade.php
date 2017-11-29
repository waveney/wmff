<?php
  include_once("fest.php");
  A_Check('Committee','Stalls');

  dostaffhead("Trade Stall Booking", "<script src=/js/Participants.js></script>");

  include_once("TradeLib.php");
  include_once("DateTime.php"); 

  Trade_Main(1,'Trade.php');

  dotail();
?>
