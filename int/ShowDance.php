<?php
  include_once("fest.php");

  set_ShowYear();
  include_once("DanceLib.php");
  if (isset($_GET['sidenum'])) {
    Show_Side($_GET['sidenum'],'',1);
  } else if (isset($_GET['id'])) {
    Show_Side($_GET['id'],'',1);
  } else {
    echo "No Side Indicated";
  }

  dotail();
?>

