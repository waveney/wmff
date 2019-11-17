<?php
  include_once("fest.php");
  dohead("Trade FAQ",[],1);
  
  include_once("TradeLib.php");
  global $FESTSYS;
  
  Trade_TandC();
  echo $FESTSYS['TradeTimes'];
  echo $FESTSYS['TradeFAQ'];


  dotail();
?>

