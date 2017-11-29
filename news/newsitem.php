<?php
  include_once('int/fest.php');
  include_once("int/NewsLib.php");
  dohead("News Item");

  include_once("int/Social.php");
  
  $n = Get_News($_GET{'id'});
  News_Item($n,0,2);
  Social_Links();

  dotail();
?>
