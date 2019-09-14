<?php
  include_once("fest.php");

  A_Check('SysAdmin');
  
  include_once("DanceLib.php");
  dostaffhead("Check and Fix Blank Access Keys");

  global $db;
  
  $ans = $db->query("SELECT * FROM Sides");
  while ($side = $ans->fetch_assoc()) {
    if( $side['AccessKey'] == '') {
      echo $side['SN'] . "<br>";
      $side['AccessKey'] = rand_string(40);
      Put_Side($side);
    }
  }
  echo "Finished<p>";
  
  dotail();

