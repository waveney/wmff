<?php
  include_once("fest.php");

  set_ShowYear();
  include_once("DanceLib.php");
  if (isset($_GET['sidenum'])) {
    Show_Side($_GET['sidenum']);
  } else if (isset($_GET['id'])) {
    Show_Side($_GET['id']);
  } else {
    echo "No Side Indicated";
  }

  dotail();
?>

