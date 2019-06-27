<?php
  include_once("fest.php");
  include_once("NewsLib.php");
  include_once("DispLib.php");
  
  $page = $_REQUEST['w'];
  if (preg_match('/[^0-9a-zA-Z ]/',$page)) Error_Page("Sorry No hacking");
  $Arts = Get_All_Articles(0,$page);
  if (!$Arts) Error_Page("Sorry $page is not available");
  dohead($Arts[0]['SN']);
  echo "<h2  class=maintitle>" . $Arts[0]['SN'] . "</h2>";
  if (count($Arts) == 1) {
    echo $Arts[0]['Text'];
  } else {
    Show_Articles_For($page);
  }
  dotail();
  
?>
