<?php
  include_once("fest.php");
  A_Check('Committee','Venues');

  dostaffhead("Fetch and Cache Bus Times");

  include_once("ProgLib.php");
  include_once("TradeLib.php");

  $Routes = array(3=>3949, 4=>3908, 13=>3914);

  foreach ($Routes as $r=>$tab) {
    $Page = file_get_contents("http://morebus.co.uk/service.shtml?serviceid=$tab");
    $Cut1 = stristr($Page,'<div class="service-serviceurls">');
    $Cut2 = stristr($Cut1,'<table class="footerTable">',true);
 
var_dump(htmlspecialchars($Cut1));
exit;
  }


  dotail();

?>
