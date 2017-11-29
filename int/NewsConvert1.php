<?php
  include_once("fest.php");
  A_Check('SysAdmin');

  dostaffhead("News Data Modify #1");
  include_once("NewsLib.php");
  include_once("DateTime.php");

  global $db,$YEAR,$MASTER;
  
  $AllNews = Get_All_News(1,500);
  $c =0;
  foreach ($AllNews as $n) {
    $n['created'] = Date_BestGuess($n['articledate']);
    Put_News($n);
    $c++;
  }

  echo "Finished...$c<p>";
  dotail();
?>
