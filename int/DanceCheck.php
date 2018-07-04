<?php
  include_once("fest.php");
  A_Check('Committee','Dance');

  dostaffhead("Dance Checking");

  echo '<h2>Dance Check</h2>';
  echo "Programming does this as you go, this is mainly to enable it to be tested<p>";

  include_once("CheckDance.php");
  CheckDance(2);
  dotail(); 
?>

