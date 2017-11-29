<?php
  include_once('int/fest.php');
  include_once("int/NewsLib.php");

  dohead("News Item");
  $items = Get_All_News(0);
  foreach ($items as $n) News_Item($n,0,1);

  dotail();
?>
