<?php
  include_once("fest.php");

  dohead("Music Acts");
  set_ShowYear();
  include_once("DanceLib.php");
  if (isset($_GET{'sidenum'})) {
    Show_Side($_GET{'sidenum'});
  } else {
    echo "No Act Indicated";
  }

  dotail();
?>
