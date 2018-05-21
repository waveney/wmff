<?php
  include_once("fest.php");
  A_Check('Committee','Dance');
  dostaffhead("Mark Wristbands as sent");
  include_once("DanceLib.php");

  $sides = Select_Come_All();
  $sc = 0;

  foreach($sides as $side) {
    if ($side['Performers'] < 1 || strlen($side['Address']) < 10 || $side['WristbandsSent']) continue;
    $sidey = Get_SideYear($side['SideId']);
    $sidey['WristbandsSent'] = 1;
    $sc++;
    Put_SideYear($sidey);
  }
  echo "Done - $sc sides updated";
  dotail();
?> 
