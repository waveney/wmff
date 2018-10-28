<?php
  include_once("fest.php");
  include_once("NewsLib.php");
  include_once("DispLib.php");
  
  $page = $_REQUEST['w'];
  $Arts = Get_All_Articles(0,$page);
  dohead($Arts[0]['SN']);
  echo "<h2  class=maintitle>" . $Arts[0]['SN'] . "</h2>";
  if (count($Arts) == 1) {
    echo $Arts[0]['Text'];
  } else {
    Show_Articles_For($page);
  }
  dotail();
  
?>
