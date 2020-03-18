<?php
  include_once("fest.php");

  dostaffhead("Trader Application", ["/js/Participants.js","js/dropzone.js","css/dropzone.css"]);

  include_once("TradeLib.php");
  include_once("DateTime.php"); 

  $TTTid = 0;
  if (isset($_GET['id'])) $TTTid = $_GET['id'];
  if (isset($_POST['id'])) $TTTid = $_POST['id'];
  Trade_Main(0,'TraderPage',$TTTid);

  dotail();
?>
