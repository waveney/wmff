<?php
  include_once("fest.php");

  dohead("Dance Side");
  set_ShowYear();
  include_once("DanceLib.php");
  if (isset($_GET{'sidenum'})) {
    Show_Side($_GET{'sidenum'});
  } else {
    echo "No Side Indicated";
  }

  dotail();
?>

