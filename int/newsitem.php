<?php
  include_once('int/fest.php');
  include_once("int/NewsLib.php");
  dohead("News Item");
  
  $n = Get_News($_GET{'id'});
  if (!is_numeric($n)) exit("Invalid News Item ");
  News_Item($n,0,2,'newsimglrg');
  echo "<br clear=all>";
//  Social_Links();

  dotail();
?>
